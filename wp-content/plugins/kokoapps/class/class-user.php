<?php

/**
 * User in wordpress
 * 
 * @package ApiLogin
 */
if (!class_exists('KokoApps_User')) {
  class KokoApps_User
  {
    /**
     * Get user information
     *
     * @param [type] $user
     * @param [type] $token
     * @return void
     */
    public static function getInfo($user)
    {
      $user_id = $user->ID;

      if (class_exists('WC_Customer')) {
        $customer = new WC_Customer($user_id);
        $data = $customer->get_data();
        unset($data['meta_data']);
        unset($data['username']);
        $result = $data;

        unset($data);
        unset($customer);
      } else {
        //WC Plugin is active
        $result = array(
          'id' => $user_id,
          'email' => $user->user_email,
          'date_created' => $user->user_registered,
          'display_name' => $user->display_name,
        );
      }

      if (is_integer($user_id)) {
        $result['avatar'] = get_avatar_url($user_id);
      }

      return $result;
    }

    private static function wp_get_current_user_translatable_role_name($current_user_role_slug = '')
    {
      $role_name = '';

      if (!function_exists('get_editable_roles')) {
        require_once ABSPATH . 'wp-admin/includes/user.php';
      }

      // Please note that translate_user_role doesn't work in the front-end currently.
      load_textdomain('default', WP_LANG_DIR . '/admin-' . get_locale() . '.mo');

      $editable_roles = array_reverse(get_editable_roles());

      foreach ($editable_roles as $role => $details) {
        $name = translate_user_role($details['name']);
        // preselect specified role
        if ($current_user_role_slug == $role) {
          $role_name  = $name;
        }
      }

      return $role_name;
    }

    /**
     * Get roles names
     *
     * @param string $roles
     * @return string
     */
    public static function getRole($roles)
    {
      $rolesdata = array();
      foreach ($roles as $role) {
        $rolesdata[] .= self::wp_get_current_user_translatable_role_name($role);
      }
      return implode(', ', $rolesdata);
    }
  }
}
