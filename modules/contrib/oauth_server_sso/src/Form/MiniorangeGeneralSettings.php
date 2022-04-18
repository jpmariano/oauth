<?php

/**
 * @file
 * Contains \Drupal\oauth_server_sso\Form\MiniorangeGeneralSettings.
 */
namespace Drupal\oauth_server_sso\Form;
use Drupal\Core\Form\FormBase;
use Drupal\oauth_server_sso\Utilities;
use Drupal\oauth_server_sso\MiniorangeOAuthServerSupport;

class MiniorangeGeneralSettings extends FormBase
{
      /**
       * {@inheritdoc}
       */
      public function getFormId() {
          return 'miniorange_general_settings';
      }

      public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state)
      {
          global $base_url;

          $form['markup_library'] = array(
              '#attached' => array(
                  'library' => array(
                      "oauth_server_sso/oauth_server_sso.style_settings",
                  )
              ),
          );

          $form['mo_oauth_server_style'] = array('#markup' => '<div class="mo_oauth_table_layout_1"><div class="mo_oauth_table_layout mo_oauth_container">');

          $form['oauth_server_sso_accesstoken_expiry'] = array(
              '#type' => 'textfield',
              '#title' => t('Access Token Expiry Time:'),
              '#disabled' => true,
              '#attributes' => array('placeholder' => 'in seconds'),
              '#prefix' => '<h3>GENERAL SETTINGS<a href="' . $base_url . '/admin/config/people/oauth_server_sso/licensing"> [Premium]</a></h3><hr><br>',
          );

          $form['oauth_server_sso_refreshtoken_expiry'] = array(
              '#type' => 'textfield',
              '#title' => t('Refresh Token Expiry Time:'),
              '#disabled' => true,
              '#attributes' => array('placeholder' => 'in seconds'),
              '#suffix' => '<br>',
          );

          $form['oauth_server_sso_enforce_state_parameter'] = array(
            '#type' => 'checkbox',
            '#disabled' => true,
            '#title' => t('Enforce State Parameter '),
            '#description' => t('When enabled, the authorization request will fail if state parameter is not provided or is incorrect.'),
            '#suffix' => '<br>',
        );

        $form['oauth_server_sso_enable_callback_validation'] = array(
            '#type' => 'checkbox',
            '#disabled' => true,
            '#title' => t('Callback/Redirect URL Validation '),
            '#description' => t('By default, server is configured with default redirect URL.<br> You can use this feature incase you have Dynamic or Conditional Callback/Redirect URIs and which to redirect back to different pages'),
            '#suffix' => '<br>',
        );

          $form['next_step_1'] = array(
              '#type' => 'submit',
              '#id' => 'button_config_center',
              '#disabled' => true,
              '#value' => t('Save Settings'),
          );

          Utilities::AddsupportTab( $form, $form_state);


          return $form;
    }

    public function submitForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {

    }

    /**
     * Send support query.
     */
    function send_support_query(&$form, $form_state)
    {
        Utilities::send_query($form, $form_state);
    }
}
