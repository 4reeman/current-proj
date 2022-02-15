(function ($) {
  Drupal.behaviors.myModuleBehavior = {
    attach: function (context, settings) {
      $(context).find('.navbar-toggler').once('myCustomBehavior').click(function () {
        var myCollapsible = document.getElementById('CollapsingNavbar');
        myCollapsible.addEventListener('show.bs.collapse', e => {

        });
      });
    }
  };
})(jQuery);
