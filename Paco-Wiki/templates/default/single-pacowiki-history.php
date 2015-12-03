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
		<?php 

		$title = get_the_title( $post );
		if ( empty( $title ) )
			$title = __( '(no title)' );
		$post_title = '<a href="' . get_edit_post_link() . '">' . $title . '</a>';
		$h1 = sprintf( __( 'Compare Revisions of &#8220;%1$s&#8221;' ), $post_title );
		?>
		<header class="entry-header">
			<h1 class="entry-title"><?php echo $h1; ?></h1>
		</header>
		<div class="entry-content">
		<?php
		$revisions = wp_get_post_revisions( $post->ID );
		$revision_id = @$_REQUEST['revision'];
		$old_revision = @$_REQUEST['old'];
		$new_revision = @$_REQUEST['new'];
		$view_revision = @$_REQUEST['view_revision'];
		if($revision_id){
		// Compare revision with current post
			require_once PACOWIKI_PLUGIN_PATH . 'library/Diff.php';
			require_once PACOWIKI_PLUGIN_PATH . 'library/Diff/Renderer/Html/Inline.php';

			$revision = wp_get_post_revision( $revision_id );
			$new_content = explode("\n", $post->post_content);
			$old_content = explode("\n", $revision->post_content );
			// Options for generating the diff
			$options = array(
				'ignoreWhitespace' => false,
				'ignoreCase' => false,
			);
			// Initialize the diff class
			$diff = new Diff($new_content, $old_content, $options);
			$renderer = new Diff_Renderer_Html_Inline;
			$diff_output = $diff->Render($renderer);
			if(empty($diff_output))
				echo '<p>' . __( 'No changes were found in post content of this revision!', 'pacowiki' ). '</p>';
			else
				echo $diff_output;
		}elseif($old_revision){
		// Compare two different revisions
			if(! $new_revision){
				$new_revision = $post->ID;
				$new_title = $post->post_title;
				$new_content = explode("\n", $post->post_content);
			}else{
				$new_post = get_post($new_revision); 
				$new_title = $new_post->post_title;
				$new_content = explode("\n", $new_post->post_content);
			}
			$old_post = get_post($old_revision); 
			$old_title = $old_post->post_title;
			$old_content = explode("\n", $old_post->post_content );
			require_once PACOWIKI_PLUGIN_PATH . 'library/Diff.php';
			require_once PACOWIKI_PLUGIN_PATH . 'library/Diff/Renderer/Html/Inline.php';
			require_once PACOWIKI_PLUGIN_PATH . 'library/Diff/Renderer/Html/SideBySide.php';
			$options = array(
				'ignoreWhitespace' => false,
				'ignoreCase' => false,
			);
			// Initialize the diff class for title
			$diff = new Diff(array($new_title), array($old_title), $options);
			$renderer = new Diff_Renderer_Html_SideBySide;
			$diff_output = $diff->Render($renderer);
			if(empty($diff_output))
				echo '<p>' . __( 'No changes were found in wiki Title!', 'pacowiki' ). '</p>';
			else{
				echo '<h2>Title:</h2>';
				echo $diff_output;
			}
				
			$diff = new Diff($new_content, $old_content, $options);
			$renderer = new Diff_Renderer_Html_Inline;
			$diff_output = $diff->Render($renderer);
			if(empty($diff_output))
				echo '<p>' . __( 'No changes were found in wiki content!', 'pacowiki' ). '</p>';
			else{
				echo '<h2>Content:</h2>';
				echo $diff_output;
			}
		}elseif($view_revision){
		// View an old revision
		?>
			<header class="entry-header">
				<h1 class="entry-title"><?php the_title(); ?></h1>
			</header>
		<?php
				$old_post = get_post($view_revision); 
				$content = $old_post->post_content;
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
		}else{
			if ( count( $revisions ) > 1 ) {
				?>
				<form action="<?php echo add_query_arg('action', 'history', get_permalink()); ?>" method="post">
				<table class="pacowiki-revision-table">
					<tr>
						<th><?php _e( 'Old', 'pacowiki' );?></th>
						<th><?php _e( 'New', 'pacowiki' );?></th>
						<th><?php _e( 'Title', 'pacowiki' );?></th>
						<th><?php _e( 'Author', 'pacowiki' );?></th>
						<th><?php _e( 'Date', 'pacowiki' );?></th>
						<th><?php _e( 'Action', 'pacowiki' );?></th>
					</tr>
				<?php
				foreach($revisions as $revision){
					$id = $revision->ID;
					$time_ago = human_time_diff(get_post_time('G', true, $revision), current_time('timestamp') );
					$date = wp_post_revision_title( $revision, false );
					$author = get_the_author_meta( 'display_name', $revision->post_author );
					$author_url = get_the_author_meta( 'user_url', $revision->post_author );
					$avatar = get_avatar( $revision->post_author, 24, false, $author ); 
					$title = $revision->post_title;
					$permalink = get_permalink();
					$revision_url = add_query_arg(array( 'action' => 'history', 'revision' => $revision->ID ), $permalink);
					$view_revision_url = add_query_arg(array( 'action' => 'history', 'view_revision' => $revision->ID ), $permalink);
					$restore_url = wp_nonce_url(add_query_arg(array('revision' => $revision->ID), admin_url('revision.php')), $revision->ID. '_' . $post->ID);
					//echo '<p>' . sprintf( __( 'Edited %1$s ago by %2$s: <a href="%3$s">%4$s</a>', 'pacowiki' ),$date, $author, $revision_url, $title ) . '</p>';
				
				?>
					<tr>
						<td><input type='radio' name='old' value='<?php echo $id; ?>' /></td>
						<td><input type='radio' name='new' value='<?php echo $id; ?>' /></td>
						<td><a href="<?php echo $view_revision_url; ?>" title="<?php _e( 'View this revisions', 'pacowiki' ); ?>"><?php echo $title;?></a></td>
						<td><?php 
							if($author_url){
								echo '<a href="' . $author_url . '">';
								echo $avatar . $author;
								echo '</a>';
							}else
								echo $avatar . $author;
						?></td>
						<td><?php echo $time_ago. ' ' . __( 'ago', 'pacowiki' ). '(<a href="' . $revision_url . '" title="' . __( 'Compare with current version', 'pacowiki' ) . '">' .$date . '</a>)'; ?></td>
						<td><a href="<?php echo $restore_url; ?>"><?php _e( 'Restore', 'pacowiki' ); ?></a></td>
					</tr>
				<?php
				}
				?>
				</table>
				<input type="submit" class="button-secondary" value="<?php _e( 'Compare Revisions', 'pacowiki' ); ?>" />
				<input type="hidden" name="xxxxaction" value="history" />
				</form>
				<?php
			}else
				echo '<p>' . __( 'No revisions were found for this post!', 'pacowiki' ). '</p>';
		}
		?>	
		</div>
    </div>
</div>
<?php
get_sidebar('pacowiki');
get_sidebar();
get_footer('pacowiki'); 
?>