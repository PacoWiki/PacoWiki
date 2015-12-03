<?php
/**
 * The Template for displaying Single WIKI Pages
 */
global $wp_pacowiki, $post;
get_header( 'pacowiki' );
?>
<div id="primary" class="content-area wiki-area">
    <div id="content" class="site-content wiki-content" role="main">
	<!--
		<form class="alignleft" method="get" name="filter-bar">
		<label for="orderby">Sort by:</label>
		<div>
			<select title="Select one" name="orderby" class="select sort">
				<option value="date">Most Recent</option>
				<option value="comment_count">Most Commented</option>
				<option value="modified">Last Modified</option>
				<option value="title">Alphabetical</option>
			</select>
		</div>
		<input type="hidden" value="" name="s">
		<button class="button" type="submit">Filter</button>
	</form>
	-->
		<?php 
		if ( have_posts() )
			while ( have_posts() ){
				the_post();
				get_template_part( 'content', get_post_format() );
			}
		
		?>
    </div>
</div>
<?php
get_sidebar('pacowiki');
get_sidebar();
get_footer('pacowiki'); 
?>