<?php
/**
 * @var array      $user
 * @var bool       $isEditing
 * @var array|null $listing
 * @var array      $old
 * @var array      $fieldErrors
 * @var array      $amenities
 */
$pageTitle = $isEditing ? 'Edit Listing | RentSmart' : 'Create Listing | RentSmart';
$pageHeading = $isEditing ? 'Edit Listing' : 'Post New Listing';
$pageSubheading = $isEditing
    ? 'Update pricing, images, amenities, and status for this property.'
    : 'Create a new listing and share it with renters on RentSmart.';
$bodyClass = 'dashboard-shell';
$layoutMode = 'dashboard';
$dashboardSection = $isEditing ? 'listings' : 'create';

$defaults = [
    'title' => '',
    'description' => '',
    'listing_type' => 'rent',
    'property_type' => 'apartment',
    'status' => 'available',
    'price' => '',
    'address' => '',
    'city' => '',
    'sub_city' => '',
    'bedrooms' => 1,
    'bathrooms' => 1,
    'area_sqm' => '',
];

$formValues = $defaults;
if (!empty($listing)) {
    $formValues = array_merge($formValues, $listing);
}
if (!empty($old)) {
    $formValues = array_merge($formValues, $old);
}

$selectedAmenities = array_map('intval', $old['amenities'] ?? $listing['amenity_ids'] ?? []);
$currentImages = $listing['images'] ?? [];

$fieldError = static fn(string $field): string => $fieldErrors[$field][0] ?? '';
$fieldClass = static fn(string $field): string => !empty($fieldErrors[$field])
    ? 'border-error focus:border-error focus:ring-error/20'
    : '';

$propertyTypes = [
    'apartment' => 'Apartment',
    'house' => 'House',
    'villa' => 'Villa',
    'studio' => 'Studio',
    'office' => 'Office',
    'land' => 'Land',
];

$statusOptions = [
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
  <section class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
      <a href="<?= route('dashboard/listings') ?>" class="inline-flex items-center gap-2 text-sm font-medium text-on-surface-variant transition hover:text-primary">
        <span class="material-symbols-outlined text-base">arrow_back</span>
        Back to My Listings
      </a>
      <h2 class="mt-4 text-3xl font-semibold tracking-tight text-primary"><?= e($isEditing ? 'Refine your property listing' : 'Create a new listing') ?></h2>
      <p class="mt-3 max-w-3xl text-base leading-7 text-on-surface-variant">
        <?= e($isEditing
          ? 'Changes will appear on your listing as soon as you save them.'
          : 'Add the details renters need to understand the property at a glance.') ?>
      </p>
    </div>
    <a href="<?= route('dashboard/messages') ?>" class="btn-secondary">
      <span class="material-symbols-outlined text-base">mail</span>
      <span>Open Inbox</span>
    </a>
  </section>

  <section class="mt-8 grid gap-4 md:grid-cols-3">
    <div class="dashboard-panel p-5">
      <p class="text-xs font-semibold uppercase tracking-[0.2em] text-secondary">1. Details</p>
      <p class="mt-3 text-sm leading-6 text-on-surface-variant">Add the location, pricing, and core property information.</p>
    </div>
    <div class="dashboard-panel p-5">
      <p class="text-xs font-semibold uppercase tracking-[0.2em] text-secondary">2. Media</p>
      <p class="mt-3 text-sm leading-6 text-on-surface-variant">Upload clear photos to help renters picture the space.</p>
    </div>
    <div class="dashboard-panel p-5">
      <p class="text-xs font-semibold uppercase tracking-[0.2em] text-secondary">3. Publish</p>
      <p class="mt-3 text-sm leading-6 text-on-surface-variant">Choose the listing status and save when you are ready.</p>
    </div>
  </section>

  <form
    action="<?= $isEditing ? route('dashboard/listings/' . (int)$listing['id'] . '/edit') : route('dashboard/listings/create') ?>"
    method="POST"
    enctype="multipart/form-data"
    class="mt-8 space-y-8"
  >
    <?= csrfField() ?>

    <div class="dashboard-panel p-7 sm:p-8">
      <?php include __DIR__ . '/../partials/flash.php'; ?>

      <div class="grid gap-6 lg:grid-cols-[minmax(0,1.2fr)_minmax(0,0.8fr)]">
        <div>
          <label for="listingTitle" class="field-label">Property title</label>
          <input
            id="listingTitle"
            name="title"
            class="field-input <?= e($fieldClass('title')) ?>"
            value="<?= e($formValues['title']) ?>"
            placeholder="e.g. Modern Loft in Downtown Addis"
            required
          />
          <?php if ($fieldError('title')): ?>
          <p class="mt-2 text-sm text-error"><?= e($fieldError('title')) ?></p>
          <?php endif; ?>
        </div>

        <div>
          <label class="field-label">Listing type</label>
          <div class="inline-flex w-full rounded-2xl border border-outline-variant/30 bg-surface-container-low p-1" data-listing-toggle-group data-target="listingListingType">
            <button type="button" data-listing-toggle="rent" class="flex-1 rounded-xl px-4 py-2 text-sm font-semibold <?= $formValues['listing_type'] === 'rent' ? 'bg-primary text-white' : 'bg-surface-container-lowest text-on-surface-variant' ?>">Rent</button>
            <button type="button" data-listing-toggle="sale" class="flex-1 rounded-xl px-4 py-2 text-sm font-semibold <?= $formValues['listing_type'] === 'sale' ? 'bg-primary text-white' : 'bg-surface-container-lowest text-on-surface-variant' ?>">Sale</button>
          </div>
          <input type="hidden" id="listingListingType" name="listing_type" value="<?= e($formValues['listing_type']) ?>" />
          <?php if ($fieldError('listing_type')): ?>
          <p class="mt-2 text-sm text-error"><?= e($fieldError('listing_type')) ?></p>
          <?php endif; ?>
        </div>

        <div class="lg:col-span-2">
          <label for="listingDescription" class="field-label">Description</label>
          <textarea
            id="listingDescription"
            name="description"
            rows="6"
            class="field-input min-h-[180px] resize-y <?= e($fieldClass('description')) ?>"
            placeholder="Describe the property, neighborhood, and standout features."
            required
          ><?= e($formValues['description']) ?></textarea>
          <?php if ($fieldError('description')): ?>
          <p class="mt-2 text-sm text-error"><?= e($fieldError('description')) ?></p>
          <?php endif; ?>
        </div>

        <div>
          <label for="listingPrice" class="field-label">Price (ETB)</label>
          <input
            id="listingPrice"
            name="price"
            type="number"
            min="0"
            step="0.01"
            class="field-input <?= e($fieldClass('price')) ?>"
            value="<?= e((string)$formValues['price']) ?>"
            placeholder="45000"
            required
          />
          <?php if ($fieldError('price')): ?>
          <p class="mt-2 text-sm text-error"><?= e($fieldError('price')) ?></p>
          <?php endif; ?>
        </div>

        <div>
          <label for="listingPropertyType" class="field-label">Property type</label>
          <select id="listingPropertyType" name="property_type" class="field-input <?= e($fieldClass('property_type')) ?>" required>
            <?php foreach ($propertyTypes as $value => $label): ?>
            <option value="<?= e($value) ?>" <?= $formValues['property_type'] === $value ? 'selected' : '' ?>><?= e($label) ?></option>
            <?php endforeach; ?>
          </select>
          <?php if ($fieldError('property_type')): ?>
          <p class="mt-2 text-sm text-error"><?= e($fieldError('property_type')) ?></p>
          <?php endif; ?>
        </div>

        <div class="lg:col-span-2">
          <label for="listingAddress" class="field-label">Street address</label>
          <input
            id="listingAddress"
            name="address"
            class="field-input <?= e($fieldClass('address')) ?>"
            value="<?= e($formValues['address']) ?>"
            placeholder="House number, street, and landmark"
            required
          />
          <?php if ($fieldError('address')): ?>
          <p class="mt-2 text-sm text-error"><?= e($fieldError('address')) ?></p>
          <?php endif; ?>
        </div>

        <div>
          <label for="listingCity" class="field-label">City</label>
          <input
            id="listingCity"
            name="city"
            class="field-input <?= e($fieldClass('city')) ?>"
            value="<?= e($formValues['city']) ?>"
            placeholder="Addis Ababa"
            required
          />
          <?php if ($fieldError('city')): ?>
          <p class="mt-2 text-sm text-error"><?= e($fieldError('city')) ?></p>
          <?php endif; ?>
        </div>

        <div>
          <label for="listingSubCity" class="field-label">Sub city / neighborhood</label>
          <input
            id="listingSubCity"
            name="sub_city"
            class="field-input"
            value="<?= e($formValues['sub_city']) ?>"
            placeholder="Bole, Sar Bet, Ayat"
          />
        </div>

        <div>
          <label for="listingBedrooms" class="field-label">Bedrooms</label>
          <input
            id="listingBedrooms"
            name="bedrooms"
            type="number"
            min="0"
            class="field-input <?= e($fieldClass('bedrooms')) ?>"
            value="<?= e((string)$formValues['bedrooms']) ?>"
            required
          />
          <?php if ($fieldError('bedrooms')): ?>
          <p class="mt-2 text-sm text-error"><?= e($fieldError('bedrooms')) ?></p>
          <?php endif; ?>
        </div>

        <div>
          <label for="listingBathrooms" class="field-label">Bathrooms</label>
          <input
            id="listingBathrooms"
            name="bathrooms"
            type="number"
            min="0"
            step="1"
            class="field-input <?= e($fieldClass('bathrooms')) ?>"
            value="<?= e((string)$formValues['bathrooms']) ?>"
            required
          />
          <?php if ($fieldError('bathrooms')): ?>
          <p class="mt-2 text-sm text-error"><?= e($fieldError('bathrooms')) ?></p>
          <?php endif; ?>
        </div>

        <div>
          <label for="listingArea" class="field-label">Area (sqm)</label>
          <input
            id="listingArea"
            name="area_sqm"
            type="number"
            min="0"
            step="0.01"
            class="field-input <?= e($fieldClass('area_sqm')) ?>"
            value="<?= e((string)$formValues['area_sqm']) ?>"
            placeholder="120"
          />
          <?php if ($fieldError('area_sqm')): ?>
          <p class="mt-2 text-sm text-error"><?= e($fieldError('area_sqm')) ?></p>
          <?php endif; ?>
        </div>

        <div>
          <label for="listingStatus" class="field-label">Status</label>
          <select id="listingStatus" name="status" class="field-input <?= e($fieldClass('status')) ?>" required>
            <?php foreach ($statusOptions as $value => $label): ?>
            <option value="<?= e($value) ?>" <?= $formValues['status'] === $value ? 'selected' : '' ?>><?= e($label) ?></option>
            <?php endforeach; ?>
          </select>
          <?php if ($fieldError('status')): ?>
          <p class="mt-2 text-sm text-error"><?= e($fieldError('status')) ?></p>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="dashboard-panel p-7 sm:p-8">
      <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
        <div>
          <h3 class="text-2xl font-semibold tracking-tight text-primary">Amenities & features</h3>
          <p class="mt-2 text-sm leading-6 text-on-surface-variant">Select the amenities that should appear on the property detail page.</p>
        </div>
      </div>

      <?php if (!empty($amenities)): ?>
      <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        <?php foreach ($amenities as $amenity): ?>
        <label class="flex items-center gap-3 rounded-2xl border border-outline-variant/20 bg-surface-container-low px-4 py-4 transition hover:border-secondary/40 hover:bg-surface-container-high">
          <input
            type="checkbox"
            name="amenities[]"
            value="<?= (int)$amenity['id'] ?>"
            class="h-4 w-4 rounded border-outline-variant text-secondary focus:ring-secondary"
            <?= in_array((int)$amenity['id'], $selectedAmenities, true) ? 'checked' : '' ?>
          />
          <div>
            <p class="text-sm font-medium text-primary"><?= e($amenity['name']) ?></p>
            <p class="text-xs uppercase tracking-[0.18em] text-on-surface-variant"><?= e($amenity['icon'] ?? 'feature') ?></p>
          </div>
        </label>
        <?php endforeach; ?>
      </div>
      <?php else: ?>
      <div class="mt-6 rounded-[1.5rem] bg-surface-container-low px-5 py-5 text-sm leading-6 text-on-surface-variant">
        Amenities can be added later. You can still publish this listing now.
      </div>
      <?php endif; ?>
    </div>

    <div class="dashboard-panel p-7 sm:p-8">
      <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
        <div>
          <h3 class="text-2xl font-semibold tracking-tight text-primary">Listing media</h3>
          <p class="mt-2 text-sm leading-6 text-on-surface-variant">
            <?= e($isEditing ? 'Add more images to the current gallery or keep the existing set.' : 'Upload at least one image to make the listing visible with a property card.') ?>
          </p>
        </div>
      </div>

      <?php if (!empty($currentImages)): ?>
      <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <?php foreach ($currentImages as $image): ?>
        <div class="overflow-hidden rounded-[1.25rem] border border-outline-variant/20 bg-surface-container-low shadow-soft">
          <img src="<?= e(imageUrl($image['image_path'])) ?>" alt="Listing image" class="h-40 w-full object-cover" />
          <div class="flex items-center justify-between px-4 py-3 text-xs uppercase tracking-[0.18em] text-on-surface-variant">
            <span><?= !empty($image['is_primary']) ? 'Primary' : 'Gallery' ?></span>
            <span>#<?= (int)$image['sort_order'] + 1 ?></span>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <div class="mt-6 rounded-[1.5rem] border-2 border-dashed border-outline-variant/40 bg-surface-container-low px-6 py-8">
        <label for="listingImages" class="block text-center">
          <span class="material-symbols-outlined text-5xl text-primary-fixed-variant">cloud_upload</span>
          <span class="mt-4 block text-lg font-semibold tracking-tight text-primary">Upload property images</span>
          <span class="mt-2 block text-sm leading-6 text-on-surface-variant">JPG, PNG, and WebP are supported. You can upload multiple files at once.</span>
          <span class="btn-secondary mt-6">Choose Images</span>
        </label>
        <input
          id="listingImages"
          name="images[]"
          type="file"
          accept=".jpg,.jpeg,.png,.webp"
          multiple
          class="sr-only"
        />
        <?php if ($fieldError('images')): ?>
        <p class="mt-4 text-center text-sm text-error"><?= e($fieldError('images')) ?></p>
        <?php endif; ?>
      </div>
    </div>

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <a href="<?= route('dashboard/listings') ?>" class="btn-secondary">Cancel</a>
      <button type="submit" class="btn-primary">
        <span class="material-symbols-outlined text-base"><?= $isEditing ? 'save' : 'publish' ?></span>
        <span><?= e($isEditing ? 'Save Changes' : 'Publish Listing') ?></span>
      </button>
    </div>
  </form>
</main>
<?php
$footerMode = 'none';
include __DIR__ . '/../partials/footer.php';
?>
