<?php

/**
 * Login with facebook
 * @link https://developers.facebook.com/tools/explorer/?method=GET&path=me%2F%3Ffields%3Demail%2Clink%2Cpicture%2Cfirst_name&version=v4.0&classic=0
 * @link https://developers.facebook.com/docs/graph-api/reference/user
 */
if (!class_exists('KokoApps_Login_Facebook')) {
  class KokoApps_Login_Facebook extends KokoApps_Login
  {
    protected static function get_graph($token)
    {
      //search email in graph
      $url = 'https://graph.facebook.com/v4.0/me/?';
      $params = array(
        'access_token' => $token,
        'fields' => 'id,first_name,last_name,name,email,picture'
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
