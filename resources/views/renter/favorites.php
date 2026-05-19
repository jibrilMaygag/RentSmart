<?php
/**
 * @var array $user
 * @var array $favorites
 */
$pageTitle = 'Saved Properties | RentSmart';
$pageHeading = 'Saved Properties';
$pageSubheading = 'Manage your favorites and jump back into the listings you care about most.';
$bodyClass = 'dashboard-shell';
$layoutMode = 'dashboard';
$dashboardSection = 'saved';

include __DIR__ . '/../partials/head.php';
include __DIR__ . '/../partials/sidebar.php';
include __DIR__ . '/../partials/header.php';
?>
<main class="app-container py-8 pb-28 lg:pb-12">
  <?php include __DIR__ . '/../partials/flash.php'; ?>

  <section class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
    <div>
      <h2 class="text-3xl font-semibold tracking-tight text-primary">Your saved properties</h2>
      <p class="mt-3 max-w-3xl text-base leading-7 text-on-surface-variant">
        Keep track of the homes you want to revisit. Open any listing to compare details or remove it from your saved list.
      </p>
    </div>
    <a href="<?= route('search') ?>" class="btn-primary">
      <span class="material-symbols-outlined text-base">explore</span>
      <span>Browse More</span>
    </a>
  </section>

  <?php if (!empty($favorites)): ?>
  <section class="mt-8" data-property-collection data-empty-state-target="favoritesEmptyState">
    <div class="property-grid">
      <?php foreach ($favorites as $property): ?>
        <?php
        $showFavoriteButton = true;
        $isFavorite = true;
        $removeOnUnfavorite = true;
        $showDetailsCta = true;
        $badgeLabel = !empty($property['is_featured']) ? 'Featured' : '';
        include __DIR__ . '/../partials/property-card.php';
        ?>
      <?php endforeach; ?>
    </div>
  </section>
  <?php endif; ?>

  <section id="favoritesEmptyState" class="<?= !empty($favorites) ? 'hidden ' : '' ?>mt-8 dashboard-panel p-10 text-center">
    <span class="material-symbols-outlined text-6xl text-primary-fixed-variant">favorite_border</span>
    <h3 class="mt-5 text-2xl font-semibold tracking-tight text-primary">No saved properties yet</h3>
    <p class="mx-auto mt-3 max-w-2xl text-base leading-7 text-on-surface-variant">
      Save any listing from the search results or property detail page and it will appear here automatically.
    </p>
    <div class="mt-6">
      <a href="<?= route('search') ?>" class="btn-primary">Browse Properties</a>
    </div>
  </section>
</main>
<?php
$footerMode = 'none';
include __DIR__ . '/../partials/footer.php';
?>
