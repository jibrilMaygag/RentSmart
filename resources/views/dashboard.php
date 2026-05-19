<?php
/**
 * @var array      $user         Current user
 * @var array      $myProperties Landlord properties
 * @var array      $favorites    User favorites (renters)
 */
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard | RentSmart</title>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
  <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/style.css" />
</head>
<body>

<?php include __DIR__ . '/partials/navbar.php'; ?>

<div class="container" style="padding-top:2.5rem;padding-bottom:4rem;">

  <!-- Flash -->
  <?php if ($success = flash('success')): ?>
  <div class="alert-banner alert-success"><i class="fas fa-check-circle"></i> <?= e($success) ?>
    <button class="alert-close" onclick="this.parentElement.remove()">×</button></div>
  <?php endif; ?>

  <!-- Profile Header -->
  <div style="display:flex;align-items:center;gap:1.25rem;margin-bottom:2.5rem;padding-bottom:2rem;border-bottom:1px solid #e2e8f0;">
    <div style="width:64px;height:64px;border-radius:50%;background:#2563eb;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.6rem;flex-shrink:0;">
      <i class="fas fa-user"></i>
    </div>
    <div>
      <h1 style="margin:0;font-size:1.6rem;">Welcome, <?= e(explode(' ', $user['full_name'])[0]) ?></h1>
      <p style="margin:0;color:#64748b;"><?= e($user['email']) ?> &nbsp;·&nbsp; <?= ucfirst($user['role']) ?></p>
    </div>
    <div style="margin-left:auto;">
      <a href="<?= APP_URL ?>/logout" class="btn-outline" style="color:#ef4444;border-color:#ef4444;">
        <i class="fas fa-sign-out-alt" style="margin-right:0.4rem;"></i>Logout
      </a>
    </div>
  </div>

  <!-- Quick Actions -->
  <div class="properties-grid" style="margin-bottom:2.5rem;">

    <div class="property-card" style="padding:1.5rem;text-align:center;">
      <i class="fas fa-search fa-2x" style="color:#2563eb;margin-bottom:0.75rem;display:block;"></i>
      <h3 style="margin-bottom:0.5rem;">Browse Properties</h3>
      <p style="color:#64748b;font-size:0.9rem;margin-bottom:1rem;">Find your next home.</p>
      <a href="<?= APP_URL ?>/search" class="btn-primary">Search Now</a>
    </div>

    <?php if ($user['role'] === 'landlord'): ?>
    <div class="property-card" style="padding:1.5rem;text-align:center;">
      <i class="fas fa-building fa-2x" style="color:#2563eb;margin-bottom:0.75rem;display:block;"></i>
      <h3 style="margin-bottom:0.5rem;">My Listings</h3>
      <p style="color:#64748b;font-size:0.9rem;margin-bottom:1rem;"><?= count($myProperties) ?> active listing<?= count($myProperties) !== 1 ? 's' : '' ?>.</p>
      <a href="#my-listings" class="btn-primary">View All</a>
    </div>
    <?php else: ?>
    <div class="property-card" style="padding:1.5rem;text-align:center;">
      <i class="fas fa-heart fa-2x" style="color:#ef4444;margin-bottom:0.75rem;display:block;"></i>
      <h3 style="margin-bottom:0.5rem;">Saved Properties</h3>
      <p style="color:#64748b;font-size:0.9rem;margin-bottom:1rem;"><?= count($favorites) ?> saved property<?= count($favorites) !== 1 ? 'ies' : '' ?>.</p>
      <a href="#favorites" class="btn-primary">View Saved</a>
    </div>
    <?php endif; ?>

    <div class="property-card" style="padding:1.5rem;text-align:center;">
      <i class="fas fa-envelope fa-2x" style="color:#2563eb;margin-bottom:0.75rem;display:block;"></i>
      <h3 style="margin-bottom:0.5rem;">Contact Support</h3>
      <p style="color:#64748b;font-size:0.9rem;margin-bottom:1rem;">We're here to help.</p>
      <a href="<?= APP_URL ?>/contact" class="btn-outline">Get Help</a>
    </div>

  </div>

  <!-- Landlord: My Properties -->
  <?php if ($user['role'] === 'landlord'): ?>
  <div id="my-listings">
    <h2 style="margin-bottom:1.25rem;">My Properties</h2>
    <?php if (!empty($myProperties)): ?>
    <div class="properties-grid">
      <?php foreach ($myProperties as $p):
        $imgSrc = imageUrl($p['image_filename'] ?? DEFAULT_PROPERTY_IMG);
      ?>
      <a href="<?= APP_URL ?>/property/<?= (int)$p['id'] ?>" class="property-card">
        <div class="property-image">
          <img src="<?= $imgSrc ?>" alt="<?= e($p['title']) ?>" loading="lazy" />
          <span class="property-badge <?= $p['status'] === 'available' ? 'new' : '' ?>">
            <?= ucfirst($p['status']) ?>
          </span>
        </div>
        <div class="property-content">
          <div class="property-price">
            <span class="price"><?= number_format($p['price']) ?> ETB</span>
            <?php if ($p['listing_type'] === 'rent'): ?><span class="period">/ mo</span><?php endif; ?>
          </div>
          <h3 class="property-title"><?= e($p['title']) ?></h3>
          <p class="property-location"><i class="fas fa-map-marker-alt" style="font-size:0.8rem;"></i> <?= e($p['city']) ?></p>
          <div class="property-features">
            <span><?= (int)$p['bedrooms'] ?> Beds</span>
            <span><?= (int)$p['bathrooms'] ?> Baths</span>
          </div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div style="text-align:center;padding:3rem;color:#64748b;border:1px dashed #e2e8f0;border-radius:12px;">
      <i class="fas fa-home" style="font-size:2.5rem;margin-bottom:0.75rem;opacity:0.3;display:block;"></i>
      <p>You haven't listed any properties yet.</p>
      <a href="<?= APP_URL ?>/contact" class="btn-primary" style="margin-top:0.75rem;">Contact us to list</a>
    </div>
    <?php endif; ?>
  </div>
  <?php endif; ?>

  <!-- Renter: Favorites -->
  <?php if ($user['role'] === 'renter'): ?>
  <div id="favorites">
    <h2 style="margin-bottom:1.25rem;">Saved Properties</h2>
    <?php if (!empty($favorites)): ?>
    <div class="properties-grid">
      <?php foreach ($favorites as $p):
        $imgSrc = imageUrl($p['image_filename'] ?? DEFAULT_PROPERTY_IMG);
      ?>
      <a href="<?= APP_URL ?>/property/<?= (int)$p['id'] ?>" class="property-card">
        <div class="property-image">
          <img src="<?= $imgSrc ?>" alt="<?= e($p['title']) ?>" loading="lazy" />
        </div>
        <div class="property-content">
          <div class="property-price">
            <span class="price"><?= number_format($p['price']) ?> ETB</span>
            <?php if ($p['listing_type'] === 'rent'): ?><span class="period">/ mo</span><?php endif; ?>
          </div>
          <h3 class="property-title"><?= e($p['title']) ?></h3>
          <p class="property-location"><i class="fas fa-map-marker-alt" style="font-size:0.8rem;"></i> <?= e($p['city']) ?></p>
          <div class="property-features">
            <span><?= (int)$p['bedrooms'] ?> Beds</span>
            <span><?= (int)$p['bathrooms'] ?> Baths</span>
          </div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div style="text-align:center;padding:3rem;color:#64748b;border:1px dashed #e2e8f0;border-radius:12px;">
      <i class="fas fa-heart" style="font-size:2.5rem;margin-bottom:0.75rem;opacity:0.3;display:block;"></i>
      <p>You haven't saved any properties yet.</p>
      <a href="<?= APP_URL ?>/search" class="btn-primary" style="margin-top:0.75rem;">Browse Properties</a>
    </div>
    <?php endif; ?>
  </div>
  <?php endif; ?>

</div>

<?php include __DIR__ . '/partials/footer.php'; ?>
<script src="<?= APP_URL ?>/assets/javascript/script.js"></script>
</body>
</html>
