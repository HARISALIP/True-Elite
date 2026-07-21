<?php
// api/quotations.php
require_once '../config/db.php';
require_once 'helpers.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $action = $_GET['action'] ?? '';
    if ($action === 'get_new_number') {
        $year = date('y');
        try {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM quotations WHERE quotation_number LIKE ?");
            $stmt->execute(["TEK-$year-%"]);
            $count = $stmt->fetchColumn();
            $next = $count + 1;
            $newNumber = "TEK-$year-" . str_pad($next, 4, '0', STR_PAD_LEFT);
            jsonResponse(true, ['quotation_number' => $newNumber]);
        } catch (Exception $e) {
            jsonResponse(false, [], "Database error: " . $e->getMessage());
        }
    }
}

if ($method === 'POST') {
    // We expect a JSON payload
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if (!$data) {
        jsonResponse(false, [], "Invalid JSON payload.");
    }
    
    $action = $data['action'] ?? '';
    
    if ($action === 'save') {
        $customerId = $data['customer_id'] ?? null;
        if (!$customerId) {
            jsonResponse(false, [], "Customer is required.");
        }
        
        $quotationId = $data['quotation_id'] ?? null;
        $quotationNumber = $data['quotation_number'] ?? '';
        
        $pdo->beginTransaction();
        try {
            if ($quotationId) {
                // UPDATE existing quotation
                $stmt = $pdo->prepare("UPDATE quotations SET customer_id=?, address=?, quotation_date=?, expiry_date=?, payment_terms=?, payment_method=?, department=?, attention=?, subject=?, salesperson_id=?, subtotal=?, tax_total=?, grand_total=?, discount_amount=?, notes=? WHERE id=?");
                $stmt->execute([
                    $customerId,
                    $data['address'] ?? '',
                    $data['quotation_date'] ?? date('Y-m-d'),
                    $data['expiry_date'] ?? null,
                    $data['payment_terms'] ?? '',
                    $data['payment_method'] ?? 'Bank Transfer',
                    $data['department'] ?? '',
                    $data['attention'] ?? '',
                    $data['subject'] ?? '',
                    1, // salesperson_id hardcoded for now
                    $data['subtotal'] ?? 0,
                    $data['tax_total'] ?? 0,
                    $data['grand_total'] ?? 0,
                    $data['discount_amount'] ?? 0,
                    $data['notes'] ?? '',
                    $quotationId
                ]);
                
                // Delete old items
                $delStmt = $pdo->prepare("DELETE FROM quotation_items WHERE quotation_id = ?");
                $delStmt->execute([$quotationId]);
            } else {
                // INSERT new quotation
                if (empty($quotationNumber)) {
                    $year = date('y');
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM quotations WHERE quotation_number LIKE ?");
                    $stmt->execute(["TEK-$year-%"]);
                    $next = $stmt->fetchColumn() + 1;
                    $quotationNumber = "TEK-$year-" . str_pad($next, 4, '0', STR_PAD_LEFT);
                }
                
                $stmt = $pdo->prepare("INSERT INTO quotations (quotation_number, customer_id, address, quotation_date, expiry_date, payment_terms, payment_method, department, attention, subject, salesperson_id, workflow_status, subtotal, tax_total, grand_total, discount_amount, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $quotationNumber,
                    $customerId,
                    $data['address'] ?? '',
                    $data['quotation_date'] ?? date('Y-m-d'),
                    $data['expiry_date'] ?? null,
                    $data['payment_terms'] ?? '',
                    $data['payment_method'] ?? 'Bank Transfer',
                    $data['department'] ?? '',
                    $data['attention'] ?? '',
                    $data['subject'] ?? '',
                    1, // salesperson_id hardcoded for now
                    'Quotation',
                    $data['subtotal'] ?? 0,
                    $data['tax_total'] ?? 0,
                    $data['grand_total'] ?? 0,
                    $data['discount_amount'] ?? 0,
                    $data['notes'] ?? ''
                ]);
                
                $quotationId = $pdo->lastInsertId();
            }
            
            // Insert Lines (same for both INSERT and UPDATE)
            if (!empty($data['lines']) && is_array($data['lines'])) {
                $lineStmt = $pdo->prepare("INSERT INTO quotation_items (quotation_id, product_id, description, quantity, cost, markup_percent, unit_price, tax_rate, discount_percent, subtotal, total, row_order, is_section, is_note) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                
                foreach ($data['lines'] as $index => $line) {
                    $lineStmt->execute([
                        $quotationId,
                        $line['product_id'] ?: null,
                        $line['description'] ?? '',
                        $line['quantity'] ?? 1,
                        $line['cost'] ?? 0,
                        $line['markup_percent'] ?? 0,
                        $line['unit_price'] ?? 0,
                        $line['tax_rate'] ?? 0,
                        $line['discount_percent'] ?? 0,
                        $line['subtotal'] ?? 0,
                        $line['total'] ?? 0,
                        $index,
                        $line['is_section'] ? 1 : 0,
                        $line['is_note'] ? 1 : 0
                    ]);
                }
            }
            
            logActivity($pdo, 1, 'Quotation Saved', 'Sales', $quotationId, "Saved Quotation $quotationNumber");
            
            $pdo->commit();
            jsonResponse(true, ['quotation_id' => $quotationId, 'quotation_number' => $quotationNumber]);
        } catch (Exception $e) {
            $pdo->rollBack();
            jsonResponse(false, [], "Database error: " . $e->getMessage());
        }
    }
    
    if ($action === 'update_status') {
        $quotationId = $data['quotation_id'] ?? null;
        $status = $data['status'] ?? '';
        
        if (!$quotationId || !$status) {
            jsonResponse(false, [], "Quotation ID and Status are required.");
        }
        
        try {
            $stmt = $pdo->prepare("UPDATE quotations SET workflow_status = ? WHERE id = ?");
            $stmt->execute([$status, $quotationId]);
            
            logActivity($pdo, 1, 'Status Updated', 'Sales', $quotationId, "Changed status to $status");
            jsonResponse(true, []);
        } catch (Exception $e) {
            jsonResponse(false, [], "Database error: " . $e->getMessage());
        }
    }
}
