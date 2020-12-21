;(function ($) {
    "use strict";

    $("#metismenu").metisMenu();

    $('.cp-user-sidebar-toggler, .mb-sidebar-toggler').on('click', function () {
        $('.cp-user-sidebar').toggleClass('sidebar-show');
    });

    $(".cp-user-deposit-card-select ul").on("click", ".init", function () {
        $(this).closest("ul").children('li:not(.init)').toggle();
    });

    var allOptions = $(".cp-user-deposit-card-select ul").children('li:not(.init)');
    $(".cp-user-deposit-card-select ul").on("click", "li:not(.init)", function () {
        allOptions.removeClass('selected');
        $(this).addClass('selected');
        $(".cp-user-deposit-card-select ul").children('.init').html($(this).html());
        allOptions.toggle();
    });


    // $(window).resize(function() {

    //     if ($(window).width() <= 769) {

    //         $('.cp-user-top-bar-logo').hide();
    //         $('.cp-user-top-bar').addClass('content-expend');
    //         $('.cp-user-main-wrapper').addClass('content-expend');
    //         $('.cp-user-sidebar').addClass('sidebar-hide');

    //     }
    //     if ($(window).width() <= 426) {
    //         $('.cp-user-top-bar-logo').show();
    //     }

    // });

    $(document).ready(function () {
        $('.scrollbar-inner').scrollbar();
    });



}(jQuery));
