<?php
// api/inventory.php
require_once '../config/db.php';
require_once 'helpers.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $search = $_GET['q'] ?? '';
    $category = $_GET['category'] ?? '';
    $supplier_id = $_GET['supplier_id'] ?? '';
    $low_stock = $_GET['low_stock'] ?? '';
    
    $query = "SELECT p.*, s.supplier_name as linked_supplier_name 
              FROM products p 
              LEFT JOIN suppliers s ON p.supplier_id = s.id 
              WHERE 1=1";
    $params = [];

    if (!empty($search)) {
        $query .= " AND (p.product_name LIKE :search OR p.item_code LIKE :search OR p.internal_reference LIKE :search OR p.equipment_serial_number LIKE :search)";
        $params[':search'] = "%$search%";
    }

    if (!empty($category)) {
        $query .= " AND p.category = :category";
        $params[':category'] = $category;
    }

    if (!empty($supplier_id)) {
        $query .= " AND p.supplier_id = :supplier_id";
        $params[':supplier_id'] = $supplier_id;
    }

    if ($low_stock === '1' || $low_stock === 'true') {
        $query .= " AND p.quantity_in_stock <= p.min_stock_alert";
    }

    $query .= " ORDER BY p.product_name ASC";

    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch list of suppliers for filter dropdowns
        $supStmt = $pdo->query("SELECT id, supplier_name FROM suppliers ORDER BY supplier_name ASC");
        $suppliers = $supStmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch categories for filter dropdowns
        $catStmt = $pdo->query("SELECT DISTINCT category FROM products WHERE category IS NOT NULL AND category != '' ORDER BY category ASC");
        $categories = $catStmt->fetchAll(PDO::FETCH_COLUMN);

        jsonResponse(true, [
            'items' => $items,
            'suppliers' => $suppliers,
            'categories' => $categories
        ]);
    } catch (Exception $e) {
        jsonResponse(false, [], "Database error: " . $e->getMessage());
    }
}

if ($method === 'POST') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (!$data) {
        // Fallback to $_POST
        $data = $_POST;
    }

    $action = $data['action'] ?? '';

    if ($action === 'update_item' || $action === 'save_item') {
        $id = !empty($data['id']) ? $data['id'] : null;

        try {
            $supplierName = '';
            if (!empty($data['supplier_id'])) {
                $supStmt = $pdo->prepare("SELECT supplier_name FROM suppliers WHERE id = ?");
                $supStmt->execute([$data['supplier_id']]);
                $supplierName = $supStmt->fetchColumn() ?: ($data['supplier_name'] ?? '');
            } else {
                $supplierName = $data['supplier_name'] ?? '';
            }

            if ($id) {
                // UPDATE existing item
                $stmt = $pdo->prepare("UPDATE products SET 
                    product_name = ?,
                    item_code = ?,
                    category = ?,
                    supplier_id = ?,
                    supplier_name = ?,
                    cost = ?,
                    sales_price = ?,
                    quantity_in_stock = ?,
                    min_stock_alert = ?,
                    equipment_serial_number = ?
                    WHERE id = ?");

                $stmt->execute([
                    $data['product_name'] ?? '',
                    $data['item_code'] ?? '',
                    $data['category'] ?? 'General',
                    $data['supplier_id'] ?: null,
                    $supplierName,
                    $data['cost'] ?? 0,
                    $data['sales_price'] ?? 0,
                    $data['quantity_in_stock'] ?? 0,
                    $data['min_stock_alert'] ?? 5,
                    $data['equipment_serial_number'] ?? '',
                    $id
                ]);

                logActivity($pdo, 1, 'Inventory Item Updated', 'Inventory', $id, "Updated inventory parameters for product ID: $id");
                jsonResponse(true, ['message' => 'Item updated successfully']);
            } else {
                // INSERT new item
                $stmt = $pdo->prepare("INSERT INTO products (
                    product_name, item_code, category, supplier_id, supplier_name, 
                    cost, sales_price, quantity_in_stock, min_stock_alert, equipment_serial_number, stock_in_total
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                $qty = (float)($data['quantity_in_stock'] ?? 0);

                $stmt->execute([
                    $data['product_name'] ?? '',
                    $data['item_code'] ?? '',
                    $data['category'] ?? 'General',
                    $data['supplier_id'] ?: null,
                    $supplierName,
                    $data['cost'] ?? 0,
                    $data['sales_price'] ?? 0,
                    $qty,
                    $data['min_stock_alert'] ?? 5,
                    $data['equipment_serial_number'] ?? '',
                    $qty
                ]);

                $newId = $pdo->lastInsertId();

                if ($qty > 0) {
                    $smStmt = $pdo->prepare("INSERT INTO stock_movements (product_id, movement_type, source_type, quantity, notes) VALUES (?, 'STOCK_IN', 'ADJUSTMENT', ?, 'Initial stock creation')");
                    $smStmt->execute([$newId, $qty]);
                }

                logActivity($pdo, 1, 'Inventory Item Created', 'Inventory', $newId, "Created new inventory product ID: $newId");
                jsonResponse(true, ['message' => 'Item created successfully', 'id' => $newId]);
            }
        } catch (Exception $e) {
            jsonResponse(false, [], "Database error: " . $e->getMessage());
        }
    }

    if ($action === 'adjust_stock') {
        $id = $data['id'] ?? null;
        $type = $data['type'] ?? 'IN'; // IN or OUT
        $qty = (float)($data['quantity'] ?? 0);
        $notes = $data['notes'] ?? 'Manual Stock Adjustment';

        if (!$id || $qty <= 0) {
            jsonResponse(false, [], "Valid Item ID and positive Quantity are required.");
        }

        $pdo->beginTransaction();
        try {
            // Log movement
            $smStmt = $pdo->prepare("INSERT INTO stock_movements (product_id, movement_type, source_type, quantity, notes) VALUES (?, ?, 'ADJUSTMENT', ?, ?)");
            $smStmt->execute([$id, $type, $qty, $notes]);

            // Update product totals
            if ($type === 'IN') {
                $updStmt = $pdo->prepare("UPDATE products SET quantity_in_stock = quantity_in_stock + ?, stock_in_total = stock_in_total + ? WHERE id = ?");
                $updStmt->execute([$qty, $qty, $id]);
            } else {
                $updStmt = $pdo->prepare("UPDATE products SET quantity_in_stock = quantity_in_stock - ?, stock_out_total = stock_out_total + ? WHERE id = ?");
                $updStmt->execute([$qty, $qty, $id]);
            }

            logActivity($pdo, 1, 'Stock Adjustment', 'Inventory', $id, "Adjusted stock ($type $qty) for product ID: $id");

            $pdo->commit();
            jsonResponse(true, ['message' => 'Stock adjusted successfully']);
        } catch (Exception $e) {
            $pdo->rollBack();
            jsonResponse(false, [], "Database error: " . $e->getMessage());
        }
    }
}
