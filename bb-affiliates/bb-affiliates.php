<?php 
/*
Plugin Name: Bristol Bronies affiliates
Plugin URI: http://bristolbronies.co.uk/
Description: Creates the functions necessary for editing the affiliates section of the site.
Author: Kimberly Grey
Author URI: http://greysadventures.com/
*/

/**
 * Affiliates post type
 */
function bb_affiliates_post_type() {
  $labels = array(
    "name" => _x("Affiliates", "post type general name"),
    "singular_name" => _x("Affiliate", "post type singular name"),
    "add_new" => _x("Add New", "book"),
    "add_new_item" => __("Add New Affiliate"),
    "edit_item" => __("Edit Affiliate"),
    "new_item" => __("New Affiliate"),
    "all_items" => __("All Affiliates"),
    "view_item" => __("View Affiliates"),
    "search_items" => __("Search Affiliates"),
    "not_found" => __("No affiliates found"),
    "not_found_in_trash" => __("No affiliates found in the trash"),
    "parent_item_colon" => "",
    "menu_name" => "Affiliates"
  );
  $args = array(
    "labels" => $labels,
    "description" => "Contains featured front-page links.",
    "public" => false,
    "menu_position" => 7,
    "supports" => array("title", "custom-fields"),
    "has_archive" => false,
    "show_ui" => true,
    "show_in_menu" => true
  );
  register_post_type("affiliates", $args);
}
add_action("init", "bb_affiliates_post_type");