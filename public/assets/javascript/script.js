document.addEventListener('DOMContentLoaded', () => {
  const body = document.body;
  const appUrl = body.dataset.appUrl || '';
  const csrfToken = body.dataset.csrfToken || '';

  const mobileMenuToggle = document.querySelector('[data-mobile-menu-toggle]');
  const mobileMenu = document.querySelector('[data-mobile-menu]');

  if (mobileMenuToggle && mobileMenu) {
    mobileMenuToggle.addEventListener('click', () => {
      mobileMenu.classList.toggle('hidden');
    });
  }

  document.querySelectorAll('[data-flash-close]').forEach((button) => {
    button.addEventListener('click', () => {
      button.closest('[data-flash-banner]')?.remove();
    });
  });

  document.querySelectorAll('[data-flash-banner]').forEach((banner) => {
    window.setTimeout(() => {
      banner.remove();
    }, 5000);
  });

  document.querySelectorAll('[data-password-toggle]').forEach((button) => {
    button.addEventListener('click', () => {
      const target = document.getElementById(button.dataset.passwordToggle || '');
      if (!target) {
        return;
      }

      const isPassword = target.getAttribute('type') === 'password';
      target.setAttribute('type', isPassword ? 'text' : 'password');

      const icon = button.querySelector('.material-symbols-outlined');
      if (icon) {
        icon.textContent = isPassword ? 'visibility_off' : 'visibility';
      }
    });
  });

  document.querySelectorAll('[data-listing-toggle]').forEach((button) => {
    button.addEventListener('click', () => {
      const group = button.closest('[data-listing-toggle-group]');
      if (!group) {
        return;
      }

      group.querySelectorAll('[data-listing-toggle]').forEach((item) => {
        item.classList.remove('bg-primary', 'text-white');
        item.classList.add('bg-surface-container-lowest', 'text-on-surface-variant');
      });

      button.classList.remove('bg-surface-container-lowest', 'text-on-surface-variant');
      button.classList.add('bg-primary', 'text-white');

      const hiddenInput = document.getElementById(group.dataset.target || '');
      if (hiddenInput) {
        hiddenInput.value = button.dataset.listingToggle;
      }
    });
  });

  document.querySelectorAll('[data-gallery-thumb]').forEach((thumb) => {
    thumb.addEventListener('click', () => {
      const target = document.getElementById(thumb.dataset.galleryTarget || '');
      if (!target) {
        return;
      }

      target.setAttribute('src', thumb.getAttribute('src') || '');
    });
  });

  document.querySelectorAll('[data-favorite-toggle]').forEach((button) => {
    button.addEventListener('click', async (event) => {
      event.preventDefault();
      event.stopPropagation();

      const propertyId = button.dataset.propertyId;
      if (!propertyId || !appUrl) {
        return;
      }

      try {
        const response = await fetch(`${appUrl}/api/toggle-favorite/${propertyId}`, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken
          }
        });

        if (response.status === 401) {
          window.location.href = `${appUrl}/login`;
          return;
        }

        const data = await response.json();
        const favorited = Boolean(data.favorited);
        const icon = button.querySelector('.material-symbols-outlined');
        const label = button.querySelector('[data-favorite-label]');

        button.dataset.favorited = favorited ? 'true' : 'false';

        if (icon) {
          icon.style.fontVariationSettings = `'FILL' ${favorited ? 1 : 0}`;
        }

        if (label) {
          label.textContent = favorited
            ? (button.dataset.labelActive || 'Saved')
            : (button.dataset.labelInactive || 'Save');
        }

        if (!favorited && button.hasAttribute('data-remove-on-unfavorite')) {
          const card = button.closest('[data-property-card]');
          const collection = button.closest('[data-property-collection]');

          card?.remove();

          if (collection && !collection.querySelector('[data-property-card]')) {
            collection.classList.add('hidden');

            const emptyStateId = collection.dataset.emptyStateTarget || '';
            if (emptyStateId) {
              document.getElementById(emptyStateId)?.classList.remove('hidden');
            }
          }
        }
      } catch (error) {
        window.location.href = `${appUrl}/login`;
      }
    });
  });
});
