<?php

/**
 * Login with google
 */
if (!class_exists('KokoApps_Login_Google')) {
  class KokoApps_Login_Google extends KokoApps_Login
  {
    protected static function get_graph($token)
    {
      //search email in graph
      $url = 'https://www.googleapis.com/oauth2/v1/tokeninfo?';
      $params = array(
        'access_token' => $token
      );
      $url .= http_build_query($params);

      try {
        $data = (array) json_decode(file_get_contents($url));
      } catch (Exception $e) {
        $data = null;
      }

      return $data;
    }


    protected static function update_user_meta($uid, $data = array())
    {
      if (empty($data)) {
        return;
      }

      update_user_meta($uid, 'first_name', $data['first_name']);
      update_user_meta($uid, 'last_name', $data['last_name']);
      update_user_meta($uid, 'fb_id', $data['id']);
      update_user_meta($uid, 'fb_avatar', $data['picture']->data->url);
      update_user_meta($uid, 'avatar_url', $data['picture']->data->url);
    }


    public static function Login($request, $expiration)
    {
      return parent::auth($request, $expiration);
    }
  }
}
