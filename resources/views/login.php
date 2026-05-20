<?php
/**
 * @var array $old
 */
$pageTitle = 'Login | RentSmart';
$bodyClass = 'min-h-screen bg-background';
$old = $old ?? [];
$authImage = 'https://images.unsplash.com/photo-1771495562804-373fb516114c?auto=format&fit=crop&fm=jpg&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&ixlib=rb-4.1.0&q=60&w=1400';

include __DIR__ . '/partials/head.php';
?>
<main class="grid min-h-screen lg:grid-cols-2">
  <section class="relative hidden overflow-hidden bg-primary lg:block">
    <img
      src="<?= e(imageUrl($authImage)) ?>"
      alt="RentSmart architecture background"
      class="absolute inset-0 h-full w-full object-cover opacity-75 mix-blend-luminosity"
    />
    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/25 to-slate-950/5"></div>
    <div class="relative z-10 flex h-full flex-col justify-between p-10 text-white xl:p-14">
      <div>
        <a href="<?= route('') ?>" class="text-4xl font-semibold tracking-[-0.03em]">RentSmart</a>
        <p class="mt-4 max-w-md text-lg leading-8 text-slate-200">
          Sign in to view saved homes, manage listings, and stay on top of your account.
        </p>
      </div>

      <div class="space-y-6">
        <div class="flex items-start gap-4">
          <span class="material-symbols-outlined rounded-2xl border border-white/15 p-3 text-secondary-container">verified_user</span>
          <div>
            <p class="font-semibold">Secure access</p>
            <p class="mt-1 text-sm leading-6 text-slate-300">Your account stays protected so you can pick up right where you left off.</p>
          </div>
        </div>
        <div class="flex items-start gap-4">
          <span class="material-symbols-outlined rounded-2xl border border-white/15 p-3 text-secondary-container">favorite</span>
          <div>
            <p class="font-semibold">Saved properties</p>
            <p class="mt-1 text-sm leading-6 text-slate-300">Return to saved homes and continue browsing from your dashboard.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="flex min-h-screen items-center justify-center px-5 py-10 sm:px-8">
    <div class="w-full max-w-[460px]">
      <a href="<?= route('') ?>" class="text-3xl font-semibold tracking-[-0.03em] text-primary lg:hidden">RentSmart</a>

      <div class="mt-10">
        <h1 class="text-3xl font-semibold tracking-tight text-primary">Welcome back</h1>
        <p class="mt-3 text-base leading-7 text-on-surface-variant">
          Enter your details to access your renter or landlord dashboard.
        </p>
      </div>

      <div class="mt-8">
        <?php include __DIR__ . '/partials/flash.php'; ?>
      </div>

      <form action="<?= route('login') ?>" method="POST" class="mt-6 space-y-5">
        <?= csrfField() ?>

        <div>
          <label for="loginEmail" class="field-label">Email address</label>
          <input
            id="loginEmail"
            name="email"
            type="email"
            class="field-input"
            value="<?= e($old['email'] ?? '') ?>"
            placeholder="name@example.com"
            autocomplete="email"
            required
          />
        </div>

        <div>
          <div class="mb-2 flex items-center justify-between">
            <label for="loginPassword" class="text-sm font-medium text-on-surface-variant">Password</label>
            <span class="text-xs font-medium uppercase tracking-[0.18em] text-secondary">Secure sign-in</span>
          </div>
          <div class="relative">
            <input
              id="loginPassword"
              name="password"
              type="password"
              class="field-input pr-12"
              placeholder="Enter your password"
              autocomplete="current-password"
              required
            />
            <button
              type="button"
              class="absolute inset-y-0 right-3 inline-flex items-center text-on-surface-variant transition hover:text-primary"
              data-password-toggle="loginPassword"
              aria-label="Toggle password visibility"
            >
              <span class="material-symbols-outlined text-[20px]">visibility</span>
            </button>
          </div>
        </div>

        <label class="flex items-center gap-3 rounded-2xl bg-surface-container-low px-4 py-4 text-sm text-on-surface-variant">
          <input
            type="checkbox"
            name="remember"
            value="1"
            class="h-4 w-4 rounded border-outline-variant text-secondary focus:ring-secondary"
            <?= !empty($old['remember']) ? 'checked' : '' ?>
          />
          <span>Keep me signed in on this device</span>
        </label>

        <button type="submit" class="btn-primary w-full justify-center">
          <span>Sign in</span>
          <span class="material-symbols-outlined text-base">arrow_forward</span>
        </button>
      </form>

      <p class="mt-8 text-center text-sm text-on-surface-variant">
        Don&apos;t have an account?
        <a href="<?= route('signup') ?>" class="font-semibold text-secondary transition hover:text-primary">Create one</a>
      </p>
    </div>
  </section>
</main>
<?php
$footerMode = 'minimal';
include __DIR__ . '/partials/footer.php';
?>
