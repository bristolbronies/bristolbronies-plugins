<?php
/**
 * @package Hello_Brony
 * @version 1.0
 */
/*
Plugin Name: Hello Brony
Plugin URI: http://bristolbronies.co.uk/
Description: Outputs dumb quotes to do with ponies on the WP backend. (Inspired by the default Hello Dolly plugin.)
Author: Matt Mullenweg / Kimberly Grey
Version: 1.0
Author URI: http://ma.tt/
*/

function hello_brony_messages() {
	$messages = "You can't have a nightmare if you never dream.
Needs to be about twenty percent cooler.
yay.
I really like her mane!
I'LL DESTROY HER!
Clock is ticking, Twilight. Clock. Is. Ticking!
Betcha can't make a face crazier than this!
Oatmeal are you crazy?!
Ah-ha! The fun has been doubled!
You're the most basic of jokes.
What's soaking wet and clueless? YOUR FACE!
I just don't know what went wrong!
Maybe it's just a friendship problem, and it'll all be cleared up in half an hour or so.
What is a cutie mark but a constant reminder that we're all only one bugbear attack away from oblivion?
Prepare yourselves, everypony! Winter is coming!";

	// Here we split it into lines
	$messages = explode( "\n", $messages );

	// And then randomly choose a line
	return wptexturize( $messages[ mt_rand( 0, count( $messages ) - 1 ) ] );
}

// This just echoes the chosen line, we'll position it later
function hello_brony() {
	$chosen = hello_brony_messages();
	echo "<p id='hello_brony'>$chosen</p>";
}

// Now we set that function up to execute when the admin_notices action is called
add_action( 'admin_notices', 'hello_brony' );

// We need some CSS to position the paragraph
function brony_css() {
	// This makes sure that the positioning is also good for right-to-left languages
	$x = is_rtl() ? 'left' : 'right';

	echo "
	<style type='text/css'>
	#hello_brony {
		float: $x;
		padding-$x: 15px;
		padding-top: 5px;		
		margin: 0;
		font-size: 11px;
	}
	</style>
	";
}

add_action( 'admin_head', 'brony_css' );

?>
