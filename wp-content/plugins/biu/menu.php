<?php


function posts_for_current_author($query)
{
  global $pagenow;

  if ('edit.php' != $pagenow || !$query->is_admin)
    return $query;

  $user = wp_get_current_user();
  $roles=(array) $user->roles;

  if (!in_array( 'editor', $roles ) && 
      !in_array( 'administrator', $roles ) ) {
    
    $query->set('author', $user->ID);
    
  }

  return $query;
}
add_filter('pre_get_posts', 'posts_for_current_author');


add_action('pre_get_posts', 'users_own_attachments');
function users_own_attachments($wp_query_obj)
{

  global $current_user, $pagenow;

  $is_attachment_request = ($wp_query_obj->get('post_type') == 'attachment');

  if (!$is_attachment_request)
    return;

  if (!is_a($current_user, 'WP_User'))
    return;

  if (!in_array($pagenow, array('upload.php', 'admin-ajax.php')))
    return;

  if (!current_user_can('delete_pages'))
    $wp_query_obj->set('author', $current_user->ID);

  return;
}


// Removes from admin menu
add_action('admin_menu', 'biu_remove_admin_menus');
function biu_remove_admin_menus()
{
  if (!current_user_can('administrator')) {
    remove_menu_page('edit-comments.php');
    remove_menu_page('index.php');
    remove_menu_page('edit.php?post_type=page');
    remove_menu_page('admin.php?page=ezcache#/');
  }
}
// Removes from post and pages
add_action('init', 'remove_comment_support', 100);

function remove_comment_support()
{
  remove_post_type_support('post', 'comments');
  remove_post_type_support('page', 'comments');
}
// Removes from admin bar
function biu_admin_bar_render()
{
  global $wp_admin_bar;
  $wp_admin_bar->remove_menu('comments');
}
add_action('wp_before_admin_bar_render', 'biu_admin_bar_render');



add_action('admin_head', 'biu_custom_fonts');

function biu_custom_fonts()
{
    echo '<style>
    li.toplevel_page_ezcache{display:none;}
    .column-image img{
      border-radius: 9px;
    }
  </style>';
}
