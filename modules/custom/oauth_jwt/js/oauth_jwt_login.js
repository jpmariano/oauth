/**
 * @file
 * Global utilities.
 *
 */
 (function ($, Drupal) {
  'use strict';

  Drupal.behaviors.oauth_jwt_login = {
    attach: function(context, settings) {

      function validateInput() {
        let username = $("#oauth-login .username");
        if(!username.val().length){
          username.css("border-color", "red");
          //return false;
        }
        let password = $("#oauth-login .password");
        if(!password.val().length){
          password.css("border-color", "red");
          //return false;
        }
        if((!username.val().length) || (!password.val().length)){
          return false;
        }
        return true;
      }

      $('#oauth-login', context).once('usersubmit').submit(function (event){
          if(!validateInput()){
            event.preventDefault();
          }
      });


    }
  };

})(jQuery, Drupal);
