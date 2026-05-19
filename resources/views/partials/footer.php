<?php
$footerMode = $footerMode ?? 'default';
$includeAppScript = $includeAppScript ?? true;
?>

<?php if ($footerMode === 'minimal'): ?>
<footer class="border-t border-outline-variant/20 bg-surface py-6">
  <div class="app-container flex flex-col gap-3 text-sm text-on-surface-variant sm:flex-row sm:items-center sm:justify-between">
    <p>&copy; <?= date('Y') ?> RentSmart. Secure, simple property discovery.</p>
    <div class="flex items-center gap-4">
      <a href="<?= route('') ?>" class="transition hover:text-primary">Home</a>
      <a href="<?= route('contact') ?>" class="transition hover:text-primary">Support</a>
    </div>
  </div>
</footer>
<?php elseif ($footerMode !== 'none'): ?>
<footer class="border-t border-outline-variant/30 bg-surface">
  <div class="app-container py-12">
    <div class="flex flex-col gap-10 lg:flex-row lg:justify-between">
      <div class="max-w-sm">
        <h2 class="text-2xl font-semibold tracking-tight text-primary">RentSmart</h2>
        <p class="mt-4 text-sm leading-6 text-on-surface-variant">
          A cleaner rental experience for renters and landlords, built around real listings, clear contact, and dependable tools.
        </p>
      </div>

      <div class="grid grid-cols-2 gap-8 sm:grid-cols-3">
        <div>
          <h3 class="text-sm font-semibold uppercase tracking-[0.2em] text-on-surface-variant">Explore</h3>
          <div class="mt-4 space-y-3 text-sm text-on-surface-variant">
            <a href="<?= route('') ?>" class="block transition hover:text-primary">Home</a>
            <a href="<?= route('search', ['listing_type' => 'rent']) ?>" class="block transition hover:text-primary">Rent</a>
            <a href="<?= route('search', ['listing_type' => 'sale']) ?>" class="block transition hover:text-primary">Buy</a>
          </div>
        </div>
        <div>
          <h3 class="text-sm font-semibold uppercase tracking-[0.2em] text-on-surface-variant">Account</h3>
          <div class="mt-4 space-y-3 text-sm text-on-surface-variant">
            <a href="<?= route('dashboard') ?>" class="block transition hover:text-primary">Dashboard</a>
            <a href="<?= route('login') ?>" class="block transition hover:text-primary">Login</a>
            <a href="<?= route('signup') ?>" class="block transition hover:text-primary">Sign Up</a>
          </div>
        </div>
        <div>
          <h3 class="text-sm font-semibold uppercase tracking-[0.2em] text-on-surface-variant">Support</h3>
          <div class="mt-4 space-y-3 text-sm text-on-surface-variant">
            <a href="<?= route('contact') ?>" class="block transition hover:text-primary">Contact Us</a>
            <a href="mailto:info@rentsmart.com" class="block transition hover:text-primary">info@rentsmart.com</a>
            <span class="block">Addis Ababa, Ethiopia</span>
          </div>
        </div>
      </div>
    </div>

    <div class="mt-10 flex flex-col gap-3 border-t border-outline-variant/20 pt-6 text-sm text-on-surface-variant sm:flex-row sm:items-center sm:justify-between">
      <p>&copy; <?= date('Y') ?> RentSmart. All rights reserved.</p>
      <p>Helping renters and landlords move with confidence.</p>
    </div>
  </div>
</footer>
<?php endif; ?>

<?php if ($includeAppScript): ?>
<script src="<?= asset('javascript/script.js') ?>"></script>
<?php endif; ?>

<?php if (($dashboardSection ?? '') === 'create' || ($dashboardSection ?? '') === 'listings'): ?>
<script src="<?= asset('javascript/listing-form.js') ?>"></script>
<?php endif; ?>
</body>
</html>
