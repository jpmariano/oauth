<?php

/**
 * @file
 * Contains \Drupal\oauth_server_sso\Form\MiniorangeConfigOAuthClient.
 */

namespace Drupal\oauth_server_sso\Form;
use Drupal\Core\Form\FormBase;
use Drupal\oauth_server_sso\handler;
use Drupal\oauth_server_sso\Utilities;
use Drupal\oauth_server_sso\mo_oauth_server_visualTour;
use Drupal\Core\Render\Markup;


class MiniorangeConfigOAuthClient extends FormBase {

    public function getFormId() {
        return 'oauth_server_sso_config_client';
    }

    public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state)
    {

        $moTour = mo_oauth_server_visualTour::genArray();
        $form['tourArray'] = array(
            '#type' => 'hidden',
            '#value' => $moTour,
        );

        $form['markup_library'] = array(
            '#attached' => array(
                'library' => array(
                    "oauth_server_sso/oauth_server_sso.style_settings",
                    "oauth_server_sso/miniorange_oauth_server.Vtour",
                    "oauth_server_sso/mo-card",
                    'oauth_server_sso/miniorange_oauth_copy.icon',
                )
            ),
        );


        \Drupal::configFactory()->getEditable('oauth_server_sso.settings')->set('oauth_server_sso_disabled', FALSE)->save();


        global $base_url;
        $stat = '';
        $module_path = drupal_get_path('module', 'oauth_server_sso');
        $finalpath = $base_url;
        $stat = \Drupal::config('oauth_server_sso.settings')->get('oauth_server_sso_add_client_status');

        if($stat == '')
        {
            $form['mo_oauth_server_style'] = array('#markup' => '<div class="mo_oauth_table_layout_1"><div class="mo_oauth_table_layout mo_oauth_container">');

            $form['markup_top_vt_start'] = array(
                '#markup' => '<h3>ADD OAUTH CLIENT&nbsp;&nbsp;<a id="Restart_moTour" class="btn btn-primary-color btn-large">Take a Tour</a></h3><hr><br>',
            );


            $form['oauth_server_sso_client_name'] = array(
                '#type' => 'textfield',
                '#prefix' => "<div  style='background-color: white; padding: 10px ;margin-left: 20px; width: 70%'><h4>Client Name: </h4>",
                '#suffix' => "</div>",
                '#disabled' => \Drupal::config('oauth_server_sso.settings')->get('oauth_server_sso_disabled'),
                '#id' => 'firstname',
                '#default_value' => \Drupal::config('oauth_server_sso.settings')->get('oauth_server_sso_client_name'),
            );

            $form['oauth_server_sso_redirect_url'] = array(
                '#type' => 'textfield',
                '#prefix' => "<div  style='background-color: white; padding: 10px ;margin-left: 20px; width: 70%'><h4>Authorized Redirect URL: </h4>",
                '#suffix' => "</div><br><br>",
                '#disabled' => \Drupal::config('oauth_server_sso.settings')->get('oauth_server_sso_disabled'),
                '#id' => 'redirecturl',
                '#default_value' => \Drupal::config('oauth_server_sso.settings')->get('oauth_server_sso_redirect_url'),
            );

            $form['next_step_1'] = array(
                '#type' => 'submit',
                '#prefix' => "<div  style='background-color: white; padding: 10px ;margin-left: 20px; width: 70%'>",
                '#suffix' => "</div>",
                '#id' => 'button_config_center',
                '#value' => t('NEXT'),
                '#disabled' => \Drupal::config('oauth_server_sso.settings')->get('oauth_server_sso_disabled'),
                '#submit' => array('::oauth_server_sso_next_1'),
            );

            Utilities::AddsupportTab( $form, $form_state);

        }
        else {

            $form['oauth_server_sso_client_name'] = array(
                '#type' => 'textfield',
                '#title' => t('Client Name: '),
                '#id' => 'clientname',
                '#disabled' => true,
                '#required' => 'true',
                '#default_value' => \Drupal::config('oauth_server_sso.settings')->get('oauth_server_sso_client_name'),
                '#prefix' => '<div class="mo_oauth_table_layout_1"><div class="mo_oauth_table_layout mo_oauth_container">
                                <h3>ADD OAUTH CLIENT&nbsp;&nbsp;<a id="Restart_moTour" class="btn btn-primary-color btn-large">Take a Tour</a></h3><hr><br>',
            );

            $form['oauth_server_sso_redirect_url'] = array(
                '#type' => 'textfield',
                '#title' => t('Authorized Redirect URL:'),
                '#id' => 'autoRedirectURL',
                '#required' => 'true',
                '#default_value' => \Drupal::config('oauth_server_sso.settings')->get('oauth_server_sso_redirect_url'),
            );

            $form['oauth_server_sso_client_id'] = array(
                '#type' => 'textfield',
                '#title' => t('Client ID:'),
                '#disabled' => true,
                '#default_value' => \Drupal::config('oauth_server_sso.settings')->get('oauth_server_sso_client_id'),
                '#prefix' => '<div id="clientID">',
            );

            $form['oauth_server_sso_client_secret'] = array(
                '#type' => 'textfield',
                '#title' => t('Client Secret:'),
                '#disabled' => true,
                '#default_value' => \Drupal::config('oauth_server_sso.settings')->get('oauth_server_sso_client_secret'),
                '#prefix' => '</div><div id="clientSecret">',
            );

            $form['oauth_server_sso_delete_client'] = array(
                '#type' => 'submit',
                '#id' => 'button_config_vt',
                '#value' => t('Delete Client'),
                '#submit' => array('::oauth_server_sso_delete_client'),
                '#attributes' => array('style' => 'border-radius: 4px;background: #337ab7;color: #ffffff;text-shadow: 0 -1px 1px #337ab7, 1px 0 1px #337ab7, 0 1px 1px #337ab7, -1px 0 1px #337ab7;box-shadow: 0 1px 0 #337ab7;border-color: #337ab7 #337ab7 #337ab7;'),
                '#prefix' => '</div><br>',
            );

            $form['next_step_1'] = array(
                '#type' => 'submit',
                '#id' => 'button_config_update_vt',
                '#value' => t('Update'),
                '#submit' => array('::oauth_server_sso_next_2'),
                '#attributes' => array('style' => 'border-radius: 4px;background: #337ab7;color: #ffffff;text-shadow: 0 -1px 1px #337ab7, 1px 0 1px #337ab7, 0 1px 1px #337ab7, -1px 0 1px #337ab7;box-shadow: 0 1px 0 #337ab7;border-color: #337ab7 #337ab7 #337ab7;'),
                '#suffix' => '<br><br><br><h3>ENDPOINT URLs</h3><hr><br>
                                <p>You can configure below endpoints in your OAuth client.<p><div id="endpointsUrl">',
            );

            $copy_image = '<img class ="fa fa-fw fa-pull-right fa-lg fa-copy mo_copy" src="'. $base_url.'/'.$module_path . '/includes/images/copy-regular.svg">';

            $auth_endpoint = [
                'data' => Markup::create('<span id="authorize">' . $finalpath.'/authorize' . '</span>'. $copy_image )
            ];

            $access_token_endpoint = [
                'data' => Markup::create('<span id="access_token">' . $finalpath.'/access_token' . '</span>'. $copy_image)
            ];

            $get_user_info_endpoint = [
                'data' => Markup::create('<span id="user_info">' . $finalpath.'/user_info'. '</span>'. $copy_image)
            ];

            $scope = [
                'data' => Markup::create('<span id="profile">profile</span> , <span id="openid">openid (OpenID is supported only in the Premium version of the module)</span>')
            ];

            $mo_table_content = array (
                array('Authorize Endpoint',$auth_endpoint),
                array('Access Token Endpoint', $access_token_endpoint),
                array('Get User Info Endpoint', $get_user_info_endpoint),
                array('Scope', $scope),
            );

            $form['mo_oauth_server_attrs_list_idp'] = array(
                '#type' => 'table',
                '#header'=> array( 'ATTRIBUTE', 'VALUE' ),
                '#rows' => $mo_table_content,
                '#empty' => t('Something is not right. Please run the update script or contact us at <a href="'.'drupalsupport@xecurify.com'.'">'.'drupalsupport@xecurify.com'.'</a>'),
                '#responsive' => TRUE ,
                '#sticky'=> TRUE,
                '#size'=> 2,
            );

            $form['mo_vt_oauth_server_div_end'] = array('#markup' => '</div>');

            Utilities::AddsupportTab( $form, $form_state);

            $form['mo_add_oauth_server_div_end'] = array('#markup' => '</div>');

        }

        return $form;
    }

    function oauth_server_sso_next_1(array &$form, \Drupal\Core\Form\FormStateInterface $form_state)
    {
        $client_name = $form['oauth_server_sso_client_name']['#value'];
        $redirect_url = $form['oauth_server_sso_redirect_url']['#value'];

        if(empty($client_name)||empty($redirect_url)){
            \Drupal::messenger()->addError($this->t('The <b><u>Client Name</u></b> and <b><u>Authorized Redirect URL</u></b> fields are mandatory.'));
            return;
        }

        \Drupal::configFactory()->getEditable('oauth_server_sso.settings')->set('oauth_server_sso_client_name',$client_name)->save();
        \Drupal::configFactory()->getEditable('oauth_server_sso.settings')->set('oauth_server_sso_redirect_url',$redirect_url)->save();

        $client_id = handler::generateRandom(30);
        $client_secret = handler::generateRandom(30);
        \Drupal::configFactory()->getEditable('oauth_server_sso.settings')->set('oauth_server_sso_client_id',$client_id)->save();
        \Drupal::configFactory()->getEditable('oauth_server_sso.settings')->set('oauth_server_sso_client_secret',$client_secret)->save();
        \Drupal::configFactory()->getEditable('oauth_server_sso.settings')->set('oauth_server_sso_add_client_status','review')->save();

        \Drupal::messenger()->addMessage($this->t('Configurations saved successfully.'));
    }

    function oauth_server_sso_next_2(array &$form, \Drupal\Core\Form\FormStateInterface $form_state)
    {
        if($form['oauth_server_sso_client_name']['#value'] != '')
        {
             $client_name = $form['oauth_server_sso_client_name']['#value'];
            \Drupal::configFactory()->getEditable('oauth_server_sso.settings')->set('oauth_server_sso_client_name',$client_name)->save();
        }

        if($form['oauth_server_sso_redirect_url']['#value'] != '')
        {
           $redirect_url = $form['oauth_server_sso_redirect_url']['#value'];
           \Drupal::configFactory()->getEditable('oauth_server_sso.settings')->set('oauth_server_sso_redirect_url',$redirect_url)->save();
        }

        if($form['oauth_server_sso_client_id']['#value'] != '')
        {
              $client_id = $form['oauth_server_sso_client_id']['#value'];
              \Drupal::configFactory()->getEditable('oauth_server_sso.settings')->set('oauth_server_sso_client_id',$client_id)->save();
        }

        if($form['oauth_server_sso_client_secret']['#value'] != '')
        {
             $client_secret = $form['oauth_server_sso_client_secret']['#value'];
            \Drupal::configFactory()->getEditable('oauth_server_sso.settings')->set('oauth_server_sso_client_secret',$client_secret)->save();
        }
        \Drupal::configFactory()->getEditable('oauth_server_sso.settings')->set('oauth_server_sso_add_client_status','review')->save();
        \Drupal::messenger()->addMessage($this->t('Configurations saved successfully.'));
    }

    function oauth_server_sso_delete_client(array &$form, \Drupal\Core\Form\FormStateInterface $form_state)
    {
        \Drupal::configFactory()->getEditable('oauth_server_sso.settings')->clear('oauth_server_sso_client_name')->save();
        \Drupal::configFactory()->getEditable('oauth_server_sso.settings')->clear('oauth_server_sso_client_id')->save();
        \Drupal::configFactory()->getEditable('oauth_server_sso.settings')->clear('oauth_server_sso_client_secret')->save();
        \Drupal::configFactory()->getEditable('oauth_server_sso.settings')->clear('oauth_server_sso_redirect_url')->save();
        \Drupal::configFactory()->getEditable('oauth_server_sso.settings')->clear('oauth_server_sso_add_client_status')->save();
    }

    function submitForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state){

    }

    /**
     * Send support query.
     */
    function send_support_query(&$form, $form_state)
    {
        Utilities::send_query($form, $form_state);
    }
}