<?php
namespace Drupal\oauth_server_sso;
    class handler{

    static function generateRandom($length=30) {
		  $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		  $charactersLength = strlen($characters);
		  $randomString = '';

        for ($i = 0; $i < $length; $i++) {
		    	$randomString .= $characters[rand(0, $charactersLength - 1)];
		    }
		  return $randomString;
    }

    static function oauth_server_sso_validate_code($code, $request_code,$request_time)
    {
      $current_time = time();
      if($current_time - $request_time >=400)
      {
        $error_message='Your authentication code has expired.';
        $solution='Please try again.';
        self::show_error_message($error_message,$solution);

      }

      if($code == $request_code)
        {
            \Drupal::configFactory()->getEditable('oauth_server_sso.settings')->set('oauth_server_sso_code','')->save();
        }
        else{

           $error_message='Incorrect Code';
           $solution='Please try again.';
           self::show_error_message($error_message,$solution);

        }

    }

    static function ValidateAccessToken($accessToken, $request_time)
    {
      $current_time = time();

      if($current_time - $request_time >=900)
      {
        $error_message='Your access token has expired.';
        $solution='Please try again.';
        self::show_error_message($error_message,$solution);

      }

    }

    static function oauth_server_sso_validate_clientSecret($client_secret)
    {
      $secret_stored = \Drupal::config('oauth_server_sso.settings')->get('oauth_server_sso_client_secret');
      if($secret_stored != '')
      {
        if($client_secret != $secret_stored)
        {

        $error_message='Client Secret is mismatched.';
        $solution='head over to OAuth Client tab of the Drupal OAuth Server module. Copy the value of Client Secret from there and paste it in the Client Secret field of OAuth Client.';
        self::show_error_message($error_message,$solution);

        }
      }
      else{

       $error_message='Client Secret is not configured.';
       $solution='head over to OAuth Client tab of the Drupal OAuth Server module. Copy the value of Client Secret from there and paste it in the Client Secret field of OAuth Client.';
       self::show_error_message($error_message,$solution);

      }
    }

    static function oauth_server_sso_validate_grant($grant_type)
    {
        if($grant_type != "authorization_code")
        {

          $error_message='Only Authorization Code grant type supported.';
          $solution='select/write authorization_code Grant type in your OAuth Client.';
          self::show_error_message($error_message,$solution);

        }
    }

    static function oauth_server_sso_validate_clientId($client_id)
    {
      $id_stored = \Drupal::config('oauth_server_sso.settings')->get('oauth_server_sso_client_id');

      if($id_stored != '')
      {
        if($client_id != $id_stored)
        {

          $error_message='Client ID is mismatched.';
          $solution='head over to OAuth Client tab of the Drupal OAuth Server module. Copy the value of Client ID from there and paste it in the Client ID field of OAuth Client.';
          self::show_error_message($error_message,$solution);

        }
      }
      else{

        $error_message='Client ID is not configured.';
        $solution='head over to OAuth Client tab of the Drupal OAuth Server module. Copy the value of Client ID from there and paste it in the Client ID field of OAuth Client.';
        self::show_error_message($error_message,$solution);

      }
    }

    static function oauth_server_sso_validate_redirectUrl($redirect_uri)
    {
      $redirect_url_stored = \Drupal::config('oauth_server_sso.settings')->get('oauth_server_sso_redirect_url');
      if($redirect_url_stored != '')
      {
        if($redirect_uri != $redirect_url_stored)
        {

          $error_message='Redirect URL is mismatched.';
          $solution='head over to OAuth Client tab of the Drupal OAuth Server module. Copy the value of Redirect URL from there and paste it in the Redirect URL field of OAuth Client.';
          self::show_error_message($error_message,$solution);

        }
      }
      else{

        $error_message='Redirect URL is not configured.';
        $solution='head over to OAuth Client tab of the Drupal OAuth Server module. Copy the value of Redirect URL from there and paste it in the Redirect URL field of OAuth Client.';
        self::show_error_message($error_message,$solution);

      }
    }

    static function oauth_server_sso_validate_scope($scope)
    {
      global $base_url;
      if($scope!=''){
        if($scope!= "profile")
        {
          $error_message='Scope is mismatched.';
          $solution='head over to OAuth Client tab of the Drupal OAuth Server module. Copy the value of Scope from there and paste it in the Scope field of OAuth Client.';
          self::show_error_message($error_message,$solution);
        }
      }
      else{

        $error_message='Scope is not configured.';
        $solution='head over to OAuth Client tab of the Drupal OAuth Server module. Copy the value of Scope from there and paste it in the Scope field of OAuth Client.';
        self::show_error_message($error_message,$solution);

      }
    }

   static function show_error_message($error_message,$solution)
   {
    echo '<div style="font-family:Calibri;padding:0 3%;">';
    echo '<div style="color: #a94442;background-color: #f2dede;padding: 15px;margin-bottom: 20px;text-align:center;border:1px solid #E6B3B2;font-size:18pt;"> ERROR</div>
                        <div style="color: #a94442;font-size:14pt; margin-bottom:20px;"><p><strong>Error: </strong>' .$error_message.'.</p>
                            <p>To fix it ,'.$solution.'</p>

                        </div>'
                        ;
    exit;

   }
}

?>