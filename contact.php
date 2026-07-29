<?php
$pageTitle = "Contact Us - Balaji Kitchenware";
require_once __DIR__ . '/includes/header.php';

$successMsg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $message = sanitize($_POST['message'] ?? '');

    if ($name && $phone) {
        $successMsg = "Thank you for reaching out to Balaji Kitchenware! Our wholesale team will get in touch with you shortly.";
    }
}
?>

<!-- Contact Component Container -->
<div class="container my-4">
    <div class="component-card-light" data-aos="fade-up">
        <div class="text-center mb-5">
            <span class="section-subtitle">Get In Touch</span>
            <h1 class="display-5 fw-bold text-dark mb-3">Contact Balaji Kitchenware</h1>
            <p class="text-secondary lead mx-auto" style="max-width: 650px;">
                Have questions about dealership, bulk ordering, or custom OEM manufacturing? Reach out to us today.
            </p>
        </div>

        <div class="row g-5 justify-content-center">
            <!-- Contact Info Sidebar -->
            <div class="col-lg-5" data-aos="fade-right">
                <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm border h-100">
                    <h3 class="fw-bold text-dark mb-4">Corporate Office & Factory</h3>

                    <div class="d-flex align-items-start gap-3 mb-4">
                        <div class="logo-icon bg-danger text-white p-3 rounded-circle fs-4"><i class="fa-solid fa-location-dot"></i></div>
                        <div>
                            <h6 class="fw-bold mb-1">Factory Address:</h6>
                            <p class="text-secondary small mb-0"><?php echo CONTACT_ADDRESS; ?></p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3 mb-4">
                        <div class="logo-icon bg-primary text-white p-3 rounded-circle fs-4"><i class="fa-solid fa-phone"></i></div>
                        <div>
                            <h6 class="fw-bold mb-1">Phone / Wholesale Inquiry:</h6>
                            <p class="text-secondary small mb-0"><?php echo CONTACT_PHONE; ?></p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3 mb-4">
                        <div class="logo-icon bg-warning text-white p-3 rounded-circle fs-4"><i class="fa-solid fa-envelope"></i></div>
                        <div>
                            <h6 class="fw-bold mb-1">Email Address:</h6>
                            <p class="text-secondary small mb-0"><?php echo CONTACT_EMAIL; ?></p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3 mb-4">
                        <div class="logo-icon bg-success text-white p-3 rounded-circle fs-4"><i class="fa-brands fa-whatsapp"></i></div>
                        <div>
                            <h6 class="fw-bold mb-1">Direct WhatsApp Support:</h6>
                            <p class="text-secondary small mb-0">+<?php echo WHATSAPP_NUMBER; ?></p>
                        </div>
                    </div>

                    <hr class="my-4">

                    <a href="<?php echo getWhatsAppLink(); ?>" target="_blank" class="btn btn-whatsapp w-100 py-3 justify-content-center fs-6 rounded-pill fw-bold">
                        <i class="fa-brands fa-whatsapp fs-4 me-2"></i> Start WhatsApp Chat
                    </a>
                </div>
            </div>

            <!-- Inquiry Form -->
            <div class="col-lg-7" data-aos="fade-left">
                <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm border">
                    <h3 class="fw-bold text-dark mb-4">Send Us a Direct Message</h3>

                    <?php if ($successMsg): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fa-solid fa-circle-check me-2"></i> <?php echo $successMsg; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form action="contact.php" method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control form-control-lg fs-6 rounded-3" placeholder="Enter your full name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" name="phone" class="form-control form-control-lg fs-6 rounded-3" placeholder="Mobile / WhatsApp number" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold text-dark">Email Address</label>
                                <input type="email" name="email" class="form-control form-control-lg fs-6 rounded-3" placeholder="yourname@domain.com">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold text-dark">Your Message / Inquiry</label>
                                <textarea name="message" rows="4" class="form-control fs-6 rounded-3" placeholder="Mention product interest, quantity requirements..."></textarea>
                            </div>
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-accent btn-lg w-100 rounded-pill py-3 fw-bold">
                                    Send Direct Inquiry <i class="fa-solid fa-paper-plane ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Google Map Embed Component -->
        <div class="mt-5 rounded-4 overflow-hidden border shadow-sm">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d118147.68202026859!2d70.73889395!3d22.2736308!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3959c98ac71cdf0f%3A0x76dd15cfbe93ad3b!2sRajkot%2C%20Gujarat!5e0!3m2!1sen!2sin!4v1700000000000!5m2!1sen!2sin" 
                    width="100%" height="380" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
