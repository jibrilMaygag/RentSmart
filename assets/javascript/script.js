/**
 * RentSmart Main Script
 * Handles UI interactions, Search, and Animations
 */

document.addEventListener('DOMContentLoaded', () => {

    // --- Mobile Menu Toggle ---
    const menuToggle = document.querySelector('.menu-toggle');
    const navMenu = document.querySelector('.nav-menu');

    if (menuToggle && navMenu) {
        menuToggle.addEventListener('click', () => {
            navMenu.classList.toggle('active');
            menuToggle.classList.toggle('active');

            // Toggle icon if using FontAwesome
            const icon = menuToggle.querySelector('i');
            if (icon) {
                if (navMenu.classList.contains('active')) {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-times');
                } else {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            }
        });

        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!navMenu.contains(e.target) && !menuToggle.contains(e.target) && navMenu.classList.contains('active')) {
                navMenu.classList.remove('active');
                menuToggle.classList.remove('active');
                const icon = menuToggle.querySelector('i');
                if (icon) {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            }
        });
    }

    // --- Navbar Scroll Effect ---
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    }

    // --- Search Functionality (Mock) ---
    const searchForm = document.querySelector('.search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const location = searchForm.querySelector('input[list="cities"]').value;
            const typeSelector = searchForm.querySelector('select:nth-of-type(1)');
            const type = typeSelector ? typeSelector.value : 'Property';

            // Animation for button
            const btn = searchForm.querySelector('.btn-search');
            if (btn) {
                const originalText = btn.textContent;
                btn.textContent = 'Searching...';
                btn.disabled = true;

                setTimeout(() => {
                    // Simulate redirect or results
                    btn.textContent = originalText;
                    btn.disabled = false;
                    alert(`Searching for ${type} in ${location || 'any location'}.`);
                }, 1000);
            }
        });
    }

    // --- Wishlist Toggle ---
    const wishlistBtns = document.querySelectorAll('.wishlist-btn');
    wishlistBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation(); // Prevent card click
            btn.classList.toggle('active');
            if (btn.classList.contains('active')) {
                btn.style.color = '#ef4444'; // Red
                btn.textContent = '♥';
            } else {
                btn.style.color = '#d1d5db'; // Gray
            }
        });
    });

    // --- Card Click to Listing ---
    const propertyCards = document.querySelectorAll('.property-card');
    propertyCards.forEach(card => {
        card.addEventListener('click', (e) => {
            // Prevent if clicking wishlist or any button
            if (e.target.closest('button')) return;

            // Direct link since we are in root and files are in root
            window.location.href = 'listing.html';
        });
    });

    // --- Intersection Observer for Animations ---
    const observerOptions = {
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Apply to elements
    const animatedElements = document.querySelectorAll('.property-card, .step-card, .location-card, .testimonial-card');
    animatedElements.forEach((el, index) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        // INCREASE SPEED: 0.3s instead of 0.6s
        el.style.transition = `all 0.3s ease ${index * 0.05}s`;
        observer.observe(el);
    });

});
