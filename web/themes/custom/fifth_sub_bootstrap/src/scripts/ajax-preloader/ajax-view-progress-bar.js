(function ($, Drupal, drupalSettings) {
  'use strict';
  Drupal.behaviors.ajaxViewProgressBar = {
    attach: function (context, settings) {
      Drupal.theme.ajaxProgressIndicatorFullscreen = function () {

        function ajax_item (quantity) {

          let ajax_items = [];

          for (let i = 1; i < quantity; i++) {
            ajax_items.push(`<div class="ajax-item item-${i}"></div>`)
          }

          return ajax_items.join('');

        }

        return `<div class="ajax">${ajax_item(10)}</div>`;

      };

      Drupal.Ajax.prototype.setProgressIndicatorFullscreen = function () {
        this.progress.element = $(Drupal.theme('ajaxProgressIndicatorFullscreen'));
        $('.view-pager-group').append(this.progress.element);
      };

    }
  };
})(jQuery, Drupal, drupalSettings);
