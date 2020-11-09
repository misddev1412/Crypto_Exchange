"use strict";
/* -------------------------------------------
::::::: Start Cookie :::::::
------------------------------------------- */
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

/* -------------------------------------------
::::::: End Cookie :::::::
------------------------------------------- */

/* -------------------------------------------
::::::: Start stripTag :::::::
------------------------------------------- */
function stripTag(input) {
    input = input.toString();
    return input.replace(/(<([^>]+)>)/ig, "");
}

/* -------------------------------------------
    ::::::: Start slicknav :::::::
    ------------------------------------------- */
if ($('#lf-main-nav').length > 0) {
    $('#lf-main-nav').find('.active').parents('li').addClass('active');
    $('#lf-main-nav').slicknav({
        prependTo: ".lf-responsive-menu-wrapper",
        'closedSymbol': '&#xf105;',
        'openedSymbol': '&#xf107;',
        label: ""
    });
}

if ($('#lf-side-nav').length > 0) {
    $(window).on("load", function () {
        $("#lf-side-nav").mCustomScrollbar({
            theme: "dark",
            scrollInertia: 300,
            axis: 'y'
        });
    });
}

if ($('.lf-side-nav').length > 0) {
    $('.lf-side-nav li.active').parents('li').addClass('active menu-open');
    // $(window).on("load", function () {
    $(document).on('click', ".lf-side-nav-controller", function () {
        if ($('.lf-side-nav').hasClass('lf-side-nav-open')) {
            $('.lf-side-nav').removeClass('lf-side-nav-open');
            if ($('body').hasClass('lf-fixed-sidenav') && $(window).width() >= 992) {
                $('.wrapper').css('margin-left', '0px');
            }
        } else {
            $('.lf-side-nav').addClass('lf-side-nav-open');
            if ($('body').hasClass('lf-fixed-sidenav') && $(window).width() >= 992) {
                $('.wrapper').css('margin-left', '280px');
            }
        }
    });
    if ($('body').hasClass('lf-fixed-sidenav')) {
        $(window).on('resize', function () {
            if ($('.lf-side-nav').hasClass('lf-side-nav-open')) {
                if ($(window).width() >= 992) {
                    $('.wrapper').css('margin-left', '280px');
                } else {
                    $('.wrapper').css('margin-left', '0px');
                }
            } else {
                $('.wrapper').css('margin-left', '0px');
            }
        })
    }
    // });
    $(document).on('click', '.lf-side-nav li a', function () {
        var a = $(this).parent();
        var b = a.hasClass('menu-open');
        if (b) {
            a.children('ul').slideUp(200, function () {
                a.removeClass('menu-open')
            });
        } else {
            a.children('ul').slideDown(200, function () {
                a.addClass('menu-open')
            });
        }
    })
}

/* -------------------------------------------
::::::: End slicknav :::::::
------------------------------------------- */

$('#notice-0').modal('show');

$('.notice-modal').on('hidden.bs.modal', function (e) {
    $('#notice-' + $(this).attr('data-next-modal')).modal('show');
});

$(document).on('click', '.lf-theme-switcher .btn', function () {
    let currentTheme = getCookie("style");
    const triggeredTheme = $(this).data('theme');

    if (currentTheme.length === 0 || triggeredTheme !== currentTheme) {
        setCookie("style", triggeredTheme, 365);
        window.location.reload();
    }
});
