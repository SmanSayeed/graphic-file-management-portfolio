/**
 * Premium Portfolio Theme JavaScript
 * ThemeForest Quality - Simplified
 * Version: 1.0.2
 */

(function ($) {
    'use strict';

    // Initialize AOS (Animate On Scroll) - Disabled for portfolio items
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            mirror: false,
            disable: function () {
                return window.innerWidth < 768;
            }
        });
    }

    // Isotope Portfolio Filter - Simple Fade Only
    var $portfolioGrid = $('.portfolio-grid');

    // Initialize Isotope after images loaded
    if ($portfolioGrid.length) {
        $portfolioGrid.imagesLoaded(function () {
            $portfolioGrid.isotope({
                itemSelector: '.portfolio-item',
                layoutMode: 'fitRows',
                percentPosition: true,
                transitionDuration: '0.5s',
                hiddenStyle: {
                    opacity: 0
                },
                visibleStyle: {
                    opacity: 1
                },
                stagger: 0,
                containerStyle: {
                    position: 'relative'
                }
            });
        });

        // Filter items on button click
        $('.filter-btn-premium').on('click', function () {
            $('.filter-btn-premium').removeClass('active');
            $(this).addClass('active');

            var filterValue = $(this).attr('data-filter');
            $portfolioGrid.isotope({
                filter: filterValue,
                transitionDuration: '0.5s'
            });
        });
    }

    // Counter Animation
    if ($('.counter').length) {
        var counterExecuted = false;

        $(window).on('scroll', function () {
            if (!counterExecuted) {
                $('.counter').each(function () {
                    var $this = $(this);
                    var countTo = $this.attr('data-count');

                    if ($this.isInViewport()) {
                        $({ countNum: $this.text() }).animate({
                            countNum: countTo
                        }, {
                            duration: 2000,
                            easing: 'linear',
                            step: function () {
                                $this.text(Math.floor(this.countNum));
                            },
                            complete: function () {
                                $this.text(this.countNum);
                            }
                        });
                        counterExecuted = true;
                    }
                });
            }
        });
    }

    // Back to Top Button
    var $backToTop = $('.back-to-top');

    $backToTop.click(function (e) {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: 'smooth' });
        return false;
    });

    // Portfolio Modal (if needed)
    $('.portfolio-item').on('click', function () {
        var title = $(this).data('title');
        var category = $(this).data('category');
        var image = $(this).find('.portfolio-image').attr('src');
        var description = $(this).data('description');

        if ($('#portfolioModal').length) {
            $('#portfolioModal').find('.modal-title').text(title);
            $('#portfolioModal').find('.modal-category').text(category);
            $('#portfolioModal').find('.modal-image').attr('src', image);
            $('#portfolioModal').find('.modal-description').text(description);
            $('#portfolioModal').modal('show');
        }
    });

    // Tooltip Initialization
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Popover Initialization
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // In viewport check helper
    $.fn.isInViewport = function () {
        var elementTop = $(this).offset().top;
        var elementBottom = elementTop + $(this).outerHeight();
        var viewportTop = $(window).scrollTop();
        var viewportBottom = viewportTop + $(window).height();
        return elementBottom > viewportTop && elementTop < viewportBottom;
    };

})(jQuery);
