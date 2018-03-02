<?php

/*

Plugin Name: Raw & Markdown Content for Posts & Pages
Plugin URI: http://github.com:njmyers/raw-post
Description: Add wordpress raw and markdown content for posts and pages to wp-json 
Version: 0.1.5
Author: Nick Myers

*/

$autoload_path = __DIR__ . '/vendor/autoload.php';

if ( file_exists( $autoload_path ) ) {
    require_once( $autoload_path );
}

use League\HTMLToMarkdown\HtmlConverter;

/*
    These are the formatters
 */

function get_markdown_content() {
    $post_id = $object['id'];

    $converter = new HtmlConverter(array('strip_tags' => true));

    // get the raw data
    $post_data = get_post( $post_id );

    // apply wordpress renderers
    $rendered_content = apply_filters('the_content', $post_data->post_content);
    $rendered_title = apply_filters('the_title', $post_data->post_title);

    // create array of converted HTML
    $markdown_fields = array(
        'content'   => $converter->convert($rendered_content),
        'title'     => $converter->convert($rendered_title),
    );

    return $markdown_fields;
}

function get_raw_content( $object ) {
    $post_id = $object['id'];

    // get the raw data
    $post_data = get_post( $post_id );

    // create array of raw data
    $raw_fields = array(
        'content'   => $post_data->post_content,
        'title'     => $post_data->post_title,
    );

    return $raw_fields;
};

function add_raw_post_content() {

    register_rest_field( 'post', 'raw', array(
        'get_callback'      => 'get_raw_content',
        'schema'            => null,
        )
    );
};

function add_markdown_post_content() {

    register_rest_field( 'post', 'markdown', array(
        'get_callback'      => 'get_markdown_content',
        'schema'            => null,
        )
    );
};

function add_raw_page_content() {

    register_rest_field( 'page', 'raw', array(
        'get_callback'      => 'get_raw_content',
        'schema'            => null,
        )
    );
}

function add_markdown_page_content() {

    register_rest_field( 'page', 'markdown', array(
        'get_callback'      => 'get_markdown_content',
        'schema'            => null,
        )
    );
}

add_action( 'rest_api_init', 'add_raw_post_content');
add_action( 'rest_api_init', 'add_markdown_post_content');
add_action( 'rest_api_init', 'add_raw_page_content');
add_action( 'rest_api_init', 'add_markdown_page_content');

?>