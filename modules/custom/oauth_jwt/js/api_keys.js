/**
 * @file
 * Global utilities.
 *
 */
 (function ($, Drupal) {
  'use strict';

  Drupal.behaviors.api_keys = {
    attach: function(context, settings) {

      const generateKey = function (type) {
        console.log('-------------- generate -----------');
        let data = { type: type };
        console.log(type);
          if(type === 'key'){
            $.ajax({
              type: "get",
              url: '/keygen',
              data: data,
              success: insertKey,
              error: function (xhr, ajaxOptions, thrownError) {
                  console.log(xhr);
                  console.log(thrownError);
              }
          });
        } else {
          $.ajax({
            type: "get",
            data: data,
            url: '/keygen',
            success: insertSecret,
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr);
                console.log(thrownError);
            }
          });
        }
      };

      const insertKey = function (data) {
        console.log(data);
        $("#clientkey").val(data);
      };
      const insertSecret = function (data) {
        console.log(data);
        $("#clientsecret").val(data);
      };


      $('.client-key-generator', context).click(function () {
        generateKey('key');
      });

      $('.client-secret-generator', context).click(function () {
        generateKey('secret');
      });

      $('#clientapp-form', context).once('usersubmit').submit(function (event){
        event.preventDefault();
        let uid = $('#clientapp-form input[name=uid]').val();
        let clientkey = $('#clientapp-form input[name=clientkey]').val();
        let clientsecret = $('#clientapp-form input[name=clientsecret]').val();
        let clientapp = $("select#clientapp option").filter(":selected").val();
        let data = {
          uid: uid,
          clientkey: clientkey,
          clientsecret, clientsecret,
          clientapp: clientapp
       }
       $.ajax({
          type: "POST",
          url: '/api-keys',
          data: data,
          success: apikeyAdded,
          error: function (xhr, ajaxOptions, thrownError) {
              console.log(xhr);
              console.log(thrownError);
          }
        });
      });

      function apikeyAdded(data){
        console.log(data);
      }

    }
  };

})(jQuery, Drupal);
