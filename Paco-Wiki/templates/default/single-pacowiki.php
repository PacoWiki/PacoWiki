<?php
/**
 * The Template for displaying Single WIKI Pages
 */
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
			if ( !post_password_required() ) {
				
				$content = $post->post_content;
				
				if(PacoWiki()->get_option('pacowiki_show_table_of_contents')){
					$result =  PacoWiki()->get_table_of_contents($content);
					$table_of_contents = $result['toc'];
					$content = $result['content'];
					if( $table_of_contents ){
						?>
						<div class="wiki-table-of-contents">
							<span class="wiki-table-of-contents-header">
								<strong><?php _e( 'Contents', 'pacowiki' ); ?></strong>
								<span class="wiki-toggle-table-of-contents"><a id="wiki-toggle-table-of-contents-link" showhide="<?php _e( 'Show', 'pacowiki' ); ?>" onclick="wiki_toggle_content_table(event);" href="#"><?php _e( 'Hide', 'pacowiki' ); ?></a></span>
							</span>
						<?php
						
							echo $table_of_contents;

						?>
						</div>
						<?php
					}
				}
				echo $content;

			}
		?>
    </div>
</div>
<?php
get_sidebar('pacowiki');
get_sidebar();
get_footer('pacowiki'); 
?>