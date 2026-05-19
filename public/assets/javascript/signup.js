document.addEventListener('DOMContentLoaded', () => {
    renderSignupPage();
});

function renderSignupPage() {
    const body = document.body;

    // Create main auth-page container
    const authPage = document.createElement('div');
    authPage.className = 'auth-page';

    // Create split-layout container
    const splitLayout = document.createElement('div');
    splitLayout.className = 'split-layout';

    // --- Left Side: Form ---
    const authLeft = document.createElement('div');
    authLeft.className = 'auth-left';

    // Logo
    const logoDiv = document.createElement('div');
    logoDiv.className = 'logo';
    const logoLink = document.createElement('a');
    logoLink.href = 'index.html';
    const logoH2 = document.createElement('h2');
    logoH2.textContent = 'RentSmart';
    logoLink.appendChild(logoH2);
    logoDiv.appendChild(logoLink);

    // Heading
    const h1 = document.createElement('h1');
    h1.textContent = 'Create an account';

    // Subtitle
    const subtitle = document.createElement('p');
    subtitle.className = 'auth-subtitle';
    subtitle.textContent = 'Start your journey to finding your dream home.';

    // Form
    const form = document.createElement('form');

    // Full Name Input
    const nameGroup = createFormGroup('Full Name', 'text', 'Enter your name');

    // Email Input
    const emailGroup = createFormGroup('Email', 'email', 'Enter your email');

    // Password Input
    const passwordGroup = createFormGroup('Password', 'password', 'Create a password');

    // Submit Button
    const submitBtn = document.createElement('button');
    submitBtn.className = 'btn-primary btn-block';
    submitBtn.textContent = 'Create Account';

    // Social Login
    const socialLogin = document.createElement('div');
    socialLogin.className = 'social-login';

    const googleBtn = document.createElement('button');
    googleBtn.type = 'button';
    googleBtn.textContent = ' Google'; // Note: Icon would go here usually

    const appleBtn = document.createElement('button');
    appleBtn.type = 'button';
    appleBtn.textContent = ' Apple';

    socialLogin.appendChild(googleBtn);
    socialLogin.appendChild(appleBtn);

    // Append form elements
    form.appendChild(nameGroup);
    form.appendChild(emailGroup);
    form.appendChild(passwordGroup);
    form.appendChild(submitBtn);
    form.appendChild(socialLogin);

    // Divider / Login Link
    const divider = document.createElement('div');
    divider.className = 'divider';
    const dividerSpan = document.createElement('span');
    dividerSpan.innerHTML = 'Already have an account? <a href="login.html" style="color: #2563eb;">Log in</a>';
    divider.appendChild(dividerSpan);

    // Assemble Left Side
    authLeft.appendChild(logoDiv);
    authLeft.appendChild(h1);
    authLeft.appendChild(subtitle);
    authLeft.appendChild(form);
    authLeft.appendChild(divider);

    // --- Right Side: Image ---
    const authRight = document.createElement('div');
    authRight.className = 'auth-right';
    const overlay = document.createElement('div');
    overlay.className = 'overlay';
    authRight.appendChild(overlay);

    // Assemble Main Layout
    splitLayout.appendChild(authLeft);
    splitLayout.appendChild(authRight);
    authPage.appendChild(splitLayout);

    // Inject into body
    body.appendChild(authPage);
}

function createFormGroup(labelText, inputType, placeholder) {
    const group = document.createElement('div');
    group.className = 'form-group';

    const label = document.createElement('label');
    label.textContent = labelText;

    const input = document.createElement('input');
    input.type = inputType;
    input.placeholder = placeholder;
    input.required = true;

    group.appendChild(label);
    group.appendChild(input);

    return group;
}
