(function($, Drupal) {
    // Ne pas toucher, c'est pour faire fonctionner par défaut Foundation
    Drupal.behaviors.F6 = {
        attach: function(context, settings) {
            $(document).foundation();
        }
    };
    Drupal.behaviors.slickNav = {
        attach: function(context, settings) {
            // $('#slicknav .menu').slicknav({
            //     prependTo: '#main'
            // });
        }
    };
})(jQuery, Drupal);