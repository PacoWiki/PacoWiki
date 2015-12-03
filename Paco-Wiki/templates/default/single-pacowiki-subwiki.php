<?php
/**
 * The Template for displaying Single WIKI Pages
 */
global $wp_pacowiki, $post;
get_header( 'pacowiki' );
?>
<div id="primary" class="content-area wiki-area">
    <div id="content" class="site-content wiki-content" role="main">
        <h1 class="entry-title"><?php the_title(); ?></h1>
			<ul class="wiki-nav">
			<?php 
			$nav = PacoWiki()->get_wiki_tabs();
			foreach($nav as $key => $nav_item){
				echo '<li class="' . $nav_item['class'] . '-item"><a class="' . $nav_item['class'] . '-link" href="' . $nav_item['url'] . '">' . $nav_item['text'] . '</a></li>';
			}
			?>
			<ul class="wiki-nav">
		<?php 
			if ( !post_password_required() ) {
				$revision_id = isset($_REQUEST['revision'])?absint($_REQUEST['revision']):0;
				$left        = isset($_REQUEST['left'])?absint($_REQUEST['left']):0;
				$right       = isset($_REQUEST['right'])?absint($_REQUEST['right']):0;
				$action      = isset($_REQUEST['action'])?$_REQUEST['action']:'view';

				if ($action == 'discussion') {
				   comments_template( '', true );
				} else {
					//echo $wp_pacowiki->decider(apply_filters('the_content', $post->post_content), $action, $revision_id, $left, $right, false);
					get_template_part( 'content', get_post_format() );
				}
        ?>
		<?php } ?>
    </div>
</div>
<?php
get_sidebar('pacowiki');
get_sidebar();
get_footer('pacowiki'); 
?>