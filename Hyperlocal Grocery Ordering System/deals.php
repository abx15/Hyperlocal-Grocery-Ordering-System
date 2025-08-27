<?php
require_once 'includes/db.php';

$page_title = "Special Offers | Locomart";
$current_page = 'deals';

try {
    // Get all active deals
    $stmt = $pdo->query("
        SELECT d.*, 
               COUNT(dp.product_id) as product_count,
               GROUP_CONCAT(DISTINCT c.name SEPARATOR ', ') as category_names
        FROM deals d
        LEFT JOIN deal_products dp ON d.id = dp.deal_id
        LEFT JOIN categories c ON dp.category_id = c.id
        WHERE d.is_active = 1 
        AND d.start_date <= NOW() 
        AND d.end_date >= NOW()
        GROUP BY d.id
        ORDER BY d.is_featured DESC, d.end_date ASC
    ");
    $deals = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get deal products for modal display
    $deal_products = [];
    foreach ($deals as $deal) {
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
        ");
        $stmt->execute([$deal['id'], $deal['id']]);
        $deal_products[$deal['id']] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}

include 'includes/header.php';
?>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h1 class="display-4">Special Offers</h1>
            <p class="lead">Save big with these exclusive Locomart deals</p>
        </div>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Flash Sale Banner -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="flash-sale-banner p-4 rounded text-center text-white" style="background: linear-gradient(135deg, #ff4e50, #f9d423);">
                <div class="d-flex flex-column flex-md-row justify-content-center align-items-center">
                    <div class="mb-3 mb-md-0 mr-md-4">
                        <h2 class="mb-1">⚡ FLASH SALE ⚡</h2>
                        <p class="mb-0">Limited time offers ending soon!</p>
                    </div>
                    <div class="countdown-timer">
                        <div id="flash-sale-countdown" class="d-flex justify-content-center">
                            <div class="mx-2 text-center">
                                <div class="countdown-value days">00</div>
                                <div class="countdown-label">Days</div>
                            </div>
                            <div class="mx-2 text-center">
                                <div class="countdown-value hours">00</div>
                                <div class="countdown-label">Hours</div>
                            </div>
                            <div class="mx-2 text-center">
                                <div class="countdown-value minutes">00</div>
                                <div class="countdown-label">Mins</div>
                            </div>
                            <div class="mx-2 text-center">
                                <div class="countdown-value seconds">00</div>
                                <div class="countdown-label">Secs</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Deals Grid -->
    <div class="row">
        <?php if (empty($deals)): ?>
            <div class="col-12 text-center py-5">
                <div class="alert alert-info">
                    <h4>No current deals available</h4>
                    <p>Check back later for exciting offers!</p>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($deals as $deal): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card deal-card h-100 border-0 shadow-sm">
                        <?php if ($deal['is_featured']): ?>
                            <div class="featured-badge">Featured</div>
                        <?php endif; ?>
                        
                        <div class="deal-image-container">
                            <img src="<?= !empty($deal['image_url']) ? htmlspecialchars($deal['image_url']) : 'images/default-deal.jpg' ?>" 
                                 class="card-img-top" 
                                 alt="<?= htmlspecialchars($deal['title']) ?>"
                                 onerror="this.onerror=null;this.src='images/default-deal.jpg'">
                        </div>
                        
                        <div class="card-body">
                            <div class="deal-badge mb-2">
                                <?php switch($deal['deal_type']) {
                                    case 'percentage': 
                                        echo '<span class="badge badge-danger">'.htmlspecialchars($deal['discount_value']).'% OFF</span>';
                                        break;
                                    case 'fixed':
                                        echo '<span class="badge badge-primary">₹'.htmlspecialchars($deal['discount_value']).' OFF</span>';
                                        break;
                                    case 'bundle':
                                        echo '<span class="badge badge-warning text-dark">BUNDLE OFFER</span>';
                                        break;
                                    case 'buy_x_get_y':
                                        echo '<span class="badge badge-success">BOGO OFFER</span>';
                                        break;
                                } ?>
                            </div>
                            
                            <h3 class="card-title h5"><?= htmlspecialchars($deal['title']) ?></h3>
                            <p class="card-text text-muted small"><?= htmlspecialchars($deal['short_description']) ?></p>
                            
                            <?php if (!empty($deal['category_names'])): ?>
                                <p class="text-info small mb-2">
                                    <i class="fas fa-tag mr-1"></i>
                                    <?= htmlspecialchars($deal['category_names']) ?>
                                </p>
                            <?php endif; ?>
                            
                            <div class="deal-meta d-flex justify-content-between align-items-center mt-3">
                                <div class="deal-timer text-danger small">
                                    <i class="fas fa-clock mr-1"></i>
                                    Ends in <?= date('d M', strtotime($deal['end_date'])) ?>
                                </div>
                                <button class="btn btn-sm btn-outline-primary view-products-btn" 
                                        data-deal-id="<?= $deal['id'] ?>">
                                    View Products
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Deal Products Modal -->
<div class="modal fade" id="dealProductsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dealModalTitle">Deal Products</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="dealProductsContainer">
                <!-- Products will be loaded here via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <a href="products.php" class="btn btn-primary">View All Products</a>
            </div>
        </div>
    </div>
</div>

<script>
// Countdown timer for flash sale
function updateCountdown() {
    const endDate = new Date();
    endDate.setDate(endDate.getDate() + 2); // Sale ends in 2 days
    endDate.setHours(23, 59, 59, 0);
    
    const now = new Date().getTime();
    const distance = endDate - now;
    
    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
    document.querySelector(".countdown-value.days").innerHTML = days.toString().padStart(2, '0');
    document.querySelector(".countdown-value.hours").innerHTML = hours.toString().padStart(2, '0');
    document.querySelector(".countdown-value.minutes").innerHTML = minutes.toString().padStart(2, '0');
    document.querySelector(".countdown-value.seconds").innerHTML = seconds.toString().padStart(2, '0');
    
    if (distance < 0) {
        clearInterval(countdownTimer);
        document.getElementById("flash-sale-countdown").innerHTML = "<div class='alert alert-warning mb-0'>Sale has ended!</div>";
    }
}

// Initialize countdown
updateCountdown();
const countdownTimer = setInterval(updateCountdown, 1000);

// Deal products modal
document.querySelectorAll('.view-products-btn').forEach(button => {
    button.addEventListener('click', function() {
        const dealId = this.getAttribute('data-deal-id');
        
        // Show loading state
        document.getElementById('dealProductsContainer').innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <p>Loading products...</p>
            </div>
        `;
        
        // Set modal title
        const dealTitle = this.closest('.deal-card').querySelector('.card-title').textContent;
        document.getElementById('dealModalTitle').textContent = dealTitle;
        
        // Fetch products via AJAX
        fetch(`ajax/get_deal_products.php?deal_id=${dealId}`)
            .then(response => response.text())
            .then(data => {
                document.getElementById('dealProductsContainer').innerHTML = data;
            })
            .catch(error => {
                document.getElementById('dealProductsContainer').innerHTML = `
                    <div class="alert alert-danger">Error loading products. Please try again.</div>
                `;
            });
        
        // Show modal
        $('#dealProductsModal').modal('show');
    });
});
</script>

<style>
.deal-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-radius: 10px;
    overflow: hidden;
}

.deal-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.deal-image-container {
    height: 180px;
    overflow: hidden;
}

.deal-image-container img {
    object-fit: cover;
    width: 100%;
    height: 100%;
    transition: transform 0.3s ease;
}

.deal-card:hover .deal-image-container img {
    transform: scale(1.05);
}

.featured-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #ff5722;
    color: white;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
    z-index: 1;
}

.flash-sale-banner {
    background: linear-gradient(135deg, #ff4e50, #f9d423);
    border: none;
}

.countdown-timer {
    background: rgba(0,0,0,0.2);
    padding: 10px 20px;
    border-radius: 50px;
}

.countdown-value {
    font-size: 24px;
    font-weight: bold;
    min-width: 40px;
}

.countdown-label {
    font-size: 12px;
    text-transform: uppercase;
    opacity: 0.8;
}

.deal-badge .badge {
    font-size: 14px;
    padding: 5px 10px;
}

@media (max-width: 768px) {
    .countdown-timer {
        padding: 8px 15px;
    }
    .countdown-value {
        font-size: 18px;
        min-width: 30px;
    }
}
</style>

<?php include 'includes/footer.php'; ?>