<?php
if (!class_exists('Kokoapps_WP_User')) {
  class Kokoapps_WP_User
  {
    public function __construct()
    {
      add_filter('get_avatar', array($this, 'get_avatar'), 10, 5);

      //add login provider to users
      add_filter('manage_users_custom_column', array($this, 'manage_users_custom_column'), 10, 3);
      add_filter('manage_users_columns', array($this, 'manage_users_columns'));
    }

    function get_avatar($avatar = '', $id_or_email, $size = '96', $default = '', $alt = false)
    {
      //If is email, try and find user ID
      if (!is_numeric($id_or_email) && is_email($id_or_email)) {
        $user  =  get_user_by('email', $id_or_email);
        if ($user) {
          $id_or_email = $user->ID;
        }
      }

      //if not user ID, return
      if (!is_numeric($id_or_email)) {
        return $avatar;
      }

      //Find ID of attachment saved user meta
      $saved = get_user_meta($id_or_email, 'avatar_url', true);
      if (0 < absint($saved)) {
        //return saved image
        return wp_get_attachment_image($saved, [$size, $size], false, ['alt' => $alt]);
      }

      //return normal
      return $avatar;
    }

    function manage_users_custom_column($val, $column_name, $user_id)
    {
      switch ($column_name) {
        case 'auth':
          $fb_id = get_the_author_meta('fb_id', $user_id);
          if ($fb_id != '') {
            $first_name = get_the_author_meta('first_name', $user_id);
            $last_name = get_the_author_meta('last_name', $user_id);
            $fb_avatar = get_the_author_meta('fb_avatar', $user_id);
            return '<a href="https://www.facebook.com/search/top/?q=' . $first_name . ' ' . $last_name . '" target="_blank"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/c2/F_icon.svg/401px-F_icon.svg.png" width="20%" /><img src="' . $fb_avatar . '" width="20%" /></a>';
          }

          $google = get_the_author_meta('google_id', $user_id);
          if ($google != '') {
            return '<img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2d/Google_Plus_logo_2015.svg/168px-Google_Plus_logo_2015.svg.png" width="10%" />';
          }

          return '';
        default:
      }
      return $val;
    }

    function manage_users_columns($column)
    {
      $column['auth'] = __('Auth');
      return $column;
    }
  }
  return new Kokoapps_WP_User();
}
