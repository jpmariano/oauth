<?php
/**
 * @file
 * Contains \Drupal\oauth_jwt\Form\LoginForm.
 */

namespace Drupal\oauth_jwt\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;

/**
 * Implements an example form.
 */
class LoginForm extends FormBase {

  /**
   * {@inheritdoc}.
   */
  public function getFormID() {
    return 'oauth_jwt_login_form';
  }

  /**
   * {@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['username'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Username'),
      '#attributes' => array('class' => array('username'))
    );
    $form['password'] = array(
      '#type' => 'password',
      '#title' => $this->t('Password'),
      '#attributes' => array('class' => array('password'))
    );
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#button_type' => 'primary',
      '#attributes' => array('class' => array('submit'))
    );

    $form['#attributes'] = array('id' => array('oauth-login'));
    $form['#attached']['library'][] = 'oauth_jwt/oauth_jwt_login';



    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $email = $form_state->getValue('username');
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $form_state->setErrorByName('username', $this->t('username is invalid'));
    } else {
      //$users = \Drupal::entityTypeManager()->getStorage('user')->loadByProperties(['mail' => $form_state->getValue('username')]);
      $user = user_load_by_name($form_state->getValue('username'));
      if(!$user){
         $form_state->setErrorByName('username', $this->t('Username or Password is invalid'));
         $form_state->setErrorByName('password');
      } else {
        $password_hasher = \Drupal::service('password');
        $match = $password_hasher->check($form_state->getValue('password'), $user->getPassword());
        if($match){
          \Drupal::logger('oauth_jwt_pass')->notice('match');
        } else {
          \Drupal::logger('oauth_jwt_pass')->notice('not matched');
          $form_state->setErrorByName('username', $this->t('Username or Password is invalid'));
          $form_state->setErrorByName('password');
        }
      }

    }


  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $json = json_encode(array('@username' => $form_state->getValue('username'),'@password' => $form_state->getValue('password') ));
    \Drupal::logger('oauth_jwt')->notice($json);
    $redirect_uri = \Drupal::request()->get('redirect_uri');
    \Drupal::logger('redirect_uri')->notice($redirect_uri);
    $client_id = \Drupal::request()->get('client_id');
    \Drupal::logger('client_id')->notice($client_id);
    $response_type = \Drupal::request()->get('response_type');
    \Drupal::logger('response_type')->notice($response_type);
    $state = \Drupal::request()->get('state');
    \Drupal::logger('state')->notice($state);
    $scope = \Drupal::request()->query->get('scope');
    \Drupal::logger('scope')->notice($scope);
    $response_mode = \Drupal::request()->get('response_mode');
    \Drupal::logger('response_mode')->notice($response_mode);
    /*
    http://localhost:8086/authorize/login?client_id=client_id&response_type=response_type&state=state&scope=scope&response_mode=response_mode&redirect_uri=redirect_uri
    - ?response_type=code (code tells the server that you are using the authorization code flow -  https://docs.spring.io/spring-security/site/docs/current/api/org/springframework/security/oauth2/core/endpoint/OAuth2AuthorizationResponseType.html), https://www.oauth.com/oauth2-servers/server-side-apps/authorization-code/
  - &client_id - asdfasdfasdf
  - &redicrect_uri = https://client.example.com/callback
  - &state=xyz (ungessable) - https://stackoverflow.com/questions/26132066/what-is-the-purpose-of-the-state-parameter-in-oauth-authorization-request
    - The state parameter is used to protect against XSRF. Your application generates a random string and send it to the authorization server using the state parameter. The authorization server send back the state parameter. If both state are the same => OK. If state parameters are differents, someone else has initiated the request.
  - &scope=api1 api2.read  (text with space or comma delimiter)
  - &response_mode: query (tells the server you want the response in query string - https://auth0.com/docs/authorization/protocols/protocol-oauth2)
*/

    //drupal_set_message($this->t('oauth_jwt', array('@username' => $form_state->getValue('username'),'@password' => $form_state->getValue('password') )));
  }
}
