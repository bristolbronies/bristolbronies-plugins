<?php 
/*
Plugin Name: Bristol Bronies billboards
Plugin URI: http://bristolbronies.co.uk/
Description: Creates the functions necessary for editing the billboards section of the site.
Author: Kimberly Grey
Author URI: http://greysadventures.com/
*/


/**
 * Billboard post type
 */
function bb_billboard_post_type() {
  $labels = array(
    "name" => _x("Billboards", "post type general name"),
    "singular_name" => _x("Billboard", "post type singular name"),
    "add_new" => _x("Add New", "book"),
    "add_new_item" => __("Add New Billboard"),
    "edit_item" => __("Edit Billboard"),
    "new_item" => __("New Billboard"),
    "all_items" => __("All Billboards"),
    "view_item" => __("View Billboards"),
    "search_items" => __("Search Billboards"),
    "not_found" => __("No billboards found"),
    "not_found_in_trash" => __("No billboards found in the trash"),
    "parent_item_colon" => "",
    "menu_name" => "Billboards"
  );
  $args = array(
    "labels" => $labels,
    "description" => "Contains featured front-page links.",
    "public" => false,
    "menu_position" => 7,
    "supports" => array("title", "editor", "custom-fields"),
    "has_archive" => false,
    "show_ui" => true,
    "show_in_menu" => true
  );
  register_post_type("billboard", $args);
}
add_action("init", "bb_billboard_post_type");

/**
 * Advanced Custom Fields configuration
 */
if(function_exists("register_field_group"))
{
    register_field_group(array (
        'id' => 'acf_billboard-details',
        'title' => 'Billboard Details',
        'fields' => array (
            array (
                'key' => 'field_533b198500aee',
                'label' => 'Billboard Image',
                'name' => 'billboard_image',
                'type' => 'image_crop',
                'required' => 1,
                'save_format' => 'object',
                'crop_type' => 'hard',
                'target_size' => 'custom',
                'width' => 420,
                'height' => 140,
                'force_crop' => 'yes',
                'preview_size' => 'thumbnail',
                'save_in_media_library' => 'yes',
                'retina_mode' => 'no',
            ),
            array (
                'key' => 'field_533b199a00aef',
                'label' => 'Billboard URL',
                'name' => 'billboard_url',
                'type' => 'text',
                'required' => 1,
                'default_value' => '',
                'placeholder' => 'http://www.example.com/interesting-cool-things/',
                'prepend' => '',
                'append' => '',
                'formatting' => 'none',
                'maxlength' => '',
            ),
        ),
        'location' => array (
            array (
                array (
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'billboard',
                    'order_no' => 0,
                    'group_no' => 0,
                ),
            ),
        ),
        'options' => array (
            'position' => 'normal',
            'layout' => 'default',
            'hide_on_screen' => array (
                0 => 'custom_fields',
            ),
        ),
        'menu_order' => 0,
    ));
}
