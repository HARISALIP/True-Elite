<?php
// api/purchases.php
require_once '../config/db.php';
require_once 'helpers.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $action = $_GET['action'] ?? 'list';

    if ($action === 'get_new_voucher_number') {
        $year = date('y');
        try {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM direct_purchases WHERE voucher_number LIKE ?");
            $stmt->execute(["PV-$year-%"]);
            $count = $stmt->fetchColumn();
            $next = $count + 1;
            $voucherNumber = "PV-$year-" . str_pad($next, 4, '0', STR_PAD_LEFT);
            jsonResponse(true, ['voucher_number' => $voucherNumber]);
        } catch (Exception $e) {
            jsonResponse(false, [], "Database error: " . $e->getMessage());
        }
    }

    if ($action === 'get_suppliers') {
        try {
            $stmt = $pdo->query("SELECT * FROM suppliers ORDER BY supplier_name ASC");
            $suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);
            jsonResponse(true, $suppliers);
        } catch (Exception $e) {
            jsonResponse(false, [], "Database error: " . $e->getMessage());
        }
    }

    if ($action === 'list' || $action === 'get_vouchers') {
        try {
            $stmt = $pdo->query("SELECT dp.*, COUNT(dpi.id) as item_count 
                                FROM direct_purchases dp 
                                LEFT JOIN direct_purchase_items dpi ON dp.id = dpi.purchase_id 
                                GROUP BY dp.id 
                                ORDER BY dp.id DESC");
            $vouchers = $stmt->fetchAll(PDO::FETCH_ASSOC);
            jsonResponse(true, $vouchers);
        } catch (Exception $e) {
            jsonResponse(false, [], "Database error: " . $e->getMessage());
        }
    }
}

if ($method === 'POST') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (!$data) {
        jsonResponse(false, [], "Invalid JSON payload.");
    }

    $action = $data['action'] ?? '';

    if ($action === 'save_direct_purchase') {
        $supplierId = $data['supplier_id'] ?? null;
        $supplierName = trim($data['supplier_name'] ?? '');
        $purchaseDate = $data['purchase_date'] ?? date('Y-m-d');
        $referenceNo = $data['reference_no'] ?? '';
        $paymentTerms = $data['payment_terms'] ?? 'Immediate';
        $voucherNumber = $data['voucher_number'] ?? '';
        $lines = $data['lines'] ?? [];

        if (empty($lines)) {
            jsonResponse(false, [], "At least one item line is required for a direct purchase voucher.");
        }

        $pdo->beginTransaction();
        try {
            // Handle Supplier
            if (!$supplierId && !empty($supplierName)) {
                // Check if supplier name exists
                $supCheck = $pdo->prepare("SELECT id FROM suppliers WHERE supplier_name = ?");
                $supCheck->execute([$supplierName]);
                $supplierId = $supCheck->fetchColumn();

                if (!$supplierId) {
                    $insSup = $pdo->prepare("INSERT INTO suppliers (supplier_name) VALUES (?)");
                    $insSup->execute([$supplierName]);
                    $supplierId = $pdo->lastInsertId();
                }
            } elseif ($supplierId && empty($supplierName)) {
                $supNameStmt = $pdo->prepare("SELECT supplier_name FROM suppliers WHERE id = ?");
                $supNameStmt->execute([$supplierId]);
                $supplierName = $supNameStmt->fetchColumn() ?: 'Unknown Supplier';
            }

            // Generate Voucher Number if empty
            if (empty($voucherNumber)) {
                $year = date('y');
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM direct_purchases WHERE voucher_number LIKE ?");
                $stmt->execute(["PV-$year-%"]);
                $next = $stmt->fetchColumn() + 1;
                $voucherNumber = "PV-$year-" . str_pad($next, 4, '0', STR_PAD_LEFT);
            }

            // Calculate totals
            $subtotal = 0;
            foreach ($lines as $line) {
                $subtotal += (float)($line['quantity'] ?? 0) * (float)($line['purchase_price'] ?? 0);
            }
            $taxTotal = $subtotal * 0.05; // 5% VAT default
            $grandTotal = $subtotal + $taxTotal;

            // Insert Direct Purchase Voucher
            $dpStmt = $pdo->prepare("INSERT INTO direct_purchases (voucher_number, supplier_id, supplier_name, purchase_date, reference_no, payment_terms, subtotal, tax_total, grand_total, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $dpStmt->execute([
                $voucherNumber,
                $supplierId ?: null,
                $supplierName,
                $purchaseDate,
                $referenceNo,
                $paymentTerms,
                $subtotal,
                $taxTotal,
                $grandTotal,
                $data['notes'] ?? ''
            ]);

            $purchaseId = $pdo->lastInsertId();

            // Insert Items & Update Inventory Stock
            $itemStmt = $pdo->prepare("INSERT INTO direct_purchase_items (purchase_id, product_id, item_code, product_name, description, category, quantity, purchase_price, selling_price, subtotal, equipment_serial_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            foreach ($lines as $line) {
                $productId = $line['product_id'] ?: null;
                $pName = trim($line['product_name'] ?? '');
                $itemCode = trim($line['item_code'] ?? '');
                $category = trim($line['category'] ?? 'General');
                $qty = (float)($line['quantity'] ?? 1);
                $purPrice = (float)($line['purchase_price'] ?? 0);
                $sellPrice = (float)($line['selling_price'] ?? 0);
                $lineSubtotal = $qty * $purPrice;
                $serialNo = trim($line['equipment_serial_number'] ?? '');

                if (empty($pName) && !$productId) {
                    continue; // Skip invalid empty lines
                }

                // Match existing product or create new
                if (!$productId && !empty($pName)) {
                    $prodFind = $pdo->prepare("SELECT id FROM products WHERE product_name = ? OR (item_code IS NOT NULL AND item_code = ? AND item_code != '')");
                    $prodFind->execute([$pName, $itemCode]);
                    $productId = $prodFind->fetchColumn();
                }

                if ($productId) {
                    // Update existing product stock & details
                    $updProd = $pdo->prepare("UPDATE products SET 
                        quantity_in_stock = quantity_in_stock + ?, 
                        stock_in_total = stock_in_total + ?, 
                        cost = ?, 
                        sales_price = CASE WHEN ? > 0 THEN ? ELSE sales_price END,
                        supplier_id = COALESCE(?, supplier_id),
                        supplier_name = COALESCE(?, supplier_name),
                        equipment_serial_number = CASE WHEN ? != '' THEN ? ELSE equipment_serial_number END,
                        item_code = CASE WHEN ? != '' THEN ? ELSE item_code END
                        WHERE id = ?");
                    $updProd->execute([
                        $qty,
                        $qty,
                        $purPrice,
                        $sellPrice, $sellPrice,
                        $supplierId ?: null,
                        $supplierName ?: null,
                        $serialNo, $serialNo,
                        $itemCode, $itemCode,
                        $productId
                    ]);
                } else {
                    // Insert new product into inventory
                    $insProd = $pdo->prepare("INSERT INTO products 
                        (product_name, item_code, category, supplier_id, supplier_name, cost, sales_price, quantity_in_stock, stock_in_total, min_stock_alert, equipment_serial_number) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 5.00, ?)");
                    $insProd->execute([
                        $pName,
                        $itemCode,
                        $category,
                        $supplierId ?: null,
                        $supplierName,
                        $purPrice,
                        $sellPrice,
                        $qty,
                        $qty,
                        $serialNo
                    ]);
                    $productId = $pdo->lastInsertId();
                }

                // Insert into direct_purchase_items
                $itemStmt->execute([
                    $purchaseId,
                    $productId,
                    $itemCode,
                    $pName,
                    $line['description'] ?? '',
                    $category,
                    $qty,
                    $purPrice,
                    $sellPrice,
                    $lineSubtotal,
                    $serialNo
                ]);

                // Log Stock Movement IN
                $smStmt = $pdo->prepare("INSERT INTO stock_movements (product_id, movement_type, source_type, source_id, quantity, reference_no, notes) VALUES (?, 'IN', 'DIRECT_PURCHASE', ?, ?, ?, ?)");
                $smStmt->execute([
                    $productId,
                    $purchaseId,
                    $qty,
                    $voucherNumber,
                    "Direct Purchase Voucher from $supplierName"
                ]);
            }

            logActivity($pdo, 1, 'Direct Purchase Created', 'Purchase', $purchaseId, "Saved Direct Purchase Voucher $voucherNumber");

            $pdo->commit();
            jsonResponse(true, ['purchase_id' => $purchaseId, 'voucher_number' => $voucherNumber]);
        } catch (Exception $e) {
            $pdo->rollBack();
            jsonResponse(false, [], "Database error: " . $e->getMessage());
        }
    }
}
