<?php /**
 * @file
 * Contains \Drupal\oauth_server_sso\Controller\DefaultController.
 */

namespace Drupal\oauth_server_sso\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\oauth_server_sso\MiniorangeOAuthServerConstants;
use Drupal\user\Entity\Role;
use Drupal\user\Entity\User;
use Drupal\user\Plugin\views\field\Roles;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Drupal\oauth_server_sso\DBQueries;
use Drupal\oauth_server_sso\handler;
use Drupal\Component\Render\FormattableMarkup;
use Drupal\Component\Utility\Html;
use Drupal\oauth_server_sso\Utilities;

class oauth_server_ssoController extends ControllerBase {

  public static function oauth_server_sso_authorize(){
    global $base_url;
    if(\Drupal::currentUser()->isAuthenticated()){
      $user = \Drupal::currentUser();
      $allUserRolesInTheSystem = user_roles(TRUE);
      $currentUserIsAdmin = FALSE;
      foreach ($user->getRoles() as $roleName) {
       if($allUserRolesInTheSystem[$roleName]->isAdmin()){
         $currentUserIsAdmin = TRUE;
         break;
       }
      }

      //Check for user trying to perform sso has administrator permission then allow sso else show error page.
      if (!$currentUserIsAdmin ){
        Utilities::showErrorMessage('Single Sign On not Allowed','This is a trial module meant for Super User/Administrator use only.','The Single Sign On feature for end users is available in the <a target="_blank" href="https://plugins.miniorange.com/drupal-oauth-server"> premium </a> version of the module.');
      }

      $does_exist = DBQueries::get_user_id($user);
      if($does_exist == FALSE){
        DBQueries::insert_user_in_table($user);
      }
      $request = \Drupal::request();
      $scope= $request->get('scope');
      $client_id = $request->get('client_id');
      $client_id = Html::escape($client_id);
      $redirect_uri =$request->get('redirect_uri');
      $redirect_uri = Html::escape($redirect_uri);
      $state = $request->get('state');
      $state = Html::escape($state);
      handler::oauth_server_sso_validate_clientId($client_id);
      handler::oauth_server_sso_validate_redirectUrl($redirect_uri);
      handler::oauth_server_sso_validate_scope($scope);
      $code = handler::generateRandom(16);
      $num_updated = DBQueries ::insert_code_from_user_id($code, $user);
      if(!empty(\Drupal::config('oauth_server_sso.settings')->get('oauth_server_sso_redirect_url'))){
        $url = \Drupal::config('oauth_server_sso.settings')->get('oauth_server_sso_redirect_url');
        if (strpos($url,'?') !== false) {
          $url =$url.'&code='.$code."&state=".$state;
        }
        else{
          $url =$url.'?code='.$code."&state=".$state;
        }
        $code_time = time();
        $authCodeTime = DBQueries ::insert_code_expiry_from_user_id($code_time,$user);
        $response = new RedirectResponse($url);
        $response->send();
      }
      else{
        echo "Redirect URL not configured.";
      }
    }

    else{

     $rem_val = $_SERVER['QUERY_STRING'];
     $redirecting_url = $base_url.'/authorize?'.$rem_val;
     $array_form = explode(' ', $redirecting_url);
     \Drupal::configFactory()->getEditable('oauth_server_sso.settings')->set('oauth_server_sso_red',$redirecting_url)->save();
     $response = new RedirectResponse('user/login?q=mo_redirect');
     $response->send();

    }
    return new Response();
  }

  public function oauth_server_sso_access_token(){

    $request = \Drupal::request();
    if($request->get('client_id')==''){

      $message = array(
      'error_description' => 'Client credentials missing in body.To fix the issue, please send Client credentials in Body from your OAuth Client.'
      );
      return new JsonResponse($message);

    }
    $request_code = $request->get('code');
    $request_code = Html::escape($request_code);
    $redirect_uri = $request->get('redirect_uri');
    $redirect_uri = Html::escape($redirect_uri);
    $code = DBQueries ::get_same_code_as_received($request_code);
    if($code !=''){
      $user_id = DBQueries ::get_code_from_user_id($request_code);
      $client_id =$request->get('client_id');
      $client_id = Html::escape($client_id);
      handler::oauth_server_sso_validate_clientId($client_id);
      $client_secret = $request->get('client_secret');
      $client_secret = Html::escape($client_secret);
      handler::oauth_server_sso_validate_clientSecret($client_secret);
      $code_request_time = $user_id['auth_code_expiry_time'];
      $grant_type = $request->get('grant_type');
      $grant_type = Html::escape($grant_type);
      handler::oauth_server_sso_validate_code($code,$request_code,$code_request_time);
      handler::oauth_server_sso_validate_grant($grant_type);
      handler::oauth_server_sso_validate_redirectUrl($redirect_uri);
      $access_token = handler::generateRandom(255);
      $access_token_inserted = DBQueries ::insert_access_token_with_user_id($user_id['user_id_val'], $access_token);
      $url =$request->get('redirect_uri');
      $url = Html::escape($url);
      $expires_in = 900;
      if (strpos($url,'?') !== false) {
        $url =$url.'&access_token='.$access_token."&expires_in=900&token_type=Bearer&scope=profile";
      }
      else {
        $url =$url.'?access_token='.$access_token."&expires_in=900&token_type=Bearer&scope=profile";
      }
      $arr = array('access_token' => $access_token, 'expires_in' => $expires_in, 'token_type' => 'Bearer', 'scope' => 'profile');
      $req_time = time();
      $accessToken_expiry_time_inserted = DBQueries::insert_access_token_expiry_time($user_id['user_id_val'],$req_time);
      echo json_encode($arr);exit;
    }
    return new Response();
  }

  public function oauth_server_sso_user_info(){
    $access_values = array();
    foreach (getallheaders() as $name => $value) {
      $access_values[$name] = $value;
    }
    $string_full = $access_values['Authorization'];
    $access_token_received = trim(substr($string_full, 6));

    $user_id = DBQueries::get_user_id_from_access_token($access_token_received);

    $req_time = DBQueries::get_access_token_request_time_from_user_id($user_id['user_id_val']);

    if(!empty($user_id))
    {
      handler::ValidateAccessToken($req_time['access_token'], $req_time['access_token_request_time']);
    }
    else{
      echo json_encode(array(
        "statusCode" => "ERROR",
        "statusMessage" => "Access Token could not be retreived. Please try again or contact your administrator",
      ));
    }
    $user_id = $user_id['user_id_val'];
    $user_obj = User::load($user_id);
    $user_id = $user_obj->id();

    $genericObject = (object) array(
  'id'=>$user_id,
  'uid'=>$user_obj-> id(),
  'name'=>$user_obj-> getDisplayName(),
  'mail'=>$user_obj-> getEmail(),
  'roles'=>$user_obj-> getRoles(),
  );
    echo json_encode($genericObject);exit;
    return new Response();
  }

  public function miniorange_oauth_feedback_func(){
    global $base_url;
    $config = \Drupal::config('oauth_server_sso.settings');
    if (isset($_GET['miniorange_feedback_submit'])){
      $modules_info = \Drupal::service('extension.list.module')->getExtensionInfo('oauth_server_sso');
      $modules_version = $modules_info['version'];
      $_SESSION['mo_other']="False";
      $reason=$_GET['deactivate_plugin'];
      $q_feedback=$_GET['query_feedback'];
      $message='Reason: '.$reason.'<br>Feedback: '.$q_feedback;
      $url = MiniorangeOAuthServerConstants::BASE_URL . '/moas/api/notify/send';
      $ch = curl_init($url);
      $email =$config->get('oauth_server_sso_customer_admin_email');
      if(empty($email))
        $email = $_GET['miniorange_feedback_email'];
      $phone = $config->get('oauth_server_sso_customer_admin_phone');
      $customerKey= $config->get('oauth_server_sso_customer_id');
      $apikey = $config->get('oauth_server_sso_customer_api_key');
      if($customerKey==''){
        $customerKey="16555";
        $apikey="fFd2XcvTGDemZvbw1bcUesNJWEqKbbUq";
      }
      $currentTimeInMillis = self::get_oauth_timestamp();
      $stringToHash 		= $customerKey .  $currentTimeInMillis . $apikey;
      $hashValue 			= hash("sha512", $stringToHash);
      $customerKeyHeader 	= "Customer-Key: " . $customerKey;
      $timestampHeader 	= "Timestamp: " .  $currentTimeInMillis;
      $authorizationHeader= "Authorization: " . $hashValue;
      $fromEmail 			= $email;
      $subject            = 'Drupal ' . \DRUPAL::VERSION . ' OAuth Server Module Feedback | '.$modules_version;
      $query        = '[Drupal ' . \DRUPAL::VERSION . ' OAuth Server | '.$modules_version.']: ' . $message;
      $content='<div >Hello, <br><br>Company :<a href="'.$_SERVER['SERVER_NAME'].'" target="_blank" >'.$_SERVER['SERVER_NAME'].'</a><br><br>Phone Number :'.$phone.'<br><br>Email :<a href="mailto:'.$fromEmail.'" target="_blank">'.$fromEmail.'</a><br><br>Query :'.$query.'</div>';
      $fields = array(
        'customerKey'	=> $customerKey,
        'sendEmail' 	=> true,
        'email' 		=> array(
          'customerKey' 	=> $customerKey,
          'fromEmail' 	=> $fromEmail,
          'fromName' 		=> 'miniOrange',
          'toEmail' 		=> 'drupalsupport@xecurify.com',
          'toName' 		=> 'drupalsupport@xecurify.com',
          'subject' 		=> $subject,
          'content' 		=> $content
        ),
      );
      $field_string = json_encode($fields);
      curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
      curl_setopt( $ch, CURLOPT_ENCODING, "" );
      curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
      curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
      curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls
      curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $customerKeyHeader,
        $timestampHeader, $authorizationHeader));
      curl_setopt( $ch, CURLOPT_POST, true);
      curl_setopt( $ch, CURLOPT_POSTFIELDS, $field_string);
      $content = curl_exec($ch);
      if(curl_errno($ch)){
        return json_encode(array("status"=>'ERROR','statusMessage'=>curl_error($ch)));
      }
      curl_close($ch);
    }
    \Drupal::configFactory()->getEditable('oauth_server_sso.settings')->clear('miniorange_oauth_uninstall_status')->save();
    \Drupal::service('module_installer')->uninstall(['oauth_server_sso']);
    $uninstall_redirect = $base_url.'/admin/modules';
    \Drupal::messenger()->addMessage('The module has been successfully uninstalled.');
    return new RedirectResponse($uninstall_redirect);
  }

  /**
   * This function is used to get the timestamp value
   */
  public static function get_oauth_timestamp() {
    $url = MiniorangeOAuthServerConstants::BASE_URL .'/moas/rest/mobile/get-timestamp';
    $ch  = curl_init( $url );
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
    curl_setopt( $ch, CURLOPT_ENCODING, "" );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false ); // required for https urls
    curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
    curl_setopt( $ch, CURLOPT_POST, true );
    $content = curl_exec( $ch );
    if ( curl_errno( $ch ) ) {
      echo 'Error in sending curl Request';
      exit ();
    }
    curl_close( $ch );
    if(empty( $content )){
      $currentTimeInMillis = round( microtime( true ) * 1000 );
      $currentTimeInMillis = number_format( $currentTimeInMillis, 0, '', '' );
    }
    return empty( $content ) ? $currentTimeInMillis : $content;
  }


}
