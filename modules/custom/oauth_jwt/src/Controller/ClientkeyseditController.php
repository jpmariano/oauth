<?php

namespace Drupal\oauth_jwt\Controller;

use Drupal\Core\Controller\ControllerBase; //Required for dependency Injection
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface; //Required for dependency Injection
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\node\Entity\Node;
use Drupal\user\Entity\User;

class ClientkeyseditController extends ControllerBase {

   /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function clientkeyeditPage($uid, $ckid) {


    $build['clientkey_template'] = [
      '#theme' => 'clientkey_template',
      '#uid' => $this->t($uid),
      '#ckid' => $this->t($ckid),
      '#attached' => array(
        'library' => array(
          'oauth_jwt/api_keys',
        ),
      )
    ];
    return $build;
  }
}
