<?php
/**
 * @var array      $properties
 * @var array      $cities
 * @var array      $filters
 * @var array|null $user
 */
$pageTitle = 'Browse Properties | RentSmart';
$bodyClass = 'app-shell';

$listingType = $filters['listing_type'] ?? 'rent';
$propertyTypes = [
    'apartment' => 'Apartment',
    'house' => 'House',
    'villa' => 'Villa',
    'studio' => 'Studio',
    'office' => 'Office',
    'land' => 'Land',
];

$resultHeading = $listingType === 'sale' ? 'Properties for sale' : 'Properties for rent';
if (!empty($filters['city'])) {
    $resultHeading .= ' in ' . $filters['city'];
}

include __DIR__ . '/partials/head.php';
include __DIR__ . '/partials/header.php';
?>
<main class="app-container pb-16 pt-24 sm:pt-28">
  <section class="rounded-[2rem] border border-outline-variant/20 bg-surface-container-low px-6 py-10 sm:px-8">
    <?php include __DIR__ . '/partials/flash.php'; ?>
    <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
      <div class="max-w-3xl">
        <span class="section-eyebrow">Browse properties</span>
        <h1 class="text-3xl font-semibold tracking-tight text-primary sm:text-4xl"><?= e($resultHeading) ?></h1>
        <p class="mt-3 text-base leading-7 text-on-surface-variant">
          <?= number_format(count($properties)) ?> result<?= count($properties) === 1 ? '' : 's' ?> found.
          Use the filters to narrow down homes that fit your needs.
        </p>
      </div>
      <div class="flex flex-wrap gap-3 text-sm text-on-surface-variant">
        <?php if (!empty($filters['keyword'])): ?>
        <span class="inline-flex items-center rounded-full bg-surface-container-lowest px-4 py-2 shadow-soft">Keyword: <?= e($filters['keyword']) ?></span>
        <?php endif; ?>
        <?php if (!empty($filters['property_type'])): ?>
        <span class="inline-flex items-center rounded-full bg-surface-container-lowest px-4 py-2 shadow-soft"><?= e($propertyTypes[$filters['property_type']] ?? ucfirst($filters['property_type'])) ?></span>
        <?php endif; ?>
        <?php if (!empty($filters['bedrooms'])): ?>
        <span class="inline-flex items-center rounded-full bg-surface-container-lowest px-4 py-2 shadow-soft"><?= (int)$filters['bedrooms'] ?>+ bedrooms</span>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <section class="mt-8 grid gap-8 lg:grid-cols-[320px_minmax(0,1fr)]">
    <aside class="lg:sticky lg:top-28 lg:self-start">
      <div class="app-card p-6">
        <div class="flex items-center justify-between">
          <h2 class="text-xl font-semibold tracking-tight text-primary">Filters</h2>
          <a href="<?= route('search') ?>" class="text-sm font-medium text-on-surface-variant transition hover:text-primary">Clear all</a>
        </div>

        <form action="<?= route('search') ?>" method="GET" class="mt-6 space-y-5">
          <div>
            <label class="field-label">I want to</label>
            <div
              class="inline-flex w-full rounded-2xl border border-outline-variant/30 bg-surface-container-low p-1"
              data-listing-toggle-group
              data-target="searchListingType"
            >
              <button
                type="button"
                data-listing-toggle="rent"
                class="flex-1 rounded-xl px-4 py-2 text-sm font-semibold <?= $listingType === 'rent' ? 'bg-primary text-white' : 'bg-surface-container-lowest text-on-surface-variant' ?>"
              >
                Rent
              </button>
              <button
                type="button"
                data-listing-toggle="sale"
                class="flex-1 rounded-xl px-4 py-2 text-sm font-semibold <?= $listingType === 'sale' ? 'bg-primary text-white' : 'bg-surface-container-lowest text-on-surface-variant' ?>"
              >
                Buy
              </button>
            </div>
            <input type="hidden" name="listing_type" id="searchListingType" value="<?= e($listingType) ?>" />
          </div>

          <div>
            <label for="searchCity" class="field-label">Location</label>
            <input
              id="searchCity"
              name="city"
              list="search-cities"
              class="field-input"
              placeholder="City or neighborhood"
              value="<?= e($filters['city'] ?? '') ?>"
            />
            <datalist id="search-cities">
              <?php foreach ($cities as $city): ?>
              <option value="<?= e($city) ?>"></option>
              <?php endforeach; ?>
            </datalist>
          </div>

          <div>
            <label for="searchKeyword" class="field-label">Keyword</label>
            <input
              id="searchKeyword"
              name="keyword"
              class="field-input"
              placeholder="Bole, studio, balcony"
              value="<?= e($filters['keyword'] ?? '') ?>"
            />
          </div>

          <div>
            <label for="searchPropertyType" class="field-label">Property type</label>
            <select id="searchPropertyType" name="property_type" class="field-input">
              <option value="">Any type</option>
              <?php foreach ($propertyTypes as $value => $label): ?>
              <option value="<?= e($value) ?>" <?= ($filters['property_type'] ?? '') === $value ? 'selected' : '' ?>><?= e($label) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="grid gap-4 sm:grid-cols-2">
            <div>
              <label for="searchMinPrice" class="field-label">Min price</label>
              <input
                id="searchMinPrice"
                name="min_price"
                type="number"
                min="0"
                class="field-input"
                placeholder="0"
                value="<?= e($filters['min_price'] ?? '') ?>"
              />
            </div>
            <div>
              <label for="searchMaxPrice" class="field-label">Max price</label>
              <input
                id="searchMaxPrice"
                name="max_price"
                type="number"
                min="0"
                class="field-input"
                placeholder="50000"
                value="<?= e($filters['max_price'] ?? '') ?>"
              />
            </div>
          </div>

          <div>
            <label for="searchBedrooms" class="field-label">Bedrooms</label>
            <select id="searchBedrooms" name="bedrooms" class="field-input">
              <option value="">Any</option>
              <option value="1" <?= ($filters['bedrooms'] ?? '') === '1' ? 'selected' : '' ?>>1+</option>
              <option value="2" <?= ($filters['bedrooms'] ?? '') === '2' ? 'selected' : '' ?>>2+</option>
              <option value="3" <?= ($filters['bedrooms'] ?? '') === '3' ? 'selected' : '' ?>>3+</option>
              <option value="4" <?= ($filters['bedrooms'] ?? '') === '4' ? 'selected' : '' ?>>4+</option>
            </select>
          </div>

          <button type="submit" class="btn-primary w-full">Apply filters</button>
        </form>
      </div>
    </aside>

    <div class="min-w-0">
      <?php if (!empty($properties)): ?>
      <div class="property-grid">
        <?php foreach ($properties as $property): ?>
          <?php
          $showFavoriteButton = !$user || (($user['role'] ?? 'renter') === 'renter');
          $badgeLabel = !empty($property['is_featured']) ? 'Featured' : '';
          include __DIR__ . '/partials/property-card.php';
          ?>
        <?php endforeach; ?>
      </div>
      <?php else: ?>
      <div class="app-card p-10 text-center">
        <span class="material-symbols-outlined text-5xl text-primary-fixed-variant">search_off</span>
        <h2 class="mt-4 text-2xl font-semibold tracking-tight text-primary">No properties matched this search</h2>
        <p class="mx-auto mt-3 max-w-xl text-base leading-7 text-on-surface-variant">
          Try broadening the location, removing a budget limit, or clearing the filters to explore all available listings.
        </p>
        <div class="mt-6 flex flex-col justify-center gap-3 sm:flex-row">
          <a href="<?= route('search') ?>" class="btn-primary">Clear filters</a>
          <a href="<?= route('contact') ?>" class="btn-secondary">Need help?</a>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </section>
</main>
<?php include __DIR__ . '/partials/footer.php'; ?>
