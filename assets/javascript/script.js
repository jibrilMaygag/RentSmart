/**
 * RentSmart Main Script 
 * Handles UI interactions, Search, and Animations
 * (With LocalStorage Wishlist Integrated)
 */

document.addEventListener('DOMContentLoaded', () => {

    // --- Search Results Filtering ---
    const searchTabs = document.querySelectorAll('.search-tabs .search-tab');
    const propertyCards = document.querySelectorAll('.property-card');

    const urlParams = new URLSearchParams(window.location.search);
    const initialMode = urlParams.get('mode') || 'rent';

    function applyFilter(mode) {
        searchTabs.forEach(tab => {
            if (tab.dataset.mode === mode) {
                tab.classList.add('active');
                tab.style.color = '#2563eb';
                tab.style.borderBottom = '2px solid #2563eb';
            } else {
                tab.classList.remove('active');
                tab.style.color = '#64748b';
                tab.style.borderBottom = 'none';
            }
        });

        let count = 0;
        propertyCards.forEach(card => {
            const cardMode = card.dataset.mode;
            if (cardMode === mode || mode === 'all') {
                card.style.display = 'block';
                count++;
            } else {
                card.style.display = 'none';
            }
        });

        const heading = document.querySelector('.search-results-layout h1');
        if (heading) {
            heading.textContent = `Properties for ${mode.charAt(0).toUpperCase() + mode.slice(1)} in Addis Ababa`;
        }

        const countText = document.querySelector('.search-results-layout p');
        if (countText) {
            countText.textContent = `Showing ${count} results`;
        }
    }

    if (searchTabs.length > 0) {
        applyFilter(initialMode);
        searchTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                applyFilter(tab.dataset.mode);
            });
        });
    }

    // --- Mobile Menu Toggle ---
    const menuToggle = document.querySelector('.menu-toggle');
    const navMenu = document.querySelector('.nav-menu');

    if (menuToggle && navMenu) {
        menuToggle.addEventListener('click', () => {
            navMenu.classList.toggle('active');
            menuToggle.classList.toggle('active');

            const icon = menuToggle.querySelector('i');
            if (icon) {
                if (navMenu.classList.contains('active')) {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-times');
                    document.body.style.overflow = 'hidden';
                } else {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                    document.body.style.overflow = '';
                }
            }
        });

        document.addEventListener('click', (e) => {
            if (!navMenu.contains(e.target) && !menuToggle.contains(e.target) && navMenu.classList.contains('active')) {
                navMenu.classList.remove('active');
                menuToggle.classList.remove('active');
                document.body.style.overflow = '';
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

    // --- Search Functionality ---
    const searchForm = document.querySelector('.search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const activeTab = document.querySelector('.search-tab.active');
            const mode = activeTab ? activeTab.textContent.trim().toLowerCase() : 'rent';
            const inputs = searchForm.querySelectorAll('input, select');
            const params = new URLSearchParams();

            params.append('mode', mode);

            inputs.forEach(input => {
                const label = input.closest('.search-input')?.querySelector('label')?.textContent.toLowerCase();
                const value = input.value;

                if (label && value) {
                    let key = 'q';
                    if (label.includes('location')) key = 'location';
                    else if (label.includes('type')) key = 'type';
                    else if (label.includes('price')) key = 'price';
                    params.append(key, value);
                } else if (value && input.name) {
                    params.append(input.name, value);
                }
            });

            window.location.href = `search-results.html?${params.toString()}`;
        });
    }

    // --- Wishlist with LocalStorage (Integrated carefully) ---
    const STORAGE_KEY = 'rentsmart_wishlist';
    
    // Helper functions to talk to LocalStorage
    const getWishlist = () => JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
    const saveWishlist = (data) => localStorage.setItem(STORAGE_KEY, JSON.stringify(data));

    const wishlistBtns = document.querySelectorAll('.wishlist-btn');
    
    wishlistBtns.forEach((btn, index) => {
        // Use data-id if available, otherwise use index as fallback
        const card = btn.closest('.property-card');
        const propertyId = card?.dataset.id || `prop-${index}`;

        // 1. Initial State: Check if this property is already liked
        let currentWishlist = getWishlist();
        if (currentWishlist.includes(propertyId)) {
            btn.classList.add('active');
            btn.style.color = '#ef4444';
            btn.textContent = '♥';
        }

        // 2. Click Handler
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();

            let wishlist = getWishlist();

            if (wishlist.includes(propertyId)) {
                // Remove from wishlist
                wishlist = wishlist.filter(id => id !== propertyId);
                btn.classList.remove('active');
                btn.style.color = '#d1d5db'; // Back to gray
                btn.textContent = '♡'; // Assuming empty heart for inactive
            } else {
                // Add to wishlist
                wishlist.push(propertyId);
                btn.classList.add('active');
                btn.style.color = '#ef4444'; // Red
                btn.textContent = '♥';
            }

            saveWishlist(wishlist);
        });
    });

    // --- Card Click to Listing ---
    if (propertyCards) {
        propertyCards.forEach(card => {
            card.addEventListener('click', (e) => {
                if (e.target.closest('button')) return;
                window.location.href = 'property-detail.html';
            });
        });
    }

    // --- Intersection Observer for Animations ---
    const observerOptions = { threshold: 0, rootMargin: '50px' };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    const animatedElements = document.querySelectorAll('.property-card, .step-card, .location-card, .testimonial-card');
    animatedElements.forEach((el, index) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        const delay = index < 4 ? 0 : (index % 4) * 0.1;
        el.style.transition = `all 0.4s ease-out ${delay}s`;
        observer.observe(el);
    });

});