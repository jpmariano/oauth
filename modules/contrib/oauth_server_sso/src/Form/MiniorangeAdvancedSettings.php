<?php

/**
 * @file
 * Contains \Drupal\oauth_server_sso\Form\MiniorangeAdvancedSettings.
 */
namespace Drupal\oauth_server_sso\Form;
use Drupal\Core\Form\FormBase;
use Drupal\oauth_server_sso\Utilities;
use Drupal\oauth_server_sso\MiniorangeOAuthServerSupport;

class MiniorangeAdvancedSettings extends FormBase
{
      /**
       * {@inheritdoc}
       */
      public function getFormId() {
          return 'miniorange_advanced_settings';
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
              '#markup' => '<h3>ADVANCED SETTINGS <a href="' . $base_url . '/admin/config/people/oauth_server_sso/licensing"> [Premium]</a></h3><hr><br>',
          );

          $form['oauth_server_sso_enable_openid'] = array(
            '#type' => 'checkbox',
            '#disabled' => true,
            '#title' => t('Enable OpenID '),
            '#description' => t('Enable or Disable the support for OpenID Connect Protocol.'),
            '#suffix' => '<br>',
        );

        $form['oauth_server_sso_enable_jwt'] = array(
            '#type' => 'checkbox',
            '#disabled' => true,
            '#title' => t('Enable  Support for JWT '),
            '#description' => t('Enable only if JWT is supported by your OpenID(OIDC) client'),
            '#suffix' => '<br>',
        );

        $form['oauth_server_sso_grant_type_markup'] = array(
            '#markup' => '<br>Enable Support for the following Grant types:<hr>',
        );

        $form['oauth_server_sso_enable_authorization'] = array(
            '#type' => 'checkbox',
            '#disabled' => true,
            '#default_value' => 1,
            '#title' => t('Authorization Grant '),
        );
        $form['oauth_server_sso_enable_implicit'] = array(
            '#type' => 'checkbox',
            '#disabled' => true,
            '#title' => t('Implicit Grant '),
        );
        $form['oauth_server_sso_enable_password'] = array(
            '#type' => 'checkbox',
            '#disabled' => true,
            '#title' => t('Password Grant '),
        );
        $form['oauth_server_sso_enable_client'] = array(
            '#type' => 'checkbox',
            '#disabled' => true,
            '#title' => t('Client Credentials Grant '),
        );
        $form['oauth_server_sso_enable_refresh'] = array(
            '#type' => 'checkbox',
            '#disabled' => true,
            '#title' => t('Refresh Token Grant '),
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
