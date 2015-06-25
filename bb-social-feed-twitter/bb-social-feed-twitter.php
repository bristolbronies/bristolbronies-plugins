<?php
/*
Plugin Name: Bristol Bronies social feed - Twitter
Plugin URI: http://bristolbronies.co.uk/
Description: Pull down our most recent tweets. 
Author: Kimberly Grey
Author URI: http://greysadventures.com/
*/

require_once "libs/TwitterAPIExchange.class.php";

function bb_tweet_feed($account, $limit = 5) { 
	$cache_file = __DIR__ . "/cache/tweetcache";
	if(file_exists($cache_file) && time() - 1800 < filemtime($cache_file)) {
		include($cache_file);
	}
	else {
		ob_start();
		$settings = array(
			"oauth_access_token" => TWITTER_OAUTH_TOKEN,
			"oauth_access_token_secret" => TWITTER_OAUTH_SECRET,
			"consumer_key" => TWITTER_CONSUMER_KEY,
			"consumer_secret" => TWITTER_CONSUMER_SECRET
		);
		$url = "https://api.twitter.com/1.1/search/tweets.json";
		$options = "?result_type=recent&q=from%3A" . $account;
		if($limit > 0) {
			$options .= "&count=" . $limit;
		}
		$method = "GET";
		$twitter = new TwitterAPIExchange($settings);
		$data = json_decode($twitter->setGetfield($options)->buildOauth($url, $method)->performRequest());
		$output = '<div class="social-feed">';
		foreach($data->statuses as $status) {
			$output .= '<div class="social-feed__item social-feed__item--twitter">'
			         . '<div class="social-feed__body">'
			         . '<a class="social-feed__permalink" href="https://twitter.com/' . $status->user->screen_name . '/status/' . $status->id_str . '">'
			         . '<time data-timeago class="social-feed__timestamp" datetime="' . date("c", strtotime($status->created_at)) . '" title="' . date("c", strtotime($status->created_at)) . '">' . date("Y-m-d H:i:s", strtotime($status->created_at)) . '</time>'
			         . '</a>'
			         . '<div class="content social-feed__content">' . $status->text . '</div>'
			         . '</div>'
			         . '</div>';
		}
		$output .= '</div>';
		echo $output;
		$file_handler = fopen($cache_file, 'w');
		fwrite($file_handler, ob_get_contents());
		fclose($file_handler);
		ob_end_flush();
	}
}