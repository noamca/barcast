<?php

/**
 * LOGIN
 */
function v_forcelogin()
{

  // Exceptions for AJAX, Cron, or WP-CLI requests
  if ((defined('DOING_AJAX') && DOING_AJAX) || (defined('DOING_CRON') && DOING_CRON) || (defined('WP_CLI') && WP_CLI)) {
    return;
  }

  // Redirect unauthorized visitors
  if (!is_user_logged_in()) {
    // Get visited URL
    $url  = isset($_SERVER['HTTPS']) && 'on' === $_SERVER['HTTPS'] ? 'https' : 'http';
    $url .= '://' . $_SERVER['HTTP_HOST'];
    // port is prepopulated here sometimes
    if (strpos($_SERVER['HTTP_HOST'], ':') === FALSE) {
      $url .= in_array($_SERVER['SERVER_PORT'], array('80', '443')) ? '' : ':' . $_SERVER['SERVER_PORT'];
    }
    $url .= $_SERVER['REQUEST_URI'];

    /**
     * Bypass filters.
     *
     * @since 3.0.0 The `$whitelist` filter was added.
     * @since 4.0.0 The `$bypass` filter was added.
     * @since 5.2.0 The `$url` parameter was added.
     */
    $bypass = apply_filters('v_forcelogin_bypass', false, $url);
    $whitelist = apply_filters('v_forcelogin_whitelist', array());

    if (preg_replace('/\?.*/', '', $url) !== preg_replace('/\?.*/', '', wp_login_url()) && !$bypass && !in_array($url, $whitelist)) {
      // Determine redirect URL
      $redirect_url = apply_filters('v_forcelogin_redirect', $url);
      // Set the headers to prevent caching
      nocache_headers();
      // Redirect
      wp_safe_redirect(wp_login_url($redirect_url), 302);
      exit;
    }
  } elseif (function_exists('is_multisite') && is_multisite()) {
    // Only allow Multisite users access to their assigned sites
    if (!is_user_member_of_blog() && !current_user_can('setup_network')) {
      wp_die(__("You're not authorized to access this site.", 'wp-force-login'), get_option('blogname') . ' &rsaquo; ' . __("Error", 'wp-force-login'));
    }
  }
}
add_action('template_redirect', 'v_forcelogin');

add_filter('option_users_can_register', function ($value) {
  $script = basename(parse_url($_SERVER['SCRIPT_NAME'], PHP_URL_PATH));

  if ($script == 'wp-login.php') {
    $value = false;
  }

  return $value;
});
