/**
 * @file
 * Global utilities.
 *
 */
 (function ($, Drupal) {
  'use strict';

  Drupal.behaviors.clientsecret_widget = {
    attach: function(context, settings) {
      function clientKeySecretField(count){
        if (count === null){
            count = 0;
        }
        let output = `
        <div id="clientkeysecret-${count}" class="clientkeysecret" count="${count}">
            <label>App</label>
            <select id="app-${count}" class="app form-control" name="app${count}">
              <option value="1">example.site1.com</option>
              <option value="2">example.site2.com</option>
              <option value="3">example.site3.com</option>
            </select>
            <label>Client Key</label>
            <input id="clientkey-${count}" type="text" class="clientkey form-text" size="60" maxlength="255" name="clientkey${count}" readonly><br>
            <label>Client Secret</label>
            <input id="clientsecret-${count}" type="text" class="clientsecret form-text" size="60" maxlength="255" name="clientsecret${count}" readonly><br>
            <button id="removeapp-${count}" count="${count}" type="button" class="removeapp button">Remove App</button><button id="generate-${count}" count="${count}" type="button" class="generatekeysecret button">Secret and Key Generator</button>
        </div>
        `;
        return output;
      }

      function makeid(length) {
          let result           = '';
          let characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
          let charactersLength = characters.length;
          for ( let i = 0; i < length; i++ ) {
            result += characters.charAt(Math.floor(Math.random() *
      charactersLength));
        }
        return result;
      }
      //alert('test');
      $('.field--name-field-authorize-apps table tr', context).each(function(index, tr) {
        //let clientsecretitem = "#clientkeysercret-" + index;
        $("#clientkeysercret-" + index).append(clientKeySecretField(index));
        //$(tr).find("#clientkeysercret-" + index).append(clientKeySecretField(index));
        /*$(tr).find('td').each (function (indextwo, td) {
          console.log($(td).find('.clientkeysercret').attr('id'));
        });*/
        //console.log($(tr).find('.clientkeysecret').attr('id'));
      });

      $('.field--name-field-authorize-apps', context).on("click", ".generatekeysecret" , function(e) {
        let appcount = $(this).attr('count');
        let clienKey = appcount + makeid(10);
        let clientsecret = makeid(5) + '-' + makeid(5) + '-' + makeid(5) + '-' + makeid(5) + '-' + makeid(5);

        $("#clientkey-" + appcount).val(clienKey);
        $("#clientsecret-" + appcount).val(clientsecret);
      });

      $('.client-key-generator', context).click(function () {

      });

    }//End Attached


  };

})(jQuery, Drupal);
