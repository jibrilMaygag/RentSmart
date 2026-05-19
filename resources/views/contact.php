<?php
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact Us | RentSmart</title>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
  <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/style.css" />
</head>
<body>

<?php include __DIR__ . '/partials/navbar.php'; ?>

<!-- Flash -->
<?php if ($success = flash('success')): ?>
<div class="alert-banner alert-success"><i class="fas fa-check-circle"></i> <?= e($success) ?>
  <button class="alert-close" onclick="this.parentElement.remove()">×</button></div>
<?php endif; ?>
<?php if ($error = flash('error')): ?>
<div class="alert-banner alert-danger"><i class="fas fa-exclamation-circle"></i> <?= e($error) ?>
  <button class="alert-close" onclick="this.parentElement.remove()">×</button></div>
<?php endif; ?>

<section class="contact-section">
  <div class="container">
    <div class="section-header">
      <h2>Contact Us</h2>
      <p>We'd love to hear from you. Send us a message and we'll respond shortly.</p>
    </div>

    <div class="contact-grid">
      <!-- Form -->
      <div class="contact-form-wrapper">
        <?php if ($errors = flash('errors')): ?>
        <div class="form-alert form-alert-danger">
          <ul style="margin:0;padding-left:1.25rem;">
            <?php foreach ($errors as $fieldErrors): ?>
              <?php foreach ($fieldErrors as $err): ?>
              <li><?= e($err) ?></li>
              <?php endforeach; ?>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endif; ?>

        <form method="POST" action="<?= APP_URL ?>/contact" id="contactForm">
          <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>" />

          <div class="form-group">
            <label for="name">Full Name <span style="color:#ef4444;">*</span></label>
            <input type="text" id="name" name="name" placeholder="Your full name"
                   value="<?= e($user['full_name'] ?? '') ?>" required />
          </div>

          <div class="form-group">
            <label for="email">Email <span style="color:#ef4444;">*</span></label>
            <input type="email" id="email" name="email" placeholder="your@email.com"
                   value="<?= e($user['email'] ?? '') ?>" required />
          </div>

          <div class="form-group">
            <label for="phone">Phone (optional)</label>
            <input type="tel" id="phone" name="phone" placeholder="+251 900 000 000" />
          </div>

          <div class="form-group">
            <label for="message">Message <span style="color:#ef4444;">*</span></label>
            <textarea id="message" name="message" rows="5" placeholder="How can we help you?" required
                      style="width:100%;padding:0.75rem 1rem;border:1px solid #e2e8f0;border-radius:8px;font-family:inherit;font-size:1rem;resize:vertical;"></textarea>
          </div>

          <button type="submit" class="btn-primary btn-block" id="contactBtn">Send Message</button>
        </form>
      </div>

      <!-- Info Panel -->
      <div class="contact-info" style="padding-left:1rem;">
        <div style="margin-bottom:2rem;">
          <h3 style="margin-bottom:1rem;">Get in Touch</h3>
          <div style="display:flex;gap:0.75rem;align-items:flex-start;margin-bottom:1rem;">
            <i class="fas fa-envelope" style="color:#2563eb;margin-top:0.2rem;"></i>
            <div>
              <p style="margin:0;font-weight:600;">Email</p>
              <p style="margin:0;color:#64748b;">info@rentsmart.com</p>
            </div>
          </div>
          <div style="display:flex;gap:0.75rem;align-items:flex-start;margin-bottom:1rem;">
            <i class="fas fa-phone" style="color:#2563eb;margin-top:0.2rem;"></i>
            <div>
              <p style="margin:0;font-weight:600;">Phone</p>
              <p style="margin:0;color:#64748b;">+251 900 000 000</p>
            </div>
          </div>
          <div style="display:flex;gap:0.75rem;align-items:flex-start;">
            <i class="fas fa-map-marker-alt" style="color:#2563eb;margin-top:0.2rem;"></i>
            <div>
              <p style="margin:0;font-weight:600;">Location</p>
              <p style="margin:0;color:#64748b;">Addis Ababa, Ethiopia</p>
            </div>
          </div>
        </div>

        <div style="background:#f8fafc;border-radius:12px;padding:1.5rem;">
          <h4 style="margin-bottom:0.5rem;">Office Hours</h4>
          <p style="color:#64748b;margin:0;">Mon–Fri: 8:00 AM – 6:00 PM</p>
          <p style="color:#64748b;margin:0;">Sat: 9:00 AM – 3:00 PM</p>
          <p style="color:#64748b;margin:0;">Sun: Closed</p>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
<script src="<?= APP_URL ?>/assets/javascript/script.js"></script>
<script>
document.getElementById('contactForm').addEventListener('submit', function() {
  const btn = document.getElementById('contactBtn');
  btn.textContent = 'Sending…';
  btn.disabled = true;
});
</script>
</body>
</html>
