document.addEventListener('DOMContentLoaded', () => {
    setupSearchTabs();
    setupSearchForm();
});

function setupSearchTabs() {
    const tabs = document.querySelectorAll('.search-tab');
    if (!tabs.length) return;

    tabs.forEach(tab => {
        tab.addEventListener('click', (e) => {
            // Remove active class from all tabs
            tabs.forEach(t => t.classList.remove('active'));
            // Add active class to clicked tab
            e.target.classList.add('active');
        });
    });
}

function setupSearchForm() {
    const searchForm = document.querySelector('.search-form');
    if (!searchForm) return;

    searchForm.addEventListener('submit', (e) => {
        e.preventDefault();

        // Get Search Mode (Rent or Buy)
        const activeTab = document.querySelector('.search-tab.active');
        const mode = activeTab ? activeTab.textContent.toLowerCase() : 'rent';

        // Get Input Values
        const inputs = searchForm.querySelectorAll('input, select');
        const params = new URLSearchParams();

        params.append('mode', mode);

        inputs.forEach(input => {
            const label = input.closest('.search-input')?.querySelector('label')?.textContent.toLowerCase();
            const value = input.value;

            if (label && value) {
                // Map label to param key (simple mapping)
                let key = 'q';
                if (label.includes('location')) key = 'location';
                else if (label.includes('type')) key = 'type';
                else if (label.includes('price')) key = 'price';

                params.append(key, value);
            }
        });

        // Redirect to search results
        const queryString = params.toString();
        // Since we don't have a backend, we'll just log or reload mock
        console.log(`Searching for: ${queryString}`);
        // window.location.href = `search-results.html?${queryString}`;
        alert(`Search Initiated!\nMode: ${mode}\nParams: ${queryString}`);
    });
}
