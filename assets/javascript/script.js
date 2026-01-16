/**
 * RentSmart Main Script
 * Handles UI interactions, Search, and Animations
 */

document.addEventListener('DOMContentLoaded', () => {

    // --- Search Results Filtering ---
    const searchTabs = document.querySelectorAll('.search-tabs .search-tab');
    const propertyCards = document.querySelectorAll('.property-card');

    // Check URL Params
    const urlParams = new URLSearchParams(window.location.search);
    const initialMode = urlParams.get('mode') || 'rent';

    // Function to apply filter
    function applyFilter(mode) {
        // Update Tabs
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

        // Filter Cards
        let count = 0;
        propertyCards.forEach(card => {
            const cardMode = card.dataset.mode;
            if (cardMode === mode || mode === 'all') { // Optional 'all' support
                card.style.display = 'block';
                count++;
            } else {
                card.style.display = 'none';
            }
        });

        // Update Heading
        const heading = document.querySelector('.search-results-layout h1');
        if (heading) {
            heading.textContent = `Properties for ${mode.charAt(0).toUpperCase() + mode.slice(1)} in Addis Ababa`;
        }

        // Update Count
        const countText = document.querySelector('.search-results-layout p');
        if (countText) {
            countText.textContent = `Showing ${count} results`;
        }
    }

    // Init Filter
    if (searchTabs.length > 0) {
        applyFilter(initialMode);

        // Event Listeners for Tabs
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

            // Toggle icon if using FontAwesome
            const icon = menuToggle.querySelector('i');
            if (icon) {
                if (navMenu.classList.contains('active')) {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-times');
                    document.body.style.overflow = 'hidden'; // Lock Scroll
                } else {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                    document.body.style.overflow = ''; // Unlock Scroll
                }
            }
        });

        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!navMenu.contains(e.target) && !menuToggle.contains(e.target) && navMenu.classList.contains('active')) {
                navMenu.classList.remove('active');
                menuToggle.classList.remove('active');
                document.body.style.overflow = ''; // Unlock Scroll
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

    // --- Search Functionality (Real Redirection) ---
    const searchForm = document.querySelector('.search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', (e) => {
            e.preventDefault();

            // Get Search Mode (Rent or Buy) - Check logic for active tab
            const activeTab = document.querySelector('.search-tab.active');
            // Default to 'rent' if no tab or text found
            const mode = activeTab ? activeTab.textContent.trim().toLowerCase() : 'rent';

            // Get Input Values
            const inputs = searchForm.querySelectorAll('input, select');
            const params = new URLSearchParams();

            params.append('mode', mode);

            inputs.forEach(input => {
                // Try to find label for context
                const label = input.closest('.search-input')?.querySelector('label')?.textContent.toLowerCase();
                const value = input.value;

                if (label && value) {
                    let key = 'q';
                    if (label.includes('location')) key = 'location';
                    else if (label.includes('type')) key = 'type';
                    else if (label.includes('price')) key = 'price';

                    params.append(key, value);
                } else if (value && input.name) {
                    // Fallback to name attribute
                    params.append(input.name, value);
                }
            });

            // Redirect to search results
            window.location.href = `search-results.html?${params.toString()}`;
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
    // --- Card Click to Listing ---
    // Reuse propertyCards from top of scope
    if (propertyCards) {
        propertyCards.forEach(card => {
            card.addEventListener('click', (e) => {
                // Prevent if clicking wishlist or any button
                if (e.target.closest('button')) return;

                // Direct link since we are in root and files are in root
                window.location.href = 'property-detail.html';
            });
        });
    }

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
