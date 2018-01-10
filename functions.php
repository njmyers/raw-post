<?php

/*

Plugin Name: Raw Post Content
Plugin URI: http://github.com:njmyers/raw-post
Description: Add wordpress raw unrendered content to wp-json
Version: 0.1
Author: Nick Myers

 */

// require 'vendor/autoload.php';

// use League\HTMLToMarkdown\HtmlConverter;

add_action( 'rest_api_init', 'add_raw_post_content');

function add_raw_post_content() {

	register_rest_field( 'post', 'raw', array(
		'get_callback'		=> 'get_raw_content',
		'schema'			=> null,
		)
	);
};

function get_raw_content( $object ) {
    $post_id = $object['id'];

// 	$converter = new HtmlConverter();

    $post_data = get_post( $post_id, 'ARRAY_A' );
    $raw_fields = array(
    	'content'	=> $post_data['post_content'],
    	'title'		=> $post_data['post_title'],
//     	'markdown'	=> $converter->convert($post_data['post_content']),
    );

    return $post_data;
};

?>

