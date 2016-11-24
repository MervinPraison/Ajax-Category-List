<?php
/**
 * Plugin Name: Ajax Category List
 * Plugin URI: https://mer.vin
 * Description: Allow users to List Category, using shortcode [category_list]
 * Version: 1.0.0
 * Author: Mervin Praison
 * Author URI: https://mer.vin
 * License: GPL2
 */

add_action( 'wp_enqueue_scripts', 'ajax_category_list_enqueue_scripts' );
function ajax_category_list_enqueue_scripts() {
	if( is_single() ) {
		wp_enqueue_style( 'catlist', plugins_url( '/cat.css', __FILE__ ) );
	}

	wp_enqueue_script( 'catlist', plugins_url( '/cat.js', __FILE__ ), array('jquery'), '1.0', true );

	wp_localize_script( 'catlist', 'categorylist', array(
		'ajax_url' => admin_url( 'admin-ajax.php' )
	));

}

add_shortcode( 'category_list' , 'ajax_list_category_display' );
function ajax_list_category_display( ) {
	$category_text = '';

	if ( is_single() ) {

	$terms = get_terms( array(
	    'taxonomy' => 'category',
	    'hide_empty' => true,
	) );

	$category_text .= '<ul>';
	foreach ($terms as $term) {
		$term_ids[] = $term->term_id;

		$category_text .= '<li class="cat-list-received"><a id="cat-list-button-id" class="cat-list-button" href="#" data-id-list="' . get_the_ID() .'" data-category-list="' . $term->term_id . '">'.$term->name.'</a></li>';
	}

	$category_text .= '</ul>';

	}

	return $category_text.'<span id="cat-display"></span>';

}

add_action( 'wp_ajax_nopriv_ajax_category_list', 'ajax_category_list' );
add_action( 'wp_ajax_ajax_category_list', 'ajax_category_list' );

function ajax_category_list() {

	$category = $_REQUEST['category_name'];
	$args = array( 'posts_per_page' => 1000, 'order'=> 'ASC', 'orderby' => 'title', 'category' => $category );
	$postslist = get_posts( $args );

	$response .= '<h2>'.get_the_category_by_ID($category[0]).'</h2><ul>';
	foreach ( $postslist as $post ) :
  	setup_postdata( $post ); 
		$response .= '<li>'.$post->post_title.'</li>';
	endforeach; 
	$response .= '</ul>';
	wp_reset_postdata();

	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		echo $response;
		die();
	}
	else {
		wp_redirect( get_permalink( $_REQUEST['post_id'] ) );
		exit();
	}
}
