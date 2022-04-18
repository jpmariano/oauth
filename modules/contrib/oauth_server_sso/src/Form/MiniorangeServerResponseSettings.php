<?php

/**
 * @file
 * Contains \Drupal\oauth_server_sso\Form\MiniorangeServerResponseSettings.
 */
namespace Drupal\oauth_server_sso\Form;
use Drupal\Core\Form\FormBase;
use Drupal\oauth_server_sso\Utilities;
use Drupal\oauth_server_sso\MiniorangeOAuthServerSupport;

class MiniorangeServerResponseSettings extends FormBase
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
              '#markup' => t('In this tab, you can view and configure the data that you wish to send from Drupal to your OAuth or OIDC Client.<br><br><h3>Basic Attribute Mapping </h3><hr><br>You can customize and send below attributes in response to your OAuth Client.<br><br>'),
          );
         
          $form['oauth_server_sso_server_response_markup'] = array(
              '#markup' => t('In the below section, Attribute key is the key name in which Drupal send the data in the response whereas in the Attribute Value includes the machine name of the Drupal attribute.<br><table><th>Attribute Key</th><th>Attribute value</th><tr>'),
            );
        $form['oauth_server_sso_attribute_key_1'] = array(
            '#type' => 'textfield',
            '#disabled' => true,
            '#default_value' => 'mail',
            '#prefix' => '<td>',
            '#suffix' => '</td><td>',
        );
        $form['oauth_server_sso_attribute_value_1'] = array(
          '#type' => 'textfield',
          '#disabled' => true,
          '#default_value' => 'mail',
          '#suffix' => '</td></tr>',
        );

        $form['oauth_server_sso_attribute_key_2'] = array(
            '#type' => 'textfield',
            '#disabled' => true,
            '#default_value' => 'name',
            '#prefix' => '<tr><td>',
            '#suffix' => '</td><td>',
        );
        $form['oauth_server_sso_attribute_value_2'] = array(
            '#type' => 'textfield',
            '#disabled' => true,
            '#default_value' => 'Username/DisplayName',
            '#suffix' => '</td></tr>',
        );


        $form['oauth_server_sso_attribute_key_3'] = array(
            '#type' => 'textfield',
            '#disabled' => true,
            '#default_value' => 'id',
            '#prefix' => '<tr><td>',
            '#suffix' => '</td><td>',
        );
        $form['oauth_server_sso_attribute_value_3'] = array(
            '#type' => 'textfield',
            '#default_value' => 'uid',
            '#disabled' => true,
            '#suffix' => '</td></tr>',
        );

        $form['oauth_server_sso_attribute_key_4'] = array(
            '#type' => 'textfield',
            '#disabled' => true,
            '#default_value' => 'roles',
            '#prefix' => '<tr><td>',
            '#suffix' => '</td><td>',
        );
        $form['oauth_server_sso_attribute_value_4'] = array(
            '#type' => 'textfield',
            '#disabled' => true,
            '#default_value' => 'Drupal Roles',
            '#suffix' => '</td></tr>',
        );

      $form['oauth_server_sso_attribute_merkup_end'] = array(
          '#markup' => '</table>',
      );

      $form['oauth_server_sso_custom_mapping_markup'] = array(
        '#markup' => '<br><br><h3>Custom Attribute Mapping <a href="' . $base_url . '/admin/config/people/oauth_server_sso/licensing"> [Premium]</a></h3><hr>In this section, you can send custom attributes from Drupal to your OAuth/OIDC Client.<br><br>',
    );

    $form['oauth_server_sso_server_custom_response_markup'] = array(
        '#markup' => '<table><th>Attribute Key</th><th>Attribute value</th><tr>',
      );
    $form['oauth_server_sso_custom_attribute_key_1'] = array(
        '#type' => 'textfield',
        '#disabled' => true,
        '#prefix' => '<td>',
        '#attributes' => array('style'=>'width:100%','placeholder' => 'Enter the Attribute Name'),
        '#suffix' => '</td><td>',
    );

    $form['oauth_server_sso_custom_attribute_value_1'] = array(
      '#type' => 'textfield',
      '#disabled' => true,
      '#attributes' => array('style'=>'width:100%','placeholder' => 'Machine Name of Custom Drupal Attribute'),
      '#suffix' => '</td><td>',
    );
    $form['oauth_server_sso_custom_attribute_value_2'] = array(
        '#markup'=> '<div class="btn btn-know-more">+</div></td><td><div class="btn mo-oauth-server-btn-danger">-</div></td>',
        '#suffix' => '</td></tr>',
      );
    $form['oauth_server_sso_custom_attribute_markup_end'] = array(
        '#markup' => '</table>',
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
