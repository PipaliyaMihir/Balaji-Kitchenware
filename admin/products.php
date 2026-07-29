<?php
require_once __DIR__ . '/includes/admin_header.php';

$action = $_GET['action'] ?? 'list';
$editProduct = null;

// Handle Delete
if (isset($_GET['delete_id'])) {
    $delId = (int)$_GET['delete_id'];
    
    // Fetch product to remove images from directory
    $stmt = $pdo->prepare("SELECT images FROM products WHERE id = :id");
    $stmt->execute([':id' => $delId]);
    $prod = $stmt->fetch();
    
    if ($prod && !empty($prod['images'])) {
        $imgs = json_decode($prod['images'], true);
        if (is_array($imgs)) {
            foreach ($imgs as $imgFile) {
                $filePath = __DIR__ . '/../assets/uploads/products/' . $imgFile;
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            }
        }
    }

    $delStmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
    $delStmt->execute([':id' => $delId]);
    setFlash('success', 'Product deleted successfully!');
    header("Location: products.php");
    exit();
}

// Handle Form Submission (Create / Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prod_id = isset($_POST['prod_id']) ? (int)$_POST['prod_id'] : 0;
    $category_id = (int)($_POST['category_id'] ?? 0);
    $name = sanitize($_POST['name'] ?? '');
    $sku = sanitize($_POST['sku'] ?? '');
    $inner_pack = sanitize($_POST['inner_pack'] ?? '1 Pcs');
    $outer_pack = sanitize($_POST['outer_pack'] ?? '24 Pcs');
    $description = sanitize($_POST['description'] ?? '');
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;

    $features = sanitize($_POST['features'] ?? 'Heavy-Gauge Stainless Steel, Induction & Gas Compatible, Cool-Touch Bakelite Handles, Export Master Packing');

    if (empty($name) || empty($sku) || $category_id <= 0) {
        setFlash('danger', 'Product Name, SKU, and Category are required.');
    } else {
        // Manage existing images array
        $existingImagesJson = $_POST['existing_images'] ?? '[]';
        $imagesList = json_decode($existingImagesJson, true);
        if (!is_array($imagesList)) {
            $imagesList = [];
        }

        // Upload New Multiple Images
        if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            $targetDir = __DIR__ . '/../assets/uploads/products';
            $newUploaded = uploadMultipleFiles($_FILES['images'], $targetDir, ['jpg', 'jpeg', 'png', 'webp']);
            if (!empty($newUploaded)) {
                $imagesList = array_merge($imagesList, $newUploaded);
            }
        }

        // If no images at all, set default placeholder
        if (empty($imagesList)) {
            $imagesList = ['prod_cooker_1.jpg'];
        }

        $imagesJson = json_encode(array_values($imagesList));

        if ($prod_id > 0) {
            // Update Product
            $sql = "UPDATE products 
                    SET category_id = :category_id, name = :name, sku = :sku, 
                        inner_pack = :inner_pack, outer_pack = :outer_pack, 
                        description = :description, features = :features, images = :images, is_featured = :is_featured 
                    WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':category_id' => $category_id,
                ':name' => $name,
                ':sku' => $sku,
                ':inner_pack' => $inner_pack,
                ':outer_pack' => $outer_pack,
                ':description' => $description,
                ':features' => $features,
                ':images' => $imagesJson,
                ':is_featured' => $is_featured,
                ':id' => $prod_id
            ]);
            setFlash('success', 'Product updated successfully!');
        } else {
            // Insert Product
            $sql = "INSERT INTO products (category_id, name, sku, inner_pack, outer_pack, description, features, images, is_featured) 
                    VALUES (:category_id, :name, :sku, :inner_pack, :outer_pack, :description, :features, :images, :is_featured)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':category_id' => $category_id,
                ':name' => $name,
                ':sku' => $sku,
                ':inner_pack' => $inner_pack,
                ':outer_pack' => $outer_pack,
                ':description' => $description,
                ':features' => $features,
                ':images' => $imagesJson,
                ':is_featured' => $is_featured
            ]);
            setFlash('success', 'New product added successfully!');
        }

        header("Location: products.php");
        exit();
    }
}

// Fetch categories for dropdown
$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();

// Edit view
if ($action == 'edit' && isset($_GET['id'])) {
    $editId = (int)$_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->execute([':id' => $editId]);
    $editProduct = $stmt->fetch();
}

// Fetch products list
$products = $pdo->query("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC")->fetchAll();
?>

<!-- Header Action Buttons -->
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">Product Inventory</h4>
        <p class="text-muted small mb-0">Manage kitchenware products, SKU specifications, inner/outer packaging & multi-photo uploads.</p>
    </div>
    <?php if ($action == 'list'): ?>
        <a href="products.php?action=add" class="btn btn-danger font-heading fw-bold px-4 py-2">
            <i class="fa-solid fa-plus-circle me-1"></i> Add New Product
        </a>
    <?php else: ?>
        <a href="products.php" class="btn btn-outline-secondary font-heading fw-bold px-4 py-2">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Product List
        </a>
    <?php endif; ?>
</div>

<?php if ($action == 'add' || $action == 'edit'): ?>
    <!-- Product Form (Add / Edit) -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h5><?php echo $editProduct ? 'Edit Product Details' : 'Add New Product'; ?></h5>
        </div>
        <div class="admin-card-body">
            <form action="products.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="prod_id" value="<?php echo $editProduct['id'] ?? 0; ?>">
                <input type="hidden" name="existing_images" value="<?php echo htmlspecialchars($editProduct['images'] ?? '[]'); ?>">

                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-bold text-dark small">Product Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-lg fs-6" 
                               placeholder="e.g. Royal Stainless Steel Pressure Cooker 3L" 
                               value="<?php echo htmlspecialchars($editProduct['name'] ?? ''); ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold text-dark small">Category <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select form-select-lg fs-6" required>
                            <option value="">Select Category...</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" 
                                    <?php echo (isset($editProduct['category_id']) && $editProduct['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- SKU / Inner Pack / Outer Pack as requested in handwritten spec sheet -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-dark small">SKU Code <span class="text-danger">*</span></label>
                        <input type="text" name="sku" class="form-control fs-6" 
                               placeholder="e.g. BK-PC-301" 
                               value="<?php echo htmlspecialchars($editProduct['sku'] ?? ''); ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold text-dark small">Inner Pack Details</label>
                        <input type="text" name="inner_pack" class="form-control fs-6" 
                               placeholder="e.g. 1 Pcs (Display Box)" 
                               value="<?php echo htmlspecialchars($editProduct['inner_pack'] ?? '1 Pcs'); ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold text-dark small">Outer Pack Details (Master Carton)</label>
                        <input type="text" name="outer_pack" class="form-control fs-6" 
                               placeholder="e.g. 24 Pcs (Carton)" 
                               value="<?php echo htmlspecialchars($editProduct['outer_pack'] ?? '24 Pcs'); ?>">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold text-dark small">Product Description</label>
                        <textarea name="description" rows="4" class="form-control fs-6" 
                                  placeholder="Enter detailed features, gauge specs, and material info..."><?php echo htmlspecialchars($editProduct['description'] ?? ''); ?></textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold text-dark small">Feature Highlights Badges (Comma Separated)</label>
                        <input type="text" name="features" class="form-control fs-6" 
                               placeholder="e.g. Heavy-Gauge Stainless Steel, Induction & Gas Compatible, Cool-Touch Bakelite Handles, Export Master Packing" 
                               value="<?php echo htmlspecialchars($editProduct['features'] ?? 'Heavy-Gauge Stainless Steel, Induction & Gas Compatible, Cool-Touch Bakelite Handles, Export Master Packing'); ?>">
                        <div class="form-text text-muted extra-small">These feature badges appear automatically on the Product Details page.</div>
                    </div>

                    <!-- Multi-Photo Upload Section (* multiple photo as specified in handwritten drawing) -->
                    <div class="col-12">
                        <label class="form-label fw-bold text-dark small">
                            Product Photos <span class="badge bg-danger-subtle text-danger ms-2"><i class="fa-solid fa-images me-1"></i> Multiple Photo Upload Supported</span>
                        </label>
                        <div class="photo-upload-zone" onclick="document.getElementById('multiImagesInput').click();">
                            <i class="fa-solid fa-images display-6 text-muted mb-2"></i>
                            <div class="small text-secondary font-weight-bold">Click to select one or multiple photos</div>
                            <div class="extra-small text-muted">You can select multiple image files at once</div>
                            <input type="file" name="images[]" id="multiImagesInput" class="d-none" accept="image/*" multiple>
                        </div>

                        <!-- Multi Preview Container -->
                        <div class="d-flex flex-wrap gap-2 mt-3" id="multiPreviewContainer">
                            <?php 
                            if (!empty($editProduct['images'])) {
                                $existingImgs = json_decode($editProduct['images'], true);
                                if (is_array($existingImgs)) {
                                    foreach ($existingImgs as $imgFile) {
                                        echo '<div style="position:relative; width:80px; height:80px; border-radius:8px; overflow:hidden; border:1px solid #E2E8F0;">
                                                <img src="../assets/uploads/products/' . htmlspecialchars($imgFile) . '" style="width:100%; height:100%; object-fit:cover;">
                                              </div>';
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="is_featured" id="isFeaturedCheck" 
                                   <?php echo (isset($editProduct['is_featured']) && $editProduct['is_featured'] == 1) ? 'checked' : ''; ?>>
                            <label class="form-check-label fw-bold text-dark small" for="isFeaturedCheck">Showcase as Featured Product on Homepage</label>
                        </div>
                    </div>

                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-danger btn-lg px-5 py-3 font-heading fw-bold">
                            <i class="fa-solid fa-save me-1"></i> <?php echo $editProduct ? 'Update Product' : 'Save Product'; ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php else: ?>
    <!-- Products List Table -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h5>All Products</h5>
            <span class="badge bg-danger px-3 py-2"><?php echo count($products); ?> Products</span>
        </div>
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Photos</th>
                        <th>Product Name</th>
                        <th>SKU</th>
                        <th>Category</th>
                        <th>Inner Pack</th>
                        <th>Outer Pack</th>
                        <th>Featured</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($products)): ?>
                        <tr><td colspan="8" class="text-center text-muted py-4">No products found. Click "Add New Product" to create one.</td></tr>
                    <?php else: ?>
                        <?php foreach ($products as $p): 
                            $imgs = json_decode($p['images'], true);
                            if (!is_array($imgs)) $imgs = [];
                        ?>
                            <tr>
                                <td>
                                    <div class="img-multi-stack">
                                        <?php if (!empty($imgs)): ?>
                                            <?php foreach (array_slice($imgs, 0, 3) as $imgFile): ?>
                                                <img src="../assets/uploads/products/<?php echo htmlspecialchars($imgFile); ?>" onerror="this.src='../assets/uploads/products/prod_cooker_1.jpg';">
                                            <?php endforeach; ?>
                                            <?php if (count($imgs) > 3): ?>
                                                <span class="badge bg-dark small d-flex align-items-center">+<?php echo count($imgs) - 3; ?></span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <img src="../assets/uploads/products/prod_cooker_1.jpg">
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark"><?php echo htmlspecialchars($p['name']); ?></div>
                                </td>
                                <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($p['sku']); ?></span></td>
                                <td><span class="badge bg-danger-subtle text-danger"><?php echo htmlspecialchars($p['category_name']); ?></span></td>
                                <td class="small"><?php echo htmlspecialchars($p['inner_pack']); ?></td>
                                <td class="small"><?php echo htmlspecialchars($p['outer_pack']); ?></td>
                                <td>
                                    <?php if ($p['is_featured']): ?>
                                        <span class="badge bg-success"><i class="fa-solid fa-check me-1"></i> Yes</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">No</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="products.php?action=edit&id=<?php echo $p['id']; ?>" class="btn btn-sm btn-light border text-primary" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                        <a href="products.php?delete_id=<?php echo $p['id']; ?>" class="btn btn-sm btn-light border text-danger btn-confirm-delete" title="Delete"><i class="fa-solid fa-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
