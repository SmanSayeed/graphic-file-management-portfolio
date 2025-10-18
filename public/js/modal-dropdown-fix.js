/**
 * Modal Dropdown Fix
 * Ensures Bootstrap dropdown works inside modals
 */

document.addEventListener('DOMContentLoaded', function () {
    // Fix dropdown in modal
    const portfolioModal = document.getElementById('portfolioModal');

    if (portfolioModal) {
        portfolioModal.addEventListener('shown.bs.modal', function () {
            // Initialize or reinitialize dropdown when modal opens
            const dropdownButton = document.getElementById('dropdownMenuButton');

            if (dropdownButton) {
                // Remove any existing dropdown instance
                const existingDropdown = bootstrap.Dropdown.getInstance(dropdownButton);
                if (existingDropdown) {
                    existingDropdown.dispose();
                }

                // Create new dropdown instance
                new bootstrap.Dropdown(dropdownButton);

                console.log('Dropdown initialized in modal');
            }
        });
    }

    // Alternative: Manual dropdown toggle if Bootstrap fails
    const dropdownButton = document.getElementById('dropdownMenuButton');
    if (dropdownButton) {
        dropdownButton.addEventListener('click', function (e) {
            const dropdownMenu = this.nextElementSibling;
            if (dropdownMenu && dropdownMenu.classList.contains('dropdown-menu')) {
                dropdownMenu.classList.toggle('show');
                this.setAttribute('aria-expanded', dropdownMenu.classList.contains('show'));
                e.stopPropagation();
            }
        });
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function (e) {
        const dropdownButton = document.getElementById('dropdownMenuButton');
        const dropdownMenu = document.querySelector('#dropdownMenuButton + .dropdown-menu');

        if (dropdownButton && dropdownMenu) {
            if (!dropdownButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.remove('show');
                dropdownButton.setAttribute('aria-expanded', 'false');
            }
        }
    });

    // Prevent dropdown from closing when clicking inside
    document.querySelectorAll('.dropdown-menu').forEach(function (dropdown) {
        dropdown.addEventListener('click', function (e) {
            e.stopPropagation();
        });
    });
});
