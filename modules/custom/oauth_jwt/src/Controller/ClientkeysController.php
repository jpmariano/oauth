<?php

namespace Drupal\oauth_jwt\Controller;

use Drupal\oauth_jwt\Keygen\GenerateKey;

use Drupal\Core\Controller\ControllerBase; //Required for dependency Injection
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface; //Required for dependency Injection
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\node\Entity\Node;
use Drupal\user\Entity\User;




class ClientkeysController extends ControllerBase {

   /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function clientkeyPage($uid) {

    //$generateKey = new GenerateKey();//2
   // $randomKey = $generateKey->getKey(10);

    $build['clientkey_template'] = [
      '#theme' => 'clientkey_template',
      '#uid' => $this->t($uid),
      '#attached' => array(
        'library' => array(
          'oauth_jwt/api_keys',
        ),
      )
    ];
    $build['tablekey_template'] = [
      '#theme' => 'tablekey_template',
      '#uid' => $this->t($uid)
    ];
    return $build;
  }
}
