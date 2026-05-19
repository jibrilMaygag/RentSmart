<?php
// Generate CSRF token if needed
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login | RentSmart</title>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
  <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/style.css" />
</head>
<body>
  <div class="auth-page">
    <div class="split-layout">
      <!-- Left: Form -->
      <div class="auth-left">
        <div class="logo">
          <a href="<?= APP_URL ?>"><h2>RentSmart</h2></a>
        </div>
        <h1>Welcome back</h1>
        <p class="auth-subtitle">Welcome back! Please enter your details.</p>

        <?php if ($error = flash('error')): ?>
        <div class="form-alert form-alert-danger">
          <i class="fas fa-exclamation-circle"></i> <?= e($error) ?>
        </div>
        <?php endif; ?>

        <?php if ($success = flash('success')): ?>
        <div class="form-alert form-alert-success">
          <i class="fas fa-check-circle"></i> <?= e($success) ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="<?= APP_URL ?>/login" id="loginForm" novalidate>
          <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>" />

          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email"
                   value="<?= e($_POST['email'] ?? '') ?>" required autocomplete="email" />
            <span class="field-error" id="emailError"></span>
          </div>

          <div class="form-group">
            <label for="password">Password</label>
            <div class="input-icon-right">
              <input type="password" id="password" name="password" placeholder="••••••••" required autocomplete="current-password" />
              <button type="button" class="toggle-pw" tabindex="-1" aria-label="Show password">
                <i class="fas fa-eye"></i>
              </button>
            </div>
            <span class="field-error" id="passwordError"></span>
          </div>

          <div class="form-options">
            <label style="display:flex;gap:0.5rem;align-items:center;cursor:pointer;">
              <input type="checkbox" name="remember" style="width:auto;" /> Remember me
            </label>
            <a href="#">Forgot password?</a>
          </div>

          <button type="submit" class="btn-primary btn-block" id="loginBtn">Sign In</button>
        </form>

        <div class="divider">
          <span>Don't have an account? <a href="<?= APP_URL ?>/signup" style="color:#2563eb;">Sign up for free</a></span>
        </div>
      </div>

      <!-- Right: Image -->
      <div class="auth-right">
        <div class="overlay"></div>
      </div>
    </div>
  </div>

  <script>
  // Client-side validation
  const form = document.getElementById('loginForm');
  form.addEventListener('submit', e => {
    let ok = true;
    const email = document.getElementById('email');
    const pw    = document.getElementById('password');
    document.getElementById('emailError').textContent    = '';
    document.getElementById('passwordError').textContent = '';

    if (!email.value.trim() || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
      document.getElementById('emailError').textContent = 'Please enter a valid email.';
      ok = false;
    }
    if (!pw.value) {
      document.getElementById('passwordError').textContent = 'Password is required.';
      ok = false;
    }
    if (!ok) { e.preventDefault(); return; }
    document.getElementById('loginBtn').textContent = 'Signing in…';
    document.getElementById('loginBtn').disabled = true;
  });

  // Toggle password visibility
  document.querySelector('.toggle-pw').addEventListener('click', () => {
    const pw = document.getElementById('password');
    const ic = document.querySelector('.toggle-pw i');
    if (pw.type === 'password') { pw.type = 'text'; ic.classList.replace('fa-eye','fa-eye-slash'); }
    else                        { pw.type = 'password'; ic.classList.replace('fa-eye-slash','fa-eye'); }
  });
  </script>
</body>
</html>
