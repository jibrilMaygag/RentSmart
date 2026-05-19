<?php
$pageTitle = '404 | RentSmart';
$bodyClass = 'app-shell';
$user = $user ?? null;

include __DIR__ . '/partials/head.php';
include __DIR__ . '/partials/header.php';
?>
<main class="app-container flex min-h-[calc(100vh-9rem)] items-center justify-center pt-24 sm:pt-28">
  <div class="app-card max-w-2xl p-10 text-center sm:p-14">
    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-secondary">404</p>
    <h1 class="mt-4 text-4xl font-semibold tracking-tight text-primary sm:text-5xl">Page not found</h1>
    <p class="mx-auto mt-5 max-w-xl text-base leading-8 text-on-surface-variant">
      The page you&apos;re looking for may have moved or no longer exists.
    </p>
    <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
      <a href="<?= route('') ?>" class="btn-primary">Go home</a>
      <a href="<?= route('search') ?>" class="btn-secondary">Browse properties</a>
    </div>
  </div>
</main>
<?php include __DIR__ . '/partials/footer.php'; ?>
