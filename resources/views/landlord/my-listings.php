<?php
/**
 * @var array $user
 * @var array $listings
 * @var array $counts
 * @var array $filters
 */
$pageTitle = 'My Listings | RentSmart';
$pageHeading = 'My Listings';
$pageSubheading = 'Manage your portfolio, update availability, and keep listing details current.';
$bodyClass = 'dashboard-shell';
$layoutMode = 'dashboard';
$dashboardSection = 'listings';

$statusOptions = [
    'all' => 'All',
    'available' => 'Available',
    'pending' => 'Pending',
    'rented' => 'Rented',
    'sold' => 'Sold',
];

include __DIR__ . '/../partials/head.php';
include __DIR__ . '/../partials/sidebar.php';
include __DIR__ . '/../partials/header.php';
?>
<main class="app-container py-8 pb-28 lg:pb-12">
  <?php include __DIR__ . '/../partials/flash.php'; ?>

  <section class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
    <div>
      <h2 class="text-3xl font-semibold tracking-tight text-primary">Your property portfolio</h2>
      <p class="mt-3 max-w-3xl text-base leading-7 text-on-surface-variant">
        Keep your listings polished, update availability, and jump into edits whenever details change.
      </p>
    </div>
    <div class="flex flex-col gap-3 sm:flex-row">
      <a href="<?= route('dashboard/messages') ?>" class="btn-secondary">
        <span class="material-symbols-outlined text-base">mail</span>
        <span>Open Inbox</span>
      </a>
      <a href="<?= route('dashboard/listings/create') ?>" class="btn-primary">
        <span class="material-symbols-outlined text-base">add_home</span>
        <span>Post New Listing</span>
      </a>
    </div>
  </section>

  <section class="mt-8 dashboard-panel p-6">
    <form action="<?= route('dashboard/listings') ?>" method="GET" class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
      <div class="flex flex-wrap gap-3">
        <?php foreach ($statusOptions as $status => $label): ?>
        <a
          href="<?= route('dashboard/listings', array_filter(['status' => $status !== 'all' ? $status : null, 'q' => $filters['keyword'] ?: null])) ?>"
          class="inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-semibold transition <?= ($filters['status'] ?? 'all') === $status ? 'bg-primary text-white' : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-container-high' ?>"
        >
          <span><?= e($label) ?></span>
          <span class="rounded-full bg-black/10 px-2 py-0.5 text-xs <?= ($filters['status'] ?? 'all') === $status ? 'bg-white/10' : '' ?>">
            <?= number_format((int)($counts[$status] ?? 0)) ?>
          </span>
        </a>
        <?php endforeach; ?>
      </div>

      <div class="flex gap-3">
        <input
          type="hidden"
          name="status"
          value="<?= e(($filters['status'] ?? 'all') !== 'all' ? $filters['status'] : '') ?>"
        />
        <input
          type="text"
          name="q"
          class="field-input min-w-[240px]"
          placeholder="Search by title or location"
          value="<?= e($filters['keyword'] ?? '') ?>"
        />
        <button type="submit" class="btn-secondary">Search</button>
      </div>
    </form>
  </section>

  <?php if (!empty($listings)): ?>
  <section class="mt-8 grid gap-8 md:grid-cols-2 lg:gap-10">
    <?php foreach ($listings as $listing): ?>
    <article class="dashboard-panel overflow-hidden shadow-lg hover:shadow-xl transition-shadow">
      <div class="relative h-72 overflow-hidden bg-surface-container-high">
        <img src="<?= e(imageUrl($listing['image_path'] ?? DEFAULT_PROPERTY_IMG)) ?>" alt="<?= e($listing['title']) ?>" class="h-full w-full object-cover transition duration-500 hover:scale-110" />
        <span class="absolute left-4 top-4 rounded-full bg-secondary-container px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] text-on-secondary-container shadow-md">
          <?= e(ucfirst($listing['status'])) ?>
        </span>
      </div>

      <div class="space-y-6 p-8">
        <div class="space-y-3">
          <h3 class="line-clamp-2 text-2xl font-bold tracking-tight text-primary leading-tight"><?= e($listing['title']) ?></h3>
          <p class="flex items-center gap-2 text-base text-on-surface-variant font-medium">
            <span class="material-symbols-outlined text-xl text-secondary">location_on</span>
            <span class="line-clamp-1"><?= e($listing['city']) ?><?= !empty($listing['sub_city']) ? ', ' . e($listing['sub_city']) : '' ?></span>
          </p>
          <div class="pt-2 border-t border-outline-variant/20">
            <p class="text-2xl font-bold text-primary"><?= number_format((float)$listing['price']) ?> <span class="text-sm font-semibold text-on-surface-variant">ETB</span></p>
            <p class="text-sm text-on-surface-variant mt-1"><?= e($listing['listing_type'] === 'rent' ? 'Per month' : 'For sale') ?></p>
          </div>
        </div>

        <?php if (!empty($listing['description'])): ?>
        <p class="text-sm leading-6 text-on-surface-variant">
          <?= e(strlen($listing['description']) > 180 ? substr($listing['description'], 0, 177) . '...' : $listing['description']) ?>
        </p>
        <?php endif; ?>

        <?php if (!empty($listing['amenity_names'])): ?>
        <div class="flex flex-wrap gap-2">
          <?php foreach (array_slice($listing['amenity_names'], 0, 4) as $amenityName): ?>
          <span class="rounded-full bg-surface-container-low px-3 py-1 text-xs font-medium text-on-surface-variant">
            <?= e($amenityName) ?>
          </span>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="grid grid-cols-3 gap-4 rounded-2xl bg-surface-container-low px-6 py-6">
          <div class="text-center">
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-on-surface-variant/70">Views</p>
            <p class="mt-3 text-2xl font-bold text-primary"><?= number_format((int)$listing['views']) ?></p>
          </div>
          <div class="text-center border-l border-r border-outline-variant/20">
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-on-surface-variant/70">Beds</p>
            <p class="mt-3 text-2xl font-bold text-primary"><?= (int)$listing['bedrooms'] ?></p>
          </div>
          <div class="text-center">
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-on-surface-variant/70">Area</p>
            <p class="mt-3 text-2xl font-bold text-primary"><?= !empty($listing['area_sqm']) ? number_format((float)$listing['area_sqm']) : '--' ?></p>
          </div>
        </div>

        <form action="<?= route('dashboard/listings/' . (int)$listing['id'] . '/status') ?>" method="POST" class="space-y-3">
          <?= csrfField() ?>
          <label class="block text-sm font-semibold text-on-surface-variant uppercase tracking-[0.15em]">Quick Status Update</label>
          <div class="flex gap-3">
            <select name="status" class="field-input flex-1">
              <?php foreach (array_slice($statusOptions, 1, null, true) as $value => $label): ?>
              <option value="<?= e($value) ?>" <?= $listing['status'] === $value ? 'selected' : '' ?>><?= e($label) ?></option>
              <?php endforeach; ?>
            </select>
            <button type="submit" class="btn-secondary px-6 whitespace-nowrap">Update</button>
          </div>
        </form>

        <div class="grid gap-3 grid-cols-3 pt-4 border-t border-outline-variant/20">
          <a href="<?= route('property/' . (int)$listing['id']) ?>" class="btn-secondary justify-center text-sm font-semibold">
            <span class="material-symbols-outlined text-base">preview</span>
            <span class="hidden sm:inline">View</span>
          </a>
          <a href="<?= route('dashboard/listings/' . (int)$listing['id'] . '/edit') ?>" class="btn-secondary justify-center text-sm font-semibold">
            <span class="material-symbols-outlined text-base">edit</span>
            <span class="hidden sm:inline">Edit</span>
          </a>
          <form action="<?= route('dashboard/listings/' . (int)$listing['id'] . '/delete') ?>" method="POST" onsubmit="return confirm('Delete this listing? This cannot be undone.');" class="contents">
            <?= csrfField() ?>
            <button type="submit" class="inline-flex items-center justify-center rounded-xl border-2 border-error/40 bg-error-container/40 px-4 py-2.5 text-sm font-semibold text-error transition hover:bg-error-container/60 hover:border-error/60 gap-2">
              <span class="material-symbols-outlined text-base">delete</span>
              <span class="hidden sm:inline">Delete</span>
            </button>
          </form>
        </div>
      </div>
    </article>
    <?php endforeach; ?>
  </section>
  <?php else: ?>
  <section class="mt-8 dashboard-panel p-10 text-center">
    <span class="material-symbols-outlined text-5xl text-primary-fixed-variant">add_home</span>
    <h3 class="mt-4 text-2xl font-semibold tracking-tight text-primary">No listings match this view</h3>
    <p class="mx-auto mt-3 max-w-2xl text-base leading-7 text-on-surface-variant">
      Try a different status filter, clear the search field, or publish a new property listing to start building your portfolio.
    </p>
    <div class="mt-6 flex flex-col justify-center gap-3 sm:flex-row">
      <a href="<?= route('dashboard/listings') ?>" class="btn-secondary">Clear filters</a>
      <a href="<?= route('dashboard/listings/create') ?>" class="btn-primary">Post New Listing</a>
    </div>
  </section>
  <?php endif; ?>
</main>
<?php
$footerMode = 'none';
include __DIR__ . '/../partials/footer.php';
?>
