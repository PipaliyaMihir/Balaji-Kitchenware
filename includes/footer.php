<?php
// Fetch categories for footer Product Range links
$footerCatStmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC LIMIT 6");
$footerCategories = $footerCatStmt->fetchAll();
?>
    <!-- Floating WhatsApp Action Button -->
    <a href="<?php echo getWhatsAppLink(); ?>" target="_blank" class="whatsapp-float-btn" title="Chat on WhatsApp">
        <i class="fa-brands fa-whatsapp"></i>
    </a>

    <!-- Footer -->
    <footer class="footer-main">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="mb-3">
                        <img src="assets/images/logo.png" alt="Balaji Enterprise Logo" style="height: 50px; width: auto; filter: brightness(0) invert(1);">
                    </div>
                    <p class="small mb-4 text-white">
                        Balaji Kitchenware (Balaji Enterprise) is a trusted manufacturer and exporter of high-grade stainless steel pressure cookers, non-stick cookware, containers, and household utensils.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="#" class="btn btn-sm btn-outline-light rounded-circle"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="btn btn-sm btn-outline-light rounded-circle"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="btn btn-sm btn-outline-light rounded-circle"><i class="fa-brands fa-linkedin-in"></i></a>
                        <a href="#" class="btn btn-sm btn-outline-light rounded-circle"><i class="fa-brands fa-youtube"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6">
                    <h5 class="text-white fw-bold">Quick Links</h5>
                    <ul class="footer-links">
                        <li><a href="index.php"><i class="fa-solid fa-chevron-right me-1 small"></i> Home</a></li>
                        <li><a href="about.php"><i class="fa-solid fa-chevron-right me-1 small"></i> About Us</a></li>
                        <li><a href="categories.php"><i class="fa-solid fa-chevron-right me-1 small"></i> Categories</a></li>
                        <li><a href="products.php"><i class="fa-solid fa-chevron-right me-1 small"></i> Products</a></li>
                        <li><a href="catalogue.php"><i class="fa-solid fa-chevron-right me-1 small"></i> Catalogue PDF</a></li>
                        <li><a href="contact.php"><i class="fa-solid fa-chevron-right me-1 small"></i> Contact Us</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h5 class="text-white fw-bold">Product Categories</h5>
                    <ul class="footer-links">
                        <?php foreach ($footerCategories as $fCat): ?>
                            <li>
                                <a href="products.php?cat=<?php echo $fCat['id']; ?>">
                                    <i class="fa-solid fa-chevron-right me-1 small"></i> <?php echo htmlspecialchars($fCat['name']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h5 class="text-white fw-bold">Contact Information</h5>
                    <div class="small text-white">
                        <p class="mb-2 text-white"><i class="fa-solid fa-location-dot text-danger me-2"></i> <?php echo CONTACT_ADDRESS; ?></p>
                        <p class="mb-2 text-white"><i class="fa-solid fa-phone text-danger me-2"></i> <?php echo CONTACT_PHONE; ?></p>
                        <p class="mb-2 text-white"><i class="fa-solid fa-envelope text-danger me-2"></i> <?php echo CONTACT_EMAIL; ?></p>
                        <p class="mb-0 text-white"><i class="fa-brands fa-whatsapp text-success me-2"></i> WhatsApp: +<?php echo WHATSAPP_NUMBER; ?></p>
                    </div>
                </div>
            </div>

            <hr class="my-4 border-secondary">

            <div class="row align-items-center">
                <div class="col-12 text-center small text-white">
                    © <?php echo date('Y'); ?> <strong>Balaji Enterprise (Balaji Kitchenware)</strong>. All Rights Reserved.
                </div>
            </div>
        </div>
    </footer>

    <!-- Product Detail & Multi-Photo Gallery Modal -->
    <div class="modal fade" id="productDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header border-0 bg-light">
                    <h5 class="modal-title font-heading fw-bold" id="modalProdTitle">Product Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <!-- Multi-Photo Gallery Slider / Thumbnails -->
                        <div class="col-md-6">
                            <div class="modal-product-gallery">
                                <div class="modal-main-img-wrap">
                                    <img id="modalMainImg" src="" alt="Product Image">
                                </div>
                                <div class="modal-thumbs-row" id="modalThumbsRow">
                                    <!-- Thumbnails populated dynamically via JS -->
                                </div>
                            </div>
                        </div>

                        <!-- Product Specs -->
                        <div class="col-md-6 d-flex flex-column justify-content-between">
                            <div>
                                <span class="badge bg-danger mb-2" id="modalProdCat">Category</span>
                                <h4 class="fw-bold text-dark mb-3" id="modalProdTitleBody">Item Name</h4>
                                
                                <div class="bg-light p-3 rounded-3 mb-3 border">
                                    <div class="row text-center">
                                        <div class="col-4 border-end">
                                            <div class="text-muted small fw-bold">SKU</div>
                                            <div class="fw-bold text-dark" id="modalProdSku">SKU-000</div>
                                        </div>
                                        <div class="col-4 border-end">
                                            <div class="text-muted small fw-bold">INNER PACK</div>
                                            <div class="fw-bold text-dark" id="modalProdInnerPack">1 Pcs</div>
                                        </div>
                                        <div class="col-4">
                                            <div class="text-muted small fw-bold">OUTER PACK</div>
                                            <div class="fw-bold text-dark" id="modalProdOuterPack">24 Pcs</div>
                                        </div>
                                    </div>
                                </div>

                                <h6 class="fw-bold text-dark mb-1">Product Description:</h6>
                                <p class="text-secondary small mb-4" id="modalProdDesc">Description...</p>
                            </div>

                            <div>
                                <a id="modalWaBtn" href="#" target="_blank" class="btn btn-whatsapp w-100 py-3 justify-content-center">
                                    <i class="fa-brands fa-whatsapp fs-4"></i> Inquire Price & Order on WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/bootstrap.bundle.min.js"></script>
    
    <!-- AOS Animation Library CDN -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true,
            easing: 'ease-out-cubic'
        });
    </script>

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>
</body>
</html>
