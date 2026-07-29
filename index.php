<?php
$pageTitle = "Home - Premium Stainless Steel & Non-Stick Kitchenware";
require_once __DIR__ . '/includes/header.php';

// Fetch Categories for homepage
$categoriesStmt = $pdo->query("SELECT * FROM categories ORDER BY id ASC LIMIT 6");
$categories = $categoriesStmt->fetchAll();

// Fetch Featured Products for homepage
$featuredProdStmt = $pdo->query("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.is_featured = 1 ORDER BY p.id DESC LIMIT 6");
$featuredProducts = $featuredProdStmt->fetchAll();
?>

<!-- 1. Hero Component Card -->
<div class="container my-4">
    <div class="component-card-light" data-aos="fade-up">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="hero-badge">
                    <i class="fa-solid fa-award"></i> Premium Kitchenware Manufacturer
                </span>
                <h1 class="hero-title">
                    Crafting <span class="text-accent">Perfection</span> For Your Modern Kitchen
                </h1>
                <p class="hero-subtitle">
                    Discover Balaji Kitchenware's elite range of tri-ply stainless steel pressure cookers, German non-stick cookware, and heavy-gauge utensils engineered for high performance.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="products.php" class="btn btn-accent fs-6 rounded-pill px-4">
                        Explore Product Catalog <i class="fa-solid fa-arrow-right ms-2"></i>
                    </a>
                    <a href="catalogue.php" class="btn btn-outline-custom fs-6 rounded-pill px-4">
                        <i class="fa-solid fa-download me-1"></i> Download PDF Catalogue
                    </a>
                </div>

                <div class="mt-4 pt-3 d-flex align-items-center gap-4 border-top">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-shield-halved text-danger fs-4"></i>
                        <div>
                            <div class="fw-bold small text-dark">ISO Certified</div>
                            <div class="text-muted extra-small">100% Quality Assured</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 border-start ps-4">
                        <i class="fa-solid fa-truck-fast text-danger fs-4"></i>
                        <div>
                            <div class="fw-bold small text-dark">Bulk Supply</div>
                            <div class="text-muted extra-small">Pan India & Export</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="hero-card-stack">
                    <img src="assets/images/hero_banner.jpg" alt="Balaji Kitchenware Hero" class="hero-image-main rounded-4">
                    <div class="hero-floating-card">
                        <div class="logo-icon bg-danger text-white p-3 rounded-circle fs-4">
                            <i class="fa-solid fa-fire-burner"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark fs-5">Tri-Ply Cookware</div>
                            <div class="text-secondary small">3x Faster Uniform Heating</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 2. Stats Counter Component Card -->
<div class="container mb-4">
    <div class="component-card py-4" data-aos="fade-up">
        <div class="row g-4">
            <div class="col-md-3 col-6 text-center" data-aos="zoom-in" data-aos-delay="100">
                <div class="stat-box border-0 shadow-none">
                    <div class="stat-num">500+</div>
                    <div class="stat-label">Product SKUs</div>
                </div>
            </div>
            <div class="col-md-3 col-6 text-center border-start" data-aos="zoom-in" data-aos-delay="200">
                <div class="stat-box border-0 shadow-none">
                    <div class="stat-num">25+</div>
                    <div class="stat-label">Years Experience</div>
                </div>
            </div>
            <div class="col-md-3 col-6 text-center border-start" data-aos="zoom-in" data-aos-delay="300">
                <div class="stat-box border-0 shadow-none">
                    <div class="stat-num">10,000+</div>
                    <div class="stat-label">Happy Dealers</div>
                </div>
            </div>
            <div class="col-md-3 col-6 text-center border-start" data-aos="zoom-in" data-aos-delay="400">
                <div class="stat-box border-0 shadow-none">
                    <div class="stat-num">100%</div>
                    <div class="stat-label">Food Grade Steel</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 3. Featured Categories Component Card -->
<div class="container mb-4">
    <div class="component-card-light" data-aos="fade-up">
        <div class="section-title-wrap mb-4">
            <span class="section-subtitle">Explore Our Range</span>
            <h2 class="section-title">Popular Product Categories</h2>
            <p class="text-secondary">Engineered with high quality materials for long-lasting durability.</p>
        </div>

        <div class="row g-4 justify-content-center">
            <?php foreach ($categories as $index => $cat): ?>
                <div class="col-lg-2 col-md-4 col-6" data-aos="fade-up" data-aos-delay="<?php echo ($index + 1) * 100; ?>">
                    <a href="products.php?cat=<?php echo $cat['id']; ?>" class="category-card d-block">
                        <div class="category-img-wrap">
                            <img src="assets/uploads/categories/<?php echo htmlspecialchars($cat['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($cat['name']); ?>" 
                                 onerror="this.src='assets/uploads/categories/cat_steel.jpg';">
                        </div>
                        <h6 class="fw-bold text-dark mb-0"><?php echo htmlspecialchars($cat['name']); ?></h6>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-4 pt-2">
            <a href="categories.php" class="btn btn-outline-custom px-4 rounded-pill">
                View All Categories <i class="fa-solid fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</div>

<!-- 4. Flagship Products Component Card (Matching Screenshot 2 Spec) -->
<div class="container mb-4">
    <div class="component-card-light" data-aos="fade-up">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
            <div>
                <span class="section-subtitle mb-1">FLAGSHIP PRODUCTS</span>
                <h2 class="section-title mb-2">Featured Kitchenware Range</h2>
                <p class="text-secondary mb-0">Explore our complete wholesale catalog of stainless steel cookers, non-stick cookware, and utensils.</p>
            </div>
            <div class="mt-4 mt-md-0">
                <a href="products.php" class="btn btn-accent btn-lg px-4 rounded-pill shadow-sm">
                    View Entire Collection <i class="fa-solid fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- 5. Why Choose Us Component Card -->
<div class="container mb-4">
    <div class="component-card" data-aos="fade-up">
        <div class="section-title-wrap mb-4">
            <span class="section-subtitle">Our Excellence</span>
            <h2 class="section-title">Why Choose Balaji Kitchenware?</h2>
            <p class="text-secondary">We combine state-of-the-art manufacturing with strict quality testing.</p>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="card h-100 border-0 bg-light p-4 text-center rounded-4 shadow-sm">
                    <div class="feature-circle-icon bg-danger text-white">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Heavy Duty Steel</h5>
                    <p class="text-secondary small mb-0">Made from food-grade AISI 304 & 202 grade heavy gauge stainless steel for maximum life.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="card h-100 border-0 bg-light p-4 text-center rounded-4 shadow-sm">
                    <div class="feature-circle-icon bg-primary text-white">
                        <i class="fa-solid fa-temperature-arrow-up"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Induction Bottom</h5>
                    <p class="text-secondary small mb-0">Encapsulated aluminum core bottom ensures even heat distribution on gas & induction.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="card h-100 border-0 bg-light p-4 text-center rounded-4 shadow-sm">
                    <div class="feature-circle-icon bg-warning text-white">
                        <i class="fa-solid fa-boxes-packing"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Master Carton Packing</h5>
                    <p class="text-secondary small mb-0">Standardized inner pack & outer master carton packaging designed for safe logistics transport.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="card h-100 border-0 bg-light p-4 text-center rounded-4 shadow-sm">
                    <div class="feature-circle-icon bg-success text-white">
                        <i class="fa-brands fa-whatsapp"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Direct Wholesale</h5>
                    <p class="text-secondary small mb-0">Fast customer service, quick WhatsApp quotes, and bulk shipment dispatch within 48 hours.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 6. Call to Action Dark Component Card -->
<div class="container mb-5">
    <div class="component-card-dark text-center" data-aos="zoom-in">
        <h2 class="display-6 fw-bold mb-3 text-white">Looking for Bulk Kitchenware Distribution?</h2>
        <p class="lead text-light mb-4 opacity-75 mx-auto" style="max-width: 650px;">Connect directly with our sales team on WhatsApp for price lists and dealership catalogs.</p>
        <a href="<?php echo getWhatsAppLink(); ?>" target="_blank" class="btn btn-whatsapp btn-lg px-5 py-3 fs-5 rounded-pill shadow-lg">
            <i class="fa-brands fa-whatsapp me-2"></i> Contact Wholesale Team on WhatsApp
        </a>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
