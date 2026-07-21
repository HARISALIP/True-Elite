<?php
// api/customers.php
require_once '../config/db.php';
require_once 'helpers.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $search = $_GET['q'] ?? '';
    $query = "SELECT * FROM customers";
    $params = [];
    
    if ($search) {
        $query .= " WHERE customer_name LIKE :search OR company LIKE :search";
        $params[':search'] = "%$search%";
    }
    
    $query .= " ORDER BY customer_name ASC";
    
    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $customers = $stmt->fetchAll();
        
        $formatted = [];
        foreach ($customers as $c) {
            $formatted[$c['id']] = [
                'id' => $c['id'],
                'name' => $c['customer_name'],
                'email' => $c['email'],
                'phone' => $c['phone'],
                'address' => $c['address'],
                'initial' => strtoupper(substr($c['customer_name'], 0, 1))
            ];
        }
        
        jsonResponse(true, $formatted);
    } catch (Exception $e) {
        jsonResponse(false, [], $e->getMessage());
    }
}

if ($method === 'POST') {
    // Creating a new customer
    // The client sends FormData via fetch or application/json, but since we use FormData in products, let's stick to POST form-data
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $city = $_POST['city'] ?? '';
    $country = $_POST['country'] ?? '';
    $trn = $_POST['trn'] ?? '';
    $company = $_POST['company'] ?? '';
    
    if (empty($name)) {
        jsonResponse(false, [], "Customer name is required.");
    }
    
    try {
        $id = $_POST['id'] ?? null;
        if ($id) {
            $stmt = $pdo->prepare("UPDATE customers SET customer_name=?, email=?, phone=?, address=?, city=?, country=?, trn=?, company=? WHERE id=?");
            $stmt->execute([
                $name, $email, $phone, $address, $city, $country, $trn, $company, $id
            ]);
            logActivity($pdo, 1, 'Customer Updated', 'Customers', $id, "Updated customer: $name");
            $newId = $id;
        } else {
            $stmt = $pdo->prepare("INSERT INTO customers (customer_name, email, phone, address, city, country, trn, company) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $name, $email, $phone, $address, $city, $country, $trn, $company
            ]);
            $newId = $pdo->lastInsertId();
            logActivity($pdo, 1, 'Customer Created', 'Customers', $newId, "Created customer: $name");
        }
        
        jsonResponse(true, [
            'id' => $newId,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'initial' => strtoupper(substr($name, 0, 1))
        ]);
        
    } catch (Exception $e) {
        jsonResponse(false, [], "Database error: " . $e->getMessage());
    }
}
