<?php

function biu_admin_print_scripts() {
  wp_enqueue_media();
  wp_enqueue_script('wina_classic-uadmin-js', plugin_dir_url(__FILE__) . '/js/uploader.js', array('jquery','media-upload','thickbox'));
}

add_action('admin_print_scripts', 'biu_admin_print_scripts');

/**
 * META-BOX
 */
function biu_get_meta_box($meta_boxes)
{
  $prefix = '_biu_';
 
  $meta_boxes[] = array(
    'id' => 'podcast',
    'title' => esc_html__('Podcast File', 'biu'),
    'post_types' => array('post'),
    'context' => 'advanced',
    'priority' => 'high',
    'autosave' => 'false',
    'fields' => array(
      array(
				'id' => $prefix . 'podcast_cdn_duration',
				'type' => 'hidden',
				'name' => esc_html__( 'Hidden', 'metabox-online-generator' ),
			),
      array(
				'id' => $prefix . 'podcast_cdn',
				'type' => 'url',
				'name' => esc_html__( 'Mp3 File', 'metabox-online-generator' ),
			),
    ),
  );

  return $meta_boxes;
}
add_filter('rwmb_meta_boxes', 'biu_get_meta_box');


add_action('edit_category_form_fields', 'biu_edit_category_form_fields');
function biu_edit_category_form_fields($tag)
{    //check for existing featured ID
  $t_id = $tag->term_id;
  $cat_meta = get_option("category_$t_id");
  ?>
  <tr class="form-field">
    <th scope="row" valign="top"><label for="cat_Image_url"><?php _e('Category Image Url'); ?></label></th>
    <td>
      <input type="hidden" name="cat_meta[img_id]" id="cat_meta_img_id" value="<?php echo isset($cat_meta['img_id']) ? $cat_meta['img_id'] : ''; ?>" />
      <input type="hidden" name="cat_meta[img_url]" id="cat_meta_img_url" value="<?php echo isset($cat_meta['img_url']) ? $cat_meta['img_url'] : ''; ?>" />
      <input type="button" class="button button-secondary" value="Upload Image" id="upload-button" />
      <p class="description"><?php _e( 'Enter a Image Link','flatsome' ); ?></p>
      <div id="cat_meta_img_preview" <?php print (isset($cat_meta['img_url']) && $cat_meta['img_url'] == '' ? 'style="display:none;"' : '');?>>
        <img src="<?php echo isset($cat_meta['img_url']) ? $cat_meta['img_url'] : ''; ?>" width="30%" />
      </div>
    </td>
  </tr>
  <!-- <tr class="form-field">
    <th scope="row" valign="top"><label for="cat_status"><?php _e('Color'); ?></label></th>
    <td>
      <input type="text" name="cat_meta[color]" id="cat_meta[color]" size="6" style="width:60%;" value="<?php echo $cat_meta['color'] ? $cat_meta['color'] : ''; ?>"><br />
      <span class="description"><?php _e('Color for caregory: use hexadesimal number'); ?></span>
    </td>
  </tr> -->
  <tr class="form-field">
    <th scope="row" valign="top"><label for="cat_status"><?php _e('Status'); ?></label></th>
    <td>
    <input type="checkbox" 
           name="cat_meta[status]" 
           id="cat_meta[status]" 
           value="1"
           <?php print ((isset($cat_meta['status']) && $cat_meta['status'] == '1') ? 'checked' : '')?>> <?php print _e('Status');?>
    </td>
  </tr>

<?php
}


add_action('edited_category', 'biu_save_extra_category_fileds');

// save extra category extra fields callback function
function biu_save_extra_category_fileds($term_id)
{
 

  if (isset($_POST['cat_meta'])) {
    if(!isset($_POST['cat_meta']['status'])){
      $_POST['cat_meta']['status']=0;
    }
    $t_id = $term_id;
    $cat_meta = get_option("category_$t_id");
    $cat_keys = array_keys($_POST['cat_meta']);
    foreach ($cat_keys as $key) {
      if (isset($_POST['cat_meta'][$key])) {
        $cat_meta[$key] = $_POST['cat_meta'][$key];
      }
    }
    //save the option array
    update_option("category_$t_id", $cat_meta);
  }
}
