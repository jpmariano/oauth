<?php
/**
 * @file
 * Contains support form for miniOrange OAuth Server Module.
 */

/**
 * Showing Support form info.
 */
namespace Drupal\oauth_server_sso\Form;

use Drupal\Core\Form\FormBase;
use Drupal\oauth_server_sso\Utilities;
use Drupal\oauth_server_sso\MiniorangeOAuthServerSupport;

class MiniorangeSupport extends FormBase {

    public function getFormId() {
        return 'oauth_server_sso_support';
    }

    public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state) {

        $form['markup_library'] = array(
            '#attached' => array(
                'library' => array(
                    "oauth_server_sso/oauth_server_sso.style_settings",
                )
            ),
        );

        $form['mo_oauth_server_styles'] = array('#markup' => '<div class="mo_oauth_table_layout_1"><div class="mo_oauth_table_layout mo_oauth_container">');

        $form['oauth_server_sso_email_address'] = array(
            '#type' => 'textfield',
            '#title' => t('Email Address'),
            '#attributes' => array('style'=>'width:73%','placeholder' => 'Enter your email'),
            '#required' => TRUE,
            '#prefix' => '<h3>SUPPORT</h3><hr><br>
                          Need any help? Just send us a query so we can help you.<br/><br/>',
        );

        $form['oauth_server_sso_phone_number'] = array(
            '#type' => 'textfield',
            '#title' => t('Phone number'),
            '#attributes' => array('style'=>'width:73%','placeholder' => 'Enter your phone number'),
        );

        $form['oauth_server_sso_support_query'] = array(
            '#type' => 'textarea',
            '#title' => t('Query'),
            '#cols' => '10',
            '#rows' => '5',
            '#attributes' => array('style'=>'width:73%','placeholder' => 'Write your query here'),
            '#required' => TRUE,
        );

        $form['oauth_server_sso_support_submit'] = array(
            '#type' => 'submit',
            '#value' => t('Submit Query'),
            '#id' => 'button_config_center',
            '#suffix' => '<div><br/>If you want custom features in the module, just drop an email to <a href="mailto:drupalsupport@xecurify.com">drupalsupport@xecurify.com</a></div>',
        );

        Utilities::advertiseClient( $form, $form_state);

        return $form;

    }

    /**
     * Send support query.
     */
    public function submitForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {

        $email = $form['oauth_server_sso_email_address']['#value'];
        $phone = $form['oauth_server_sso_phone_number']['#value'];
        $query = $form['oauth_server_sso_support_query']['#value'];
        $support = new MiniorangeOAuthServerSupport($email, $phone, $query);
        $support_response = $support->sendSupportQuery();
        if($support_response) {
            \Drupal::messenger()->addMessage($this->t('Support query successfully sent'));
        }
        else {
            \Drupal::messenger()->addError($this->t('Error sending support query'));
        }
    }
}