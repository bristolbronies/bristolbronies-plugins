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