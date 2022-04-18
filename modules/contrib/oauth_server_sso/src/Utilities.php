<?php
/**
 * @package    miniOrange
 * @subpackage Plugins
 * @license    GNU/GPLv3
 * @copyright  Copyright 2015 miniOrange. All Rights Reserved.
 *
 *
 * This file is part of miniOrange OAuth Server module for Drupal.
 *
 * miniOrange Drupal OAuth Server module is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * miniOrange Drupal OAuth Server module is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with miniOrange OAuth Server module.  If not, see <http://www.gnu.org/licenses/>.
 */
namespace Drupal\oauth_server_sso;
use Drupal\oauth_server_sso\MiniorangeOAuthServerSupport;

class Utilities {

	public static function isCurlInstalled() {
	    if (in_array('curl', get_loaded_extensions())) {
	        return 1;
	    }
	    else {
	        return 0;
	    }
	}

    public static function AddsupportTab(array &$form, \Drupal\Core\Form\FormStateInterface $form_state)
    {
        $form['markup_idp_attr_header_top_support'] = array('#markup' => '</div><div class="mo_oauth_server_table_layout_support_1 mo_oauth_container" id="mo_oauth_server_support_query">',);

        $form['markup_support_1'] = array(
            '#markup' => '<h3><b>Feature Request/Contact Us:</b></h3><div>Need any help? We can help you with configuring your OAuth Client. Just send us a query and we will get back to you soon.<br /></div><br>',
        );

        $form['oauth_server_sso_email_address'] = array(
            '#type' => 'textfield',
            '#attributes' => array('style'=>'width:100%','placeholder' => 'Enter your email'),
        );

        $form['oauth_server_sso_phone_number'] = array(
            '#type' => 'textfield',
            '#attributes' => array('style'=>'width:100%','placeholder' => 'Enter your phone number'),
        );

        $form['oauth_server_sso_support_query'] = array(
            '#type' => 'textarea',
            '#cols' => '10',
            '#rows' => '5',
            '#attributes' => array('style'=>'width:100%','placeholder' => 'Write your query here'),
        );

        $form['oauth_server_sso_support_submit'] = array(
            '#type' => 'submit',
            '#value' => t('Submit Query'),
            '#id' => 'button_config_center',
            '#submit' => array('::send_support_query'),
        );

        $form['oauth_server_sso_support_note'] = array(
            '#markup' => '<div><br/>If you want custom features in the module, just drop an email to <a href="mailto:drupalsupport@xecurify.com">drupalsupport@xecurify.com</a></div>'
        );
    }

    public static function advertiseClient(array &$form, \Drupal\Core\Form\FormStateInterface $form_state)
    {
        $form['markup_oauth_server_attr_header_top_support'] = array('#markup' => '</div><div class="mo_oauth_server_table_layout_support_1 mo_oauth_container" id="mo_oauth_server_support_query">',);
        global $base_url;
        $module_path = drupal_get_path('module', 'oauth_server_sso');
        $form['miniorange_oauth_server_setup_guide_link'] = array(
            '#markup' => '<div class="mo_oauth_server_table_layout" id="mo_oauth_guide_vt">',
        );

        $form['miniorange_oauth_server_guide_link1'] = array(
            '#markup' => '<div style="font-size: 15px;"><i>Looking for a Drupal OAuth Client module? Now create your own Drupal site as an OAuth Client.</i></div></br>',
        );
        $form['miniorange_oauth_server_guide_table_list'] = array(
            '#markup' => '<div class="table-responsive mo_guide_text-center" style="font-family: sans-serif;font-size: 15px;">
                <table class="" style="border: none !important;max-width: 100%;border-collapse: collapse;">
                    <thead>
                        <tr><th class="mo_guide_text-center" style="border: none;"><img src="'.$base_url.'/'. $module_path . '/includes/images/miniorange.png" alt="Simply Easy Learning" height = 80px width = 80px ></th><th class="mo_guide_text-center" style = "border: none;"><b>OAuth Single Sign On – SSO (OAuth Client)</b></th></tr>
                    </thead>
                </table>
                <div>
                    <p>OAuth Client module allows Single Sign-On by creating your Drupal site as an OAUth Client. It allows you to login in Drupal with any OAuth Server / Provider of your choice using OAuth Protocol</p>
                    <br>
                </div>
                <table>
                    <tr>
                    <a class="btn btn-get-module btn-large" href="https://www.drupal.org/project/miniorange_oauth_client" target ="_blank">
                        Download module
                    </a>
                    <a class="btn btn-know-more btn-large" href="https://plugins.miniorange.com/drupal-oauth-client" target ="_blank">
                        Know more
                    </a>
                    </tr>
                </table>
            </div>',
        );
    }

    public static function send_query(&$form, $form_state)
    {

        $email = $form['oauth_server_sso_email_address']['#value'];
        $phone = $form['oauth_server_sso_phone_number']['#value'];
        $query = $form['oauth_server_sso_support_query']['#value'];
        if(empty($email)||empty($query)){
            \Drupal::messenger()->addError(t('The <b><u>Email</u></b> and <b><u>Query</u></b> fields are mandatory.'));
            return;
        } elseif(!(\Drupal::service('email.validator')->isValid($email))) {
            \Drupal::messenger()->addError(t('The email address <b><i>' . $email . '</i></b> is not valid.'));
            return;
        }
        $support = new MiniorangeOAuthServerSupport($email, $phone, $query);
        $support_response = $support->sendSupportQuery();
        if($support_response) {
            \Drupal::messenger()->addMessage(t('Support query successfully sent'));
        }
        else {
            \Drupal::messenger()->addError(t('Error sending support query'));
        }
    }

    public static function showErrorMessage( $error, $message, $cause, $closeWindow = FALSE ) {
      global $base_url;
      $actionToTakeUponWindow = $closeWindow === TRUE ? 'onClick="self.close();"' : 'href="' . $base_url . '/user/login"';
      echo '<div style="font-family:Calibri;padding:0 3%;">';
      echo '<div style="color: #a94442;background-color: #f2dede;padding: 15px;margin-bottom: 20px;text-align:center;border:1px solid #E6B3B2;font-size:18pt;"> ERROR</div>
                                    <div style="color: #a94442;font-size:14pt; margin-bottom:20px;"><p><strong>Error: </strong>' .  $error  . '</p>                                      <p>' .  $message  . '</p>
                                        <p><strong>Possible Cause: </strong>' .  $cause  . '</p>
                                    </div>'
                                    ;
      exit;
    }

    public static function drupal_is_cli()
    {
      $server = \Drupal::request()->server;
      $server_software = $server->get('SERVER_SOFTWARE');
      $server_argc = $server->get('argc');

      return !isset($server_software) && (php_sapi_name() == 'cli' || (is_numeric($server_argc) && $server_argc > 0)) ? true : false;
    }

    public static function mo_get_drupal_core_version() {
      return \DRUPAL::VERSION[0];
    }

}
?>
