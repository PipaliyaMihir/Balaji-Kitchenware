<?php
$pageTitle = "Product Catalog - Balaji Kitchenware";
require_once __DIR__ . '/includes/header.php';

// Fetch all categories for filter tabs
$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();

// Get active category filter from URL parameter
$selectedCatId = isset($_GET['cat']) ? (int)$_GET['cat'] : 'all';

// Fetch products with category details
$sql = "SELECT p.*, c.name as category_name 
        FROM products p 
        JOIN categories c ON p.category_id = c.id 
        ORDER BY p.id DESC";
$products = $pdo->query($sql)->fetchAll();
?>

<!-- Products Component Container -->
<div class="container my-4">
    <div class="component-card-light" data-aos="fade-up">
        <div class="text-center mb-4">
            <span class="section-subtitle">Wholesale Catalogue</span>
            <h1 class="display-5 fw-bold text-dark mb-3">Our Kitchenware Collection</h1>
            <p class="text-secondary lead mx-auto" style="max-width: 650px;">
                Explore our complete product catalog with SKU specifications, inner pack & outer carton packing info.
            </p>

            <!-- Live Search Input -->
            <div class="mt-4 mx-auto" style="max-width: 550px;">
                <div class="input-group input-group-lg shadow-sm rounded-pill overflow-hidden border">
                    <span class="input-group-text bg-white border-0 ps-4 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" id="productSearchInput" class="form-control border-0 px-2 fs-6 shadow-none" placeholder="Search by Product Name or SKU (e.g. BK-PC-301)...">
                    <button class="btn btn-accent px-4" type="button">Search</button>
                </div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="d-flex flex-wrap align-items-center justify-content-center gap-2 mb-5" data-aos="fade-up">
            <button type="button" 
                    class="btn cat-filter-btn <?php echo $selectedCatId == 'all' ? 'btn-accent active' : 'btn-outline-custom'; ?> px-4 rounded-pill" 
                    data-cat-id="all">
                All Products
            </button>
            <?php foreach ($categories as $cat): ?>
                <button type="button" 
                        class="btn cat-filter-btn <?php echo $selectedCatId == $cat['id'] ? 'btn-accent active' : 'btn-outline-custom'; ?> px-4 rounded-pill" 
                        data-cat-id="<?php echo $cat['id']; ?>">
                    <?php echo htmlspecialchars($cat['name']); ?>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Products Grid -->
        <div class="row g-4 justify-content-center" id="productsGridContainer">
            <?php foreach ($products as $index => $prod): 
                $images = json_decode($prod['images'], true);
                $firstImg = (is_array($images) && !empty($images)) ? $images[0] : 'prod_cooker_1.jpg';
                $isCatMatched = ($selectedCatId == 'all' || $selectedCatId == $prod['category_id']);
            ?>
                <div class="col-xl-3 col-lg-4 col-md-6 product-item-col" 
                     data-category="<?php echo $prod['category_id']; ?>"
                     data-sku="<?php echo htmlspecialchars($prod['sku']); ?>"
                     style="display: <?php echo $isCatMatched ? 'block' : 'none'; ?>;">
                     
                    <div class="product-card" onclick="if (!event.target.closest('a')) window.location.href='product-detail.php?id=<?php echo $prod['id']; ?>';">
                        <div class="product-thumb-wrap">
                            <?php if ($prod['is_featured']): ?>
                                <span class="product-badge"><i class="fa-solid fa-star me-1"></i> FEATURED</span>
                            <?php endif; ?>
                            <span class="product-cat-tag"><?php echo htmlspecialchars($prod['category_name']); ?></span>
                            
                            <img src="assets/uploads/products/<?php echo htmlspecialchars($firstImg); ?>" 
                                 alt="<?php echo htmlspecialchars($prod['name']); ?>"
                                 onerror="this.src='assets/uploads/products/prod_cooker_1.jpg';">
                        </div>

                        <div class="product-body">
                            <a href="product-detail.php?id=<?php echo $prod['id']; ?>" class="text-decoration-none">
                                <h5 class="product-title"><?php echo htmlspecialchars($prod['name']); ?></h5>
                            </a>

                            <p class="text-secondary small mb-3 text-truncate"><?php echo htmlspecialchars($prod['description']); ?></p>

                            <div class="d-flex gap-2">
                                <a href="product-detail.php?id=<?php echo $prod['id']; ?>" class="btn btn-accent btn-sm flex-fill rounded-pill py-2 shadow-sm fw-bold">
                                    <i class="fa-solid fa-circle-info me-1"></i> View Details
                                </a>

                                <a href="<?php echo getWhatsAppLink($prod['name'], $prod['sku']); ?>" 
                                   target="_blank" 
                                   class="btn btn-whatsapp btn-sm rounded-pill px-3 py-2 shadow-sm fw-bold">
                                    <i class="fa-brands fa-whatsapp"></i> Inquire
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
