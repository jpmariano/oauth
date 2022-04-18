<?php

/**
 * @file
 * Contains \Drupal\oauth_server_sso\Form\MiniorangeOAuthServerCustomerSetup.
 */

namespace Drupal\oauth_server_sso\Form;
use Drupal\oauth_server_sso\MiniorangeOAuthServerCustomer;
use Drupal\Core\Form\FormBase;
use Drupal\oauth_server_sso\Utilities;
use Drupal\oauth_server_sso\MiniorangeOAuthServerSupport;

class MiniorangeOAuthServerCustomerSetup extends FormBase {
    public function getFormId() {
        return 'oauth_server_sso_customer_setup';
    }

    public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state)
    {
        global $base_url;
        $current_status = \Drupal::config('oauth_server_sso.settings')->get('oauth_server_sso_status');

        $form['markup_library'] = array(
            '#attached' => array(
                'library' => array(
                    "oauth_server_sso/oauth_server_sso.style_settings",
                )
            ),
        );

        if ($current_status == 'VALIDATE_OTP') {

            $form['mo_oauth_server_style'] = array('#markup' => '<div class="mo_oauth_table_layout_1"><div class="mo_oauth_table_layout mo_oauth_container">');

            $form['oauth_server_sso_customer_otp_token'] = array(
                '#type' => 'textfield',
                '#title' => t('OTP'),
                '#suffix' => '<br><br>',
            );

            $form['oauth_server_sso_customer_validate_otp_button'] = array(
                '#type' => 'submit',
                '#value' => t('Validate OTP'),
                '#submit' => array('::oauth_server_sso_validate_otp_submit'),
            );

            $form['oauth_server_sso_customer_setup_resendotp'] = array(
                '#type' => 'submit',
                '#value' => t('Resend OTP'),
                '#submit' => array('::oauth_server_sso_resend_otp'),
            );

            $form['oauth_server_sso_customer_setup_back'] = array(
                '#type' => 'submit',
                '#value' => t('Back'),
                '#submit' => array('::oauth_server_sso_back'),
            );
            Utilities::AddsupportTab( $form, $form_state);
            $form['markup_top_div_end'] = array(
                '#markup' => '</div>',
            );

            return $form;
        }
        elseif ($current_status == 'PLUGIN_CONFIGURATION')
        {
            $form['header_top_style_1'] = array('#markup' => '<div class="mo_oauth_table_layout_1">');

            $form['markup_top'] = array(
                '#markup' => '<div class="mo_oauth_table_layout mo_oauth_container">',
            );

            $form['markup_top_message'] = array(
                '#markup' => '<div class="mo_oauth_server_welcome_message">Thank you for registering with miniOrange</div><br><br>
                    <h4>Your Profile: </h4><br>'
            );

            $header = array(
                'email' => array(
                    'data' => t('Customer Email')
                ),
                'customerid' => array(
                    'data' => t('Customer ID')
                ),
                'token' => array(
                    'data' => t('Token Key')
                ),
                'apikey' => array(
                    'data' => t('API Key')
                ),
            );

            $options = [];

            $options[0] = array(
                'email' => \Drupal::config('oauth_server_sso.settings')->get('oauth_server_sso_customer_admin_email'),
                'customerid' => \Drupal::config('oauth_server_sso.settings')->get('oauth_server_sso_customer_id'),
                'token' => \Drupal::config('oauth_server_sso.settings')->get('oauth_server_sso_customer_admin_token'),
                'apikey' => \Drupal::config('oauth_server_sso.settings')->get('oauth_server_sso_customer_api_key'),
            );

            $form['fieldset']['customerinfo'] = array(
                '#theme' => 'table',
                '#header' => $header,
                '#rows' => $options,
            );
            $form['mo_oauth_server_remove_Account'] = array(
                '#type' => 'submit',
                '#prefix' => '<br><br>',
                '#value' => 'Remove Account',
                '#disabled' => true,
                '#markup' => '<p><i>(This feature is present in the other versions of the module)</i></p>',
                '#suffix' => '<br><br><br><br><br><br><br>',
            );

            Utilities::AddsupportTab( $form, $form_state);
            $form['oauth_server_sso_support_div_cust'] = array(
                '#markup' => '<br><br><br><br></div></div>'
            );
            return $form;
        }

        $form['mo_oauth_register_style'] = array('#markup' => '<div class="mo_oauth_table_layout_1">');

        $form['mo_oauth_register_style_div'] = array(
            '#markup' => '<div class="mo_oauth_table_layout mo_oauth_container">',
        );

        $form['oauth_server_sso_customer_setup_username'] = array(
            '#type' => 'textfield',
            '#title' => t('Email'),
            '#prefix' => '<h3>Register/Login with miniOrange</h3>
                            Just complete the short registration below to configure the OAuth Server module. Please enter a valid email id <br>that you have
                            access to. An OTP will be sent to this email address for verification purposes.',
        );

        $form['oauth_server_sso_customer_setup_phone'] = array(
            '#type' => 'textfield',
            '#title' => t('Phone'),
        );

        $form['oauth_server_sso_customer_setup_password'] = array(
            '#type' => 'password_confirm',
            '#prefix' => '<b>NOTE:</b> We will only call if you need support.',
        );

        $form['oauth_server_sso_customer_setup_button'] = array(
            '#type' => 'submit',
            '#value' => t('Register'),
            '#id' => 'button_config_center'
        );
        Utilities::AddsupportTab( $form, $form_state);
        $form['div_end'] = array(
            '#markup' => '</div></div>',
        );

        return $form;
    }

    public function submitForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {
        $username = $form['oauth_server_sso_customer_setup_username']['#value'];
        $phone = $form['oauth_server_sso_customer_setup_phone']['#value'];
        $password = $form['oauth_server_sso_customer_setup_password']['#value']['pass1'];
        if(empty($username)||empty($password)){
            \Drupal::messenger()->addError($this->t('The <b><u>Email </u></b> and <b><u>Password</u></b> fields are mandatory.'));
            return;
        }

        if (!(\Drupal::service('email.validator')->isValid($username))) {
            \Drupal::messenger()->addError($this->t('The email address <i>' . $username . '</i> is not valid.'));
            return;
        }

        $customer_config = new MiniorangeOAuthServerCustomer($username, $phone, $password, NULL);
        $check_customer_response = json_decode($customer_config->checkCustomer());

        if ($check_customer_response->status == 'CUSTOMER_NOT_FOUND') {
            \Drupal::configFactory()->getEditable('oauth_server_sso.settings')->set('oauth_server_sso_customer_admin_email', $username)->save();
            \Drupal::configFactory()->getEditable('oauth_server_sso.settings')->set('oauth_server_sso_customer_admin_phone', $phone)->save();
            \Drupal::configFactory()->getEditable('oauth_server_sso_idp.settings')->set('oauth_server_sso_customer_admin_password', $password)->save();
            $send_otp_response = json_decode($customer_config->sendOtp());

            if ($send_otp_response->status == 'SUCCESS') {
                \Drupal::configFactory()->getEditable('oauth_server_sso.settings')->set('oauth_server_sso_tx_id', $send_otp_response->txId)->save();
                $current_status = 'VALIDATE_OTP';
                \Drupal::configFactory()->getEditable('oauth_server_sso.settings')->set('oauth_server_sso_status', $current_status)->save();
                \Drupal::messenger()->addMessage($this->t('Verify email address by entering the passcode sent to @username', [
                    '@username' => $username
                ]));
            }
        }
        elseif ($check_customer_response->status == 'CURL_ERROR') {
            \Drupal::messenger()->addError($this->t('cURL is not enabled. Please enable cURL'));
        }
        else {
            $customer_keys_response = json_decode($customer_config->getCustomerKeys());

            if (json_last_error() == JSON_ERROR_NONE) {
                \Drupal::configFactory()->getEditable('oauth_server_sso.settings')->set('oauth_server_sso_customer_id', $customer_keys_response->id)->save();
                \Drupal::configFactory()->getEditable('oauth_server_sso.settings')->set('oauth_server_sso_customer_admin_token', $customer_keys_response->token)->save();
                \Drupal::configFactory()->getEditable('oauth_server_sso.settings')->set('oauth_server_sso_customer_admin_email', $username)->save();
                \Drupal::configFactory()->getEditable('oauth_server_sso.settings')->set('oauth_server_sso_customer_admin_phone', $phone)->save();
                \Drupal::configFactory()->getEditable('oauth_server_sso.settings')->set('oauth_server_sso_customer_api_key', $customer_keys_response->apiKey)->save();
                $current_status = 'PLUGIN_CONFIGURATION';
                \Drupal::configFactory()->getEditable('oauth_server_sso.settings')->set('oauth_server_sso_status', $current_status)->save();
                \Drupal::messenger()->addMessage($this->t('Successfully retrieved your account.'));
            }
            else {
                \Drupal::messenger()->addError($this->t('Invalid credentials'));
            }
        }
    }

    public function oauth_server_sso_back(&$form, $form_state) {
        $current_status = 'CUSTOMER_SETUP';
        \Drupal::configFactory()->getEditable('oauth_server_sso.settings')->set('oauth_server_sso_status', $current_status)->save();
        \Drupal::configFactory()->getEditable('oauth_server_sso.settings')->clear('miniorange_oauth_server_sso_customer_admin_email')->save();
        \Drupal::configFactory()->getEditable('oauth_server_sso.settings')->clear('oauth_server_sso_customer_admin_phone')->save();
        \Drupal::configFactory()->getEditable('oauth_server_sso.settings')->clear('oauth_server_sso_tx_id')->save();
        \Drupal::messenger()->addStatus($this->t('Register/Login with your miniOrange Account'));
    }

    public function oauth_server_sso_resend_otp(&$form, $form_state) {
        \Drupal::configFactory()->getEditable('oauth_server_sso.settings')->clear('oauth_server_sso_tx_id')->save();
        $username = \Drupal::config('oauth_server_sso.settings')->get('oauth_server_sso_customer_admin_email');
        $phone = \Drupal::config('oauth_server_sso.settings')->get('oauth_server_sso_customer_admin_phone');
        $customer_config = new MiniorangeOAuthServerCustomer($username, $phone, NULL, NULL);
        $send_otp_response = json_decode($customer_config->sendOtp());
        if ($send_otp_response->status == 'SUCCESS') {
            // Store txID.
            \Drupal::configFactory()->getEditable('oauth_server_sso.settings')->set('oauth_server_sso_tx_id', $send_otp_response->txId)->save();
            $current_status = 'VALIDATE_OTP';
            \Drupal::configFactory()->getEditable('oauth_server_sso.settings')->set('oauth_server_sso_status', $current_status)->save();
            \Drupal::messenger()->addMessage($this->t('Verify email address by entering the passcode sent to @username', array('@username' => $username)));
        }
    }

    public function oauth_server_sso_validate_otp_submit(&$form, $form_state) {
        $otp_token = $form['oauth_server_sso_customer_otp_token']['#value'];
        $username = \Drupal::config('oauth_server_sso.settings')->get('oauth_server_sso_customer_admin_email');
        $phone = \Drupal::config('oauth_server_sso.settings')->get('oauth_server_sso_customer_admin_phone');
        $tx_id = \Drupal::config('oauth_server_sso.settings')->get('oauth_server_sso_tx_id');
        $customer_config = new MiniorangeOAuthServerCustomer($username, $phone, NULL, $otp_token);
        $validate_otp_response = json_decode($customer_config->validateOtp($tx_id));

        if ($validate_otp_response->status == 'SUCCESS')
        {

            \Drupal::configFactory()->getEditable('oauth_server_sso.settings')->clear('oauth_server_sso_tx_id')->save();
            $password = \Drupal::config('oauth_server_sso.settings')->get('oauth_server_sso_customer_admin_password');
            $customer_config = new MiniorangeOAuthServerCustomer($username, $phone, $password, NULL);
            $create_customer_response = json_decode($customer_config->createCustomer());

            if ($create_customer_response->status == 'SUCCESS') {
                $current_status = 'PLUGIN_CONFIGURATION';
                \Drupal::configFactory()->getEditable('oauth_server_sso.settings')->set('oauth_server_sso_status', $current_status)->save();
                \Drupal::configFactory()->getEditable('oauth_server_sso.settings')->set('oauth_server_sso_customer_admin_email', $username)->save();
                \Drupal::configFactory()->getEditable('oauth_server_sso.settings')->set('oauth_server_sso_customer_admin_phone', $phone)->save();
                \Drupal::configFactory()->getEditable('oauth_server_sso.settings')->set('oauth_server_sso_customer_admin_token', $create_customer_response->token)->save();
                \Drupal::configFactory()->getEditable('oauth_server_sso.settings')->set('oauth_server_sso_customer_id', $create_customer_response->id)->save();
                \Drupal::configFactory()->getEditable('oauth_server_sso.settings')->set('oauth_server_sso_customer_api_key', $create_customer_response->apiKey)->save();
                \Drupal::messenger()->addMessage($this->t('Customer account created.'));
            }
            else {
                \Drupal::messenger()->addError($this->t('Error creating customer'));
            }
        }
        else {
            \Drupal::messenger()->addError($this->t('Error validating OTP'));
        }
    }

    function saved_support($form, &$form_state)
    {
        $email = $form['oauth_server_sso_email_address_support']['#value'];
        $phone = $form['oauth_server_sso_phone_number_support']['#value'];
        $query = $form['oauth_server_sso_support_query_support']['#value'];
        if(empty($email)||empty($query)){
            \Drupal::messenger()->addError($this->t('The <b><u>Email Address</u></b> and <b><u>Query</u></b> fields are mandatory.'));
            return;
        }

        if (!(\Drupal::service('email.validator')->isValid($email))) {
            \Drupal::messenger()->addError($this->t('The email address <i>' . $email . '</i> is not valid.'));
            return;
        }
        $support = new MiniorangeOAuthServerSupport($email, $phone, $query);
        $support_response = $support->sendSupportQuery();
        if ($support_response) {
            \Drupal::messenger()->addMessage($this->t('Support query successfully sent'));
        } else {
            \Drupal::messenger()->addError($this->t('Error sending support query'));
        }
    }
    /**
     * Send support query.
     */
    function send_support_query(&$form, $form_state)
    {
        Utilities::send_query($form, $form_state);
    }

}
