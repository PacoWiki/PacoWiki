<?php
/**
 * The Template for displaying Single WIKI Pages
 */
if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly
	
global $wp_pacowiki, $post;
get_header( 'pacowiki' );
?>

<?php

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
			require_once(ABSPATH . "wp-admin/includes/image.php");
			require_once(ABSPATH . "wp-admin/includes/file.php");
			require_once(ABSPATH . "wp-admin/includes/media.php");
			
			echo '<form action="" id="primaryPostForm" method="POST">';
			$title = get_the_title( $post );
			$post_title = '<a href="' . get_edit_post_link() . '">' . $title . '</a>';
			echo '<input type="text" name="post_title" id="post_title" value="'.$post->post_title.'" class="pacowiki-edit-title" size="30" />';
			wp_editor( $post->post_content, 'pacowiki_editor', array('media_buttons' => true, 'textarea_rows' => 16));
			
			wp_nonce_field( 'pacowiki_nonce', 'pacowiki_nonce_field' );
	 
			echo '<input type="hidden" name="submitted" id="submitted" value="true" />';
			echo '<button type="submit">'. __( 'Save Post', 'pacowiki' ) .'</button>';
			
			echo '</form>';
	 
		?>
    </div>
</div>
<?php
get_sidebar('pacowiki');
get_sidebar();
get_footer('pacowiki'); 
?>