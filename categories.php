<?php
$pageTitle = "Product Categories - Balaji Kitchenware";
require_once __DIR__ . '/includes/header.php';

// Fetch all categories with product counts
$sql = "SELECT c.*, COUNT(p.id) as total_products 
        FROM categories c 
        LEFT JOIN products p ON c.id = p.category_id 
        GROUP BY c.id 
        ORDER BY c.name ASC";
$categories = $pdo->query($sql)->fetchAll();
?>

<!-- Categories Component Container -->
<div class="container my-4">
    <div class="component-card-light" data-aos="fade-up">
        <div class="text-center mb-5">
            <span class="section-subtitle">Categorized Range</span>
            <h1 class="display-5 fw-bold text-dark mb-3">Kitchenware Product Categories</h1>
            <p class="text-secondary lead mx-auto" style="max-width: 650px;">
                Browse our specialized range of kitchenware categories designed for households, hotels, restaurants, and catering distributors.
            </p>
        </div>

        <div class="row g-4 justify-content-center">
            <?php foreach ($categories as $index => $cat): ?>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo ($index + 1) * 80; ?>">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative">
                        <div class="ratio ratio-16x9 bg-light">
                            <img src="assets/uploads/categories/<?php echo htmlspecialchars($cat['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($cat['name']); ?>" 
                                 class="object-fit-cover"
                                 onerror="this.src='assets/uploads/categories/cat_steel.jpg';">
                        </div>
                        <div class="card-body p-4 d-flex flex-column justify-content-between">
                            <div>
                                <span class="badge bg-danger mb-2"><?php echo $cat['total_products']; ?> Items Available</span>
                                <h4 class="fw-bold text-dark mb-2"><?php echo htmlspecialchars($cat['name']); ?></h4>
                                <p class="text-secondary small mb-3">High quality <?php echo strtolower(htmlspecialchars($cat['name'])); ?> with superior finish & packing.</p>
                            </div>
                            <a href="products.php?cat=<?php echo $cat['id']; ?>" class="btn btn-accent w-100 rounded-pill py-2 fw-bold">
                                Explore Products <i class="fa-solid fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
