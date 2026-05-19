<?php
/**
 * @var array|null $user
 * @var array      $old
 */
$pageTitle = 'Contact Us | RentSmart';
$bodyClass = 'app-shell';
$old = $old ?? [];

include __DIR__ . '/partials/head.php';
include __DIR__ . '/partials/header.php';
?>
<main class="app-container pb-16 pt-24 sm:pt-28">
  <section class="rounded-[2rem] border border-outline-variant/20 bg-surface-container-low px-6 py-10 sm:px-8">
    <span class="section-eyebrow">Support</span>
    <h1 class="text-3xl font-semibold tracking-tight text-primary sm:text-4xl">Contact the RentSmart team</h1>
    <p class="mt-4 max-w-2xl text-base leading-7 text-on-surface-variant">
      Send us a message if you need help with your account, a listing, or anything else on RentSmart.
    </p>
  </section>

  <section class="mt-8 grid gap-8 lg:grid-cols-[minmax(0,1.25fr)_minmax(320px,0.85fr)]">
    <div class="app-card p-7 sm:p-8">
      <?php include __DIR__ . '/partials/flash.php'; ?>

      <form action="<?= route('contact') ?>" method="POST" class="space-y-5">
        <?= csrfField() ?>

        <div class="grid gap-5 md:grid-cols-2">
          <div>
            <label for="contactName" class="field-label">Full name</label>
            <input
              id="contactName"
              name="name"
              class="field-input"
              value="<?= e($old['name'] ?? $user['full_name'] ?? '') ?>"
              placeholder="Your full name"
              required
            />
          </div>

          <div>
            <label for="contactEmail" class="field-label">Email</label>
            <input
              id="contactEmail"
              name="email"
              type="email"
              class="field-input"
              value="<?= e($old['email'] ?? $user['email'] ?? '') ?>"
              placeholder="you@example.com"
              required
            />
          </div>
        </div>

        <div>
          <label for="contactPhone" class="field-label">Phone</label>
          <input
            id="contactPhone"
            name="phone"
            class="field-input"
            value="<?= e($old['phone'] ?? '') ?>"
            placeholder="+251 900 000 000"
          />
        </div>

        <div>
          <label for="contactMessage" class="field-label">Message</label>
          <textarea
            id="contactMessage"
            name="message"
            rows="7"
            class="field-input min-h-[180px] resize-y"
            placeholder="Tell us how we can help"
            required
          ><?= e($old['message'] ?? '') ?></textarea>
        </div>

        <button type="submit" class="btn-primary">
          <span class="material-symbols-outlined text-base">send</span>
          <span>Send message</span>
        </button>
      </form>
    </div>

    <aside class="space-y-6">
      <div class="app-card p-7">
        <h2 class="text-2xl font-semibold tracking-tight text-primary">Get in touch</h2>
        <div class="mt-6 space-y-5">
          <div class="flex items-start gap-4">
            <span class="material-symbols-outlined rounded-2xl bg-surface-container-low p-3 text-primary">mail</span>
            <div>
              <p class="text-sm font-semibold text-primary">Email</p>
              <p class="mt-1 text-sm text-on-surface-variant">info@rentsmart.com</p>
            </div>
          </div>
          <div class="flex items-start gap-4">
            <span class="material-symbols-outlined rounded-2xl bg-surface-container-low p-3 text-primary">call</span>
            <div>
              <p class="text-sm font-semibold text-primary">Phone</p>
              <p class="mt-1 text-sm text-on-surface-variant">+251 900 000 000</p>
            </div>
          </div>
          <div class="flex items-start gap-4">
            <span class="material-symbols-outlined rounded-2xl bg-surface-container-low p-3 text-primary">location_on</span>
            <div>
              <p class="text-sm font-semibold text-primary">Office</p>
              <p class="mt-1 text-sm text-on-surface-variant">Addis Ababa, Ethiopia</p>
            </div>
          </div>
        </div>
      </div>

      <div class="app-card p-7">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-secondary">Office hours</p>
        <div class="mt-5 space-y-3 text-sm leading-7 text-on-surface-variant">
          <p>Monday to Friday: 8:00 AM to 6:00 PM</p>
          <p>Saturday: 9:00 AM to 3:00 PM</p>
          <p>Sunday: Closed</p>
        </div>
      </div>

      <div class="overflow-hidden rounded-[1.75rem] bg-primary p-7 text-white shadow-float">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-secondary-container">Listing assistance</p>
        <h3 class="mt-4 text-2xl font-semibold tracking-tight">Need help publishing or managing a property?</h3>
        <p class="mt-4 text-sm leading-7 text-slate-200">
          Reach out if you need help posting a property, updating a listing, or accessing your account.
        </p>
      </div>
    </aside>
  </section>
</main>
<?php include __DIR__ . '/partials/footer.php'; ?>
