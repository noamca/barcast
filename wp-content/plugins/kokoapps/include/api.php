<?php
if (!class_exists('Kokoapps_Api')) {
  class Kokoapps_Api
  {
    public function __construct()
    {
      add_filter('rest_prepare_post', array($this,'rest_prepare_post'), 10, 3);
      add_filter('rest_prepare_category', array($this,'rest_prepare_category'), 10, 3);
      add_filter('rest_category_query', array($this,'rest_category_query'), 10, 3);

    }

    function is_admin_request() {
      if(isset($_GET["_locale"]) && $_GET["_locale"] == 'user'){
        return true;
      }
      if ( function_exists( 'wp_doing_ajax' ) ) {
        return is_admin() && ! wp_doing_ajax();
      } else {
        return is_admin() && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX );
      }
    }

    public function rest_category_query($args, $request){
      // // Bail out if no filter parameter is set.
      if ( empty( $request['filter'] ) || ! is_array( $request['filter'] ) ) {
        return $args;
      }

      // https://github.com/WP-API/rest-filter/blob/master/plugin.php
      // $filter = $request['filter'];
      // if ( isset( $filter['posts_per_page'] ) && ( (int) $filter['posts_per_page'] >= 1 && (int) $filter['posts_per_page'] <= 100 ) ) {
      //   $args['posts_per_page'] = $filter['posts_per_page'];
      // }
      // global $wp;
      // $vars = apply_filters( 'rest_query_vars', $wp->public_query_vars );
      // // Allow valid meta query vars.
      // $vars = array_unique( array_merge( $vars, array( 'meta_query', 'meta_key', 'meta_value', 'meta_compare' ) ) );
      // foreach ( $vars as $var ) {
      //   if ( isset( $filter[ $var ] ) ) {
      //     $args[ $var ] = $filter[ $var ];
      //   }
      // }

      // 
      return $args;
    }

    public function rest_prepare_category($data, $post, $request){
      if($this->is_admin_request()){
        return $data;
      }
     
      $_data = $data->data;
      $term_id=$post->term_id;
      $option = get_option("category_".$term_id);
      $img=$option['img_url'];
      if($img){
        $_data['featured_media']['large']=$img;
      }  
      
      $status = $option['status'];
      if($status === '1'){
        $_data['status']='publish';
      }else{
        $_data['status']='draft';
      }
      $data->data = $_data;
      return $data;
    }

    public function rest_prepare_post($data, $post, $request){
      if($this->is_admin_request()){
        return $data;
      }
      
      $_data = $data->data;

      unset($_data['featured_media']);
  
      $thumbnail_id = get_post_thumbnail_id($post->ID);

      if($thumbnail_id){
        $tmp=array();
        $tmp['thumbnail']=wp_get_attachment_image_src($thumbnail_id,'thumbnail');
        $tmp['medium']=wp_get_attachment_image_src($thumbnail_id,'medium');
        $tmp['large']=wp_get_attachment_image_src($thumbnail_id,'large');
    
        $_data['featured_media']=$tmp;
      }
      $data->data = $_data;

      return $data;
    }
  }
  return new Kokoapps_Api();
}
