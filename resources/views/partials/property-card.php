<?php
$property = $property ?? [];
$cardLink = $cardLink ?? (!empty($property['id']) ? route('property/' . (int)$property['id']) : '#');
$imageUrl = imageUrl($property['image_path'] ?? $property['image_filename'] ?? DEFAULT_PROPERTY_IMG);
$listingType = $property['listing_type'] ?? 'rent';
$showFavoriteButton = $showFavoriteButton ?? false;
$isFavorite = $isFavorite ?? false;
$badgeLabel = $badgeLabel ?? (!empty($property['is_featured']) ? 'Featured' : '');
$priceSuffix = $listingType === 'rent' ? '/mo' : '';
$removeOnUnfavorite = $removeOnUnfavorite ?? false;
$showDetailsCta = $showDetailsCta ?? false;
$description = trim((string)($property['description'] ?? ''));
$descriptionPreview = $description === ''
    ? ''
    : (strlen($description) > 120 ? substr($description, 0, 117) . '...' : $description);
$amenityPreview = array_slice($property['amenity_names'] ?? array_column($property['amenities'] ?? [], 'name'), 0, 3);
?>
<article class="property-card group" data-property-card>
  <div class="property-card-image">
    <a href="<?= e($cardLink) ?>" class="block h-full w-full">
      <img src="<?= e($imageUrl) ?>" alt="<?= e($property['title'] ?? 'Property') ?>" loading="lazy" />
    </a>
    <?php if ($badgeLabel): ?>
    <span class="absolute left-4 top-4 rounded-full bg-secondary-container px-3 py-1 text-xs font-semibold text-on-secondary-container">
      <?= e($badgeLabel) ?>
    </span>
    <?php endif; ?>
    <?php if ($showFavoriteButton && !empty($property['id'])): ?>
    <button
      type="button"
      class="absolute right-4 top-4 inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/90 text-primary shadow-soft transition hover:scale-105"
      data-favorite-toggle
      data-property-id="<?= (int)$property['id'] ?>"
      data-favorited="<?= $isFavorite ? 'true' : 'false' ?>"
      <?= $removeOnUnfavorite ? 'data-remove-on-unfavorite' : '' ?>
      aria-label="Save property"
    >
      <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' <?= $isFavorite ? '1' : '0' ?>;">favorite</span>
    </button>
    <?php endif; ?>
  </div>
  <div class="space-y-3 p-5">
    <div class="flex items-start justify-between gap-4">
      <div class="min-w-0">
        <h3 class="truncate text-lg font-semibold text-primary">
          <a href="<?= e($cardLink) ?>" class="transition hover:text-secondary">
            <?= e($property['title'] ?? 'Untitled property') ?>
          </a>
        </h3>
        <p class="mt-1 flex items-center gap-2 text-sm text-on-surface-variant">
          <span class="material-symbols-outlined text-base">location_on</span>
          <span class="truncate">
            <?= e($property['city'] ?? '') ?><?= !empty($property['sub_city']) ? ', ' . e($property['sub_city']) : '' ?>
          </span>
        </p>
      </div>
      <div class="shrink-0 text-right">
        <p class="text-lg font-semibold text-primary"><?= number_format((float)($property['price'] ?? 0)) ?> ETB</p>
        <?php if ($priceSuffix): ?>
        <p class="text-sm text-on-surface-variant"><?= e($priceSuffix) ?></p>
        <?php endif; ?>
      </div>
    </div>

    <?php if ($descriptionPreview !== ''): ?>
    <p class="text-sm leading-6 text-on-surface-variant">
      <?= e($descriptionPreview) ?>
    </p>
    <?php endif; ?>

    <?php if (!empty($amenityPreview)): ?>
    <div class="flex flex-wrap gap-2">
      <?php foreach ($amenityPreview as $amenityName): ?>
      <span class="rounded-full bg-surface-container-low px-3 py-1 text-xs font-medium text-on-surface-variant">
        <?= e($amenityName) ?>
      </span>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-3 gap-3 border-t border-outline-variant/20 pt-4 text-sm text-on-surface-variant">
      <div class="flex items-center gap-2">
        <span class="material-symbols-outlined text-base">bed</span>
        <span><?= (int)($property['bedrooms'] ?? 0) ?> Beds</span>
      </div>
      <div class="flex items-center gap-2">
        <span class="material-symbols-outlined text-base">bathtub</span>
        <span><?= (int)($property['bathrooms'] ?? 0) ?> Baths</span>
      </div>
      <div class="flex items-center gap-2">
        <span class="material-symbols-outlined text-base">square_foot</span>
        <span><?= !empty($property['area_sqm']) ? number_format((float)$property['area_sqm']) . ' m2' : ucfirst($property['property_type'] ?? 'Home') ?></span>
      </div>
    </div>

    <?php if ($showDetailsCta): ?>
    <a href="<?= e($cardLink) ?>" class="btn-secondary w-full">
      View Details
    </a>
    <?php endif; ?>
  </div>
</article>
