<?php

/**
 * Implement user api
 *
 * @package ApiUser
 */
if (!class_exists('KokoApps_User_Api')) {
  class KokoApps_User_Api
  {
    function __construct()
    {
      add_action('rest_api_init', function () {
        register_rest_route('wp/v2', '/user/(?P<id>[\d]+)/avatar', array(
          'methods' => WP_REST_Server::CREATABLE,
          'callback' => array(&$this, 'set_avatar'),
          'args' => array(
            'id' => array(
              'description' => __('Unique identifier for the user.'),
              'type'        => 'integer',
            ),
          ),
        ));
      });

      add_filter('rest_prepare_user', array($this, 'rest_prepare_user'), 10, 3);
    }


    function set_avatar($request)
    {
      $user = $this->get_user($request['id']);
      if (is_wp_error($user)) {
        return $user;
      }
      $id = $user->ID;

      if (!$user) {
        return new WP_Error('rest_user_invalid_id', __('Invalid user ID.'), array('status' => 404));
      }

      update_user_meta($id, 'avatar_url', $request['avatar_url']);
    }


    function rest_prepare_user($data, $user, $request)
    {
      $avatar_urls = get_user_meta($user->ID, 'avatar_url', true);
      if ($avatar_urls == '') {
        return $data;
      }

      $_data = $data->data;
      unset($_data['avatar_urls']);
      $_data['avatar_urls'] = $avatar_urls;
      $data->data = $_data;
      return $data;
    }
  }
  $KokoApps['User_Api'] = new KokoApps_User_Api();
}
