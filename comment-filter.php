<?php

/*--------- Comment structure with Foundation 6 XY Grid markup -----------*/

function ally_form_before() {
	echo '<div class="grid-x"><div class="small-12 cell">';
}
add_action('comment_form_before', 'ally_form_before');
	
function ally_form_after() {
	echo '</div></div>';
}
add_action('comment_form_after', 'ally_form_after');

function ally_comment_form($defaults, $post_id = null) {
	if ( null === $post_id )
        $post_id = get_the_ID();
	
	$commenter = wp_get_current_commenter();
	$user = wp_get_current_user();
	$user_identity = $user->exists() ? $user->display_name : '';
	$args = NULL;
	$args = wp_parse_args( $args );
    if ( ! isset( $args['format'] ) )
        $args['format'] = current_theme_supports( 'html5', 'comment-form' ) ? 'html5' : 'xhtml';
	
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );
	$html_req = ( $req ? " required='required'" : '' );
	$html5    = 'html5' === $args['format'];
	$required_text = sprintf( ' ' . __('Required fields are marked %s'), '<span class="required">*</span>' );
	
	$fields = array(
	'author' => '<div class="grid-x grid-margin-x"><p class="comment-form-author small-12 medium-6 large-6 cell right-pad">' . '<label for="author">' . __( 'Name' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
	'<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" maxlength="245"' . $aria_req . $html_req . ' placeholder="Name" /></p>',
	'email'  => '<p class="comment-form-email small-12 medium-6 large-6 cell left-pad"><label for="email">' . __( 'Email' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
	'<input id="email" name="email" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" maxlength="100" aria-describedby="email-notes"' . $aria_req . $html_req  . ' placeholder="Email" /></p></div>',
	'url'    => '<div class="grid-x"><p class="comment-form-url small-12 medium-6 medium-offset-3 cell"><label for="url">' . __( 'Website' ) . '</label> ' .
	'<input id="url" name="url" ' . ( $html5 ? 'type="url"' : 'type="text"' ) . ' value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" maxlength="200" placeholder="Website" /></p></div>',
	);
	
	$fields = apply_filters( 'comment_form_default_fields', $fields );
    $defaults = array(
        'fields'               => $fields,
        'comment_field'        => '<p class="comment-form-comment small-12 cell"><label for="comment">' . _x( 'Comment', 'noun' ) . '</label> <textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525" aria-required="true" required="required" placeholder="What would you like to say?"></textarea></p>',
		'title_reply'          => '<i class="fas fa-comments"></i> Leave a Comment',
		'submit_field'         => '<p class="form-submit small-12 cell">%1$s %2$s</p>',
		'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title cell">',
		'title_reply_after'    => '</h3>',
		'comment_notes_before' => '<p class="comment-notes cell"><span id="email-notes">' . __( 'Your email address will not be published.' ) . '</span>'. ( $req ? $required_text : '' ) . '</p>',
		'logged_in_as'         => '<p class="logged-in-as">' . sprintf(
                                      /* translators: 1: edit user link, 2: accessibility text, 3: user name, 4: logout URL */
                                      __( 'Logged in as <a href="%1$s" aria-label="%2$s" title="%3$s">%3$s</a>. <a href="%4$s" title="Log out of this account" class="logging-out">Log out</a>?' ),
                                      get_edit_user_link(),
                                      /* translators: %s: user name */
                                      esc_attr( sprintf( __( 'Logged in as %s. Edit your profile.' ), $user_identity ) ),
                                      $user_identity,
                                      wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) )
                                  ) . '</p>',
	);
	
	return $defaults;
}

add_filter('comment_form_defaults', 'ally_comment_form');