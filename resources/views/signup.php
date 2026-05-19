<?php
$pageTitle = 'Sign Up | RentSmart';
$bodyClass = 'min-h-screen bg-background';
$old = $_SESSION['old_input'] ?? [];
unset($_SESSION['old_input']);

include __DIR__ . '/partials/head.php';
?>
<main class="grid min-h-screen lg:grid-cols-2">
  <section class="relative hidden overflow-hidden bg-surface-container-low lg:block">
    <img
      src="<?= e(imageUrl('pexels-photo-1396122.jpeg')) ?>"
      alt="RentSmart home interior"
      class="absolute inset-0 h-full w-full object-cover"
    />
    <div class="absolute inset-0 bg-slate-950/55"></div>
    <div class="relative z-10 flex h-full flex-col justify-between p-10 text-white xl:p-14">
      <div>
        <a href="<?= route('') ?>" class="text-4xl font-semibold tracking-[-0.03em]">RentSmart</a>
        <p class="mt-4 max-w-md text-lg leading-8 text-slate-200">
          Join the existing RentSmart marketplace to browse, save, and manage properties through the same stable PHP backend.
        </p>
      </div>

      <div class="space-y-6">
        <div class="rounded-[1.5rem] border border-white/10 bg-white/10 p-5 backdrop-blur-md">
          <p class="text-xs font-semibold uppercase tracking-[0.2em] text-secondary-container">Renter</p>
          <p class="mt-3 text-sm leading-7 text-slate-100">Save properties, revisit favorites, and contact landlords once you sign in.</p>
        </div>
        <div class="rounded-[1.5rem] border border-white/10 bg-white/10 p-5 backdrop-blur-md">
          <p class="text-xs font-semibold uppercase tracking-[0.2em] text-secondary-container">Landlord</p>
          <p class="mt-3 text-sm leading-7 text-slate-100">Access your listing dashboard and use the connected property pages already backed by MySQL.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="flex min-h-screen items-center justify-center px-5 py-10 sm:px-8">
    <div class="w-full max-w-[520px]">
      <a href="<?= route('') ?>" class="text-3xl font-semibold tracking-[-0.03em] text-primary lg:hidden">RentSmart</a>

      <div class="mt-10">
        <h1 class="text-3xl font-semibold tracking-tight text-primary">Create your account</h1>
        <p class="mt-3 text-base leading-7 text-on-surface-variant">
          Choose your role and start using the live RentSmart experience right away.
        </p>
      </div>

      <div class="mt-8">
        <?php include __DIR__ . '/partials/flash.php'; ?>
      </div>

      <form action="<?= route('signup') ?>" method="POST" class="mt-6 space-y-5">
        <?= csrfField() ?>

        <div>
          <label for="signupFullName" class="field-label">Full name</label>
          <input
            id="signupFullName"
            name="full_name"
            class="field-input"
            value="<?= e($old['full_name'] ?? '') ?>"
            placeholder="Your full name"
            autocomplete="name"
            required
          />
        </div>

        <div>
          <label for="signupEmail" class="field-label">Email address</label>
          <input
            id="signupEmail"
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
          <p class="field-label">Account role</p>
          <div class="grid gap-4 sm:grid-cols-2">
            <label class="group cursor-pointer">
              <input type="radio" name="role" value="renter" class="peer sr-only" <?= ($old['role'] ?? 'renter') === 'renter' ? 'checked' : '' ?> />
              <span class="block rounded-[1.25rem] border border-outline-variant/30 bg-surface-container-low p-5 transition peer-checked:border-secondary peer-checked:bg-secondary-container peer-checked:text-on-secondary-container group-hover:border-secondary/50">
                <span class="material-symbols-outlined">favorite</span>
                <span class="mt-3 block text-lg font-semibold tracking-tight">Renter</span>
                <span class="mt-2 block text-sm leading-6 text-inherit/80">Looking for a property to rent or buy.</span>
              </span>
            </label>

            <label class="group cursor-pointer">
              <input type="radio" name="role" value="landlord" class="peer sr-only" <?= ($old['role'] ?? '') === 'landlord' ? 'checked' : '' ?> />
              <span class="block rounded-[1.25rem] border border-outline-variant/30 bg-surface-container-low p-5 transition peer-checked:border-secondary peer-checked:bg-secondary-container peer-checked:text-on-secondary-container group-hover:border-secondary/50">
                <span class="material-symbols-outlined">home_work</span>
                <span class="mt-3 block text-lg font-semibold tracking-tight">Landlord</span>
                <span class="mt-2 block text-sm leading-6 text-inherit/80">Managing listings and property visibility.</span>
              </span>
            </label>
          </div>
        </div>

        <div>
          <label for="signupPassword" class="field-label">Password</label>
          <div class="relative">
            <input
              id="signupPassword"
              name="password"
              type="password"
              class="field-input pr-12"
              placeholder="Minimum 8 characters"
              autocomplete="new-password"
              required
            />
            <button
              type="button"
              class="absolute inset-y-0 right-3 inline-flex items-center text-on-surface-variant transition hover:text-primary"
              data-password-toggle="signupPassword"
              aria-label="Toggle password visibility"
            >
              <span class="material-symbols-outlined text-[20px]">visibility</span>
            </button>
          </div>
        </div>

        <div>
          <label for="signupPasswordConfirm" class="field-label">Confirm password</label>
          <div class="relative">
            <input
              id="signupPasswordConfirm"
              name="password_confirm"
              type="password"
              class="field-input pr-12"
              placeholder="Repeat your password"
              autocomplete="new-password"
              required
            />
            <button
              type="button"
              class="absolute inset-y-0 right-3 inline-flex items-center text-on-surface-variant transition hover:text-primary"
              data-password-toggle="signupPasswordConfirm"
              aria-label="Toggle confirm password visibility"
            >
              <span class="material-symbols-outlined text-[20px]">visibility</span>
            </button>
          </div>
          <p class="mt-2 text-xs uppercase tracking-[0.18em] text-on-surface-variant">Passwords must match and contain at least 8 characters.</p>
        </div>

        <button type="submit" class="btn-primary w-full justify-center">
          <span>Create account</span>
          <span class="material-symbols-outlined text-base">person_add</span>
        </button>
      </form>

      <p class="mt-8 text-center text-sm text-on-surface-variant">
        Already have an account?
        <a href="<?= route('login') ?>" class="font-semibold text-secondary transition hover:text-primary">Sign in</a>
      </p>
    </div>
  </section>
</main>
<?php
$footerMode = 'minimal';
include __DIR__ . '/partials/footer.php';
?>
