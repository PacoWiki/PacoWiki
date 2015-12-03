<?php
/**
 * The Template for displaying Single WIKI Pages
 */
if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly
	
global $wp_pacowiki, $post;
get_header( 'pacowiki' );
?>
<div id="primary" class="content-area wiki-area">
    <div id="content" class="site-content wiki-content" role="main">
			<ul class="wiki-nav">
			<?php 
			$nav = PacoWiki()->get_wiki_tabs();
			foreach($nav as $key => $nav_item){
				echo '<li class="wiki-item ' . $nav_item['class'] . '-item"><a class="' . $nav_item['class'] . '-link" href="' . $nav_item['url'] . '">' . $nav_item['text'] . '</a></li>';
			}
			?>
			</ul>
			
			<header class="entry-header">
				<h1 class="entry-title"><?php the_title(); ?></h1>
			</header>
			
			<?php 
				if ( post_password_required() ){  
					?> <p id="comments-locked">Enter your password to view comments.</p><?php
					return;
				}
				if ( ! comments_open() ) {
					?> <p id="comments-locked">Discussion on this wiki is not disabled.</p><?php
					return;
				}else{
					
						?>
						<div id="comments" class="comments-area">
							<ol class="commentlist">
								<?php 
								
								$comments = get_comments(array(
									'status' => 'approve'
								));
								wp_list_comments(
									array( 'callback' => 'twentytwelve_comment', 'style' => 'ol' )
									, $comments
								);
								
								
								?>
							</ol>
						</div>
							<?php 

						$commenter = wp_get_current_commenter();

						$comment_form = array(
							'title_reply'          => (count($comments)>0) ? __( 'Join the conversation', 'pacowiki' ) : __( 'Be the first to start a discussion on ', 'pacowiki' ) . ' &ldquo;' . get_the_title() . '&rdquo;',
							'title_reply_to'       => __( 'Leave a Reply to %s', 'pacowiki' ),
							'comment_notes_before' => '',
							'comment_notes_after'  => '',
							'fields'               => array(
								'author' => '<p class="comment-form-author">' . '<label for="author">' . __( 'Name', 'pacowiki' ) . ' <span class="required">*</span></label> ' .
											'<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" aria-required="true" /></p>',
								'email'  => '<p class="comment-form-email"><label for="email">' . __( 'Email', 'pacowiki' ) . ' <span class="required">*</span></label> ' .
											'<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" aria-required="true" /></p>',
							),
							'label_submit'  => __( 'Submit', 'pacowiki' ),
							'logged_in_as'  => '',
							'comment_field' => ''
						);

						$comment_form['comment_field'] .= '<p class="comment-form-comment"><label for="comment">' . __( 'Your Comment', 'pacowiki' ) . '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>';

						comment_form( $comment_form );
					
				}
			?>		
    </div>
</div>
<?php
get_sidebar('pacowiki');
get_sidebar();
get_footer('pacowiki'); 
?>