/*jshint jquery:true */

$(document).ready(function ($) {
    "use strict";

    /* global google: false */
    /*jshint -W018 */

    /*-------------------------------------------------*/
    /* =  portfolio isotope
    /*-------------------------------------------------*/

    var winDow = $(window);
    // Needed variables
    var $container = $('.iso-call');
    var $filter = $('[data-filter]');

    try {
        $container.imagesLoaded(function () {
            $container.trigger('resize');
            $container.isotope({
                filter: '*',
                layoutMode: 'masonry',
                animationOptions: {
                    duration: 750,
                    easing: 'linear'
                }
            });

            setTimeout(Resize, 1500);
        });
    } catch (err) {
    }

    winDow.on('resize', function () {
        var selector = $filter.find('a.active').attr('data-filter');

        try {
            $container.isotope({
                filter: selector,
                animationOptions: {
                    duration: 750,
                    easing: 'linear',
                    queue: false,
                }
            });
        } catch (err) {
        }
        return false;
    });

    // Isotope Filter
    $filter.find('a').on('click', function () {
        var selector = $(this).attr('data-filter');

        try {
            $container.isotope({
                filter: selector,
                animationOptions: {
                    duration: 750,
                    easing: 'linear',
                    queue: false,
                }
            });
        } catch (err) {

        }
        return false;
    });


    var filterItemA = $('.filter li a');

    filterItemA.on('click', function () {
        var $this = $(this);
        if (!$this.hasClass('active')) {
            filterItemA.removeClass('active');
            $this.addClass('active');
        }
    });

    /* ---------------------------------------------------------------------- */
    /*	background dark-ligh toggle
    /* ---------------------------------------------------------------------- */

    function getTheme() {
        var _theme = localStorage.getItem("__fossilDarkTheme");
        if (_theme === null) return false;

        var _themeState = JSON.parse(_theme);

        if (!_themeState.hasOwnProperty("darkMode")) return false;

        return _themeState.darkMode;
    }

    function setTheme(state) {
        localStorage.setItem("__fossilDarkTheme", JSON.stringify({darkMode: state}))
    }

    function checkTheme() {
        if (getTheme()) {
            DarkReader.enable({
                brightness: 100,
                contrast: 90,
                sepia: 10
            });
        } else
            DarkReader.disable();
    }

    checkTheme();

    var togButton = $('a.toggle-dark');

    togButton.on('click', function (event) {
        event.preventDefault();
		setTheme(!getTheme());
		checkTheme();
    });

    /* ---------------------------------------------------------------------- */
    /*	shopping popup toogle
    /* ---------------------------------------------------------------------- */

    var shopButton = $('a.toggle-shopping');

    shopButton.on('click', function (event) {
        event.preventDefault();
        $('.shopping-popup').toggleClass('active');
    });

    /*-------------------------------------------------*/
    /* =  fixed right sidebar animate
    /*-------------------------------------------------*/

    var openRightSidebar = $('a.toggle-sidebar'),
        closeRightSidebar = $('a.close-fixed-sidebar'),
        rightSidebar = $('#fixed-sidebar');

    openRightSidebar.on('click', function (event) {
        event.preventDefault();
        if (!rightSidebar.hasClass('active')) {
            rightSidebar.addClass('active');
        } else {
            rightSidebar.removeClass('active');
        }
    });

    closeRightSidebar.on('click', function (event) {
        event.preventDefault();
        rightSidebar.removeClass('active');
    });

    /* ---------------------------------------------------------------------- */
    /*	magnific-popup
    /* ---------------------------------------------------------------------- */

    // Example with multiple objects
    $('.zoom').magnificPopup({
        type: 'image',
        gallery: {
            enabled: true
        }
    });

    /*-------------------------------------------------*/
    /* =  count increment
    /*-------------------------------------------------*/

    $('.statistic-post').appear(function () {
        $('.timer').countTo({
            speed: 4000,
            refreshInterval: 60,
            formatter: function (value, options) {
                return value.toFixed(options.decimals);
            }
        });
    });

    /*-------------------------------------------------*/
    /* =  OWL carousell
    /*-------------------------------------------------*/

    $('.owl-carousel').owlCarousel({
        items: 5,
        loop: true,
        margin: 10,
        merge: true,
        responsive: {
            0: {
                items: 1
            },
            991: {
                items: 4
            },
            1199: {
                items: 5
            }
        },
        nav: true
    });

    /* ---------------------------------------------------------------------- */
    /*	Contact Map
    /* ---------------------------------------------------------------------- */
    try {
        var fenway = [37.7940035, -122.2463581]; //Change a map coordinate here!
        var markerPosition = [37.7940035, -122.2463581]; //Change a map marker here!
        $('#map').gmap3({
            center: fenway,
            zoom: 12,
            scrollwheel: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        })
            .marker({
                position: markerPosition,
                icon: 'images/marker.png'
            });
    } catch (error) {

    }

    /* ---------------------------------------------------------------------- */
    /*	Shop galery image replacement
    /* ---------------------------------------------------------------------- */

    var elemToShow = $('.other-products a');

    elemToShow.on('click', function (e) {
        e.preventDefault();
        var newImg = $(this).attr('data-image');
        var prodHolder = $('.image-holder img');
        prodHolder.attr('src', newImg);
    });

    /*-------------------------------------------------*/
    /* =  shopping cart subtotals, product increase,
    /* =  decrease, delete item, dropdown remove item from
    /* =  shopping cart
    /*-------------------------------------------------*/

    var totalPrice = $('.total-price');
    var ShippingPrice = $('.shipping-price').text();
    var PriceAfterShipping = $('.total-price-withshipping');

    PriceSum();

    function PriceSum() {

        var sum = 0;

        $('.tot-price').each(function () {
            sum += parseFloat($(this).text());
        });

        totalPrice.text(parseFloat(sum, 10).toFixed(2));

        if ($.isNumeric(ShippingPrice)) {
            PriceAfterShipping.text(parseFloat(parseFloat(sum, 10) + parseFloat(ShippingPrice, 10), 10).toFixed(2));
        } else {
            PriceAfterShipping.text(parseFloat(sum, 10).toFixed(2));
        }
    }


    var btnIncrease = $('button.increase'),
        btnDecrease = $('button.decrease'),
        DeleteButton = $('a.remove-item');

    btnIncrease.on('click', function () {
        var fieldNum = $(this).parent('.quantity-add').find('input[type="text"]');
        var fieldVal = fieldNum.val();
        var nextVal = parseFloat(fieldVal) + 1;
        fieldNum.val(nextVal);
        var itemPrice = $(this).parents('tr').find('span.stat-price').text();
        var totPrice = $(this).parents('tr').find('span.tot-price');
        var newPrice = parseFloat(parseFloat(itemPrice, 10) * parseFloat(fieldNum.val(), 10), 10).toFixed(2);
        totPrice.text(newPrice);

        PriceSum();
    });

    btnDecrease.on('click', function () {
        var fieldNum = $(this).parent('.quantity-add').find('input[type="text"]');
        var fieldVal = fieldNum.val();
        var nextVal = parseFloat(fieldVal) - 1;
        if (fieldVal > 0) {
            fieldNum.val(nextVal);
            var itemPrice = $(this).parents('tr').find('span.stat-price').text();
            var totPrice = $(this).parents('tr').find('span.tot-price');
            var newPrice = parseFloat(parseFloat(itemPrice, 10) * parseFloat(fieldNum.val(), 10), 10).toFixed(2);
            totPrice.text(newPrice);
        } else {
            fieldNum.val(0);
        }

        PriceSum();
    });

    DeleteButton.on('click', function (event) {
        event.preventDefault();

        $(this).closest('tr').remove();

        PriceSum();
    });

    var removeItemShop = $('a.delete-art');

    removeItemShop.on('click', function (event) {
        event.preventDefault();
        $(this).closest('li').remove();
    });

    /*-------------------------------------------------*/
    /* =  price range code
    /*-------------------------------------------------*/
    try {
        for (var j = 100; j <= 10000; j++) {
            $('#start-val').append(
                '<option value="' + j + '">' + j + '</option>'
            );
        }
        // Initialise noUiSlider
        $('.noUiSlider').noUiSlider({
            range: [0, 280],
            start: [6, 230],
            handles: 2,
            connect: true,
            step: 1,
            serialization: {
                to: [$('#start-val'),
                    $('#end-val')],
                resolution: 1
            }
        });
    } catch (error) {

    }
    /* ---------------------------------------------------------------------- */
    /*	Load more posts from container
    /* ---------------------------------------------------------------------- */

    var LoadButton = $('a.load-post-container'),
        PortContainer = ('.iso-call'),
        i = 0,
        s = 0;

    LoadButton.on('click', function (event) {
        event.preventDefault();

        var LoadContainer = $(this).attr('data-load'),
            xel = parseInt($(this).attr('data-number'), 10);

        var storage = document.createElement('div');
        $(storage).load(LoadContainer + " .item", function () {

            var elemloadedLength = $(storage).find('.item').length;

            if (((s + 1) <= elemloadedLength)) {

                s = i + xel;

                var t = i - 1;
                var $elems;

                if (i === 0) {
                    /// create new item elements
                    $elems = $(storage).find(".item:lt(" + s + ")").appendTo(PortContainer);
                    // append elements to container
                    $container.isotope('appended', $elems);

                    setTimeout(Resize, 500);

                } else {
                    /// create new item elements
                    $elems = $(storage).find(".item:lt(" + s + "):gt(" + t + ")").appendTo(PortContainer);
                    // append elements to container
                    $container.isotope('appended', $elems);

                    setTimeout(Resize, 500);
                }

                i += xel;
            }

            if ((s >= elemloadedLength)) {
                $('a.load-post-container').text("No more posts");
            }

        });

    });


    /*-------------------------------------------------*/
    /* =  flexslider
    /*-------------------------------------------------*/

    var SliderPost = $('.flexslider');

    SliderPost.flexslider({
        slideshowSpeed: 10000,
        easing: "swing"
    });

    /*-------------------------------------------------*/
    /* = slider Testimonial
    /*-------------------------------------------------*/

    var slidertestimonial = $('.bxslider');

    slidertestimonial.bxSlider();


    /* ---------------------------------------------------------------------- */
    /*	menu responsive
    /* ---------------------------------------------------------------------- */
    var menuToggle = $('a.menu-toggle'),
        headerMobile = $('header');

    menuToggle.on('click', function (e) {
        e.preventDefault();

        if (headerMobile.hasClass('active')) {
            headerMobile.removeClass('active');
        } else {
            headerMobile.addClass('active');
        }
    });

    /* ---------------------------------------------------------------------- */
    /*	Contact Form
    /* ---------------------------------------------------------------------- */

    var submitContact = $('#submit_contact'),
        message = $('#msg');

    submitContact.on('click', function (e) {
        e.preventDefault();

        var $this = $(this);

        $.ajax({
            type: "POST",
            url: 'contact.php',
            dataType: 'json',
            cache: false,
            data: $('#contact-form').serialize(),
            success: function (data) {

                if (data.info !== 'error') {
                    $this.parents('form').find('input[type=text],textarea').filter(':visible').val('');
                    message.hide().removeClass('alert-success').removeClass('alert-danger').addClass('alert-success').html(data.msg).fadeIn('slow').delay(5000).fadeOut('slow');
                } else {
                    message.hide().removeClass('alert-success').removeClass('alert-danger').addClass('alert-danger').html(data.msg).fadeIn('slow').delay(5000).fadeOut('slow');
                }
            }
        });
    });

    /* ---------------------------------------------------------------------- */
    /*	works carousel
    /* ---------------------------------------------------------------------- */

    $(window).on('load', function () {
        var winDowHeight = $(window).outerHeight();
        $('.photo-box .photo-post').height(winDowHeight - 100);
    });
    $(window).on('resize', function () {
        var winDowHeight = $(window).outerHeight();
        $('.photo-box .photo-post').height(winDowHeight - 100);
    });

});

function Resize() {
    $(window).trigger('resize');
}
