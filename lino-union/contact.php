<?php
require_once __DIR__ . '/includes/config.php';
$meta_title = 'Contact Us | LINO UNION';
$meta_description = 'Get in touch with LINO UNION. We\'re here to help with any questions about our premium linen clothing.';
require_once __DIR__ . '/includes/header.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $subject = sanitize($_POST['subject'] ?? '');
    $message = sanitize($_POST['message'] ?? '');

    if ($name && $email && $message) {
        try {
            $db = getDB();
            $stmt = $db->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (:name, :email, :subject, :message)");
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':subject' => $subject,
                ':message' => $message
            ]);
            $success = 'Thank you for your message! We\'ll get back to you within 24 hours.';
        } catch (Exception $e) {
            $error = 'An error occurred. Please try again.';
        }
    } else {
        $error = 'Please fill in all required fields.';
    }
}
?>

<!-- ======== Page Header ======== -->
<section class="page-header">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Contact</li>
            </ol>
        </nav>
        <h1 class="page-title">Contact Us</h1>
        <p class="text-muted">We'd love to hear from you. Get in touch with our team.</p>
    </div>
</section>

<!-- ======== Contact Section ======== -->
<section class="contact-section">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-5">
                <h2 class="section-title mb-4">Get in Touch</h2>
                <p class="text-muted mb-4">Have a question about our linen, need sizing advice, or just want to say hello? We're here for you.</p>

                <div class="contact-info-card">
                    <div class="contact-info-icon"><i class="bi bi-geo-alt"></i></div>
                    <div>
                        <h5 class="contact-info-title">Visit Us</h5>
                        <p class="contact-info-text mb-0"><?php echo SITE_ADDRESS; ?><br>Mon–Sat: 10am – 7pm</p>
                    </div>
                </div>

                <div class="contact-info-card">
                    <div class="contact-info-icon"><i class="bi bi-envelope"></i></div>
                    <div>
                        <h5 class="contact-info-title">Email Us</h5>
                        <p class="contact-info-text mb-0">
                            <a href="mailto:<?php echo SITE_EMAIL; ?>"><?php echo SITE_EMAIL; ?></a><br>
                            We reply within 24 hours
                        </p>
                    </div>
                </div>

                <div class="contact-info-card">
                    <div class="contact-info-icon"><i class="bi bi-telephone"></i></div>
                    <div>
                        <h5 class="contact-info-title">Call Us</h5>
                        <p class="contact-info-text mb-0">
                            <a href="tel:<?php echo SITE_PHONE; ?>"><?php echo SITE_PHONE; ?></a><br>
                            Mon–Fri: 9am – 6pm EST
                        </p>
                    </div>
                </div>

                <div class="contact-info-card">
                    <div class="contact-info-icon"><i class="bi bi-chat-dots"></i></div>
                    <div>
                        <h5 class="contact-info-title">Live Chat</h5>
                        <p class="contact-info-text mb-0">Chat with our team during business hours for instant support.</p>
                    </div>
                </div>

                <div class="mt-4">
                    <h5 class="mb-3">Follow Us</h5>
                    <div class="d-flex gap-2">
                        <a href="<?php echo SOCIAL_INSTAGRAM; ?>" target="_blank" class="btn btn-outline-dark" style="width:48px;height:48px;padding:0;display:flex;align-items:center;justify-content:center;"><i class="bi bi-instagram"></i></a>
                        <a href="<?php echo SOCIAL_PINTEREST; ?>" target="_blank" class="btn btn-outline-dark" style="width:48px;height:48px;padding:0;display:flex;align-items:center;justify-content:center;"><i class="bi bi-pinterest"></i></a>
                        <a href="<?php echo SOCIAL_TWITTER; ?>" target="_blank" class="btn btn-outline-dark" style="width:48px;height:48px;padding:0;display:flex;align-items:center;justify-content:center;"><i class="bi bi-twitter-x"></i></a>
                        <a href="<?php echo SOCIAL_FACEBOOK; ?>" target="_blank" class="btn btn-outline-dark" style="width:48px;height:48px;padding:0;display:flex;align-items:center;justify-content:center;"><i class="bi bi-facebook"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <?php if ($success): ?>
                <div class="alert alert-success border-0 rounded-0 py-3"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>
                <?php if ($error): ?>
                <div class="alert alert-danger border-0 rounded-0 py-2"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Subject</label>
                            <input type="text" class="form-control" name="subject" placeholder="How can we help?">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Message <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="message" rows="6" required></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-dark btn-lg">Send Message <i class="bi bi-arrow-right ms-2"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
