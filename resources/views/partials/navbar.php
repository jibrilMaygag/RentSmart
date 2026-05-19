<?php
/**
 * @var array|null $user
 */
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base = parse_url(APP_URL, PHP_URL_PATH);
$rel  = '/' . ltrim(substr($currentPath, strlen($base)), '/');
?>
<nav class="navbar">
  <div class="container nav-wrapper">
    <div class="logo">
      <a href="<?= APP_URL ?>">
        <h2>RentSmart</h2>
      </a>
      <span class="logo-subtitle">Find your home</span>
    </div>

    <ul class="nav-menu">
      <li><a href="<?= APP_URL ?>" <?= $rel === '/' || $rel === '' ? 'class="active"' : '' ?>>Home</a></li>
      <li><a href="<?= APP_URL ?>/search" <?= str_starts_with($rel, '/search') ? 'class="active"' : '' ?>>Search</a></li>
      <li><a href="<?= APP_URL ?>/contact" <?= $rel === '/contact' ? 'class="active"' : '' ?>>Contact</a></li>
      <?php if ($user): ?>
        <li class="mobile-only"><a href="<?= APP_URL ?>/dashboard">Dashboard</a></li>
        <li class="mobile-only"><a href="<?= APP_URL ?>/logout">Logout</a></li>
      <?php else: ?>
        <li class="mobile-only"><a href="<?= APP_URL ?>/login">Login</a></li>
        <li class="mobile-only"><a href="<?= APP_URL ?>/signup">Sign Up</a></li>
      <?php endif; ?>
    </ul>

    <div class="nav-buttons">
      <?php if ($user): ?>
        <a href="<?= APP_URL ?>/dashboard" class="btn-secondary">
          <i class="fas fa-user" style="margin-right:0.4rem;"></i><?= e(explode(' ', $user['full_name'])[0]) ?>
        </a>
        <a href="<?= APP_URL ?>/logout" class="btn-primary">Logout</a>
      <?php else: ?>
        <a href="<?= APP_URL ?>/login" class="btn-secondary">Login</a>
        <a href="<?= APP_URL ?>/signup" class="btn-primary">Sign Up</a>
      <?php endif; ?>
    </div>

    <div class="menu-toggle">
      <i class="fas fa-bars"></i>
    </div>
  </div>
</nav>
