<?php
/**
 * @var array $user
 * @var array $myProperties
 * @var array $favorites
 */
$isLandlord = ($user['role'] ?? 'renter') === 'landlord';
$pageTitle = $isLandlord ? 'Landlord Dashboard | RentSmart' : 'Renter Dashboard | RentSmart';
$pageHeading = $isLandlord ? 'Landlord Dashboard' : 'Renter Dashboard';
$pageSubheading = $isLandlord
    ? 'Review your live listings and stay connected to property activity.'
    : 'Keep track of saved homes and continue your search with confidence.';
$bodyClass = 'dashboard-shell';
$layoutMode = 'dashboard';
$dashboardSection = 'overview';
$primaryItems = $isLandlord ? $myProperties : $favorites;
$primarySectionId = $isLandlord ? 'my-listings' : 'saved-properties';
$primarySectionTitle = $isLandlord ? 'My Listings' : 'Saved Properties';
$primarySectionDescription = $isLandlord
    ? 'These cards are rendering your current landlord listings from the existing backend.'
    : 'These are the properties you have already saved using the existing favorites endpoint.';

include __DIR__ . '/partials/head.php';
include __DIR__ . '/partials/sidebar.php';
include __DIR__ . '/partials/header.php';
?>
<main class="app-container py-8 pb-28 lg:pb-12">
  <?php include __DIR__ . '/partials/flash.php'; ?>

  <section class="grid gap-6 xl:grid-cols-[minmax(0,1.6fr)_minmax(320px,0.9fr)]">
    <div class="space-y-6">
      <div class="dashboard-panel overflow-hidden p-8">
        <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
          <div>
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-secondary">Welcome back</p>
            <h2 class="mt-3 text-3xl font-semibold tracking-tight text-primary sm:text-4xl">
              <?= $isLandlord ? 'Manage your portfolio, ' : 'Welcome home, ' ?><?= e(userFirstName($user)) ?>.
            </h2>
            <p class="mt-4 max-w-2xl text-base leading-7 text-on-surface-variant">
              <?= $isLandlord
                ? 'Your dashboard is connected to the same listings, property pages, and contact workflows already running in RentSmart.'
                : 'Your dashboard is connected to the same saved properties, detail pages, and search results already powered by RentSmart.' ?>
            </p>
          </div>

          <div class="flex flex-col gap-3 sm:flex-row">
            <a href="<?= route('search') ?>" class="btn-primary">Browse Properties</a>
            <a href="<?= $isLandlord ? route('dashboard/listings/create') : route('favorites') ?>" class="btn-secondary">
              <?= e($isLandlord ? 'Post Listing' : 'Open Saved') ?>
            </a>
          </div>
        </div>

        <div class="mt-8 grid gap-4 md:grid-cols-3">
          <div class="rounded-[1.5rem] bg-surface-container-low p-5">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-on-surface-variant">Role</p>
            <p class="mt-3 text-2xl font-semibold tracking-tight text-primary"><?= e(ucfirst($user['role'] ?? 'member')) ?></p>
          </div>
          <div class="rounded-[1.5rem] bg-surface-container-low p-5">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-on-surface-variant"><?= e($primarySectionTitle) ?></p>
            <p class="mt-3 text-2xl font-semibold tracking-tight text-primary"><?= number_format(count($primaryItems)) ?></p>
          </div>
          <div class="rounded-[1.5rem] bg-surface-container-low p-5">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-on-surface-variant">Account email</p>
            <p class="mt-3 truncate text-lg font-semibold tracking-tight text-primary"><?= e($user['email'] ?? '') ?></p>
          </div>
        </div>
      </div>

      <div id="<?= e($primarySectionId) ?>" class="dashboard-panel p-8">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
          <div>
            <h3 class="text-2xl font-semibold tracking-tight text-primary"><?= e($primarySectionTitle) ?></h3>
            <p class="mt-2 text-sm leading-6 text-on-surface-variant"><?= e($primarySectionDescription) ?></p>
          </div>
          <?php if (!$isLandlord && !empty($favorites)): ?>
          <span class="inline-flex items-center rounded-full bg-secondary-container px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-on-secondary-container">
            Click the heart to remove saved items
          </span>
          <?php endif; ?>
        </div>

        <?php if (!empty($primaryItems)): ?>
        <div class="property-grid mt-8">
          <?php foreach ($primaryItems as $property): ?>
            <?php
            $showFavoriteButton = !$isLandlord;
            $isFavorite = !$isLandlord;
            $removeOnUnfavorite = !$isLandlord;
            $showDetailsCta = true;
            $badgeLabel = $isLandlord ? ucfirst($property['status'] ?? 'Available') : '';
            include __DIR__ . '/partials/property-card.php';
            ?>
          <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="mt-8 rounded-[1.5rem] border border-dashed border-outline-variant/40 bg-surface-container-low px-6 py-10 text-center">
          <span class="material-symbols-outlined text-5xl text-primary-fixed-variant"><?= $isLandlord ? 'home_work' : 'favorite' ?></span>
          <h4 class="mt-4 text-2xl font-semibold tracking-tight text-primary">
            <?= $isLandlord ? 'No listings yet' : 'No saved properties yet' ?>
          </h4>
          <p class="mx-auto mt-3 max-w-xl text-base leading-7 text-on-surface-variant">
            <?= $isLandlord
              ? 'Your landlord dashboard is ready, but there are no active listings tied to this account yet. Use the contact flow if you need help publishing one.'
              : 'Save a property from the browse or detail pages and it will appear here immediately through the existing favorites backend.' ?>
          </p>
          <div class="mt-6 flex flex-col justify-center gap-3 sm:flex-row">
            <a href="<?= route('search') ?>" class="btn-primary"><?= $isLandlord ? 'Browse marketplace' : 'Browse properties' ?></a>
            <a href="<?= route('contact') ?>" class="btn-secondary">Contact support</a>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <aside class="space-y-6">
      <div class="dashboard-panel p-7">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-secondary">Account snapshot</p>
        <div class="mt-5 flex items-center gap-4">
          <div class="flex h-14 w-14 items-center justify-center rounded-full bg-primary text-lg font-semibold text-white">
            <?= e(strtoupper(substr(userFirstName($user), 0, 1))) ?>
          </div>
          <div class="min-w-0">
            <p class="truncate text-lg font-semibold tracking-tight text-primary"><?= e($user['full_name'] ?? '') ?></p>
            <p class="truncate text-sm text-on-surface-variant"><?= e($user['email'] ?? '') ?></p>
          </div>
        </div>
        <div class="mt-6 space-y-4 text-sm leading-6 text-on-surface-variant">
          <p><?= $isLandlord ? 'You can review your listings and open each public property page from here.' : 'You can revisit saved homes and continue exploring from the search page.' ?></p>
          <p>Authentication, sessions, and dashboard routing are still powered by the existing backend.</p>
        </div>
      </div>

      <div class="dashboard-panel p-7">
        <h3 class="text-xl font-semibold tracking-tight text-primary">Quick actions</h3>
        <div class="mt-5 space-y-3">
          <a href="<?= route('search') ?>" class="dashboard-nav-link bg-surface-container-low">
            <span class="material-symbols-outlined">search</span>
            <span>Browse Properties</span>
          </a>
          <a href="<?= $isLandlord ? route('dashboard/listings') : route('favorites') ?>" class="dashboard-nav-link bg-surface-container-low">
            <span class="material-symbols-outlined"><?= e($isLandlord ? 'home_work' : 'favorite') ?></span>
            <span><?= e($isLandlord ? 'Manage Listings' : 'Saved Properties') ?></span>
          </a>
          <?php if ($isLandlord): ?>
          <a href="<?= route('dashboard/messages') ?>" class="dashboard-nav-link bg-surface-container-low">
            <span class="material-symbols-outlined">mail</span>
            <span>Open Inbox</span>
          </a>
          <?php endif; ?>
          <a href="<?= route('contact') ?>" class="dashboard-nav-link bg-surface-container-low">
            <span class="material-symbols-outlined">support_agent</span>
            <span>Contact Support</span>
          </a>
          <a href="<?= route('logout') ?>" class="dashboard-nav-link bg-surface-container-low">
            <span class="material-symbols-outlined">logout</span>
            <span>Logout</span>
          </a>
        </div>
      </div>

      <div class="overflow-hidden rounded-[1.75rem] bg-primary p-7 text-white shadow-float">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-secondary-container">Unified application</p>
        <h3 class="mt-4 text-2xl font-semibold tracking-tight">One platform, consistent experience.</h3>
        <p class="mt-4 text-sm leading-7 text-slate-200">
          Your dashboard integrates with every part of the RentSmart platform, giving you a professional, cohesive experience across all features and workflows.
        </p>
      </div>
    </aside>
  </section>
</main>
<?php
$footerMode = 'none';
include __DIR__ . '/partials/footer.php';
?>
