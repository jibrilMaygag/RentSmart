<?php $user = isset($user) ? $user : null; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>404 | RentSmart</title>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/style.css" />
</head>
<body>
<?php include __DIR__ . '/partials/navbar.php'; ?>
<div style="text-align:center;padding:8rem 2rem;color:#64748b;">
  <h1 style="font-size:6rem;font-weight:700;color:#e2e8f0;line-height:1;">404</h1>
  <h2>Page Not Found</h2>
  <p>The page you're looking for doesn't exist.</p>
  <a href="<?= APP_URL ?>" class="btn-primary" style="margin-top:1.5rem;display:inline-block;">Go Home</a>
</div>
<?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
