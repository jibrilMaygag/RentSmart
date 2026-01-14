/**
 * RentSmart Main Script
 * Handles UI interactions, Search, and Animations
 */

document.addEventListener('DOMContentLoaded', () => {

    // --- Mobile Menu Toggle ---
    const menuToggle = document.querySelector('.menu-toggle');
    const navMenu = document.querySelector('.nav-menu');

    if (menuToggle) {
        menuToggle.addEventListener('click', () => {
            navMenu.classList.toggle('active');
            menuToggle.textContent = navMenu.classList.contains('active') ? '✕' : '☰';
        });
    }

    // --- Navbar Scroll Effect ---
    const navbar = document.querySelector('.navbar');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // --- Search Functionality (Mock) ---
    const searchForm = document.querySelector('.search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const location = searchForm.querySelector('input[list="cities"]').value;
            const type = searchForm.querySelector('select:nth-of-type(1)').value;

            // Animation for button
            const btn = searchForm.querySelector('.btn-search');
            const originalText = btn.textContent;
            btn.textContent = 'Searching...';
            btn.disabled = true;

            setTimeout(() => {
                // Simulate redirect or results
                btn.textContent = originalText;
                btn.disabled = false;
                alert(`Searching for ${type}s in ${location || 'any location'}. (Result page would load here)`);
                // window.location.href = 'structure/search-results.html'; // In a real app
            }, 1000);
        });
    }

    // --- Wishlist Toggle ---
    const wishlistBtns = document.querySelectorAll('.wishlist-btn');
    wishlistBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
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
        card.addEventListener('click', () => {
            // Check if we are in root or structure folder to determine relative path
            const isStructure = window.location.pathname.includes('structure');
            const prefix = isStructure ? '' : 'structure/';
            // For now, hardcode to the one listing we created
            window.location.href = prefix + 'listing.html';
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
        el.style.transition = `all 0.6s ease ${index * 0.1}s`; // Staggered
        observer.observe(el);
    });

});
