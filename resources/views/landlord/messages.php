<?php
/**
 * @var array      $user
 * @var array      $messages
 * @var array|null $selectedMessage
 */
$pageTitle = 'Landlord Inbox | RentSmart';
$pageHeading = 'Messages';
$pageSubheading = 'Review renter inquiries connected to your live property listings.';
$bodyClass = 'dashboard-shell';
$layoutMode = 'dashboard';
$dashboardSection = 'messages';

$unreadCount = count(array_filter($messages, static fn(array $message): bool => empty($message['is_read'])));

include __DIR__ . '/../partials/head.php';
include __DIR__ . '/../partials/sidebar.php';
include __DIR__ . '/../partials/header.php';
?>
<main class="app-container py-8 pb-28 lg:pb-12">
  <?php include __DIR__ . '/../partials/flash.php'; ?>

  <section class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
    <div>
      <h2 class="text-3xl font-semibold tracking-tight text-primary">Landlord inbox</h2>
      <p class="mt-3 max-w-3xl text-base leading-7 text-on-surface-variant">
        View renter inquiries, check sender details, and track which property each message references.
      </p>
    </div>
    <div class="flex flex-wrap gap-3">
      <span class="inline-flex items-center rounded-full bg-secondary-container px-4 py-2 text-sm font-semibold text-on-secondary-container">
        <?= number_format($unreadCount) ?> unread
      </span>
      <a href="<?= route('dashboard/listings') ?>" class="btn-secondary">My Listings</a>
    </div>
  </section>

  <section class="grid gap-6 xl:grid-cols-[360px_minmax(0,1fr)]">
    <aside class="dashboard-panel overflow-hidden">
      <div class="border-b border-outline-variant/20 px-6 py-5">
        <h3 class="text-xl font-semibold tracking-tight text-primary">Recent inquiries</h3>
      </div>

      <?php if (!empty($messages)): ?>
      <div class="divide-y divide-outline-variant/15">
        <?php foreach ($messages as $message): ?>
          <?php $isActive = !empty($selectedMessage) && (int)$selectedMessage['id'] === (int)$message['id']; ?>
        <a
          href="<?= route('dashboard/messages', ['message' => (int)$message['id']]) ?>"
          class="block px-6 py-5 transition <?= $isActive ? 'bg-surface-container-low' : 'hover:bg-surface-container-low/70' ?>"
        >
          <div class="flex items-start justify-between gap-4">
            <div class="min-w-0">
              <div class="flex items-center gap-2">
                <p class="truncate text-sm font-semibold text-primary"><?= e($message['sender_name']) ?></p>
                <?php if (empty($message['is_read'])): ?>
                <span class="inline-flex h-2.5 w-2.5 rounded-full bg-secondary"></span>
                <?php endif; ?>
              </div>
              <p class="mt-2 truncate text-xs font-semibold uppercase tracking-[0.18em] text-on-surface-variant"><?= e($message['property_title']) ?></p>
              <p class="mt-2 line-clamp-2 text-sm leading-6 text-on-surface-variant"><?= e($message['preview']) ?></p>
            </div>
            <p class="shrink-0 text-xs text-on-surface-variant"><?= e(timeAgo($message['created_at'])) ?></p>
          </div>
        </a>
        <?php endforeach; ?>
      </div>
      <?php else: ?>
      <div class="px-6 py-12 text-center">
        <span class="material-symbols-outlined text-5xl text-primary-fixed-variant">mail</span>
        <p class="mt-4 text-lg font-semibold tracking-tight text-primary">No messages yet</p>
        <p class="mt-3 text-sm leading-6 text-on-surface-variant">Renter inquiries will appear here as soon as someone sends a message from a property page.</p>
      </div>
      <?php endif; ?>
    </aside>

    <div class="dashboard-panel p-7 sm:p-8">
      <?php if ($selectedMessage): ?>
      <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
        <div>
          <p class="text-xs font-semibold uppercase tracking-[0.2em] text-secondary">Property reference</p>
          <h3 class="mt-3 text-3xl font-semibold tracking-tight text-primary"><?= e($selectedMessage['property_title']) ?></h3>
          <div class="mt-4 flex flex-wrap items-center gap-3 text-sm text-on-surface-variant">
            <span>From <?= e($selectedMessage['sender_name']) ?></span>
            <span>&bull;</span>
            <span><?= e(formatDate($selectedMessage['created_at'], 'M d, Y g:i A')) ?></span>
          </div>
        </div>
        <?php if (!empty($selectedMessage['property_id'])): ?>
        <a href="<?= route('property/' . (int)$selectedMessage['property_id']) ?>" class="btn-secondary">View Property</a>
        <?php endif; ?>
      </div>

      <div class="mt-8 grid gap-4 md:grid-cols-3">
        <div class="rounded-[1.5rem] bg-surface-container-low px-5 py-5">
          <p class="text-xs font-semibold uppercase tracking-[0.18em] text-on-surface-variant">Sender</p>
          <p class="mt-3 text-lg font-semibold tracking-tight text-primary"><?= e($selectedMessage['sender_name']) ?></p>
        </div>
        <div class="rounded-[1.5rem] bg-surface-container-low px-5 py-5">
          <p class="text-xs font-semibold uppercase tracking-[0.18em] text-on-surface-variant">Email</p>
          <p class="mt-3 truncate text-sm font-semibold text-primary"><?= e($selectedMessage['sender_email']) ?></p>
        </div>
        <div class="rounded-[1.5rem] bg-surface-container-low px-5 py-5">
          <p class="text-xs font-semibold uppercase tracking-[0.18em] text-on-surface-variant">Phone</p>
          <p class="mt-3 text-sm font-semibold text-primary"><?= e($selectedMessage['sender_phone'] ?: 'Not provided') ?></p>
        </div>
      </div>

      <div class="mt-8 rounded-[1.5rem] border border-outline-variant/20 bg-surface-container-lowest px-6 py-6">
        <h4 class="text-lg font-semibold tracking-tight text-primary">Inquiry</h4>
        <div class="mt-4 text-base leading-8 text-on-surface-variant">
          <?= nl2br(e($selectedMessage['body'])) ?>
        </div>
      </div>
      <?php else: ?>
      <div class="flex min-h-[420px] flex-col items-center justify-center text-center">
        <span class="material-symbols-outlined text-6xl text-primary-fixed-variant">mark_email_read</span>
        <h3 class="mt-5 text-2xl font-semibold tracking-tight text-primary">Select a message to read it</h3>
        <p class="mt-3 max-w-xl text-base leading-7 text-on-surface-variant">
          Your inbox is connected. Choose any inquiry from the left column to see the full renter message and property reference.
        </p>
      </div>
      <?php endif; ?>
    </div>
  </section>
</main>
<?php
$footerMode = 'none';
include __DIR__ . '/../partials/footer.php';
?>
