/**
 * Premium Navigation & Bootstrap Slider
 * Simple & Clean - No Conflicts
 */

(function ($) {
    'use strict';

    // ===== STICKY HEADER =====
    $(window).on('scroll', function () {
        if ($(this).scrollTop() > 50) {
            $('.header-premium').addClass('scrolled');
        } else {
            $('.header-premium').removeClass('scrolled');
        }
    });

    // ===== MOBILE DRAWER NAVIGATION =====
    const mobileToggle = $('.mobile-menu-toggle');
    const drawer = $('.mobile-drawer');
    const drawerOverlay = $('.drawer-overlay');
    const drawerClose = $('.drawer-close');

    // Open drawer
    mobileToggle.on('click', function (e) {
        e.preventDefault();
        $(this).toggleClass('active');
        drawer.addClass('active');
        drawerOverlay.addClass('active');
        $('body').css('overflow', 'hidden');
    });

    // Close drawer function
    function closeDrawer() {
        mobileToggle.removeClass('active');
        drawer.removeClass('active');
        drawerOverlay.removeClass('active');
        $('body').css('overflow', 'auto');
    }

    // Close drawer events
    drawerClose.on('click', function (e) {
        e.preventDefault();
        closeDrawer();
    });

    drawerOverlay.on('click', function (e) {
        e.preventDefault();
        closeDrawer();
    });

    // ===== ACTIVE LINK DETECTION ON SCROLL =====
    const sections = $('section[id]');

    $(window).on('scroll', function () {
        const scrollPos = $(this).scrollTop() + 100;

        sections.each(function () {
            const section = $(this);
            const sectionTop = section.offset().top;
            const sectionBottom = sectionTop + section.outerHeight();
            const sectionId = section.attr('id');

            if (scrollPos >= sectionTop && scrollPos < sectionBottom) {
                $('.nav-link-premium').removeClass('active');
                $(`.nav-link-premium[href="#${sectionId}"]`).addClass('active');
            }
        });
    });

    // ===== BOOTSTRAP CAROUSEL =====
    if ($('#heroCarousel').length) {
        var carousel = new bootstrap.Carousel('#heroCarousel', {
            interval: 5000,
            wrap: true,
            keyboard: true,
            pause: 'hover'
        });
    }

    // ===== KEYBOARD NAVIGATION =====
    $(document).on('keydown', function (e) {
        if (e.key === 'Escape' && drawer.hasClass('active')) {
            closeDrawer();
        }
    });

    // ===== PRELOADER =====
    $(window).on('load', function () {
        $('#preloader').fadeOut('slow');
    });

    // ===== BACK TO TOP BUTTON =====
    const backToTop = $('.back-to-top');

    $(window).on('scroll', function () {
        if ($(this).scrollTop() > 100) {
            backToTop.fadeIn();
        } else {
            backToTop.fadeOut();
        }
    });

})(jQuery);
