<?php

/*

Plugin Name: Raw Post Content
Plugin URI: http://github.com:njmyers/raw-post
Description: Add wordpress raw post and page unrendered and markdown content to wp-json 
Version: 0.1
Author: Nick Myers

 */

$autoload_path = __DIR__ . '/vendor/autoload.php';

if ( file_exists( $autoload_path ) ) {
    require_once( $autoload_path );
}

use League\HTMLToMarkdown\HtmlConverter;

add_action( 'rest_api_init', 'add_raw_post_content');

function add_raw_post_content() {

    register_rest_field( 'post', 'raw', array(
        'get_callback'      => 'get_raw_post_content',
        'schema'            => null,
        )
    );
};

function get_raw_post_content( $object ) {
    $post_id = $object['id'];

    $converter = new HtmlConverter(array('strip_tags' => true));

    $post_data = get_post( $post_id, 'ARRAY_A' );
    $raw_fields = array(
        'content'   => $post_data['post_content'],
        'title'     => $post_data['post_title'],
        'markdown'  => $converter->convert($post_data['post_content']),
    );

    return $raw_fields;
};

add_action( 'rest_api_init', 'add_raw_page_content');

function add_raw_page_content() {

    register_rest_field( 'page', 'raw', array(
        'get_callback'      => 'get_raw_page_content',
        'schema'            => null,
        )
    );
};

function get_raw_page_content( $object ) {
    $page_id = $object['id'];

    $converter = new HtmlConverter(array('strip_tags' => true));

    $page_data = get_post( $page_id, 'ARRAY_A' );
    $raw_fields = array(
        'content'   => $page_data['post_content'],
        'title'     => $page_data['post_title'],
        'markdown'  => $converter->convert($page_data['post_content']),
    );

    return $raw_fields;
};

?>