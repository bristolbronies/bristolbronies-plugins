<?php
/*
Plugin Name: Bristol Bronies social feed
Plugin URI: http://bristolbronies.co.uk/
Description: Pull down our most recent tweets and Facebook posts. 
Author: Kimberly Grey
Author URI: http://greysadventures.com/
*/

require_once "libs/TwitterAPIExchange.class.php";

/**
 * Output social feed HTML. Cache it and only allow it to be refreshed if it's more than half an hour old.
 * @param  string  $twitterAccount  The username for the Twitter account (e.g. BristolBronies)
 * @param  string  $facebookAccount The ID for the Facebook account (e.g. 443693042356765)
 * @param  integer $limit           The maximum number of posts to output.
 */
function bb_social_feed($twitterAccount = "", $facebookAccount = "", $limit = 5) {
	$cache_file = __DIR__ . "/cache/socialcache";
	if(file_exists($cache_file) && time() - 1800 < filemtime($cache_file)) {
		include($cache_file);
	}
	else {
		ob_start();

		$timestamp = array();
		$posts = array_merge(bb_tweet_feed($twitterAccount, $limit), bb_facebook_feed($facebookAccount));
		foreach($posts as $key => $row) {
			$timestamp[$key] = $row["timestamp"];
		}
		array_multisort($timestamp, SORT_DESC, $posts);

		$counter = 0;
		$output = '<div class="social-feed">';
		foreach($posts as $status) {
			if($counter >= $limit) { break; }
			$output .= '
			<div class="social-feed__item social-feed__item--' . $status["source"] .'">
				<div class="social-feed__body">
					<a class="social-feed__permalink" href="' . $status["permalink"] . '">
						<time data-timeago class="social-feed__timestamp" datetime="' . date("c", $status["timestamp"]) . '" title="' . date("c", $status["timestamp"]) . '">' . date("Y-m-d H:i:s", $status["timestamp"]) . '</time>
					</a>
					<div class="content social-feed__content">' . $status["content"] . '</div>
				</div>
			</div>';
			$counter++;
		}
		$output .= '</div>';

		echo $output;

		$file_handler = fopen($cache_file, 'w');
		fwrite($file_handler, ob_get_contents());
		fclose($file_handler);
		ob_end_flush();
	}
}

/**
 * Pulls the most recent tweets from the specified Twitter account.
 * @param  string  $account The username of the Twitter account.
 * @param  integer $limit   The number of tweets to query for.
 * @return array            An array of tweets.
 */
function bb_tweet_feed($account, $limit = 15) { 
	$settings = array(
		"oauth_access_token" => TWITTER_OAUTH_TOKEN,
		"oauth_access_token_secret" => TWITTER_OAUTH_SECRET,
		"consumer_key" => TWITTER_CONSUMER_KEY,
		"consumer_secret" => TWITTER_CONSUMER_SECRET
	);
	$url = "https://api.twitter.com/1.1/search/tweets.json";
	$options = "?q=from%3A" . $account . "&count=" . $limit;
	$method = "GET";
	$twitter = new TwitterAPIExchange($settings);
	$data = json_decode($twitter->setGetfield($options)->buildOauth($url, $method)->performRequest());
	$output = array();
	foreach($data->statuses as $status) {
		$output[] = array(
			"source" => "twitter",
			"permalink" => "https://twitter.com/" . $status->user->screen_name . "/status/" . $status->id_str,
			"timestamp" => strtotime($status->created_at),
			"content" => bb_content_parse($status->text)
		);
	}
	return $output;
}

/**
 * Pulls the most recent posts from the specified Facebook page.
 * @param  string $account The ID of the Facebook page.
 * @return array           An array of posts.
 */
function bb_facebook_feed($account) {
	$data  = file_get_contents("https://graph.facebook.com/" . $account . "/posts?access_token=" . FACEBOOK_ACCESS_TOKEN);
	$data = json_decode($data, true);
	$data = $data["data"];
	$output = array();
	foreach($data as $status) {
		switch($status["type"]) {
			case "photo":
			case "video":
			case "link":
				$content = $status["message"] . " " . $status["link"];
				break;
			case "event":
				$content = $status["description"] . " " . $status["link"];
				break;
			default: 
				$content = $status["message"];
				break;
		}
		$id = explode("_", $status["id"]);
		$output[] = array(
			"source" => "facebook",
			"permalink" => "https://www.facebook.com/" . $id[0] . "/posts/" . $id[1],
			"timestamp" => strtotime($status["updated_time"]),
			"content" => bb_content_parse($content)
		);
	}
	return $output;
}

/**
 * Parses post content to add links and somesuch.
 * @param  string $content The content to be parsed.
 * @return string          The parsed content, now with HTML added.
 */
function bb_content_parse($content) {
	$find = array(
		'/((?:[\w\d]+\:\/\/)?(?:[\w\-\d]+\.)+[\w\-\d]+(?:\/[\w\-\d]+)*(?:\/|\.[\w\-\d]+)?(?:\?[\w\-\d]+\=[\w\-\d]+\&?)?(?:\#[\w\-\d]*)?)/', // URLs
		'/@([a-z0-9_]+)/i' // Usernames
	);
	$replace = array(
		'<a href="$1">$1</a>', // URLs
		'<a href="https://twitter.com/$1">@$1</a>' // Usernames
	);
	$content = preg_replace($find, $replace, $content);
	return $content; 
}