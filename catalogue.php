<?php
$pageTitle = "Product Catalogues - Download PDF - Balaji Kitchenware";
require_once __DIR__ . '/includes/header.php';

// Fetch all uploaded catalogues from DB
$catalogues = $pdo->query("SELECT * FROM catalogues ORDER BY id DESC")->fetchAll();
?>

<!-- Catalogue Component Container -->
<div class="container my-4">
    <div class="component-card-light" data-aos="fade-up">
        <div class="text-center mb-5">
            <span class="section-subtitle">PDF Downloads</span>
            <h1 class="display-5 fw-bold text-dark mb-3">Product Catalogues & Price Lists</h1>
            <p class="text-secondary lead mx-auto" style="max-width: 650px;">
                Download our latest product catalogues in PDF format for offline viewing, dealer order placement, and export specifications.
            </p>
        </div>

        <div class="row g-4 justify-content-center">
            <?php if (empty($catalogues)): ?>
                <div class="col-12 text-center py-5">
                    <i class="fa-solid fa-file-pdf text-muted display-1 mb-3"></i>
                    <h4>No Catalogue PDF Uploaded Yet</h4>
                    <p class="text-muted">Catalogues uploaded via the admin panel will appear here.</p>
                </div>
            <?php else: ?>
                <?php foreach ($catalogues as $catPdf): ?>
                    <div class="col-lg-6" data-aos="fade-up">
                        <div class="catalogue-card">
                            <div class="catalogue-icon-wrap">
                                <i class="fa-solid fa-file-pdf"></i>
                            </div>
                            <div class="flex-fill">
                                <span class="badge bg-danger mb-1"><?php echo htmlspecialchars($catPdf['file_size'] ?? 'PDF Document'); ?></span>
                                <h4 class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($catPdf['title']); ?></h4>
                                <p class="text-secondary small mb-3">Uploaded on <?php echo date('M d, Y', strtotime($catPdf['created_at'])); ?></p>
                                
                                <div class="d-flex gap-2">
                                    <a href="assets/uploads/catalogues/<?php echo htmlspecialchars($catPdf['pdf_file']); ?>" 
                                       target="_blank" 
                                       class="btn btn-accent btn-sm px-3 rounded-pill fw-bold">
                                        <i class="fa-solid fa-eye me-1"></i> View PDF
                                    </a>
                                    <a href="assets/uploads/catalogues/<?php echo htmlspecialchars($catPdf['pdf_file']); ?>" 
                                       download 
                                       class="btn btn-outline-custom btn-sm px-3 rounded-pill fw-bold">
                                        <i class="fa-solid fa-download me-1"></i> Download File
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
