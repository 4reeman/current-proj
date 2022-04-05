(function ($, Drupal, drupalSettings) {
  'use strict';
  Drupal.behaviors.exposedFormItemsAnimation = {
    attach: function (context, settings) {
      let radiobutton =  $('.view-display-id-block_1 input');

      function stay(obj) {

        obj.css('transform','translateY(-2rem)');
        obj.css('opacity','0');

        setTimeout(()=> {
          obj.css('transition','all 0.1s ease-in-out');
          obj.css('transform','translateY(0)');
          obj.css('opacity','1');
        }, 100);

      }

      radiobutton.once('exposedItemHover').hover(function() {

        $(this).click();
        console.log(111)
      });

      radiobutton.once('exposedItemChange').change(()=>{
        console.log(1222)
        $('.button.js-form-submit').click();

        $(context).ajaxStart(function(){
          $('.view-pager-group').find('.view-content').css('opacity', '0.35');
        });

        $(context).ajaxComplete(function(event, xhr, settings) {
          if(typeof settings.extraData != 'undefined' && typeof settings.extraData.view_display_id != 'undefined') {

            switch(settings.extraData.view_name){

              case "main_header":

                stay($('.view-pager-group').find('.view-content'));

                break;

              default:

                console.log('some other ajax result...');

                break;
            }
          }
        });
      });
    }
  };
})(jQuery, Drupal, drupalSettings);
