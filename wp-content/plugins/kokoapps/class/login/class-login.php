<?php

/**
 * Implement wordpres login for API
 * 
 * @package ApiLogin
 */


if (!class_exists('KokoApps_Login')) {
  class KokoApps_Login extends KokoApps_User
  {

    protected static function error(
      $message = 'Login',
      $data = '',
      $code = 401
    ) {
      return wp_send_json(new WP_Error($code, $message, $data), $code);
    }

    /**
     * Login to wordpress
     *
     * @param JSON $request  
     * @param timestamp $expiration
     * @return JSON
     */
    public static function Login($request, $expiration)
    {
      $result = array();
      $username = (empty($request['username']) ? null : $request['username']);
      $password = (empty($request['password']) ? null : $request['password']);
      $user = wp_authenticate($username, $password);
      if (!$user->errors) {
        if (!empty($user->data->ID)) {
          $access_token_kokoapps = KokoApps_Authenticate_Token::set($user->data->ID, $expiration);

          $result = parent::getInfo($user, $access_token_kokoapps);

          $result['token']['nonce'] = wp_create_nonce('wp_rest');
          $result['token']['kokoapps'] = $access_token_kokoapps;

          return new WP_REST_Response($result);
        }
      } else {
        return self::error('Incorrect username/password');
      }
    }

    protected static function auth($request, $expiration)
    {
      if (!empty($request['token'])) {
        $args = array(
          'meta_key' => 'token',
          'meta_value' => $request['token']
        );
        $user_query = new WP_User_Query($args);
        $users = $user_query->get_results();
        if (!empty($users)) {
          $user = $user_query->get_results()[0]->data;
          $result['action'] = 'existing user';
          $result['token'] = KokoApps_Authenticate_Token::set($user->data->ID, $expiration);
          $result = parent::GetInfo($user->data, $result['token']);
          return new WP_REST_Response($result);
        } else {
          $token = $request['token'];
          //call to child function and get data 
          $data = static::get_graph($token);
          if (!isset($data['id']) || !isset($data['email'])) {
            return parent::error('Access token', 'Facebook Login failed');
          } else {
            $user = get_user_by('email', $data['email']);
            if (!$user) {
              // Create an username
              $username = sanitize_user(str_replace(' ', '_', strtolower($data['name'])));
              // Creating our user
              $user_id = wp_create_user($username, wp_generate_password(), $data['email']);
              if (is_wp_error($user_id)) {
                return parent::error('Access token', 'Facebook User Registration failed');
              } else {
                $user = new WP_User($user_id);
              }
              if ($user) {
                update_user_meta($user->ID, 'token', $request['token']);
                static::update_user_meta($user->ID, $data);
                return new WP_REST_Response(parent::GetInfo($user));
              } else {
                return parent::error('Access token');
              }
            } else {
              return new WP_REST_Response(parent::GetInfo($user));
            }
          }
          return parent::error('Access token');
        }
      } else {
        return parent::error('Access token');
      }
    }
  }
}
