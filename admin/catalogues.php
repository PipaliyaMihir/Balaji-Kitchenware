<?php
require_once __DIR__ . '/includes/admin_header.php';

// Handle Delete
if (isset($_GET['delete_id'])) {
    $delId = (int)$_GET['delete_id'];
    
    $stmt = $pdo->prepare("SELECT pdf_file, thumbnail FROM catalogues WHERE id = :id");
    $stmt->execute([':id' => $delId]);
    $catPdf = $stmt->fetch();
    
    if ($catPdf) {
        if ($catPdf['pdf_file'] && file_exists(__DIR__ . '/../assets/uploads/catalogues/' . $catPdf['pdf_file'])) {
            @unlink(__DIR__ . '/../assets/uploads/catalogues/' . $catPdf['pdf_file']);
        }
        if ($catPdf['thumbnail'] && file_exists(__DIR__ . '/../assets/uploads/catalogues/' . $catPdf['thumbnail'])) {
            @unlink(__DIR__ . '/../assets/uploads/catalogues/' . $catPdf['thumbnail']);
        }
    }

    $delStmt = $pdo->prepare("DELETE FROM catalogues WHERE id = :id");
    $delStmt->execute([':id' => $delId]);
    setFlash('success', 'Catalogue PDF deleted successfully!');
    header("Location: catalogues.php");
    exit();
}

// Handle Form Upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title'] ?? '');

    if (empty($title) || !isset($_FILES['pdf_file']) || $_FILES['pdf_file']['error'] !== UPLOAD_ERR_OK) {
        setFlash('danger', 'Catalogue title and PDF file are required.');
    } else {
        $targetDir = __DIR__ . '/../assets/uploads/catalogues';
        
        // Upload PDF
        $pdfResult = uploadSingleFile($_FILES['pdf_file'], $targetDir, ['pdf']);
        
        if (!$pdfResult['status']) {
            setFlash('danger', $pdfResult['error']);
        } else {
            $pdfFileName = $pdfResult['filename'];
            
            // Format file size string (e.g. 5.2 MB)
            $bytes = $_FILES['pdf_file']['size'];
            $fileSizeStr = round($bytes / (1024 * 1024), 2) . ' MB';

            // Optional Thumbnail Upload
            $thumbFileName = 'catalogue_thumb.jpg';
            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
                $thumbResult = uploadSingleFile($_FILES['thumbnail'], $targetDir, ['jpg', 'jpeg', 'png', 'webp']);
                if ($thumbResult['status']) {
                    $thumbFileName = $thumbResult['filename'];
                }
            }

            $stmt = $pdo->prepare("INSERT INTO catalogues (title, pdf_file, thumbnail, file_size) VALUES (:title, :pdf_file, :thumbnail, :file_size)");
            $stmt->execute([
                ':title' => $title,
                ':pdf_file' => $pdfFileName,
                ':thumbnail' => $thumbFileName,
                ':file_size' => $fileSizeStr
            ]);

            setFlash('success', 'PDF Catalogue uploaded successfully!');
            header("Location: catalogues.php");
            exit();
        }
    }
}

// Fetch all catalogues
$catalogues = $pdo->query("SELECT * FROM catalogues ORDER BY id DESC")->fetchAll();
?>

<div class="row g-4">
    <!-- PDF Upload Form -->
    <div class="col-lg-5">
        <div class="admin-card">
            <div class="admin-card-header">
                <h5>Upload New Catalogue PDF</h5>
            </div>
            <div class="admin-card-body">
                <form action="catalogues.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark small">Catalogue Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control form-control-lg fs-6" 
                               placeholder="e.g. Balaji Kitchenware Master Catalogue 2026" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark small">Select PDF File <span class="text-danger">*</span></label>
                        <div class="photo-upload-zone" onclick="document.getElementById('pdfFileInput').click();">
                            <i class="fa-solid fa-file-pdf display-5 text-danger mb-2"></i>
                            <div class="small text-secondary font-weight-bold">Click to select PDF document</div>
                            <div class="extra-small text-muted">Maximum file size: 20MB (.pdf)</div>
                            <input type="file" name="pdf_file" id="pdfFileInput" class="d-none" accept=".pdf" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark small">Cover Thumbnail (Optional)</label>
                        <input type="file" name="thumbnail" class="form-control fs-6" accept="image/*">
                    </div>

                    <button type="submit" class="btn btn-danger btn-lg w-100 py-3 font-heading fw-bold">
                        <i class="fa-solid fa-cloud-arrow-up me-1"></i> Upload Catalogue PDF
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Catalogues Table -->
    <div class="col-lg-7">
        <div class="admin-card">
            <div class="admin-card-header">
                <h5>Uploaded PDF Catalogues</h5>
                <span class="badge bg-danger px-3 py-2"><?php echo count($catalogues); ?> Files</span>
            </div>
            <div class="table-responsive">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Size</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($catalogues)): ?>
                            <tr><td colspan="4" class="text-center text-muted py-4">No PDF catalogues uploaded yet.</td></tr>
                        <?php else: ?>
                            <?php foreach ($catalogues as $cat): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="bg-danger-subtle text-danger p-3 rounded-3 fs-4">
                                                <i class="fa-solid fa-file-pdf"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark fs-6"><?php echo htmlspecialchars($cat['title']); ?></div>
                                                <div class="small text-muted"><?php echo htmlspecialchars($cat['pdf_file']); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($cat['file_size'] ?? 'PDF'); ?></span></td>
                                    <td class="small text-muted"><?php echo date('M d, Y', strtotime($cat['created_at'])); ?></td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="../assets/uploads/catalogues/<?php echo htmlspecialchars($cat['pdf_file']); ?>" 
                                               target="_blank" class="btn btn-sm btn-light border text-primary" title="View PDF"><i class="fa-solid fa-eye"></i></a>
                                            <a href="catalogues.php?delete_id=<?php echo $cat['id']; ?>" 
                                               class="btn btn-sm btn-light border text-danger btn-confirm-delete" title="Delete"><i class="fa-solid fa-trash"></i></a>
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
