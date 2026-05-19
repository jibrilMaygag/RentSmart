/**
 * RentSmart Main Script
 * Handles UI interactions, mobile menu, tabs, and wishlist states.
 */

document.addEventListener('DOMContentLoaded', () => {

  // ── Mobile Menu Toggle ─────────────────────────────────────────────────────
  const menuToggle = document.querySelector('.menu-toggle');
  const navMenu    = document.querySelector('.nav-menu');

  if (menuToggle && navMenu) {
    menuToggle.addEventListener('click', () => {
      navMenu.classList.toggle('active');
      menuToggle.classList.toggle('active');
      const icon = menuToggle.querySelector('i');
      if (icon) {
        const isOpen = navMenu.classList.contains('active');
        icon.classList.toggle('fa-bars',  !isOpen);
        icon.classList.toggle('fa-times',  isOpen);
        document.body.style.overflow = isOpen ? 'hidden' : '';
      }
    });

    // Close menu when a link is clicked
    navMenu.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        navMenu.classList.remove('active');
        menuToggle.classList.remove('active');
        document.body.style.overflow = '';
        const icon = menuToggle.querySelector('i');
        if (icon) { icon.classList.replace('fa-times', 'fa-bars'); }
      });
    });
  }

  // ── Navbar scroll shadow ───────────────────────────────────────────────────
  const navbar = document.querySelector('.navbar');
  if (navbar) {
    const updateNav = () => {
      navbar.classList.toggle('scrolled', window.scrollY > 20);
    };
    window.addEventListener('scroll', updateNav, { passive: true });
    updateNav();
  }

  // ── Search Tabs (hero + search results listing-type tabs) ──────────────────
  document.querySelectorAll('.search-tab[data-mode]').forEach(btn => {
    btn.addEventListener('click', () => {
      const parent = btn.closest('.search-tabs');
      if (!parent) return;
      parent.querySelectorAll('.search-tab').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      // Update hidden listing_type input if present
      const hiddenInput = document.getElementById('filter-listing-type')
                       || document.getElementById('heroListingType');
      if (hiddenInput) {
        hiddenInput.value = btn.dataset.mode === 'buy' ? 'sale' : 'rent';
      }

      // Client-side property card filtering (static pages only)
      const cards = document.querySelectorAll('.property-card[data-mode]');
      if (cards.length > 0) {
        let count = 0;
        cards.forEach(card => {
          const show = card.dataset.mode === btn.dataset.mode;
          card.style.display = show ? '' : 'none';
          if (show) count++;
        });

        const heading  = document.querySelector('.search-results-layout h1');
        const countEl  = document.querySelector('.search-results-layout p');
        if (heading) {
          heading.textContent = `Properties for ${btn.dataset.mode === 'buy' ? 'Sale' : 'Rent'} in Addis Ababa`;
        }
        if (countEl) {
          countEl.textContent = `Showing ${count} result${count !== 1 ? 's' : ''}`;
        }
      }
    });
  });

  // ── Bedroom filter buttons ─────────────────────────────────────────────────
  document.querySelectorAll('.bedroom-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const beds    = btn.dataset.beds;
      const input   = document.getElementById('bedroomsInput');
      const current = input ? input.value : '';

      document.querySelectorAll('.bedroom-btn').forEach(b => b.classList.remove('active-filter'));

      if (current === beds) {
        if (input) input.value = '';
      } else {
        btn.classList.add('active-filter');
        if (input) input.value = beds;
      }
    });
  });

  // ── Wishlist (heart) buttons — static pages ────────────────────────────────
  // On static HTML pages (fallback) just toggle visual state
  document.querySelectorAll('.wishlist-btn:not(.js-favorite):not(a)').forEach(btn => {
    btn.addEventListener('click', e => {
      e.preventDefault();
      e.stopPropagation();
      btn.classList.toggle('active');
    });
  });

  // ── Gallery thumbnail swap ─────────────────────────────────────────────────
  document.querySelectorAll('.gallery-thumb').forEach(img => {
    img.addEventListener('click', () => {
      const main = document.getElementById('mainGalleryImg');
      if (main) {
        const tmp = main.src;
        main.src  = img.src;
        img.src   = tmp;
      }
    });
  });

  // ── Scroll-reveal for property cards ──────────────────────────────────────
  if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.opacity  = '1';
          entry.target.style.transform = 'translateY(0)';
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1 });

    document.querySelectorAll('.property-card, .step-card, .testimonial-card').forEach(el => {
      el.style.opacity   = '0';
      el.style.transform = 'translateY(20px)';
      el.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
      observer.observe(el);
    });
  }

  // ── Auto-dismiss flash banners after 5 s ──────────────────────────────────
  document.querySelectorAll('.alert-banner').forEach(banner => {
    setTimeout(() => {
      banner.style.transition = 'opacity 0.5s';
      banner.style.opacity    = '0';
      setTimeout(() => banner.remove(), 500);
    }, 5000);
  });

});
