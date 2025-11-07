document.addEventListener('DOMContentLoaded', function() {
    const categoryDropdownBtn = document.getElementById('categoryDropdownBtn');
    const categoryDropdownMenu = document.getElementById('categoryDropdownMenu');
    const mobileCategoryToggle = document.getElementById('mobileCategoryToggle');
    const mobileCategorySubmenu = document.getElementById('mobileCategorySubmenu');

    // Desktop dropdown
    if (categoryDropdownBtn && categoryDropdownMenu) {
        categoryDropdownBtn.addEventListener('click', function(event) {
            event.stopPropagation();
            categoryDropdownMenu.classList.toggle('show');
            categoryDropdownBtn.classList.toggle('active');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!categoryDropdownBtn.contains(event.target) && !categoryDropdownMenu.contains(event.target)) {
                categoryDropdownMenu.classList.remove('show');
                categoryDropdownBtn.classList.remove('active');
            }
        });

        // Close dropdown when pressing Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                categoryDropdownMenu.classList.remove('show');
                categoryDropdownBtn.classList.remove('active');
            }
        });

        // Close dropdown when clicking on a menu item
        categoryDropdownMenu.querySelectorAll('.custom-category-item').forEach(item => {
            item.addEventListener('click', function() {
                categoryDropdownMenu.classList.remove('show');
                categoryDropdownBtn.classList.remove('active');
            });
        });
    }

    // Mobile category toggle
    if (mobileCategoryToggle && mobileCategorySubmenu) {
        mobileCategoryToggle.addEventListener('click', function(event) {
            event.preventDefault();
            mobileCategorySubmenu.classList.toggle('active');
            const icon = this.querySelector('.bi-chevron-down');
            if (icon) {
                icon.classList.toggle('rotate');
            }
        });
    }
});



