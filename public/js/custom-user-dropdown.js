/**
 * Custom User Dropdown
 * Fully custom dropdown without Bootstrap dependency
 */

(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        const dropdownBtn = document.getElementById('userDropdownBtn');
        const dropdownMenu = document.getElementById('userDropdownMenu');

        if (!dropdownBtn || !dropdownMenu) {
            return;
        }

        // Toggle dropdown
        dropdownBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdownBtn.classList.toggle('active');
            dropdownMenu.classList.toggle('show');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownBtn.classList.remove('active');
                dropdownMenu.classList.remove('show');
            }
        });

        // Close dropdown when clicking on a menu item (except logout form)
        dropdownMenu.addEventListener('click', function(e) {
            const item = e.target.closest('.custom-dropdown-item');
            if (item && !item.closest('.custom-dropdown-item-form')) {
                // Small delay to allow navigation
                setTimeout(function() {
                    dropdownBtn.classList.remove('active');
                    dropdownMenu.classList.remove('show');
                }, 100);
            }
        });

        // Prevent dropdown from closing when clicking inside the menu
        dropdownMenu.addEventListener('click', function(e) {
            e.stopPropagation();
        });

        // Close dropdown on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && dropdownMenu.classList.contains('show')) {
                dropdownBtn.classList.remove('active');
                dropdownMenu.classList.remove('show');
            }
        });
    });
})();

