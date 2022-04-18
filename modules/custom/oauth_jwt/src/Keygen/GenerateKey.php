<?php

namespace Drupal\oauth_jwt\Keygen;

use PragmaRX\Random\Random;
use RandomLib\Factory;
use Drupal\user\Entity\User;

class GenerateKey {
  private function generateRandom($length){

    $random = rand(0,1);
    if ($random == 0){
      $this->random = new Random();
      $key = $this->random->size($length)->get();
    }
    else{
      $factory = new Factory;
      $generator = $factory->getMediumStrengthGenerator();
      $key = $generator->generateString($length);
    }

      return strval($key);
  }

  public function getKey(){
      $uid = \Drupal::currentUser()->id();
      $uid_length = strlen($uid);
      $user = User::load($uid);
      $str_uid = strval($uid);
      $username = $user->get("name")->value;
      //total string should be 15
      $key = $username[0] . $str_uid . $this->generateRandom(14 - $uid_length);
      return $key;
  }

  public function getSecret(){
    //$secret = $this->generateRandom(5) . '-' . $this->generateRandom(5) . '-' . $this->generateRandom(5) . '-' . $this->generateRandom(5) . '-' . $this->generateRandom(5);
    $secret = bin2hex(random_bytes(32));
    return $secret;
 }
}
