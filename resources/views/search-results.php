<?php
/**
 * @var array      $properties Search results
 * @var array      $cities     Available cities
 * @var array      $filters    Current filters
 * @var array|null $user       Logged-in user
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Search Properties | RentSmart</title>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
  <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/style.css" />
</head>
<body>

<?php include __DIR__ . '/partials/navbar.php'; ?>

<main class="container search-results-layout">

  <!-- FILTERS SIDEBAR -->
  <aside class="filters-sidebar">
    <h3 style="margin-bottom:1.5rem;">Filters</h3>

    <form method="GET" action="<?= APP_URL ?>/search" id="filterForm">

      <!-- Listing Type Tabs -->
      <div class="form-group" style="margin-bottom:1.5rem;">
        <label style="display:block;margin-bottom:0.5rem;font-weight:600;">I want to</label>
        <div class="search-tabs" style="display:flex;gap:1rem;border-bottom:1px solid #e2e8f0;padding-bottom:0.5rem;">
          <button type="button" class="search-tab <?= ($filters['listing_type'] ?? 'rent') === 'rent' ? 'active' : '' ?>"
                  data-mode="rent" style="background:none;border:none;font-weight:600;cursor:pointer;">Rent</button>
          <button type="button" class="search-tab <?= ($filters['listing_type'] ?? '') === 'sale' ? 'active' : '' ?>"
                  data-mode="buy" style="background:none;border:none;font-weight:600;cursor:pointer;">Buy</button>
        </div>
        <input type="hidden" name="listing_type" id="filter-listing-type" value="<?= e($filters['listing_type'] ?? 'rent') ?>" />
      </div>

      <!-- Location -->
      <div class="form-group" style="margin-bottom:1.5rem;">
        <label style="display:block;margin-bottom:0.5rem;font-weight:600;">Location</label>
        <input type="text" name="city" list="cities-list" placeholder="City, Neighborhood"
               value="<?= e($filters['city'] ?? '') ?>" />
        <datalist id="cities-list">
          <?php foreach ($cities as $c): ?>
          <option value="<?= e($c) ?>"></option>
          <?php endforeach; ?>
        </datalist>
      </div>

      <!-- Keyword -->
      <div class="form-group" style="margin-bottom:1.5rem;">
        <label style="display:block;margin-bottom:0.5rem;font-weight:600;">Keyword</label>
        <input type="text" name="keyword" placeholder="e.g. Bole, studio, luxury"
               value="<?= e($filters['keyword'] ?? '') ?>" />
      </div>

      <!-- Property Type -->
      <div class="form-group" style="margin-bottom:1.5rem;">
        <label style="display:block;margin-bottom:0.5rem;font-weight:600;">Type</label>
        <select name="property_type">
          <option value="">Any</option>
          <?php foreach (['apartment'=>'Apartment','house'=>'House','villa'=>'Villa','studio'=>'Studio','office'=>'Office'] as $val => $label): ?>
          <option value="<?= $val ?>" <?= ($filters['property_type'] ?? '') === $val ? 'selected' : '' ?>><?= $label ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Price Range -->
      <div class="form-group" style="margin-bottom:1.5rem;">
        <label style="display:block;margin-bottom:0.5rem;font-weight:600;">Price Range (ETB)</label>
        <div style="display:flex;gap:0.5rem;">
          <input type="number" name="min_price" placeholder="Min" min="0"
                 value="<?= e($filters['min_price'] ?? '') ?>" style="width:50%;" />
          <input type="number" name="max_price" placeholder="Max" min="0"
                 value="<?= e($filters['max_price'] ?? '') ?>" style="width:50%;" />
        </div>
      </div>

      <!-- Bedrooms -->
      <div class="form-group" style="margin-bottom:1.5rem;">
        <label style="display:block;margin-bottom:0.5rem;font-weight:600;">Min Bedrooms</label>
        <div style="display:flex;gap:0.5rem;">
          <?php foreach ([1,2,3] as $n): ?>
          <button type="button"
                  class="btn-outline bedroom-btn <?= (int)($filters['bedrooms'] ?? 0) === $n ? 'active-filter' : '' ?>"
                  data-beds="<?= $n ?>"
                  style="padding:0.5rem 1rem;flex:1;"><?= $n ?><?= $n === 3 ? '+' : '' ?></button>
          <?php endforeach; ?>
        </div>
        <input type="hidden" name="bedrooms" id="bedroomsInput" value="<?= e($filters['bedrooms'] ?? '') ?>" />
      </div>

      <button type="submit" class="btn-primary" style="width:100%;">Apply Filters</button>
      <a href="<?= APP_URL ?>/search" style="display:block;text-align:center;margin-top:0.75rem;color:#64748b;font-size:0.875rem;">Clear all</a>
    </form>
  </aside>

  <!-- RESULTS AREA -->
  <div style="flex:1;min-width:0;">
    <div style="margin-bottom:2rem;">
      <h1 style="font-size:2rem;margin-bottom:0.5rem;" id="resultsHeading">
        Properties for <?= ($filters['listing_type'] ?? 'rent') === 'rent' ? 'Rent' : 'Sale' ?>
        <?php if (!empty($filters['city'])): ?> in <?= e($filters['city']) ?><?php endif; ?>
      </h1>
      <p><?= count($properties) ?> result<?= count($properties) !== 1 ? 's' : '' ?> found</p>
    </div>

    <?php if (!empty($properties)): ?>
    <div class="properties-grid" style="margin-bottom:0;">
      <?php foreach ($properties as $p):
        $imgSrc    = imageUrl($p['image_filename'] ?? DEFAULT_PROPERTY_IMG);
        $detailUrl = APP_URL . '/property/' . $p['id'];
        $period    = ($p['listing_type'] === 'rent') ? '/ mo' : '';
      ?>
      <a href="<?= $detailUrl ?>" class="property-card" data-mode="<?= $p['listing_type'] === 'sale' ? 'buy' : 'rent' ?>">
        <div class="property-image">
          <img src="<?= $imgSrc ?>" alt="<?= e($p['title']) ?>" loading="lazy" />
          <?php if ($p['is_featured']): ?>
          <span class="property-badge premium">Featured</span>
          <?php endif; ?>
        </div>
        <div class="property-content">
          <div class="property-price">
            <span class="price"><?= number_format($p['price']) ?> ETB</span>
            <?php if ($period): ?><span class="period"><?= $period ?></span><?php endif; ?>
          </div>
          <h3 class="property-title"><?= e($p['title']) ?></h3>
          <p class="property-location">
            <i class="fas fa-map-marker-alt" style="font-size:0.8rem;"></i>
            <?= e($p['city']) ?><?= !empty($p['sub_city']) ? ', ' . e($p['sub_city']) : '' ?>
          </p>
          <div class="property-features">
            <span><?= (int)$p['bedrooms'] ?> Bed<?= $p['bedrooms'] != 1 ? 's' : '' ?></span>
            <span><?= (int)$p['bathrooms'] ?> Bath<?= $p['bathrooms'] != 1 ? 's' : '' ?></span>
            <?php if (!empty($p['area_sqm'])): ?><span><?= number_format($p['area_sqm']) ?> m²</span><?php endif; ?>
          </div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>

    <?php else: ?>
    <div style="text-align:center;padding:4rem 2rem;color:#64748b;">
      <i class="fas fa-home" style="font-size:3rem;margin-bottom:1rem;opacity:0.3;display:block;"></i>
      <h3 style="margin-bottom:0.5rem;">No properties found</h3>
      <p>Try adjusting your filters or <a href="<?= APP_URL ?>/search" style="color:#2563eb;">clear all filters</a>.</p>
    </div>
    <?php endif; ?>
  </div>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>

<script src="<?= APP_URL ?>/assets/javascript/script.js"></script>
<script>
// Listing type tabs
document.querySelectorAll('.search-tab[data-mode]').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.search-tab[data-mode]').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('filter-listing-type').value = btn.dataset.mode === 'buy' ? 'sale' : 'rent';
  });
});

// Bedroom buttons
document.querySelectorAll('.bedroom-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    const beds = btn.dataset.beds;
    const current = document.getElementById('bedroomsInput').value;
    if (current === beds) {
      document.getElementById('bedroomsInput').value = '';
      btn.classList.remove('active-filter');
    } else {
      document.querySelectorAll('.bedroom-btn').forEach(b => b.classList.remove('active-filter'));
      btn.classList.add('active-filter');
      document.getElementById('bedroomsInput').value = beds;
    }
  });
});
</script>
<style>
.active-filter { background: #2563eb !important; color: #fff !important; border-color: #2563eb !important; }
</style>
</body>
</html>
