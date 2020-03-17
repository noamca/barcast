<?php
/**
 * API
 */
function biu_register_meta()
{
  register_meta('post', '_biu_podcast_cdn', array(
    'show_in_rest' => true,
    'type' => 'string',
    'single' => true,
  ));
  register_meta('post', '_biu_podcast_cdn_duration', array(
    'show_in_rest' => true,
    'type' => 'string',
    'single' => true,

  ));
  register_meta('post', '_biu_podcast_cdn_duration_format', array(
    'show_in_rest' => true,
    'type' => 'string',
    'single' => true,

  ));
}
add_action('init', 'biu_register_meta');


function biu_rest_authentication_errors($result)
{
  return $result;
}
add_filter('rest_authentication_errors', 'biu_rest_authentication_errors', 99);
