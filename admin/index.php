<?php
require_once __DIR__ . '/includes/admin_header.php';

// Stats query
$totalCategories = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totalCatalogues = $pdo->query("SELECT COUNT(*) FROM catalogues")->fetchColumn();

// Recent 5 products
$recentProducts = $pdo->query("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC LIMIT 5")->fetchAll();
?>

<!-- Overview Stat Cards -->
<div class="row g-4 mb-4">
    <div class="col-xl-4 col-md-6">
        <div class="admin-stat-card">
            <div>
                <div class="text-muted small fw-bold uppercase">TOTAL CATEGORIES</div>
                <div class="display-6 fw-bold text-dark mt-1"><?php echo $totalCategories; ?></div>
                <a href="categories.php" class="small text-danger font-weight-bold text-decoration-none mt-2 d-inline-block">Manage Categories <i class="fa-solid fa-arrow-right ms-1"></i></a>
            </div>
            <div class="stat-icon-box stat-icon-rose">
                <i class="fa-solid fa-layer-group"></i>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="admin-stat-card">
            <div>
                <div class="text-muted small fw-bold uppercase">TOTAL PRODUCTS</div>
                <div class="display-6 fw-bold text-dark mt-1"><?php echo $totalProducts; ?></div>
                <a href="products.php" class="small text-primary font-weight-bold text-decoration-none mt-2 d-inline-block">Manage Products <i class="fa-solid fa-arrow-right ms-1"></i></a>
            </div>
            <div class="stat-icon-box stat-icon-blue">
                <i class="fa-solid fa-box-open"></i>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="admin-stat-card">
            <div>
                <div class="text-muted small fw-bold uppercase">ACTIVE CATALOGUES</div>
                <div class="display-6 fw-bold text-dark mt-1"><?php echo $totalCatalogues; ?></div>
                <a href="catalogues.php" class="small text-warning font-weight-bold text-decoration-none mt-2 d-inline-block">Manage PDFs <i class="fa-solid fa-arrow-right ms-1"></i></a>
            </div>
            <div class="stat-icon-box stat-icon-amber">
                <i class="fa-solid fa-file-pdf"></i>
            </div>
        </div>
    </div>
</div>

<!-- Recent Products Overview Table -->
<div class="row g-4">
    <div class="col-12">
        <div class="admin-card">
            <div class="admin-card-header">
                <h5>Recent Product Listings</h5>
                <a href="products.php" class="btn btn-sm btn-outline-danger rounded-pill px-3">View All Products</a>
            </div>
            <div class="table-responsive">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Category</th>
                            <th>Inner Pack</th>
                            <th>Outer Pack</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recentProducts)): ?>
                            <tr><td colspan="6" class="text-center text-muted py-4">No products found. Add your first product.</td></tr>
                        <?php else: ?>
                            <?php foreach ($recentProducts as $p): 
                                $imgs = json_decode($p['images'], true);
                                $firstImg = (is_array($imgs) && !empty($imgs)) ? $imgs[0] : 'prod_cooker_1.jpg';
                            ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="../assets/uploads/products/<?php echo htmlspecialchars($firstImg); ?>" 
                                                 class="img-thumb-table" 
                                                 onerror="this.src='../assets/uploads/products/prod_cooker_1.jpg';">
                                            <span class="fw-bold text-dark"><?php echo htmlspecialchars($p['name']); ?></span>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($p['sku']); ?></span></td>
                                    <td><span class="badge bg-danger-subtle text-danger"><?php echo htmlspecialchars($p['category_name']); ?></span></td>
                                    <td class="small"><?php echo htmlspecialchars($p['inner_pack']); ?></td>
                                    <td class="small"><?php echo htmlspecialchars($p['outer_pack']); ?></td>
                                    <td>
                                        <a href="products.php?action=edit&id=<?php echo $p['id']; ?>" class="btn btn-sm btn-light text-primary border" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
