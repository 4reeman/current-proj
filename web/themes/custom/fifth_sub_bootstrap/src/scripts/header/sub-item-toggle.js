(function ($, Drupal, drupalSettings) {
  'use strict';
  Drupal.behaviors.subMenuToggler = {
    attach: function (context, settings) {
      let windowWidth = window.innerWidth;
      let dropdownElement = $('[data-bs-toggle="dropdown"]', context);
      let view = $('.block-views-blockmain-header-block-1', context);
      view.css('transition', 'unset');
      function fullscreenViewport () {
        return windowWidth > 991;
      }
      //Todo animate hovering on full screen & click on after element for mobile extension (hide/display)
      if (fullscreenViewport()) {
       dropdownElement.on('show.bs.dropdown', function (e) {
         e.preventDefault();
         window.location = this.href;
         this.parentNode
       });
      }
    }
  };
})(jQuery, Drupal, drupalSettings);
