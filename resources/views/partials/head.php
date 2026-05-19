<?php
$pageTitle = $pageTitle ?? APP_NAME;
$bodyClass = $bodyClass ?? '';
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= e($pageTitle) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@400;500;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="<?= asset('css/app.css') ?>" />
</head>
<body
  class="<?= e($bodyClass) ?>"
  data-app-url="<?= e(APP_URL) ?>"
  data-csrf-token="<?= e($_SESSION['csrf_token'] ?? '') ?>"
>
