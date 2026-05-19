<?php
/**
 * @var array $user
 * @var array $property
 * @var array $old
 * @var array $fieldErrors
 */
$pageTitle = 'Contact Landlord | RentSmart';
$bodyClass = 'app-shell';

$old = $old ?? [];
$fieldErrors = $fieldErrors ?? [];
$fieldError = static fn(string $field): string => $fieldErrors[$field][0] ?? '';
$fieldClass = static fn(string $field): string => !empty($fieldErrors[$field])
    ? 'border-error focus:border-error focus:ring-error/20'
    : '';

include __DIR__ . '/../partials/head.php';
include __DIR__ . '/../partials/header.php';
?>
<main class="app-container pb-16 pt-24 sm:pt-28">
  <section class="grid gap-8 lg:grid-cols-[minmax(0,1.2fr)_minmax(320px,0.8fr)]">
    <div class="app-card p-7 sm:p-8">
      <div>
        <h1 class="text-3xl font-semibold tracking-tight text-primary">Contact the landlord</h1>
        <p class="mt-3 text-base leading-7 text-on-surface-variant">
          Send a direct inquiry about this property. Your message will appear in the landlord inbox inside RentSmart.
        </p>
      </div>

      <div class="mt-6">
        <?php include __DIR__ . '/../partials/flash.php'; ?>
      </div>

      <div class="mt-6 grid gap-4 md:grid-cols-2">
        <div class="rounded-[1.5rem] bg-surface-container-low px-5 py-5">
          <p class="text-xs font-semibold uppercase tracking-[0.18em] text-on-surface-variant">Your name</p>
          <p class="mt-3 text-lg font-semibold tracking-tight text-primary"><?= e($user['full_name']) ?></p>
        </div>
        <div class="rounded-[1.5rem] bg-surface-container-low px-5 py-5">
          <p class="text-xs font-semibold uppercase tracking-[0.18em] text-on-surface-variant">Your email</p>
          <p class="mt-3 truncate text-sm font-semibold text-primary"><?= e($user['email']) ?></p>
        </div>
      </div>

      <form action="<?= route('property/' . (int)$property['id'] . '/contact') ?>" method="POST" class="mt-8 space-y-5">
        <?= csrfField() ?>

        <div>
          <label for="inquiryPhone" class="field-label">Phone number</label>
          <input
            id="inquiryPhone"
            name="phone"
            class="field-input <?= e($fieldClass('phone')) ?>"
            value="<?= e($old['phone'] ?? $user['phone'] ?? '') ?>"
            placeholder="+251 900 000 000"
          />
          <?php if ($fieldError('phone')): ?>
          <p class="mt-2 text-sm text-error"><?= e($fieldError('phone')) ?></p>
          <?php endif; ?>
        </div>

        <div>
          <label for="inquiryMessage" class="field-label">Message</label>
          <textarea
            id="inquiryMessage"
            name="message"
            rows="7"
            class="field-input min-h-[190px] resize-y <?= e($fieldClass('message')) ?>"
            placeholder="Tell the landlord what you need to know or ask to schedule a viewing."
            required
          ><?= e($old['message'] ?? '') ?></textarea>
          <?php if ($fieldError('message')): ?>
          <p class="mt-2 text-sm text-error"><?= e($fieldError('message')) ?></p>
          <?php endif; ?>
        </div>

        <button type="submit" class="btn-primary">
          <span class="material-symbols-outlined text-base">send</span>
          <span>Send Inquiry</span>
        </button>
      </form>
    </div>

    <aside class="space-y-6 lg:sticky lg:top-28 lg:self-start">
      <div class="app-card overflow-hidden">
        <img src="<?= e(imageUrl($property['images'][0]['image_path'] ?? DEFAULT_PROPERTY_IMG)) ?>" alt="<?= e($property['title']) ?>" class="h-60 w-full object-cover" />
        <div class="space-y-4 p-6">
          <div class="flex items-start justify-between gap-4">
            <div class="min-w-0">
              <p class="text-xs font-semibold uppercase tracking-[0.18em] text-secondary">Property</p>
              <h2 class="mt-2 text-2xl font-semibold tracking-tight text-primary"><?= e($property['title']) ?></h2>
            </div>
            <span class="rounded-full bg-secondary-container px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-on-secondary-container">
              <?= e(($property['listing_type'] ?? 'rent') === 'sale' ? 'For Sale' : 'For Rent') ?>
            </span>
          </div>

          <p class="flex items-center gap-2 text-sm text-on-surface-variant">
            <span class="material-symbols-outlined text-base">location_on</span>
            <span><?= e($property['city']) ?><?= !empty($property['sub_city']) ? ', ' . e($property['sub_city']) : '' ?></span>
          </p>

          <div class="grid grid-cols-3 gap-3 rounded-[1.25rem] bg-surface-container-low px-4 py-4 text-sm text-on-surface-variant">
            <div class="text-center">
              <p class="text-xs font-semibold uppercase tracking-[0.18em]">Beds</p>
              <p class="mt-2 text-lg font-semibold text-primary"><?= (int)$property['bedrooms'] ?></p>
            </div>
            <div class="text-center">
              <p class="text-xs font-semibold uppercase tracking-[0.18em]">Baths</p>
              <p class="mt-2 text-lg font-semibold text-primary"><?= (int)$property['bathrooms'] ?></p>
            </div>
            <div class="text-center">
              <p class="text-xs font-semibold uppercase tracking-[0.18em]">Price</p>
              <p class="mt-2 text-lg font-semibold text-primary"><?= number_format((float)$property['price']) ?></p>
            </div>
          </div>
        </div>
      </div>

      <div class="app-card p-6">
        <div class="flex items-center gap-4">
          <div class="flex h-12 w-12 items-center justify-center rounded-full bg-primary text-sm font-semibold text-white">
            <?= e(strtoupper(substr($property['landlord_name'] ?? 'L', 0, 1))) ?>
          </div>
          <div>
            <p class="text-sm font-semibold text-primary"><?= e($property['landlord_name'] ?? 'Landlord') ?></p>
            <p class="text-sm text-on-surface-variant">Owner contact</p>
          </div>
        </div>
        <p class="mt-5 text-sm leading-6 text-on-surface-variant">
          Typical response time is usually within a few hours once the landlord checks the dashboard inbox.
        </p>
      </div>
    </aside>
  </section>
</main>
<?php include __DIR__ . '/../partials/footer.php'; ?>
