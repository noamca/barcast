<?php

/**
 * Export Users to CSV
 * /wp-admin/users.php
 */
add_action('manage_users_extra_tablenav', 'kokoapps_manage_users_extra_tablenav');
function kokoapps_manage_users_extra_tablenav($which)
{
  ?>
  <div class="alignleft actions">
    <?php
      $button_id = 'bottom' === $which ? 'exportcsv' : 'exportcsv';
      submit_button(__('CSV'), '', $button_id, false);
      ?>
  </div>
<?php
}


add_action('admin_init', 'kokoapps_admin_init');
function kokoapps_admin_init()
{
  if (!empty($_GET["exportcsv"])) {
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="' . time() . '.csv";');
    header('Content-type', 'text/html; charset=utf-8');

    ob_end_clean();

    $args = array(
      'orderby' => 'ID',
      'order'   => 'ASC'
    );

    if (!empty($_GET["role"])) {
      $args['role'] = $_GET["role"];
    }

    $users = get_users($args);

    $file = fopen('php://output', 'w');
    $csv_header = array(
      'ID',
      'User login',
      'Display name',
      'Nicename',
      'Email',
      'Registered',
      'User Role'
    );

    fwrite($file, "\xEF\xBB\xBF");

    fputcsv($file, $csv_header);
    foreach ($users as $user) {
      $data = array(
        $user->ID,
        $user->data->user_login,
        $user->data->display_name,
        $user->data->user_nicename,
        $user->data->user_email,
        $user->data->user_registered,
        KokoApps_User::getRole($user->roles)
      );

      fputcsv($file, $data);
    }
    fclose($file);
    exit();
  }
}
