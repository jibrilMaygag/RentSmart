<?php
/**
 * @var array      $property    Property with images/amenities
 * @var array|null $user        Logged-in user
 * @var bool       $isFavorited Whether current user has favorited this
 */
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$images   = $property['images']   ?? [];
$amenities= $property['amenities'] ?? [];
$mainImg  = !empty($images) ? imageUrl($images[0]['filename']) : imageUrl(DEFAULT_PROPERTY_IMG);
$badge    = ($property['listing_type'] === 'rent') ? 'FOR RENT' : 'FOR SALE';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= e($property['title']) ?> | RentSmart</title>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
  <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/style.css" />
</head>
<body>

<?php include __DIR__ . '/partials/navbar.php'; ?>

<main class="container listing-page-main">

  <!-- Header -->
  <div class="header-full-width">
    <div class="listing-header-section">
      <div>
        <span class="property-badge new property-badge-static"><?= $badge ?></span>
        <h1><?= e($property['title']) ?></h1>
        <p class="subtitle-large">
          <i class="fas fa-map-marker-alt" style="color:#2563eb;margin-right:0.3rem;"></i>
          <?= e($property['address']) ?>, <?= e($property['city']) ?>
          <?php if (!empty($property['sub_city'])): ?>, <?= e($property['sub_city']) ?><?php endif; ?>
        </p>
      </div>
      <div style="text-align:right;">
        <h2 class="price-tag"><?= number_format($property['price']) ?> ETB</h2>
        <?php if ($property['listing_type'] === 'rent'): ?>
        <span class="text-muted">/ month</span>
        <?php endif; ?>
        <!-- Favorite button -->
        <?php if ($user): ?>
        <br/>
        <button id="favBtn"
                class="btn-outline <?= $isFavorited ? 'favorited' : '' ?>"
                style="margin-top:0.75rem;"
                data-id="<?= (int)$property['id'] ?>">
          <i class="fas fa-heart" style="margin-right:0.4rem;<?= $isFavorited ? 'color:#ef4444;' : '' ?>"></i>
          <span><?= $isFavorited ? 'Saved' : 'Save' ?></span>
        </button>
        <?php else: ?>
        <br/>
        <a href="<?= APP_URL ?>/login" class="btn-outline" style="margin-top:0.75rem;">
          <i class="fas fa-heart" style="margin-right:0.4rem;"></i>Save
        </a>
        <?php endif; ?>
      </div>
    </div>

    <!-- Gallery -->
    <div class="gallery-grid">
      <div class="gallery-item gallery-main">
        <img src="<?= $mainImg ?>" alt="Main view" id="mainGalleryImg" style="cursor:pointer;" />
      </div>
      <?php if (count($images) > 1): ?>
      <div class="gallery-item gallery-sub">
        <img src="<?= imageUrl($images[1]['filename']) ?>" alt="View 2" class="gallery-thumb" style="cursor:pointer;" />
      </div>
      <?php endif; ?>
      <?php if (count($images) > 2): ?>
      <div class="gallery-item gallery-sub">
        <img src="<?= imageUrl($images[2]['filename']) ?>" alt="View 3" class="gallery-thumb" style="cursor:pointer;" />
      </div>
      <?php endif; ?>
      <?php if (empty($images)): ?>
      <div class="gallery-item gallery-sub">
        <img src="<?= APP_URL ?>/assets/media/img/pexels-photo-1080721.jpeg" alt="Interior" />
      </div>
      <div class="gallery-item gallery-sub">
        <img src="<?= APP_URL ?>/assets/media/img/pexels-photo-279746.jpeg" alt="Kitchen" />
      </div>
      <?php endif; ?>
    </div>

    <!-- Two-column layout -->
    <div class="contact-grid property-layout">

      <!-- Details Column -->
      <div class="details">

        <section class="section-margin">
          <h3>Description</h3>
          <p class="text-spaced"><?= nl2br(e($property['description'])) ?></p>
        </section>

        <section class="section-margin">
          <h3>Property Details</h3>
          <div class="property-features property-features-flex">
            <span>🛏 <?= (int)$property['bedrooms'] ?> Bedroom<?= $property['bedrooms'] != 1 ? 's' : '' ?></span>
            <span>🚿 <?= (int)$property['bathrooms'] ?> Bathroom<?= $property['bathrooms'] != 1 ? 's' : '' ?></span>
            <?php if (!empty($property['area_sqm'])): ?>
            <span>📐 <?= number_format($property['area_sqm']) ?> m²</span>
            <?php endif; ?>
            <span>🏠 <?= ucfirst($property['property_type']) ?></span>
          </div>
        </section>

        <?php if (!empty($amenities)): ?>
        <section>
          <h3>Amenities</h3>
          <div class="amenities-grid">
            <?php foreach ($amenities as $a): ?>
            <div class="amenity-item">
              <i class="fas <?= e($a['icon'] ?? 'fa-check') ?>"></i> <?= e($a['name']) ?>
            </div>
            <?php endforeach; ?>
          </div>
        </section>
        <?php endif; ?>

        <?php if (!empty($property['views'])): ?>
        <p style="margin-top:1.5rem;color:#64748b;font-size:0.85rem;">
          <i class="fas fa-eye"></i> <?= number_format($property['views']) ?> view<?= $property['views'] != 1 ? 's' : '' ?>
        </p>
        <?php endif; ?>
      </div>

      <!-- Contact Card -->
      <div>
        <div class="contact-card" style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:1.5rem;position:sticky;top:90px;">
          <h3 style="margin-bottom:1rem;">Contact Landlord</h3>

          <?php if (!empty($property['landlord_name'])): ?>
          <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1rem;">
            <div style="width:44px;height:44px;border-radius:50%;background:var(--primary-color,#2563eb);display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.1rem;flex-shrink:0;">
              <i class="fas fa-user"></i>
            </div>
            <div>
              <p style="margin:0;font-weight:600;"><?= e($property['landlord_name']) ?></p>
              <p style="margin:0;font-size:0.85rem;color:#64748b;">Property Owner</p>
            </div>
          </div>
          <?php endif; ?>

          <?php if ($user): ?>
          <?php if (!empty($property['landlord_phone'])): ?>
          <a href="tel:<?= e($property['landlord_phone']) ?>" class="btn-primary" style="display:block;text-align:center;margin-bottom:0.75rem;">
            <i class="fas fa-phone" style="margin-right:0.4rem;"></i><?= e($property['landlord_phone']) ?>
          </a>
          <?php endif; ?>
          <?php if (!empty($property['landlord_email'])): ?>
          <a href="mailto:<?= e($property['landlord_email']) ?>" class="btn-outline" style="display:block;text-align:center;">
            <i class="fas fa-envelope" style="margin-right:0.4rem;"></i>Send Email
          </a>
          <?php endif; ?>
          <?php if (empty($property['landlord_phone']) && empty($property['landlord_email'])): ?>
          <p style="color:#64748b;font-size:0.9rem;">Contact info not available.</p>
          <?php endif; ?>
          <?php else: ?>
          <p style="color:#64748b;font-size:0.9rem;margin-bottom:1rem;">Login to view contact details.</p>
          <a href="<?= APP_URL ?>/login" class="btn-primary" style="display:block;text-align:center;">Login to Contact</a>
          <?php endif; ?>

          <hr style="margin:1.25rem 0;border-color:#e2e8f0;" />
          <a href="<?= APP_URL ?>/search" style="color:#64748b;font-size:0.875rem;">
            <i class="fas fa-arrow-left" style="margin-right:0.3rem;"></i>Back to results
          </a>
        </div>
      </div>

    </div><!-- /.contact-grid -->
  </div><!-- /.header-full-width -->
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>

<script src="<?= APP_URL ?>/assets/javascript/script.js"></script>
<script>
// Gallery thumbnail click → swap main image
document.querySelectorAll('.gallery-thumb').forEach(img => {
  img.addEventListener('click', () => {
    document.getElementById('mainGalleryImg').src = img.src;
  });
});

// Favorite toggle
const favBtn = document.getElementById('favBtn');
if (favBtn) {
  favBtn.addEventListener('click', () => {
    const id = favBtn.dataset.id;
    fetch(`<?= APP_URL ?>/api/toggle-favorite/${id}`, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': '<?= e($_SESSION['csrf_token'] ?? '') ?>' }
    })
    .then(r => r.json())
    .then(data => {
      const icon = favBtn.querySelector('i');
      const label= favBtn.querySelector('span');
      if (data.favorited) {
        icon.style.color = '#ef4444';
        label.textContent = 'Saved';
        favBtn.classList.add('favorited');
      } else {
        icon.style.color = '';
        label.textContent = 'Save';
        favBtn.classList.remove('favorited');
      }
    });
  });
}
</script>
</body>
</html>
