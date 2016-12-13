$(document).foundation();
Drupal.behaviors.collapsedFieldset = {
    attach: function(context, settings) {
        var fieldsetLegend = $('html.js fieldset.collapsed legend');
        var fieldsetLabel = $(this).find('.fieldset-legend');
        var contenuFieldset = $(this).siblings();
        fieldsetLegend.click(function(event) {
            contenuFieldset.toggle(0);
            fieldsetLabel.toggleClass('secondary');
        });
    }
};