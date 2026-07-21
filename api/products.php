<?php
// api/products.php
require_once '../config/db.php';
require_once 'helpers.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $search = $_GET['q'] ?? '';
    $query = "SELECT * FROM products";
    $params = [];
    
    if ($search) {
        $query .= " WHERE product_name LIKE :search OR internal_reference LIKE :search";
        $params[':search'] = "%$search%";
    }
    
    $query .= " ORDER BY product_name ASC";
    
    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $products = $stmt->fetchAll();
        
        // Structure the response to match the frontend expectations
        $formattedProducts = [];
        foreach ($products as $p) {
            $formattedProducts[$p['id']] = [
                'id' => $p['id'],
                'name' => $p['product_name'],
                'description' => $p['description'],
                'brand' => $p['brand'],
                'dimension' => $p['dimension'],
                'model' => $p['model'],
                'cost' => (float)$p['cost'],
                'price' => (float)$p['sales_price'],
                'tax' => (float)$p['tax'],
                'image' => $p['image']
            ];
        }
        
        jsonResponse(true, $formattedProducts);
    } catch (Exception $e) {
        jsonResponse(false, [], $e->getMessage());
    }
}

if ($method === 'POST') {
    // Creating a new product
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? 0;
    $type = $_POST['type'] ?? 'Consumable';
    $brand = $_POST['brand'] ?? '';
    $dimension = $_POST['dimension'] ?? '';
    $model = $_POST['model'] ?? '';
    $can_be_sold = isset($_POST['can_be_sold']) && $_POST['can_be_sold'] == 'true' ? 1 : 0;
    $can_be_purchased = isset($_POST['can_be_purchased']) && $_POST['can_be_purchased'] == 'true' ? 1 : 0;
    
    if (empty($name)) {
        jsonResponse(false, [], "Product name is required.");
    }
    
    $imagePath = null;
    
    // Handle File Upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['image'];
        $maxSize = 5 * 1024 * 1024; // 5 MB
        
        if ($file['size'] > $maxSize) {
            jsonResponse(false, [], "Image exceeds 5MB limit.");
        }
        
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($file['type'], $allowedTypes)) {
            jsonResponse(false, [], "Invalid image type. Only JPG, PNG, WEBP are allowed.");
        }
        
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'product_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
        $destination = '../uploads/products/' . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            $imagePath = 'uploads/products/' . $filename; // Relative path for DB
        } else {
            jsonResponse(false, [], "Failed to save uploaded image.");
        }
    }
    
    try {
        $id = $_POST['id'] ?? null;
        if ($id) {
            // Update existing product
            if ($imagePath) {
                $stmt = $pdo->prepare("UPDATE products SET product_name=?, description=?, brand=?, dimension=?, model=?, sales_price=?, product_type=?, can_be_sold=?, can_be_purchased=?, image=? WHERE id=?");
                $stmt->execute([$name, $type, $brand, $dimension, $model, $price, $type, $can_be_sold, $can_be_purchased, $imagePath, $id]);
            } else {
                $stmt = $pdo->prepare("UPDATE products SET product_name=?, description=?, brand=?, dimension=?, model=?, sales_price=?, product_type=?, can_be_sold=?, can_be_purchased=? WHERE id=?");
                $stmt->execute([$name, $type, $brand, $dimension, $model, $price, $type, $can_be_sold, $can_be_purchased, $id]);
                
                // Fetch existing image path to return
                $imgStmt = $pdo->prepare("SELECT image FROM products WHERE id=?");
                $imgStmt->execute([$id]);
                $imagePath = $imgStmt->fetchColumn();
            }
            logActivity($pdo, 1, 'Product Updated', 'Products', $id, "Updated product: $name");
            $newId = $id;
        } else {
            // Insert new product
            $stmt = $pdo->prepare("INSERT INTO products (product_name, description, brand, dimension, model, sales_price, product_type, can_be_sold, can_be_purchased, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $name,
                $type, // Defaulting description to type for now
                $brand,
                $dimension,
                $model,
                $price,
                $type,
                $can_be_sold,
                $can_be_purchased,
                $imagePath
            ]);
            
            $newId = $pdo->lastInsertId();
            logActivity($pdo, 1, 'Product Created', 'Products', $newId, "Created product: $name");
        }
        
        // Return the product data
        jsonResponse(true, [
            'id' => $newId,
            'name' => $name,
            'description' => $type,
            'brand' => $brand,
            'dimension' => $dimension,
            'model' => $model,
            'price' => (float)$price,
            'image' => $imagePath
        ]);
        
    } catch (Exception $e) {
        jsonResponse(false, [], "Database error: " . $e->getMessage());
    }
}
