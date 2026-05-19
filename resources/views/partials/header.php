<?php
$layoutMode = $layoutMode ?? 'public';
$pageHeading = $pageHeading ?? APP_NAME;
$pageSubheading = $pageSubheading ?? '';
?>
<?php if ($layoutMode === 'dashboard'): ?>
<header class="sticky top-0 z-30 border-b border-outline-variant/30 glass-nav">
  <div class="app-container flex items-center justify-between gap-4 py-4">
    <div>
      <p class="text-xs font-semibold uppercase tracking-[0.2em] text-on-surface-variant">Dashboard</p>
      <h1 class="mt-1 text-2xl font-semibold tracking-tight text-primary"><?= e($pageHeading) ?></h1>
      <?php if ($pageSubheading): ?>
      <p class="mt-1 text-sm text-on-surface-variant"><?= e($pageSubheading) ?></p>
      <?php endif; ?>
    </div>
    <div class="flex items-center gap-3">
      <a href="<?= route('search') ?>" class="hidden rounded-xl border border-outline-variant/40 bg-surface-container-low px-4 py-2 text-sm font-medium text-on-surface-variant transition hover:bg-surface-container-high sm:inline-flex">Browse</a>
      <div class="hidden items-center gap-3 rounded-2xl border border-outline-variant/30 bg-surface-container-lowest px-4 py-2 shadow-soft sm:flex">
        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary text-sm font-semibold text-white">
          <?= e(strtoupper(substr(userFirstName($user ?? null), 0, 1))) ?>
        </div>
        <div>
          <p class="text-sm font-semibold text-primary"><?= e(userFirstName($user ?? null)) ?></p>
          <p class="text-xs text-on-surface-variant"><?= e(ucfirst($user['role'] ?? 'member')) ?></p>
        </div>
      </div>
    </div>
  </div>
</header>
<?php else: ?>
<header class="fixed inset-x-0 top-0 z-40 border-b border-outline-variant/30 glass-nav">
  <div class="app-container flex items-center justify-between gap-4 py-4">
    <a href="<?= route('') ?>" class="text-2xl font-semibold tracking-tight text-primary">RentSmart</a>

    <nav class="hidden items-center gap-8 md:flex">
      <a href="<?= route('') ?>" class="text-sm font-medium transition <?= routeIs('/') ? 'text-primary' : 'text-on-surface-variant hover:text-primary' ?>">Home</a>
      <a href="<?= route('search') ?>" class="text-sm font-medium transition <?= routeIs('/search') || routeIs('/property') ? 'text-primary' : 'text-on-surface-variant hover:text-primary' ?>">Browse</a>
      <a href="<?= route('contact') ?>" class="text-sm font-medium transition <?= routeIs('/contact') ? 'text-primary' : 'text-on-surface-variant hover:text-primary' ?>">Contact</a>
      <?php if ($user): ?>
      <a href="<?= route('dashboard') ?>" class="text-sm font-medium transition <?= routeIs('/dashboard') || routeIs('/favorites') ? 'text-primary' : 'text-on-surface-variant hover:text-primary' ?>">Dashboard</a>
      <?php endif; ?>
    </nav>

    <div class="hidden items-center gap-3 md:flex">
      <?php if ($user): ?>
      <a href="<?= route('dashboard') ?>" class="btn-secondary"><?= e(userFirstName($user)) ?></a>
      <a href="<?= route('logout') ?>" class="btn-primary">Logout</a>
      <?php else: ?>
      <a href="<?= route('login') ?>" class="btn-secondary">Login</a>
      <a href="<?= route('signup') ?>" class="btn-primary">Sign Up</a>
      <?php endif; ?>
    </div>

    <button type="button" class="inline-flex h-11 w-11 items-center justify-center rounded-xl border border-outline-variant/30 bg-surface-container-lowest text-primary md:hidden" data-mobile-menu-toggle aria-label="Open menu">
      <span class="material-symbols-outlined">menu</span>
    </button>
  </div>

  <div class="hidden border-t border-outline-variant/20 bg-surface-container-lowest md:hidden" data-mobile-menu>
    <div class="app-container space-y-2 py-4">
      <a href="<?= route('') ?>" class="block rounded-xl px-4 py-3 text-sm font-medium <?= routeIs('/') ? 'bg-surface-container-high text-primary' : 'text-on-surface-variant' ?>">Home</a>
      <a href="<?= route('search') ?>" class="block rounded-xl px-4 py-3 text-sm font-medium <?= routeIs('/search') || routeIs('/property') ? 'bg-surface-container-high text-primary' : 'text-on-surface-variant' ?>">Browse</a>
      <a href="<?= route('contact') ?>" class="block rounded-xl px-4 py-3 text-sm font-medium <?= routeIs('/contact') ? 'bg-surface-container-high text-primary' : 'text-on-surface-variant' ?>">Contact</a>
      <?php if ($user): ?>
      <a href="<?= route('dashboard') ?>" class="block rounded-xl px-4 py-3 text-sm font-medium <?= routeIs('/dashboard') || routeIs('/favorites') ? 'bg-surface-container-high text-primary' : 'text-on-surface-variant' ?>">Dashboard</a>
      <a href="<?= route('logout') ?>" class="block rounded-xl px-4 py-3 text-sm font-medium text-on-surface-variant">Logout</a>
      <?php else: ?>
      <a href="<?= route('login') ?>" class="block rounded-xl px-4 py-3 text-sm font-medium text-on-surface-variant">Login</a>
      <a href="<?= route('signup') ?>" class="block rounded-xl px-4 py-3 text-sm font-medium text-on-surface-variant">Sign Up</a>
      <?php endif; ?>
    </div>
  </div>
</header>
<?php endif; ?>
