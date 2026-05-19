<?php
/**
 * @var array   $featured   Featured properties from DB (may be empty)
 * @var array   $cities     Available cities from DB
 * @var int     $totalProps Total available properties
 * @var array|null $user    Logged-in user or null
 *
 * Fallback static cards are shown when the DB has no featured properties yet.
 */

// Static fallback data used when DB is empty
$staticProperties = [
    ['id'=>0,'title'=>'Modern Apartment','city'=>'Addis Ababa','sub_city'=>'Bole','price'=>30200,'listing_type'=>'rent','property_type'=>'apartment','bedrooms'=>2,'bathrooms'=>2,'area_sqm'=>120,'is_featured'=>1,'status'=>'available','image_filename'=>'pexels-photo-106399.jpeg','badge'=>'new'],
    ['id'=>0,'title'=>'Luxury Villa','city'=>'Addis Ababa','sub_city'=>'Ayat','price'=>20400,'listing_type'=>'rent','property_type'=>'villa','bedrooms'=>4,'bathrooms'=>3,'area_sqm'=>280,'is_featured'=>1,'status'=>'available','image_filename'=>'pexels-photo-164558.jpeg','badge'=>'premium'],
    ['id'=>0,'title'=>'Cozy Studio','city'=>'Addis Ababa','sub_city'=>'Kazanchis','price'=>30000,'listing_type'=>'rent','property_type'=>'studio','bedrooms'=>1,'bathrooms'=>1,'area_sqm'=>60,'is_featured'=>1,'status'=>'available','image_filename'=>'pexels-photo-271624.jpeg','badge'=>''],
    ['id'=>0,'title'=>'Family Home','city'=>'Addis Ababa','sub_city'=>'Sar Bet','price'=>20600,'listing_type'=>'rent','property_type'=>'house','bedrooms'=>3,'bathrooms'=>2,'area_sqm'=>180,'is_featured'=>1,'status'=>'available','image_filename'=>'pexels-photo-323780_1.jpeg','badge'=>'new'],
    ['id'=>0,'title'=>'Spacious Family House','city'=>'Addis Ababa','sub_city'=>'Sar Bet','price'=>22000,'listing_type'=>'rent','property_type'=>'house','bedrooms'=>3,'bathrooms'=>2,'area_sqm'=>180,'is_featured'=>1,'status'=>'available','image_filename'=>'pexels-photo-1396122.jpeg','badge'=>'premium'],
    ['id'=>0,'title'=>'Downtown Loft','city'=>'Addis Ababa','sub_city'=>'Piazza','price'=>15500,'listing_type'=>'rent','property_type'=>'apartment','bedrooms'=>1,'bathrooms'=>1,'area_sqm'=>85,'is_featured'=>1,'status'=>'available','image_filename'=>'pexels-photo-1080721.jpeg','badge'=>''],
    ['id'=>0,'title'=>'Luxury Penthouse','city'=>'Addis Ababa','sub_city'=>'Bole','price'=>45000,'listing_type'=>'rent','property_type'=>'apartment','bedrooms'=>3,'bathrooms'=>3,'area_sqm'=>220,'is_featured'=>1,'status'=>'available','image_filename'=>'pexels-photo-279746.jpeg','badge'=>'new'],
    ['id'=>0,'title'=>'Cozy Apartment','city'=>'Addis Ababa','sub_city'=>'Gotera','price'=>18000,'listing_type'=>'rent','property_type'=>'apartment','bedrooms'=>2,'bathrooms'=>1,'area_sqm'=>95,'is_featured'=>1,'status'=>'available','image_filename'=>'pexels-photo-1571460.jpeg','badge'=>''],
];

$displayProps = !empty($featured) ? $featured : $staticProperties;
$usingStatic  = empty($featured);
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>RentSmart – Find Your Perfect Home</title>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
  <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/style.css" />
</head>

<body>

<?php include __DIR__ . '/partials/navbar.php'; ?>

<!-- Flash Messages -->
<?php if ($msg = flash('success')): ?>
<div class="alert-banner alert-success"><i class="fas fa-check-circle"></i> <?= e($msg) ?>
  <button class="alert-close" onclick="this.parentElement.remove()">×</button></div>
<?php endif; ?>
<?php if ($msg = flash('error')): ?>
<div class="alert-banner alert-danger"><i class="fas fa-exclamation-circle"></i> <?= e($msg) ?>
  <button class="alert-close" onclick="this.parentElement.remove()">×</button></div>
<?php endif; ?>

<!-- HERO -->
<section class="hero">
  <div class="container hero-content">
    <h1>Find Your Perfect Home</h1>
    <p>Rent or buy beautiful homes in prime locations<?php if ($totalProps > 0): ?> — <?= number_format($totalProps) ?>+ available<?php endif; ?></p>

    <div class="search-box">
      <div class="search-tabs">
        <button class="search-tab active" data-type="rent">Rent</button>
        <button class="search-tab" data-type="sale">Buy</button>
      </div>

      <form class="search-form" action="<?= APP_URL ?>/search" method="GET" id="heroSearchForm">
        <input type="hidden" name="listing_type" id="heroListingType" value="rent" />

        <div class="search-input">
          <label>Location</label>
          <input type="text" name="city" list="cities-list" placeholder="City or area" />
          <datalist id="cities-list">
            <?php if (!empty($cities)): ?>
              <?php foreach ($cities as $city): ?>
              <option value="<?= e($city) ?>"></option>
              <?php endforeach; ?>
            <?php else: ?>
              <option value="Addis Ababa"></option>
              <option value="Dire Dawa"></option>
              <option value="Bahir Dar"></option>
              <option value="Hawassa"></option>
              <option value="Adama"></option>
              <option value="Gondar"></option>
              <option value="Mekelle"></option>
              <option value="Jimma"></option>
              <option value="Bishoftu"></option>
            <?php endif; ?>
          </datalist>
        </div>

        <div class="search-input">
          <label>Property Type</label>
          <select name="property_type">
            <option value="">Any Type</option>
            <option value="apartment">Apartment</option>
            <option value="house">House</option>
            <option value="villa">Villa</option>
            <option value="studio">Studio</option>
            <option value="office">Office</option>
          </select>
        </div>

        <div class="search-input">
          <label>Price Range</label>
          <select name="max_price">
            <option value="">Any Price</option>
            <option value="10000">Up to 10,000 ETB</option>
            <option value="20000">Up to 20,000 ETB</option>
            <option value="30000">Up to 30,000 ETB</option>
            <option value="50000">Up to 50,000 ETB</option>
          </select>
        </div>

        <button type="submit" class="btn-search">Search</button>
      </form>
    </div>
  </div>
</section>

<!-- FEATURED PROPERTIES -->
<section class="featured-properties">
  <div class="container">
    <div class="section-header">
      <h2>Featured Properties</h2>
      <p><?= $usingStatic ? 'Handpicked homes just for you' : 'Live listings from our database' ?></p>
    </div>

    <div class="properties-grid">
      <?php foreach ($displayProps as $p):
        $imgSrc  = imageUrl($p['image_filename'] ?? DEFAULT_PROPERTY_IMG);
        $detailUrl = ($p['id'] > 0) ? APP_URL . '/property/' . $p['id'] : '#';
        $badge   = $p['badge'] ?? ($p['is_featured'] ? 'new' : '');
        $period  = ($p['listing_type'] === 'rent') ? '/ month' : '';
        $subCity = $p['sub_city'] ?? '';
        $location = e($p['city']) . ($subCity ? ', ' . e($subCity) : '');
      ?>
      <a href="<?= $detailUrl ?>" class="property-card" data-mode="<?= $p['listing_type'] === 'sale' ? 'buy' : 'rent' ?>">
        <div class="property-image">
          <img src="<?= $imgSrc ?>" alt="<?= e($p['title']) ?>" loading="lazy" />
          <?php if ($badge): ?>
          <span class="property-badge <?= e($badge) ?>"><?= ucfirst($badge) ?></span>
          <?php endif; ?>
          <?php if ($user): ?>
          <button class="wishlist-btn js-favorite" data-id="<?= (int)$p['id'] ?>" aria-label="Toggle wishlist">♥</button>
          <?php else: ?>
          <a href="<?= APP_URL ?>/login" class="wishlist-btn" aria-label="Login to save">♥</a>
          <?php endif; ?>
        </div>

        <div class="property-content">
          <div class="property-price">
            <span class="price"><?= number_format($p['price']) ?> ETB</span>
            <?php if ($period): ?><span class="period"><?= $period ?></span><?php endif; ?>
          </div>

          <h3 class="property-title"><?= e($p['title']) ?></h3>
          <p class="property-location"><?= $location ?></p>

          <div class="property-features">
            <span><?= (int)$p['bedrooms'] ?> Bed<?= $p['bedrooms'] != 1 ? 's' : '' ?></span>
            <span><?= (int)$p['bathrooms'] ?> Bath<?= $p['bathrooms'] != 1 ? 's' : '' ?></span>
            <?php if (!empty($p['area_sqm'])): ?>
            <span><?= number_format($p['area_sqm']) ?> m²</span>
            <?php endif; ?>
          </div>

          <div class="property-tags">
            <span class="tag"><?= ucfirst($p['property_type'] ?? 'Property') ?></span>
            <span class="tag"><?= ($p['listing_type'] === 'sale') ? 'For Sale' : 'For Rent' ?></span>
          </div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>

    <div class="view-more">
      <a href="<?= APP_URL ?>/search" class="btn-outline large">View More</a>
    </div>
  </div>
</section>

<!-- POPULAR LOCATIONS -->
<section class="popular-locations">
  <div class="container">
    <div class="section-header">
      <h2>Popular Locations</h2>
      <p>Find your next home in these top cities</p>
    </div>

    <div class="locations-grid">
      <?php
      $locationData = [
          ['name'=>'Addis Ababa', 'img'=>'pexels-photo-358488.jpeg'],
          ['name'=>'Hawassa',     'img'=>'pexels-photo-2166553.jpeg'],
          ['name'=>'Dire Dawa',   'img'=>'pexels-photo-3225529.jpeg'],
          ['name'=>'Bahir Dar',   'img'=>'pexels-photo-259962_1.jpeg'],
      ];
      foreach ($locationData as $loc):
      ?>
      <a href="<?= APP_URL ?>/search?city=<?= urlencode($loc['name']) ?>" class="location-card">
        <img src="<?= APP_URL ?>/assets/media/img/<?= $loc['img'] ?>" alt="<?= e($loc['name']) ?>" loading="lazy" />
        <div class="location-overlay">
          <h3><?= e($loc['name']) ?></h3>
          <p><?= $usingStatic ? '—' : number_format((new Property())->getCityCount($loc['name'])) ?>+ Properties</p>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- HOW IT WORKS -->
<section class="how-it-works">
  <div class="container">
    <div class="section-header">
      <h2>How It Works</h2>
      <p>Simple steps to get your dream home</p>
    </div>
    <div class="steps-grid">
      <div class="step-card">
        <div class="step-icon"><span class="icon-number">1</span></div>
        <h3>Search</h3>
        <p>Browse homes by location, price, and type.</p>
      </div>
      <div class="step-card">
        <div class="step-icon"><span class="icon-number">2</span></div>
        <h3>Visit</h3>
        <p>Schedule visits and explore properties.</p>
      </div>
      <div class="step-card">
        <div class="step-icon"><span class="icon-number">3</span></div>
        <h3>Move In</h3>
        <p>Finalize the deal and enjoy your new home.</p>
      </div>
    </div>
  </div>
</section>

<!-- TESTIMONIALS -->
<section class="testimonials-section">
  <div class="container">
    <div class="section-header">
      <h2>What Our Clients Say</h2>
      <p>Real stories from happy homeowners and tenants</p>
    </div>
    <div class="testimonials-grid">
      <div class="testimonial-card">
        <div class="testimonial-content"><p>"RentSmart made finding my first apartment so easy. The filters helped me find exactly what I wanted within my budget."</p></div>
        <div class="testimonial-author">
          <img src="<?= APP_URL ?>/assets/media/img/pexels-photo-220453.jpeg" alt="Client" />
          <div class="author-info"><h4>Abebe Kebede</h4><span>Tenant</span></div>
        </div>
      </div>
      <div class="testimonial-card">
        <div class="testimonial-content"><p>"Professional service and great listings. I found a tenant for my property in just 2 weeks thanks to this platform."</p></div>
        <div class="testimonial-author">
          <img src="<?= APP_URL ?>/assets/media/img/pexels-photo-415829.jpeg" alt="Client" />
          <div class="author-info"><h4>Sara Tesfaye</h4><span>Property Owner</span></div>
        </div>
      </div>
      <div class="testimonial-card">
        <div class="testimonial-content"><p>"The detailed property photos saved me so much time. I knew it was the right house before I even visited in person."</p></div>
        <div class="testimonial-author">
          <img src="<?= APP_URL ?>/assets/media/img/pexels-photo-774909.jpeg" alt="Client" />
          <div class="author-info"><h4>Yared Alemu</h4><span>Home Buyer</span></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- NEWSLETTER -->
<section class="newsletter-section">
  <div class="container newsletter-content">
    <div class="newsletter-text">
      <h2>Stay Updated</h2>
      <p>Subscribe to our newsletter for the latest property listings and market news.</p>
    </div>
    <form class="newsletter-form" id="newsletterForm">
      <input type="email" placeholder="Enter your email address" required />
      <button type="submit" class="btn-primary">Subscribe</button>
    </form>
  </div>
</section>

<!-- CTA -->
<section class="cta-section">
  <div class="container cta-content">
    <h2>Ready to Find Your Home?</h2>
    <p>Join thousands of happy renters and buyers today</p>
    <div class="cta-buttons">
      <?php if ($user): ?>
        <a href="<?= APP_URL ?>/search" class="btn-primary large">Browse Properties</a>
        <a href="<?= APP_URL ?>/dashboard" class="btn-outline large">My Dashboard</a>
      <?php else: ?>
        <a href="<?= APP_URL ?>/signup" class="btn-primary large">Get Started</a>
        <a href="<?= APP_URL ?>/contact" class="btn-outline large">Contact Us</a>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>

<script src="<?= APP_URL ?>/assets/javascript/script.js"></script>
<script>
// Hero search tabs → update hidden listing_type field
document.querySelectorAll('.search-tab[data-type]').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.search-tab[data-type]').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('heroListingType').value = btn.dataset.type;
  });
});

// Newsletter "subscribe" feedback
const nlForm = document.getElementById('newsletterForm');
if (nlForm) {
  nlForm.addEventListener('submit', e => {
    e.preventDefault();
    const btn = nlForm.querySelector('button');
    btn.textContent = 'Subscribed!';
    btn.disabled = true;
    nlForm.querySelector('input').value = '';
    setTimeout(() => { btn.textContent = 'Subscribe'; btn.disabled = false; }, 3000);
  });
}

// Favorite (wishlist) buttons — AJAX toggle
document.querySelectorAll('.js-favorite').forEach(btn => {
  btn.addEventListener('click', e => {
    e.preventDefault();
    e.stopPropagation();
    const id = btn.dataset.id;
    if (!id || id === '0') return;

    fetch(`<?= APP_URL ?>/api/toggle-favorite/${id}`, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': '<?= e($_SESSION['csrf_token'] ?? '') ?>' }
    })
    .then(r => r.json())
    .then(data => {
      btn.classList.toggle('active', data.favorited);
    })
    .catch(() => {
      window.location.href = '<?= APP_URL ?>/login';
    });
  });
});
</script>
</body>
</html>
