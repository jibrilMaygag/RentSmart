<?php
$dashboardSection = $dashboardSection ?? 'overview';
$role = $user['role'] ?? 'renter';
$primaryRoute = $role === 'landlord' ? route('dashboard/listings') : route('favorites');
$primarySectionLabel = $role === 'landlord' ? 'My Listings' : 'Saved Properties';
$primarySectionIcon = $role === 'landlord' ? 'home_work' : 'favorite';
?>
<aside class="fixed inset-y-0 left-0 hidden w-72 border-r border-outline-variant/20 bg-surface-container-low lg:flex lg:flex-col">
  <div class="px-6 py-6">
    <a href="<?= route('') ?>" class="text-2xl font-semibold tracking-tight text-primary">RentSmart</a>
    <p class="mt-2 text-sm text-on-surface-variant"><?= e(ucfirst($role)) ?> workspace</p>
  </div>

  <nav class="flex-1 space-y-1 px-4">
    <a href="<?= route('dashboard') ?>" class="dashboard-nav-link <?= $dashboardSection === 'overview' ? 'dashboard-nav-link-active' : '' ?>">
      <span class="material-symbols-outlined">dashboard</span>
      <span>Overview</span>
    </a>
    <a href="<?= $primaryRoute ?>" class="dashboard-nav-link <?= in_array($dashboardSection, ['primary', 'listings', 'saved'], true) ? 'dashboard-nav-link-active' : '' ?>">
      <span class="material-symbols-outlined"><?= e($primarySectionIcon) ?></span>
      <span><?= e($primarySectionLabel) ?></span>
    </a>
    <?php if ($role === 'landlord'): ?>
    <a href="<?= route('dashboard/messages') ?>" class="dashboard-nav-link <?= $dashboardSection === 'messages' ? 'dashboard-nav-link-active' : '' ?>">
      <span class="material-symbols-outlined">mail</span>
      <span>Messages</span>
    </a>
    <a href="<?= route('dashboard/listings/create') ?>" class="dashboard-nav-link <?= $dashboardSection === 'create' ? 'dashboard-nav-link-active' : '' ?>">
      <span class="material-symbols-outlined">add_home</span>
      <span>New Listing</span>
    </a>
    <?php endif; ?>
    <a href="<?= route('search') ?>" class="dashboard-nav-link">
      <span class="material-symbols-outlined">search</span>
      <span>Browse Properties</span>
    </a>
    <a href="<?= route('contact') ?>" class="dashboard-nav-link">
      <span class="material-symbols-outlined">support_agent</span>
      <span>Contact Support</span>
    </a>
  </nav>

  <div class="border-t border-outline-variant/20 px-4 py-4">
    <div class="dashboard-panel mb-4 flex items-center gap-3 px-4 py-3">
      <div class="flex h-11 w-11 items-center justify-center rounded-full bg-primary text-sm font-semibold text-white">
        <?= e(strtoupper(substr(userFirstName($user ?? null), 0, 1))) ?>
      </div>
      <div class="min-w-0">
        <p class="truncate text-sm font-semibold text-primary"><?= e($user['full_name'] ?? 'RentSmart Member') ?></p>
        <p class="text-xs text-on-surface-variant"><?= e($user['email'] ?? '') ?></p>
      </div>
    </div>
    <a href="<?= route('logout') ?>" class="dashboard-nav-link">
      <span class="material-symbols-outlined">logout</span>
      <span>Logout</span>
    </a>
  </div>
</aside>

<nav class="fixed inset-x-0 bottom-0 z-40 border-t border-outline-variant/30 bg-surface-container-lowest/95 backdrop-blur-md lg:hidden">
  <div class="grid grid-cols-4">
    <a href="<?= route('dashboard') ?>" class="flex flex-col items-center gap-1 px-2 py-3 text-xs font-medium <?= $dashboardSection === 'overview' ? 'text-primary' : 'text-on-surface-variant' ?>">
      <span class="material-symbols-outlined">dashboard</span>
      <span>Overview</span>
    </a>
    <a href="<?= route('search') ?>" class="flex flex-col items-center gap-1 px-2 py-3 text-xs font-medium text-on-surface-variant">
      <span class="material-symbols-outlined">search</span>
      <span>Browse</span>
    </a>
    <a href="<?= $primaryRoute ?>" class="flex flex-col items-center gap-1 px-2 py-3 text-xs font-medium <?= in_array($dashboardSection, ['primary', 'listings', 'saved'], true) ? 'text-primary' : 'text-on-surface-variant' ?>">
      <span class="material-symbols-outlined"><?= e($primarySectionIcon) ?></span>
      <span><?= e($role === 'landlord' ? 'Listings' : 'Saved') ?></span>
    </a>
    <a href="<?= $role === 'landlord' ? route('dashboard/messages') : route('contact') ?>" class="flex flex-col items-center gap-1 px-2 py-3 text-xs font-medium <?= $dashboardSection === 'messages' ? 'text-primary' : 'text-on-surface-variant' ?>">
      <span class="material-symbols-outlined"><?= $role === 'landlord' ? 'mail' : 'support_agent' ?></span>
      <span><?= e($role === 'landlord' ? 'Inbox' : 'Support') ?></span>
    </a>
  </div>
</nav>
