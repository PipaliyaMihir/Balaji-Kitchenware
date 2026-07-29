<?php
require_once __DIR__ . '/includes/admin_header.php';

$action = $_GET['action'] ?? 'list';
$editCategory = null;

// Handle Delete
if (isset($_GET['delete_id'])) {
    $delId = (int)$_GET['delete_id'];
    
    // Get image filename to remove file
    $stmt = $pdo->prepare("SELECT image FROM categories WHERE id = :id");
    $stmt->execute([':id' => $delId]);
    $cat = $stmt->fetch();
    
    if ($cat && $cat['image'] && file_exists(__DIR__ . '/../assets/uploads/categories/' . $cat['image'])) {
        @unlink(__DIR__ . '/../assets/uploads/categories/' . $cat['image']);
    }

    $delStmt = $pdo->prepare("DELETE FROM categories WHERE id = :id");
    $delStmt->execute([':id' => $delId]);
    setFlash('success', 'Category deleted successfully!');
    header("Location: categories.php");
    exit();
}

// Handle Form Submission (Create / Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $cat_id = isset($_POST['cat_id']) ? (int)$_POST['cat_id'] : 0;

    if (empty($name)) {
        setFlash('danger', 'Category name is required.');
    } else {
        $imageName = $_POST['existing_image'] ?? '';

        // Handle Image Upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $targetDir = __DIR__ . '/../assets/uploads/categories';
            $uploadResult = uploadSingleFile($_FILES['image'], $targetDir, ['jpg', 'jpeg', 'png', 'webp']);
            
            if ($uploadResult['status']) {
                // Delete old image if updating
                if ($imageName && file_exists($targetDir . '/' . $imageName)) {
                    @unlink($targetDir . '/' . $imageName);
                }
                $imageName = $uploadResult['filename'];
            } else {
                setFlash('danger', $uploadResult['error']);
            }
        }

        if ($cat_id > 0) {
            // Update
            $updateStmt = $pdo->prepare("UPDATE categories SET name = :name, image = :image WHERE id = :id");
            $updateStmt->execute([':name' => $name, ':image' => $imageName, ':id' => $cat_id]);
            setFlash('success', 'Category updated successfully!');
        } else {
            // Insert
            $insertStmt = $pdo->prepare("INSERT INTO categories (name, image) VALUES (:name, :image)");
            $insertStmt->execute([':name' => $name, ':image' => $imageName]);
            setFlash('success', 'New category added successfully!');
        }

        header("Location: categories.php");
        exit();
    }
}

// If Edit action
if ($action == 'edit' && isset($_GET['id'])) {
    $editId = (int)$_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = :id");
    $stmt->execute([':id' => $editId]);
    $editCategory = $stmt->fetch();
}

// Fetch all categories
$categories = $pdo->query("SELECT c.*, COUNT(p.id) as total_products FROM categories c LEFT JOIN products p ON c.id = p.category_id GROUP BY c.id ORDER BY c.id DESC")->fetchAll();
?>

<div class="row g-4">
    <!-- Category Form (Add / Edit) -->
    <div class="col-lg-4">
        <div class="admin-card">
            <div class="admin-card-header">
                <h5><?php echo $editCategory ? 'Edit Category' : 'Add New Category'; ?></h5>
                <?php if ($editCategory): ?>
                    <a href="categories.php" class="btn btn-sm btn-link text-decoration-none">Cancel</a>
                <?php endif; ?>
            </div>
            <div class="admin-card-body">
                <form action="categories.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="cat_id" value="<?php echo $editCategory['id'] ?? 0; ?>">
                    <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($editCategory['image'] ?? ''); ?>">

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark small">Category Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-lg fs-6" 
                               placeholder="e.g. Pressure Cookers" 
                               value="<?php echo htmlspecialchars($editCategory['name'] ?? ''); ?>" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark small">Category Photo</label>
                        <div class="photo-upload-zone mb-2" onclick="document.getElementById('singleImageInput').click();">
                            <i class="fa-solid fa-cloud-arrow-up display-6 text-muted mb-2"></i>
                            <div class="small text-secondary font-weight-bold">Click to select photo</div>
                            <div class="extra-small text-muted">Supports JPG, PNG, WEBP</div>
                            <input type="file" name="image" id="singleImageInput" class="d-none" accept="image/*">
                        </div>

                        <!-- Preview -->
                        <?php if (!empty($editCategory['image'])): ?>
                            <img id="singleImagePreview" src="../assets/uploads/categories/<?php echo htmlspecialchars($editCategory['image']); ?>" 
                                 class="rounded-3 border mt-2" style="max-height: 100px; object-fit: cover;">
                        <?php else: ?>
                            <img id="singleImagePreview" src="" class="rounded-3 border mt-2" style="max-height: 100px; display: none;">
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn btn-danger btn-lg w-100 py-3 font-heading fw-bold">
                        <i class="fa-solid fa-save me-1"></i> <?php echo $editCategory ? 'Update Category' : 'Save Category'; ?>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Category List Table -->
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="admin-card-header">
                <h5>All Categories</h5>
                <span class="badge bg-danger px-3 py-2"><?php echo count($categories); ?> Categories</span>
            </div>
            <div class="table-responsive">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Category Name</th>
                            <th>Products Count</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($categories)): ?>
                            <tr><td colspan="4" class="text-center text-muted py-4">No categories created yet.</td></tr>
                        <?php else: ?>
                            <?php foreach ($categories as $c): ?>
                                <tr>
                                    <td>
                                        <img src="../assets/uploads/categories/<?php echo htmlspecialchars($c['image']); ?>" 
                                             class="img-thumb-table" 
                                             onerror="this.src='../assets/uploads/categories/cat_steel.jpg';">
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark fs-6"><?php echo htmlspecialchars($c['name']); ?></div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border px-3 py-2"><?php echo $c['total_products']; ?> Items</span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="categories.php?action=edit&id=<?php echo $c['id']; ?>" class="btn btn-sm btn-light border text-primary" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                            <a href="categories.php?delete_id=<?php echo $c['id']; ?>" class="btn btn-sm btn-light border text-danger btn-confirm-delete" title="Delete"><i class="fa-solid fa-trash"></i></a>
                                        </div>
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
