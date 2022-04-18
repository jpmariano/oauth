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

class AddclientkeysController extends ControllerBase
{
    protected function getRequestFormat(Request $request) {
        $format = $request->getRequestFormat();
        if (!in_array($format, $this->serializerFormats)) {
            throw new BadRequestHttpException("Unrecognized format: $format.");
        }
        return $format;
    }

    /**
   * @var Symfony\Component\HttpFoundation\RequestStack
   */
  private $requestStack;

  /**
   * Constructor.
   *
   * @param Symfony\Component\HttpFoundation\RequestStack $request_stack
   */

    public function __construct(RequestStack $request_stack) {
        $this->requestStack = $request_stack;
    }


  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('request_stack')
    );
  }


  /**
   * {@inheritdoc}
   */
  public function add(Request $request) {
    $uid = $request->request->get('uid');
    $clientkey = $request->request->get('clientkey');
    $clientsecret = $request->request->get('clientsecret');
    $clientapp = $request->request->get('clientapp');

    return new JsonResponse($clientapp);
  }

}
