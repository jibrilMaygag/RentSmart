<?php if ($success = flash('success')): ?>
<div class="flash-banner flash-success" data-flash-banner>
  <div class="flex items-start justify-between gap-3">
    <div class="flex items-start gap-3">
      <span class="material-symbols-outlined text-base">check_circle</span>
      <p><?= e($success) ?></p>
    </div>
    <button type="button" class="text-on-secondary-container/70 transition hover:text-on-secondary-container" data-flash-close aria-label="Dismiss">
      <span class="material-symbols-outlined text-base">close</span>
    </button>
  </div>
</div>
<?php endif; ?>

<?php if ($error = flash('error')): ?>
<div class="flash-banner flash-error" data-flash-banner>
  <div class="flex items-start justify-between gap-3">
    <div class="flex items-start gap-3">
      <span class="material-symbols-outlined text-base">error</span>
      <p><?= e($error) ?></p>
    </div>
    <button type="button" class="text-on-error-container/70 transition hover:text-on-error-container" data-flash-close aria-label="Dismiss">
      <span class="material-symbols-outlined text-base">close</span>
    </button>
  </div>
</div>
<?php endif; ?>

<?php if ($errors = flash('errors')): ?>
<div class="flash-banner flash-error" data-flash-banner>
  <div class="flex items-start justify-between gap-3">
    <div class="flex items-start gap-3">
      <span class="material-symbols-outlined text-base">warning</span>
      <div class="space-y-1">
        <?php foreach ($errors as $fieldErrors): ?>
          <?php foreach ($fieldErrors as $message): ?>
          <p><?= e($message) ?></p>
          <?php endforeach; ?>
        <?php endforeach; ?>
      </div>
    </div>
    <button type="button" class="text-on-error-container/70 transition hover:text-on-error-container" data-flash-close aria-label="Dismiss">
      <span class="material-symbols-outlined text-base">close</span>
    </button>
  </div>
</div>
<?php endif; ?>
