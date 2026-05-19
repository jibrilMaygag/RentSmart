<?php
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$old = $_SESSION['old_input'] ?? [];
unset($_SESSION['old_input']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign Up | RentSmart</title>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
  <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/style.css" />
</head>
<body>
  <div class="auth-page">
    <div class="split-layout">
      <div class="auth-left">
        <div class="logo">
          <a href="<?= APP_URL ?>"><h2>RentSmart</h2></a>
        </div>
        <h1>Create Account</h1>
        <p class="auth-subtitle">Start finding or listing properties today.</p>

        <?php if ($errors = flash('errors')): ?>
        <div class="form-alert form-alert-danger">
          <i class="fas fa-exclamation-circle"></i>
          <ul style="margin:0.25rem 0 0 1rem;padding:0;">
            <?php foreach ($errors as $fieldErrors): ?>
              <?php foreach ($fieldErrors as $err): ?>
              <li><?= e($err) ?></li>
              <?php endforeach; ?>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endif; ?>

        <form method="POST" action="<?= APP_URL ?>/signup" id="signupForm" novalidate>
          <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>" />

          <div class="form-group">
            <label for="full_name">Full Name</label>
            <input type="text" id="full_name" name="full_name" placeholder="Your full name"
                   value="<?= e($old['full_name'] ?? '') ?>" required autocomplete="name" />
          </div>

          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="your@email.com"
                   value="<?= e($old['email'] ?? '') ?>" required autocomplete="email" />
          </div>

          <div class="form-group">
            <label for="role">I am a</label>
            <select id="role" name="role" required>
              <option value="renter"   <?= ($old['role'] ?? 'renter') === 'renter'   ? 'selected' : '' ?>>Renter – looking for a property</option>
              <option value="landlord" <?= ($old['role'] ?? '')         === 'landlord' ? 'selected' : '' ?>>Landlord – listing a property</option>
            </select>
          </div>

          <div class="form-group">
            <label for="password">Password <small style="color:#64748b;">(min. 8 characters)</small></label>
            <div class="input-icon-right">
              <input type="password" id="password" name="password" placeholder="••••••••" required autocomplete="new-password" />
              <button type="button" class="toggle-pw" tabindex="-1" aria-label="Show password">
                <i class="fas fa-eye"></i>
              </button>
            </div>
            <!-- Password strength bar -->
            <div id="strengthBar" style="height:4px;border-radius:2px;margin-top:6px;background:#e2e8f0;overflow:hidden;">
              <div id="strengthFill" style="height:100%;width:0;transition:width .3s,background .3s;"></div>
            </div>
            <small id="strengthLabel" style="font-size:0.75rem;color:#64748b;"></small>
          </div>

          <div class="form-group">
            <label for="password_confirm">Confirm Password</label>
            <input type="password" id="password_confirm" name="password_confirm" placeholder="••••••••" required autocomplete="new-password" />
            <span class="field-error" id="confirmError"></span>
          </div>

          <button type="submit" class="btn-primary btn-block" id="signupBtn">Create Account</button>
        </form>

        <div class="divider">
          <span>Already have an account? <a href="<?= APP_URL ?>/login" style="color:#2563eb;">Sign in</a></span>
        </div>
      </div>

      <div class="auth-right">
        <div class="overlay"></div>
      </div>
    </div>
  </div>

  <script>
  // Password strength
  const pwInput = document.getElementById('password');
  pwInput.addEventListener('input', () => {
    const v = pwInput.value;
    let score = 0;
    if (v.length >= 8)  score++;
    if (/[A-Z]/.test(v)) score++;
    if (/[0-9]/.test(v)) score++;
    if (/[^A-Za-z0-9]/.test(v)) score++;
    const fill   = document.getElementById('strengthFill');
    const label  = document.getElementById('strengthLabel');
    const colors = ['#ef4444','#f97316','#eab308','#22c55e'];
    const labels = ['Weak','Fair','Good','Strong'];
    fill.style.width      = (score * 25) + '%';
    fill.style.background = colors[score - 1] || '#e2e8f0';
    label.textContent     = score > 0 ? labels[score - 1] : '';
  });

  // Confirm password live check
  document.getElementById('password_confirm').addEventListener('input', function() {
    const err = document.getElementById('confirmError');
    err.textContent = this.value !== pwInput.value ? 'Passwords do not match.' : '';
  });

  // Toggle PW visibility
  document.querySelector('.toggle-pw').addEventListener('click', () => {
    if (pwInput.type === 'password') { pwInput.type = 'text'; }
    else { pwInput.type = 'password'; }
  });

  // Submit
  const form = document.getElementById('signupForm');
  form.addEventListener('submit', e => {
    const pw  = pwInput.value;
    const pwc = document.getElementById('password_confirm').value;
    if (pw !== pwc) {
      document.getElementById('confirmError').textContent = 'Passwords do not match.';
      e.preventDefault();
      return;
    }
    document.getElementById('signupBtn').textContent = 'Creating account…';
    document.getElementById('signupBtn').disabled = true;
  });
  </script>
</body>
</html>
