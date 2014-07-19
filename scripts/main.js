/************************************

ChurchThemes for WordPress
Author: Frankie Jarrett
URI: http://churchthemes.net
Version: 1.3.2

************************************/

var ChurchThemes = {

    /**
     * Init
     */
    init: function($) {
        var $ = jQuery.noConflict();
        $("body").removeClass("no-js");
        ChurchThemes.externalLinks($);
        ChurchThemes.dropdownMenu($);
        ChurchThemes.firstLast($);
        ChurchThemes.sermonFilters($);
        ChurchThemes.flexSlider($);
        ChurchThemes.placeholderSupport($);
    },

    /**
     * External links target
     */
    externalLinks: function($) {
        $("a").filter(function() {
            return this.hostname && this.hostname !== location.hostname;
        }).attr("target", churchthemes_global_vars.external_target);
    },

    /**
     * Dropdown menu
     */
    dropdownMenu: function($) {
        $(".navbar ul li:first-child").addClass("first");
        $(".navbar ul li:last-child").addClass("last");
        $(".navbar ul li ul li:has(ul)").find("a:first").append(" &raquo;");
        $("ul.navbar li").hover(function(){
            $(this).addClass("hover");
            $("ul:first",this).css("visibility", "visible");
        }, function(){
            $(this).removeClass("hover");
            $("ul:first",this).css("visibility", "hidden");
        });
        $("ul.navbar li ul li:has(ul)").find("a:first").append(" &raquo;");
    },

    /*
     * First and last classes
     */
    firstLast: function($) {
        $("div.widget:first").addClass("first");
        $("div.widget:last").addClass("last");
        $("div.widget ul li:first").addClass("first");
        $("div.widget ul li:last").addClass("last");
    },

    /**
     * Sermon search filters
     */
    sermonFilters: function($) {
        if($("#sermon-filter").length > 0) {
            $("#sermon_speaker").selectbox();
            $("#sermon_service").selectbox();
            $("#sermon_series").selectbox();
            $("#sermon_topic").selectbox();
        }
    },

    /**
     * The home page slider
     */
    flexSlider: function($) {
        if($("#slider .mask").length > 0) {
            $("#slider .mask").flexslider({
                selector: "ul > li",
                controlsContainer: ".pag_frame",
                directionNav: false,
                video: true,
                animation: churchthemes_slide_vars.animation,
                direction: churchthemes_slide_vars.direction,
                slideshowSpeed: churchthemes_slide_vars.speed,
            });
        }
    },

    /**
     * Enables HTML5 placeholder support for legacy browsers
     */
    placeholderSupport: function($) {
        if(churchthemes_global_vars.is_IE == "true") {
            if($("input[placeholder]").length > 0) {
                $("input[placeholder]").placeholder();
            }
            if($("textarea[placeholder]").length > 0) {
                $("textarea[placeholder]").placeholder();
            }
        }
    }

}

jQuery(document).ready(function() {
    ChurchThemes.init();
});
