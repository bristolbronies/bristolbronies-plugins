<?php
/*
Plugin Name: Bristol Bronies news post type
Plugin URI: http://bristolbronies.co.uk/
Description: NEWS. 
Author: Kimberly Grey
Author URI: http://greysadventures.com/
*/

/**
 * News post type
 */

function bb_news_post_type() {
  $labels = array(
    'name' => _x('News', 'post type general name'),
    'singular_name' => _x('News', 'post type singular name'),
    'add_new' => _x('Add New', 'book'),
    'add_new_item' => __('Add News'),
    'edit_item' => __('Edit News'),
    'new_item' => __('New News'),
    'all_items' => __('All News'),
    'view_item' => __('View News'),
    'search_items' => __('Search News'),
    'not_found' => __('No posts found'),
    'not_found_in_trash' => __('No posts found in the trash'),
    'parent_item_colon' => '',
    'menu_name' => 'News'
  );
  $args = array(
    'labels' => $labels,
    'description' => 'Newsy bloggy thing.',
    'public' => true,
    'menu_position' => 7,
    'supports' => array('title', 'editor', 'thumbnail', 'revisions', 'author', 'comments'),
    'has_archive' => true
  );
  register_post_type('news', $args);
}
add_action('init', 'bb_news_post_type');

/**
 * News comments output
 */

function bb_comment_formatter($comment, $args, $depth) {
  $GLOBALS['comment'] = $comment;
?>
  <li class="comment" id="comment:<?php comment_ID(); ?>">
    <article>
      <footer class="comment__meta">
        <?php echo get_avatar($comment->comment_author_email, 34); ?>
        Written by <strong class="comment__author"><?php echo get_comment_author(); ?></strong>
        on 
        <a class="comment-permalink" href="#comment:<?php echo $comment->comment_ID; ?>">
          <time datetime="<?php echo get_comment_date("c"); ?>">
            <?php echo get_comment_date("jS F Y, g:ia"); ?>
          </time>
        </a>
      </footer>
      <div class="comment__body">
        <?php comment_text(); ?>
      </div>
    </article>
  </li>
<?php
}

function bb_comment_fields() {
  $fields['author'] = '
    <p class="comment-form__author">
      <label for="comment-form__author" class="hidden">Name</label>
      <input type="text" id="comment-form__author" name="author" placeholder="Name" value="' . esc_attr( $commenter['comment_author'] ) .
    '" aria-required="true" required>
    </p>
  ';
  $fields['email'] = '
    <p class="comment-form__email">
      <label for="comment-form__email" class="hidden">Email</label>
      <input type="email" id="comment-form__email" name="email" placeholder="Email " value="' . esc_attr( $commenter['comment_author_email'] ) .
    '" aria-required="true" required>
    </p>
  ';
  return $fields;
}

function bb_comment_form() {
  $form = '
    <p class="comment-form__comment">
      <label for="comment-form__comment" class="hidden">Comment</label>
      <textarea id="comment-form__comment" name="comment" cols="48" rows="5" placeholder="Type your comment here. Don\'t be a dick." aria-required="true" required></textarea>
    </p>
  ';
  return $form;
}