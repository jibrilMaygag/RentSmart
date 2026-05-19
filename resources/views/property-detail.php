<?php
/**
 * @var array      $property
 * @var array|null $user
 * @var bool       $isFavorited
 */
$pageTitle = ($property['title'] ?? 'Property') . ' | RentSmart';
$bodyClass = 'app-shell';

$images = $property['images'] ?? [];
$mainImage = !empty($images[0]['image_path']) ? imageUrl($images[0]['image_path']) : imageUrl(DEFAULT_PROPERTY_IMG);
$thumbnailImages = [];
$currentUserRole = $user['role'] ?? null;
$canSaveProperty = !$user || $currentUserRole === 'renter';
$canSendInquiry = !empty($user) && $currentUserRole === 'renter';
$isLandlordOwner = !empty($user)
    && $currentUserRole === 'landlord'
    && (int)($user['id'] ?? 0) === (int)($property['landlord_id'] ?? 0);

foreach (array_slice($images, 1, 3) as $image) {
    if (!empty($image['image_path'])) {
        $thumbnailImages[] = imageUrl($image['image_path']);
    }
}

$stats = [
    ['label' => 'Bedrooms', 'value' => str_pad((string)(int)($property['bedrooms'] ?? 0), 2, '0', STR_PAD_LEFT)],
    ['label' => 'Bathrooms', 'value' => str_pad((string)(int)($property['bathrooms'] ?? 0), 2, '0', STR_PAD_LEFT)],
    ['label' => 'Square m', 'value' => !empty($property['area_sqm']) ? number_format((float)$property['area_sqm']) : 'N/A'],
    ['label' => 'Views', 'value' => number_format((int)($property['views'] ?? 0))],
];

include __DIR__ . '/partials/head.php';
include __DIR__ . '/partials/header.php';
?>
<main class="app-container pb-16 pt-24 sm:pt-28">
  <section class="mb-8 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
    <div>
      <a href="<?= route('search') ?>" class="inline-flex items-center gap-2 text-sm font-medium text-on-surface-variant transition hover:text-primary">
        <span class="material-symbols-outlined text-base">arrow_back</span>
        Back to search
      </a>
      <div class="mt-4 flex flex-wrap gap-2">
        <span class="rounded-full bg-secondary-container px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-on-secondary-container">
          <?= e(($property['listing_type'] ?? 'rent') === 'sale' ? 'For Sale' : 'For Rent') ?>
        </span>
        <?php if (!empty($property['status'])): ?>
        <span class="rounded-full bg-surface-container-high px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-on-surface-variant">
          <?= e(ucfirst($property['status'])) ?>
        </span>
        <?php endif; ?>
      </div>
      <h1 class="mt-5 text-3xl font-semibold tracking-tight text-primary sm:text-4xl"><?= e($property['title'] ?? 'Property') ?></h1>
      <p class="mt-3 flex flex-wrap items-center gap-2 text-base text-on-surface-variant">
        <span class="material-symbols-outlined text-base text-primary">location_on</span>
        <span>
          <?= e($property['address'] ?? '') ?>
          <?php if (!empty($property['address']) && !empty($property['city'])): ?>, <?php endif; ?>
          <?= e($property['city'] ?? '') ?>
          <?php if (!empty($property['sub_city'])): ?>, <?= e($property['sub_city']) ?><?php endif; ?>
        </span>
      </p>
    </div>

    <div class="rounded-[1.5rem] border border-outline-variant/20 bg-surface-container-lowest px-6 py-5 shadow-soft md:min-w-[280px]">
      <p class="text-sm font-medium uppercase tracking-[0.18em] text-on-surface-variant">Price</p>
      <div class="mt-3 flex items-end gap-2">
        <span class="text-3xl font-semibold tracking-tight text-primary"><?= number_format((float)($property['price'] ?? 0)) ?> ETB</span>
        <?php if (($property['listing_type'] ?? 'rent') === 'rent'): ?>
        <span class="pb-1 text-sm text-on-surface-variant">/ month</span>
        <?php endif; ?>
      </div>
      <div class="mt-5">
        <?php if ($canSaveProperty): ?>
        <button
          type="button"
          class="btn-secondary w-full"
          data-favorite-toggle
          data-property-id="<?= (int)$property['id'] ?>"
          data-favorited="<?= $isFavorited ? 'true' : 'false' ?>"
          data-label-active="Saved"
          data-label-inactive="Save"
          aria-label="Save property"
        >
          <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' <?= $isFavorited ? '1' : '0' ?>;">favorite</span>
          <span data-favorite-label><?= $isFavorited ? 'Saved' : 'Save' ?></span>
        </button>
        <?php elseif ($isLandlordOwner): ?>
        <a href="<?= route('dashboard/listings/' . (int)$property['id'] . '/edit') ?>" class="btn-secondary w-full justify-center">
          <span class="material-symbols-outlined text-base">edit</span>
          <span>Edit Listing</span>
        </a>
        <?php else: ?>
        <div class="rounded-2xl bg-surface-container-low px-4 py-4 text-sm leading-6 text-on-surface-variant">
          Saved properties are currently available on renter accounts.
        </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <section class="grid gap-8 lg:grid-cols-[minmax(0,1.7fr)_minmax(320px,0.9fr)]">
    <div class="space-y-8">
      <div class="grid gap-4 md:grid-cols-[minmax(0,2fr)_minmax(240px,1fr)]">
        <div class="overflow-hidden rounded-[1.75rem] bg-surface-container-highest shadow-soft">
          <img
            src="<?= e($mainImage) ?>"
            alt="<?= e($property['title'] ?? 'Property image') ?>"
            id="propertyMainImage"
            class="h-full min-h-[360px] w-full object-cover"
          />
        </div>

        <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-1">
          <?php foreach ($thumbnailImages as $thumbnail): ?>
          <button type="button" class="overflow-hidden rounded-[1.25rem] bg-surface-container-highest text-left shadow-soft">
            <img
              src="<?= e($thumbnail) ?>"
              alt="Property gallery image"
              class="h-44 w-full object-cover transition duration-500 hover:scale-105"
              data-gallery-thumb
              data-gallery-target="propertyMainImage"
            />
          </button>
          <?php endforeach; ?>

          <?php if (count($thumbnailImages) < 2): ?>
          <div class="rounded-[1.25rem] border border-outline-variant/20 bg-surface-container-low p-5 shadow-soft">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-secondary">Listing type</p>
            <p class="mt-3 text-xl font-semibold tracking-tight text-primary"><?= e(ucfirst($property['property_type'] ?? 'Property')) ?></p>
            <p class="mt-2 text-sm leading-6 text-on-surface-variant">
              Detailed images, landlord contact information, and amenities are connected to the live backend data for this listing.
            </p>
          </div>
          <?php endif; ?>

          <?php if (count($thumbnailImages) < 1): ?>
          <div class="rounded-[1.25rem] border border-outline-variant/20 bg-primary p-5 text-white shadow-soft">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-secondary-container">RentSmart</p>
            <p class="mt-3 text-xl font-semibold tracking-tight">Property details that stay in sync.</p>
            <p class="mt-2 text-sm leading-6 text-slate-200">
              Views, favorites, pricing, and contact details all come from the existing PHP backend.
            </p>
          </div>
          <?php endif; ?>
        </div>
      </div>

      <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
        <?php foreach ($stats as $stat): ?>
        <div class="app-card p-5">
          <p class="text-xs font-semibold uppercase tracking-[0.18em] text-on-surface-variant"><?= e($stat['label']) ?></p>
          <p class="mt-3 text-2xl font-semibold tracking-tight text-primary"><?= e($stat['value']) ?></p>
        </div>
        <?php endforeach; ?>
      </div>

      <section class="app-card p-7">
        <h2 class="text-2xl font-semibold tracking-tight text-primary">About this property</h2>
        <p class="mt-5 text-base leading-8 text-on-surface-variant">
          <?= nl2br(e($property['description'] ?? 'No description provided.')) ?>
        </p>
      </section>

      <?php if (!empty($property['amenities'])): ?>
      <section class="app-card p-7">
        <h2 class="text-2xl font-semibold tracking-tight text-primary">What this place offers</h2>
        <div class="mt-6 grid gap-4 md:grid-cols-2">
          <?php foreach ($property['amenities'] as $amenity): ?>
          <div class="flex items-center gap-3 rounded-2xl bg-surface-container-low px-4 py-4">
            <span class="material-symbols-outlined text-secondary">check_circle</span>
            <span class="text-sm font-medium text-on-surface"><?= e($amenity['name'] ?? 'Amenity') ?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </section>
      <?php endif; ?>
    </div>

    <aside class="lg:sticky lg:top-28 lg:self-start">
      <div class="app-card p-7 shadow-float">
        <h2 class="text-2xl font-semibold tracking-tight text-primary">Contact landlord</h2>

        <?php if (!empty($property['landlord_name'])): ?>
        <div class="mt-6 flex items-center gap-4 rounded-[1.25rem] bg-surface-container-low p-4">
          <div class="flex h-12 w-12 items-center justify-center rounded-full bg-primary text-sm font-semibold text-white">
            <?= e(strtoupper(substr($property['landlord_name'], 0, 1))) ?>
          </div>
          <div>
            <p class="text-sm font-semibold text-primary"><?= e($property['landlord_name']) ?></p>
            <p class="text-sm text-on-surface-variant">Property owner</p>
          </div>
        </div>
        <?php endif; ?>

        <div class="mt-6 space-y-3">
          <?php if ($canSendInquiry): ?>
            <a href="<?= route('property/' . (int)$property['id'] . '/contact') ?>" class="btn-primary w-full justify-center">
              <span class="material-symbols-outlined text-base">send</span>
              <span>Send Inquiry</span>
            </a>
            <?php if (!empty($property['landlord_phone'])): ?>
            <a href="tel:<?= e($property['landlord_phone']) ?>" class="btn-secondary w-full justify-center">
              <span class="material-symbols-outlined text-base">call</span>
              <span><?= e($property['landlord_phone']) ?></span>
            </a>
            <?php endif; ?>

            <?php if (!empty($property['landlord_email'])): ?>
            <a href="mailto:<?= e($property['landlord_email']) ?>" class="btn-secondary w-full justify-center">
              <span class="material-symbols-outlined text-base">mail</span>
              <span>Send email</span>
            </a>
            <?php endif; ?>

            <?php if (empty($property['landlord_phone']) && empty($property['landlord_email'])): ?>
            <div class="rounded-2xl bg-surface-container-low px-4 py-4 text-sm leading-6 text-on-surface-variant">
              Contact details are not available for this property yet.
            </div>
            <?php endif; ?>
          <?php elseif ($isLandlordOwner): ?>
          <div class="rounded-2xl bg-surface-container-low px-4 py-4 text-sm leading-6 text-on-surface-variant">
            This is your listing. You can update the property details, status, and media from the landlord dashboard.
          </div>
          <a href="<?= route('dashboard/listings/' . (int)$property['id'] . '/edit') ?>" class="btn-primary w-full justify-center">
            <span class="material-symbols-outlined text-base">edit_square</span>
            <span>Manage Listing</span>
          </a>
          <?php elseif ($user): ?>
          <div class="rounded-2xl bg-surface-container-low px-4 py-4 text-sm leading-6 text-on-surface-variant">
            Property inquiries are currently available to renter accounts. Switch to a renter account to contact this landlord directly.
          </div>
          <a href="<?= route('dashboard') ?>" class="btn-secondary w-full justify-center">
            <span class="material-symbols-outlined text-base">dashboard</span>
            <span>Open Dashboard</span>
          </a>
          <?php else: ?>
          <div class="rounded-2xl bg-surface-container-low px-4 py-4 text-sm leading-6 text-on-surface-variant">
            Sign in to send an inquiry, reveal landlord contact details, and save this property to your dashboard.
          </div>
          <a href="<?= route('login') ?>" class="btn-primary w-full justify-center">
            <span class="material-symbols-outlined text-base">login</span>
            <span>Login to continue</span>
          </a>
          <?php endif; ?>
        </div>

        <div class="mt-6 border-t border-outline-variant/20 pt-6">
          <p class="text-xs font-semibold uppercase tracking-[0.18em] text-on-surface-variant">Need help?</p>
          <p class="mt-3 text-sm leading-6 text-on-surface-variant">
            Use the existing contact flow if you need listing assistance, landlord verification, or support with your account.
          </p>
          <a href="<?= route('contact') ?>" class="btn-secondary mt-5 w-full justify-center">
            <span class="material-symbols-outlined text-base">support_agent</span>
            <span>Contact support</span>
          </a>
        </div>
      </div>
    </aside>
  </section>
</main>
<?php include __DIR__ . '/partials/footer.php'; ?>
