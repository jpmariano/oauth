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
use Drupal\oauth_jwt\Keygen\GenerateKey;

class KeygenController extends ControllerBase {

   /**
   *
   * @return string
   */
  public function generate(Request $request) {
    $type = $request->query->get('type');
    if($type === 'key'){
      $data = \Drupal::service('oauth_jwt.keygen.generatekey')->getKey();
    } else {
      $data = \Drupal::service('oauth_jwt.keygen.generatekey')->getSecret();
    }
   return new Response($data);
  }
}
