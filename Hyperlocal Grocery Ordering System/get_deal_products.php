<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

header('Content-Type: text/html');

if (!isset($_GET['deal_id'])) {
    echo '<div class="alert alert-danger">Invalid request</div>';
    exit;
}

$deal_id = (int)$_GET['deal_id'];

try {
    // Get deal details
    $stmt = $pdo->prepare("SELECT * FROM deals WHERE id = ?");
    $stmt->execute([$deal_id]);
    $deal = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$deal) {
        echo '<div class="alert alert-danger">Deal not found</div>';
        exit;
    }
    
    // Get products for this deal
    $stmt = $pdo->prepare("
        SELECT p.* 
        FROM products p
        JOIN deal_products dp ON p.id = dp.product_id
        WHERE dp.deal_id = ?
        UNION
        SELECT p.*
        FROM products p
        JOIN deal_products dp ON p.category_id = dp.category_id
        WHERE dp.deal_id = ? AND dp.product_id IS NULL
        ORDER BY name
    ");
    $stmt->execute([$deal_id, $deal_id]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($products)) {
        echo '<div class="alert alert-info">No products available in this deal</div>';
        exit;
    }
    
    // Display deal summary
    echo '<div class="deal-summary mb-4 p-3 bg-light rounded">';
    echo '<h4>'.htmlspecialchars($deal['title']).'</h4>';
    echo '<p class="mb-2">'.htmlspecialchars($deal['description']).'</p>';
    
    // Display deal terms based on type
    echo '<div class="deal-terms">';
    switch($deal['deal_type']) {
        case 'percentage':
            echo '<p class="mb-1"><strong>Discount:</strong> '.htmlspecialchars($deal['discount_value']).'% off</p>';
            break;
        case 'fixed':
            echo '<p class="mb-1"><strong>Discount:</strong> ₹'.htmlspecialchars($deal['discount_value']).' off</p>';
            break;
        case 'bundle':
            echo '<p class="mb-1"><strong>Bundle Savings:</strong> ₹'.htmlspecialchars($deal['discount_value']).'</p>';
            break;
        case 'buy_x_get_y':
            echo '<p class="mb-1"><strong>Offer:</strong> Buy 1 Get 1 Free</p>';
            break;
    }
    
    if ($deal['min_order_amount'] > 0) {
        echo '<p class="mb-1"><strong>Minimum Order:</strong> ₹'.htmlspecialchars($deal['min_order_amount']).'</p>';
    }
    
    echo '<p class="mb-0"><strong>Valid Until:</strong> '.date('d M Y, h:i A', strtotime($deal['end_date'])).'</p>';
    echo '</div></div>';
    
    // Display products
    echo '<div class="row">';
    foreach ($products as $product) {
        echo '<div class="col-md-6 mb-3">';
        echo '<div class="product-item d-flex align-items-center border p-2 rounded">';
        
        // Product image
        echo '<div class="product-image mr-3" style="width: 80px; height: 80px; overflow: hidden;">';
        if (!empty($product['image_url'])) {
            echo '<img src="'.htmlspecialchars($product['image_url']).'" alt="'.htmlspecialchars($product['name']).'" class="img-fluid h-100" style="object-fit: cover;">';
        } else {
            echo '<div class="bg-secondary h-100 d-flex align-items-center justify-content-center text-white">';
            echo '<i class="fas fa-shopping-basket"></i>';
            echo '</div>';
        }
        echo '</div>';
        
        // Product details
        echo '<div class="product-details flex-grow-1">';
        echo '<h6 class="mb-1">'.htmlspecialchars($product['name']).'</h6>';
        echo '<div class="d-flex justify-content-between align-items-center">';
        echo '<span class="text-success font-weight-bold">₹'.number_format($product['price'], 2).'</span>';
        
        // Apply deal pricing logic
        if ($deal['deal_type'] == 'percentage') {
            $discounted = $product['price'] * (1 - ($deal['discount_value'] / 100));
            echo '<span class="text-danger"><del>₹'.number_format($product['price'], 2).'</del> ₹'.number_format($discounted, 2).'</span>';
        } elseif ($deal['deal_type'] == 'fixed') {
            $discounted = max(0, $product['price'] - $deal['discount_value']);
            echo '<span class="text-danger"><del>₹'.number_format($product['price'], 2).'</del> ₹'.number_format($discounted, 2).'</span>';
        }
        
        echo '</div>';
        
        // Stock status
        if ($product['stock_quantity'] > 0) {
            echo '<small class="text-muted">In Stock: '.$product['stock_quantity'].'</small>';
        } else {
            echo '<small class="text-danger">Out of Stock</small>';
        }
        
        echo '</div>';
        echo '<a href="product.php?id='.$product['id'].'" class="btn btn-sm btn-outline-primary ml-2">View</a>';
        echo '</div></div>';
    }
    echo '</div>';
    
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo '<div class="alert alert-danger">Error loading deal products</div>';
}
?>