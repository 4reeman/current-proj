(function ($, Drupal, drupalSettings) {
  'use strict';
  Drupal.behaviors.users_feedback_BasicFeedbackForm = {
    attach: function (context, settings) {
     $(context).find('audio').once('remove').click(function() {
       $(this).audioPlayer;
     });
    }
  };
})(jQuery, Drupal, drupalSettings);

