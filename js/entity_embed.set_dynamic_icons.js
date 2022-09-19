(function ($, Drupal, drupalSettings, once) {
  Drupal.behaviors.entityEmbedSetDynamicIcons = {
    attach: function (context) {
      // Get the available Embed Buttons from Drupal.
      Object.values(drupalSettings.embedButtons || {}).forEach(function (button) {
        // Iterate through the embed buttons and set the corresponding background image.
        let selector = '.ckeditor5-toolbar-button-' + button.id;
        $(once('entityEmbedSetDynamicIcons', selector, context)).css('background-image', 'url(' + button.icon + ')');
      });
    },
  }
})(jQuery, Drupal, drupalSettings, once);
