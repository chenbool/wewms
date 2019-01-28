var options = {
    "storageName": "cameo",
    "menuStateStorage": false
};

(function (a) {
    (jQuery.browser = jQuery.browser || {}).mobile = /(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(a) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0, 4));
})(navigator.userAgent || navigator.vendor || window.opera);
! function ($) {
    "use strict";

    var app = {
        initialize: function () {
            if ($(".app").data("sidebar") !== "locked") {
                app.menuState();
            }
        },
        menuState: function () {
            if (window.localStorage.getItem(options.storageName + "_navigation") !== null && options.menuStateStorage === true) {
                if (window.localStorage.getItem(options.storageName + "_navigation") === "0") {
                    app.openMenuState();
                } else {
                    app.closeMenuState();
                }
            }
        },
        closeMenuState: function () {
            $(".app").addClass("small-sidebar");
            $(".toggle-sidebar  i").removeClass("fa-angle-left").addClass("fa-angle-right");
        },
        openMenuState: function () {
            $(".app").removeClass("small-sidebar");
            $(".toggle-sidebar i").removeClass("fa-angle-right").addClass("fa-angle-left");
        }
    };

    function simulateLoad(el) {
        $(el).block({
            message: '<div class="loader"><i class="fa fa-spinner fa-spin"></i></div>',
            css: {
                border: "none",
                backgroundColor: "none"
            },
            overlayCSS: {
                backgroundColor: "#fff",
                opacity: 0.5
            }
        });
    }

    function initFooterFix() {
        $("footer").each(function () {
            var footerHeight = $(this).outerHeight();
            if ($(this).prev().hasClass("content-wrap")) {
                $(this).prev().css("bottom", footerHeight);
            } else if ($(this).prev().hasClass("slimScrollDiv")) {
                $(this).prev().find(".main-navigation").css("padding-bottom", footerHeight);
            } else if ($(this).prev().hasClass("main-navigation")) {
                $(this).prev().css("bottom", footerHeight);
            } else if ($(this).prev().hasClass("layout")) {
                $(this).prev().css("padding-bottom", footerHeight);
            }
        });
    }

    // $(function () {
        FastClick.attach(document.body);
        $(".accordion > dd").hide();
        if ($.isFunction($.fn.wizard)) {
            $("#datepicker").datepicker();
        }
        if ($.isFunction($.fn.wizard)) {
            $(".wizard").wizard();
        }
        if ($.isFunction($.fn.pillbox)) {
            $(".pillbox").pillbox();
        }
        if ($.isFunction($.fn.spinner)) {
            $(".spinner").spinner();
        }
        if ($.isFunction($.fn.selectpicker)) {
            $("select").selectpicker();
        }
        if ($.isFunction($.fn.chosen)) {
            $(".chosen").chosen();
        }
        $(".no-touch .slimscroll").each(function () {
            var data = $(this).data();
            $(this).slimScroll(data);
        });
        if ($.isFunction($.fn.audioPlayer)) {
            $("audio").audioPlayer();
        }
        if ($.fn.bxSlider) {
            $(".bxslider").bxSlider({
                mode: "horizontal",
                auto: true,
                speed: 1000,
                pause: 3000,
                pager: false,
                controls: false
            });
        }
        $(document).on("click", ".view-options label", function (e) {
            e.preventDefault();
            if ($(this).data("view") === "grid") {
                $(".switcher").addClass("view-grid").removeClass("view-list");
            } else if ($(this).data("view") === "list") {
                $(".switcher").addClass("view-list").removeClass("view-grid");
            }
        });
        $(document).on("click", ".toggle-sidebar ", function (e) {
            e.preventDefault();
            if ($(".app").hasClass("small-sidebar")) {
                app.openMenuState();
                window.localStorage.setItem(options.storageName + "_navigation", "0");
            } else {
                app.closeMenuState();
                window.localStorage.setItem(options.storageName + "_navigation", "1");
            }
        });
        $(document).on("click", ".dropdown-menu .settings", function (e) {
            e.stopPropagation();
        });
        $(document).on("click", ".toggle-active", function (e) {
            e.preventDefault();
            $(this).toggleClass("active");
        });
        $(document).on("click", ".help", function (e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).toggleClass("active");
            if ($(".about-app").hasClass("open")) {
                $(".about-app").removeClass("pulse").addClass("bounceOut");
                window.setTimeout(function () {
                    $(".about-app").removeClass("open");
                }, 1000);
            } else {
                $(".about-app").removeClass("bounceOut").addClass("pulse").addClass("open");
            }
        });
        $(document).on("click", ".panel-collapsible", function (e) {
            e.preventDefault();
            e.stopPropagation();
            var el = $(this).parents(".panel").children(".panel-body");
            if (el.is(':visible')) {
                $(this).removeClass("fa-chevron-down").addClass("fa-chevron-up");
                el.slideUp(200);
            } else if (!el.is(':visible')) {
                $(this).removeClass("fa-chevron-up").addClass("fa-chevron-down");
                el.slideDown(200);
            }
        });
        $(document).on("click", ".panel-remove", function (e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).parents(".panel").addClass("animated fadeOutRight").attr("id", "obsolete");
            setTimeout(function () {
                $("#obsolete").remove();
            }, 600);
        });
        $(document).on("click", ".panel-refresh", function (e) {
            e.preventDefault();
            e.stopPropagation();
            var elm = $(this).parents(".panel");
            simulateLoad(elm);
            window.setTimeout(function () {
                $(elm).unblock();
            }, 3000);
        });
        $(document).on("click touchstart", ".collapsible .main-navigation > ul> li > a", function (e) {
            var subMenu = $(this).next(),
                parent = $(this).closest("li");
            if (!subMenu.is("ul")) {
                return;
            }
            if ($(".app").hasClass("small-menu") && $(window).width() > 767) {
                return;
            }
            $(".sidebar li").removeClass("collapse-open");
            parent.addClass("collapse-open");
            if ((subMenu.is("ul")) && (subMenu.is(":visible")) && (!$(".app").hasClass("small-sidebar"))) {
                parent.removeClass("collapse-open");
                subMenu.slideUp();
            }
            if ((subMenu.is("ul")) && (!subMenu.is(":visible")) && (!$(".app").hasClass("small-sidebar"))) {
                $(".sidebar ul ul:visible").slideUp();
                subMenu.slideDown();
                parent.addClass("collapse-open");
            }
            if (subMenu.is("ul")) {
                return false;
            }
            e.stopPropagation();
            return true;
        });
        $(".main-navigation > ul > li.collapse-open").each(function () {
            $(this).children(".dropdown-menu").hide().show();
        });
        Offline.options = {
            interceptRequests: false
        };
        initFooterFix();
        app.initialize();
    // });
}(window.jQuery);