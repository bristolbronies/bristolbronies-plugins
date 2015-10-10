<?php

/**
 * Return the list of categories this meet is filed under.
 * @param  int    $post_id The meet's post ID.
 * @param  string $format  What kind of data to return (category name, category URL slug).
 * @return array           Array of the requested data.
 */
function bb_meet_category($post_id, $format = 'name') {
	$categories = get_the_category($post_id);
	$output = array();
	foreach($categories as $category) {
		switch($format) {
			case 'name': 
				$output[] = $category->name;
				break;
			case 'slug':
				$output[] = $category->slug;
				break;
		}
	}
	return $output;
}

/**
 * Return the HTML formatted start and end dates for a meet as an HTML5 time element.
 * @param  string $start The start date timestamp.
 * @param  mixed  $end   The end date timestamp, set to false to not output this at all.
 * @return string        HTML formatted start and end dates.
 */
function bb_meet_dates($start, $end = false) {
	$output = '<time datetime="'.date("c", $start).'">'.date("jS F Y, g:ia", $start).'</time>';
	if($end) {
		$output .= "&ndash;";
		if(date("Ymd", $start) === date("Ymd", $end)) {
			$output .= '<time datetime="'.date("c", $end).'">'.date("g:ia", $end).'</time>';
		}
		else {
			$output .= '<time datetime="'.date("c", $end).'">'.date("jS F Y, g:ia", $end).'</time>';
		}
	}
	return $output;
}

/**
 * Get meet location information.
 * @param  int    $id     The meet's post ID.
 * @param  string $format What kind of data to return (human readable address, latitude, longitude, latlng)
 * @return string         The requested data in the selected format.
 */
function bb_meet_location($id, $format = 'address') {
	$name = get_the_title($id[0]);
	$data = get_field("location_address", $id[0]);
	switch($format) {
		case 'name':
			$output = $name;
			break;
		case 'address':
			$output = $name . ', ' . $data['address'];
			break;
		case 'latitude':
			$output = $data['lat']; 
			break;
		case 'longitude':
			$output = $data['lng'];
			break;
		case 'latlng':
			$output = $data['lat'] . ',' . $data['lng'];
			break;
	}
	return $output;
}


/**
 * Get a meet runner's avatar from their user ID
 * @param  int    $id The post ID.
 * @return string     The URL for their avatar image.
 */
function bb_profile_avatar($id) {
	if($image = get_field("runner_avatar", $id)) {
		return $image;
	}
	else {
		return "http://1.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=96";
	}
}

/**
 * Get a meet runner's biography from their user ID 
 * @param  int    $id The post ID.
 * @return string     Their biography contents as HTML.
 */
function bb_profile_biography($id) {
	$content_post = get_post($id);
	$content = $content_post->post_content;
	$content = apply_filters('the_content', $content);
	$content = str_replace(']]>', ']]&gt;', $content);
	return $content;
}