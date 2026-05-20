<?php
/**
 * @var array      $featured
 * @var array      $cities
 * @var int        $totalProps
 * @var array|null $user
 */
$pageTitle = 'RentSmart | Premium Rental Marketplace';
$bodyClass = 'app-shell';

$propertyTypes = [
    'apartment' => 'Apartment',
    'house' => 'House',
    'villa' => 'Villa',
    'studio' => 'Studio',
    'office' => 'Office',
    'land' => 'Land',
];

$heroImage = 'https://images.unsplash.com/photo-1774979517612-db480ae5b587?auto=format&fit=crop&fm=jpg&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&ixlib=rb-4.1.0&q=60&w=1800';
$cityImages = [
    'Addis Ababa' => 'https://images.unsplash.com/photo-1724001079027-800ed9a8ee4d?auto=format&fit=crop&fm=jpg&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&ixlib=rb-4.1.0&q=60&w=1600',
    'Dire Dawa' => 'https://images.unsplash.com/photo-1771495604392-2008757fb32a?auto=format&fit=crop&fm=jpg&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&ixlib=rb-4.1.0&q=60&w=1600',
    'Bahir Dar' => 'https://images.unsplash.com/photo-1771495562804-373fb516114c?auto=format&fit=crop&fm=jpg&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&ixlib=rb-4.1.0&q=60&w=1600',
    'Hawassa' => 'https://images.unsplash.com/photo-1768638687896-35bde623d532?auto=format&fit=crop&fm=jpg&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&ixlib=rb-4.1.0&q=60&w=1600',
    'Adama' => 'https://images.unsplash.com/photo-1774979517612-db480ae5b587?auto=format&fit=crop&fm=jpg&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&ixlib=rb-4.1.0&q=60&w=1600',
    'Gondar' => 'https://images.unsplash.com/photo-1721395283507-1b17e527a922?auto=format&fit=crop&fm=jpg&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&ixlib=rb-4.1.0&q=60&w=1600',
];

$featuredCities = array_slice(!empty($cities) ? $cities : array_keys($cityImages), 0, 4);

include __DIR__ . '/partials/head.php';
include __DIR__ . '/partials/header.php';
?>
<main class="pb-16 pt-24 sm:pt-28">
  <section class="relative isolate overflow-hidden">
    <div class="absolute inset-0">
      <img
        src="<?= e(imageUrl($heroImage)) ?>"
        alt="Modern RentSmart property exterior"
        class="h-full w-full object-cover"
      />
      <div class="absolute inset-0 bg-slate-950/55"></div>
      <div class="absolute inset-0 bg-gradient-to-r from-slate-950/80 via-slate-950/45 to-slate-950/15"></div>
    </div>

    <div class="app-container relative z-10 py-16 sm:py-24 lg:py-32">
      <div class="max-w-4xl">
        <span class="inline-flex rounded-full bg-secondary/20 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-secondary-container">
          Smart rental marketplace
        </span>
        <h1 class="mt-8 max-w-4xl text-5xl font-bold tracking-[-0.02em] text-white sm:text-6xl lg:text-7xl">
          Your home is waiting
        </h1>
        <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-100 sm:text-xl">
          Discover verified properties, connect with trusted landlords, and find a place that fits your lifestyle.
        </p>
      </div>

      <div class="mt-12 max-w-5xl rounded-[1.5rem] border border-white/15 bg-white/92 p-3 shadow-float backdrop-blur-xl">
        <div class="app-card overflow-hidden border-none bg-transparent shadow-none">
          <div class="border-b border-outline-variant/20 bg-primary px-4 py-5 sm:px-6">
            <?php include __DIR__ . '/partials/flash.php'; ?>
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
              <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-secondary-container">Begin your search</p>
                <h2 class="mt-2 text-2xl font-bold tracking-tight text-white">Find properties in seconds</h2>
              </div>
              <div
                class="inline-flex rounded-2xl border border-outline-variant/30 bg-surface-container-low p-1"
                data-listing-toggle-group
                data-target="homeListingType"
              >
                <button
                  type="button"
                  data-listing-toggle="rent"
                  class="rounded-xl px-5 py-2 text-sm font-semibold bg-primary text-white"
                >
                  Rent
                </button>
                <button
                  type="button"
                  data-listing-toggle="sale"
                  class="rounded-xl px-5 py-2 text-sm font-semibold bg-surface-container-lowest text-on-surface-variant"
                >
                  Buy
                </button>
              </div>
            </div>
          </div>

          <form action="<?= route('search') ?>" method="GET" class="grid gap-3 px-4 py-5 sm:grid-cols-2 sm:px-6 lg:grid-cols-[minmax(0,1.1fr)_minmax(0,0.8fr)_minmax(0,0.8fr)_auto]">
            <input type="hidden" name="listing_type" id="homeListingType" value="rent" />

            <div>
              <label for="homeCity" class="field-label">Where</label>
              <input
                id="homeCity"
                name="city"
                list="home-cities"
                class="field-input"
                placeholder="City or neighborhood"
              />
              <datalist id="home-cities">
                <?php foreach ($cities as $city): ?>
                <option value="<?= e($city) ?>"></option>
                <?php endforeach; ?>
              </datalist>
            </div>

            <div>
              <label for="homePropertyType" class="field-label">Property type</label>
              <select id="homePropertyType" name="property_type" class="field-input">
                <option value="">Any type</option>
                <?php foreach ($propertyTypes as $value => $label): ?>
                <option value="<?= e($value) ?>"><?= e($label) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div>
              <label for="homeMaxPrice" class="field-label">Budget</label>
              <select id="homeMaxPrice" name="max_price" class="field-input">
                <option value="">Any budget</option>
                <option value="10000">Up to 10,000 ETB</option>
                <option value="20000">Up to 20,000 ETB</option>
                <option value="30000">Up to 30,000 ETB</option>
                <option value="50000">Up to 50,000 ETB</option>
              </select>
            </div>

            <div class="flex items-end">
              <button type="submit" class="btn-primary w-full lg:w-auto px-8 py-3.5 text-base font-semibold gap-2">
                <span class="material-symbols-outlined text-base">search</span>
                <span>Search Properties</span>
              </button>
            </div>
          </form>
        </div>
      </div>

      <div class="mt-10 grid gap-4 sm:grid-cols-3">
        <div class="rounded-2xl border border-white/10 bg-white/10 p-6 backdrop-blur-md">
          <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-3xl text-secondary">apartment</span>
            <div>
              <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-300">Active listings</p>
              <p class="mt-1 text-3xl font-bold text-white"><?= number_format($totalProps) ?></p>
            </div>
          </div>
        </div>
        <div class="rounded-2xl border border-white/10 bg-white/10 p-6 backdrop-blur-md">
          <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-3xl text-secondary">location_on</span>
            <div>
              <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-300">Locations</p>
              <p class="mt-1 text-3xl font-bold text-white"><?= number_format(count($cities)) ?></p>
            </div>
          </div>
        </div>
        <div class="rounded-2xl border border-white/10 bg-white/10 p-6 backdrop-blur-md">
          <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-3xl text-secondary">verified_user</span>
            <div>
              <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-300">Trusted</p>
              <p class="mt-1 text-base font-medium leading-6 text-slate-100">Verified listings &amp; secure messaging</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="app-container py-16 sm:py-20">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
      <div>
        <span class="section-eyebrow">Curated selection</span>
        <h2 class="text-3xl font-semibold tracking-tight text-primary">Featured properties</h2>
        <p class="mt-3 max-w-2xl text-base leading-7 text-on-surface-variant">
          Explore standout listings handpicked to help you start your search with confidence.
        </p>
      </div>
      <a href="<?= route('search') ?>" class="btn-secondary">
        View all listings
      </a>
    </div>

    <?php if (!empty($featured)): ?>
    <div class="property-grid mt-10">
        <?php foreach ($featured as $property): ?>
          <?php
        $showFavoriteButton = !$user || (($user['role'] ?? 'renter') === 'renter');
        $badgeLabel = !empty($property['is_featured']) ? 'Featured' : '';
        include __DIR__ . '/partials/property-card.php';
        ?>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="app-card mt-10 p-8 text-center">
      <span class="material-symbols-outlined text-4xl text-primary-fixed-variant">real_estate_agent</span>
      <h3 class="mt-4 text-2xl font-semibold tracking-tight text-primary">No featured properties yet</h3>
      <p class="mx-auto mt-3 max-w-xl text-base leading-7 text-on-surface-variant">
        New featured homes are on the way. Browse all listings or reach out if you need help finding the right place.
      </p>
      <div class="mt-6 flex flex-col justify-center gap-3 sm:flex-row">
        <a href="<?= route('search') ?>" class="btn-primary">Browse Properties</a>
        <a href="<?= route('contact') ?>" class="btn-secondary">Contact Support</a>
      </div>
    </div>
    <?php endif; ?>
  </section>

  <section class="border-y border-outline-variant/20 bg-surface-container-low">
    <div class="app-container py-16 sm:py-20">
      <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
          <span class="section-eyebrow">Popular locations</span>
          <h2 class="text-3xl font-semibold tracking-tight text-primary">Explore city by city</h2>
          <p class="mt-3 max-w-2xl text-base leading-7 text-on-surface-variant">
            Browse some of the cities renters and buyers explore most on RentSmart.
          </p>
        </div>
        <a href="<?= route('search') ?>" class="text-sm font-semibold text-primary transition hover:text-secondary">See all cities</a>
      </div>

      <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-4">
        <?php foreach ($featuredCities as $city): ?>
          <?php $cityImage = $cityImages[$city] ?? $cityImages['Addis Ababa']; ?>
        <a href="<?= route('search', ['city' => $city]) ?>" class="group relative overflow-hidden rounded-[1.5rem] shadow-soft">
          <img
            src="<?= e(imageUrl($cityImage)) ?>"
            alt="<?= e($city) ?>"
            class="h-72 w-full object-cover transition duration-700 group-hover:scale-105"
          />
          <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-950/35 to-transparent"></div>
          <div class="absolute inset-x-0 bottom-0 p-6">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-secondary-container">Explore</p>
            <h3 class="mt-2 text-2xl font-semibold tracking-tight text-white"><?= e($city) ?></h3>
            <p class="mt-2 text-sm text-slate-200">Browse homes in this area and narrow your search by type, budget, and bedrooms.</p>
          </div>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="app-container py-16 sm:py-20">
    <div class="grid gap-6 lg:grid-cols-2">
      <div class="app-card p-8">
        <span class="material-symbols-outlined inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-primary text-white">person_search</span>
        <h3 class="mt-6 text-2xl font-semibold tracking-tight text-primary">For renters</h3>
        <div class="mt-6 space-y-5 text-sm leading-7 text-on-surface-variant">
          <p><span class="font-semibold text-primary">01.</span> Search real listings by location, price, property type, and bedrooms.</p>
          <p><span class="font-semibold text-primary">02.</span> Open full property details, review amenities, and contact landlords once you sign in.</p>
          <p><span class="font-semibold text-primary">03.</span> Save properties and return to them later from a streamlined renter dashboard.</p>
        </div>
        <a href="<?= route('search') ?>" class="btn-secondary mt-8">Browse properties</a>
      </div>

      <div class="app-card p-8">
        <span class="material-symbols-outlined inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-secondary text-white">home_work</span>
        <h3 class="mt-6 text-2xl font-semibold tracking-tight text-primary">For landlords</h3>
        <div class="mt-6 space-y-5 text-sm leading-7 text-on-surface-variant">
          <p><span class="font-semibold text-primary">01.</span> Create and manage listings from one simple dashboard.</p>
          <p><span class="font-semibold text-primary">02.</span> Showcase pricing, amenities, photos, and key details clearly for renters.</p>
          <p><span class="font-semibold text-primary">03.</span> Keep track of inquiries and update availability as your listings change.</p>
        </div>
        <a href="<?= route('contact') ?>" class="btn-primary mt-8">Contact the team</a>
      </div>
    </div>
  </section>

  <section class="app-container pb-16">
    <div class="overflow-hidden rounded-[2rem] bg-primary px-6 py-10 text-white shadow-float sm:px-10 sm:py-12">
      <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
        <div class="max-w-2xl">
          <span class="text-xs font-semibold uppercase tracking-[0.22em] text-secondary-container">RentSmart</span>
          <h2 class="mt-4 text-3xl font-semibold tracking-tight sm:text-4xl">
            Find, list, and manage properties with confidence.
          </h2>
          <p class="mt-4 text-base leading-7 text-slate-200">
            RentSmart brings property search, saved homes, and landlord communication together in one straightforward experience.
          </p>
        </div>
        <div class="flex flex-col gap-3 sm:flex-row">
          <?php if ($user): ?>
          <a href="<?= route('dashboard') ?>" class="btn-secondary border-white/20 bg-white/10 text-white hover:bg-white/15">Open Dashboard</a>
          <?php else: ?>
          <a href="<?= route('signup') ?>" class="btn-secondary border-white/20 bg-white/10 text-white hover:bg-white/15">Create account</a>
          <?php endif; ?>
          <a href="<?= route('search') ?>" class="btn-primary border border-white/0 bg-white text-primary hover:opacity-100">Explore listings</a>
        </div>
      </div>
    </div>
  </section>
</main>
<?php include __DIR__ . '/partials/footer.php'; ?>
