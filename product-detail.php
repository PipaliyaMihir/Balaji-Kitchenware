<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($productId <= 0) {
    header("Location: products.php");
    exit();
}

// Fetch Product with Category details
$stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.id = :id");
$stmt->execute([':id' => $productId]);
$product = $stmt->fetch();

if (!$product) {
    header("Location: products.php");
    exit();
}

$pageTitle = htmlspecialchars($product['name']) . " - Balaji Kitchenware";
require_once __DIR__ . '/includes/header.php';

// Parse multi-images
$images = json_decode($product['images'], true);
if (!is_array($images) || empty($images)) {
    $images = ['prod_cooker_1.jpg'];
}
$firstImg = $images[0];

// Fetch Related Products from same category
$relStmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.category_id = :cat_id AND p.id != :id ORDER BY p.id DESC LIMIT 3");
$relStmt->execute([':cat_id' => $product['category_id'], ':id' => $productId]);
$relatedProducts = $relStmt->fetchAll();
?>

<!-- Breadcrumb Header -->
<section class="py-4 bg-white border-bottom">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted"><i class="fa-solid fa-house me-1"></i> Home</a></li>
                <li class="breadcrumb-item"><a href="products.php" class="text-decoration-none text-muted">Products</a></li>
                <li class="breadcrumb-item"><a href="products.php?cat=<?php echo $product['category_id']; ?>" class="text-decoration-none text-muted"><?php echo htmlspecialchars($product['category_name']); ?></a></li>
                <li class="breadcrumb-item active text-dark fw-bold" aria-current="page"><?php echo htmlspecialchars($product['name']); ?></li>
            </ol>
        </nav>
    </div>
</section>

<!-- Main Product Detail Section Component -->
<div class="container my-4">
    <div class="component-card-light" data-aos="fade-up">
        <div class="row g-5">
            <!-- Left Column: Multi-Photo Gallery Slider -->
            <div class="col-lg-6">
                <div class="bg-white p-3 rounded-4 border shadow-sm">
                    <div class="detail-main-img-wrap mb-3">
                        <img id="detailMainImg" 
                             src="assets/uploads/products/<?php echo htmlspecialchars($firstImg); ?>" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>" 
                             onerror="this.src='assets/uploads/products/prod_cooker_1.jpg';">
                    </div>

                    <?php if (count($images) > 1): ?>
                        <div class="d-flex gap-2 overflow-x-auto pb-2" id="detailThumbsRow">
                            <?php foreach ($images as $idx => $imgFile): ?>
                                <div class="modal-thumb-item <?php echo $idx === 0 ? 'active' : ''; ?>" 
                                     onclick="document.getElementById('detailMainImg').src='assets/uploads/products/<?php echo htmlspecialchars($imgFile); ?>'; document.querySelectorAll('.modal-thumb-item').forEach(t=>t.classList.remove('active')); this.classList.add('active');">
                                    <img src="assets/uploads/products/<?php echo htmlspecialchars($imgFile); ?>" 
                                         alt="Thumbnail <?php echo $idx+1; ?>"
                                         onerror="this.src='assets/uploads/products/prod_cooker_1.jpg';">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right Column: Details & Order CTA -->
            <div class="col-lg-6">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-danger-subtle text-danger px-3 py-2 fw-bold"><?php echo htmlspecialchars($product['category_name']); ?></span>
                    <?php if ($product['is_featured']): ?>
                        <span class="badge bg-warning text-dark px-3 py-2 fw-bold"><i class="fa-solid fa-star me-1"></i> Featured Item</span>
                    <?php endif; ?>
                </div>

                <h1 class="display-6 fw-bold text-dark mb-3"><?php echo htmlspecialchars($product['name']); ?></h1>

                <!-- Specifications Highlight Box -->
                <div class="bg-white p-4 rounded-4 border shadow-sm mb-4">
                    <div class="row text-center g-3">
                        <div class="col-4 border-end">
                            <div class="text-muted extra-small font-weight-bold uppercase">SKU CODE</div>
                            <div class="fw-bold text-dark fs-5 mt-1"><?php echo htmlspecialchars($product['sku']); ?></div>
                        </div>
                        <div class="col-4 border-end">
                            <div class="text-muted extra-small font-weight-bold uppercase">INNER PACK</div>
                            <div class="fw-bold text-dark fs-6 mt-1"><?php echo htmlspecialchars($product['inner_pack']); ?></div>
                        </div>
                        <div class="col-4">
                            <div class="text-muted extra-small font-weight-bold uppercase">OUTER PACK</div>
                            <div class="fw-bold text-dark fs-6 mt-1"><?php echo htmlspecialchars($product['outer_pack']); ?></div>
                        </div>
                    </div>
                </div>

                <h5 class="fw-bold text-dark mb-2">Description & Features:</h5>
                <p class="text-secondary lead fs-6 mb-4"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>

                <!-- Dynamic Feature Badges -->
                <?php 
                $featureStr = !empty($product['features']) ? $product['features'] : 'Heavy-Gauge Stainless Steel, Induction & Gas Compatible, Cool-Touch Bakelite Handles, Export Master Packing';
                $featureList = array_map('trim', explode(',', $featureStr));
                $featureIcons = [
                    'fa-shield-halved',
                    'fa-fire-burner',
                    'fa-hand-holding-heat',
                    'fa-boxes-packing',
                    'fa-award',
                    'fa-circle-check'
                ];
                ?>
                <div class="row g-3 mb-4">
                    <?php foreach ($featureList as $fIdx => $feat): ?>
                        <div class="col-6">
                            <div class="d-flex align-items-center gap-2 text-dark small fw-bold">
                                <i class="fa-solid <?php echo $featureIcons[$fIdx % count($featureIcons)]; ?> text-danger fs-5"></i> 
                                <?php echo htmlspecialchars($feat); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex flex-wrap gap-3 pt-2">
                    <a href="<?php echo getWhatsAppLink($product['name'], $product['sku']); ?>" target="_blank" class="btn btn-whatsapp btn-lg flex-fill py-3 justify-content-center fs-6 shadow rounded-pill">
                        <i class="fa-brands fa-whatsapp fs-4 me-2"></i> Inquire Wholesale Price on WhatsApp
                    </a>
                    <a href="catalogue.php" class="btn btn-outline-custom btn-lg py-3 px-4 rounded-pill">
                        <i class="fa-solid fa-file-pdf me-1"></i> E-Catalogue
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Related Products Section Component -->
<?php if (!empty($relatedProducts)): ?>
<div class="container mb-5">
    <div class="component-card-light" data-aos="fade-up">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h3 class="fw-bold text-dark mb-0">Related Products in <?php echo htmlspecialchars($product['category_name']); ?></h3>
            <a href="products.php?cat=<?php echo $product['category_id']; ?>" class="btn btn-outline-custom btn-sm rounded-pill px-3">View Category Range</a>
        </div>

        <div class="row g-4 justify-content-center">
            <?php foreach ($relatedProducts as $rel): 
                $relImgs = json_decode($rel['images'], true);
                $relFirstImg = (is_array($relImgs) && !empty($relImgs)) ? $relImgs[0] : 'prod_cooker_1.jpg';
            ?>
                <div class="col-lg-4 col-md-6">
                    <div class="product-card" onclick="window.location.href='product-detail.php?id=<?php echo $rel['id']; ?>';">
                        <div class="product-thumb-wrap">
                            <span class="product-cat-tag"><?php echo htmlspecialchars($rel['category_name']); ?></span>
                            <img src="assets/uploads/products/<?php echo htmlspecialchars($relFirstImg); ?>" 
                                 alt="<?php echo htmlspecialchars($rel['name']); ?>"
                                 onerror="this.src='assets/uploads/products/prod_cooker_1.jpg';">
                        </div>
                        <div class="product-body">
                            <h5 class="product-title"><?php echo htmlspecialchars($rel['name']); ?></h5>
                            <a href="product-detail.php?id=<?php echo $rel['id']; ?>" class="btn btn-accent btn-sm w-100 rounded-pill py-2 fw-bold mt-auto">
                                View Product Details <i class="fa-solid fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
