<h2>WIKI Posts</h2>
<div>
	<label class="desc" for="pacowiki_post_slug">Slug</label>
	<div>
		<input id="pacowiki_post_slug" name="pacowiki_options[pacowiki_post_slug]" type="text" class="field text fn" value="<?php echo PacoWiki()->get_option('pacowiki_post_slug'); ?>" size="8" tabindex="1">
	</div>
</div>
<div>
	<label class="desc">Allow Hierarchical Pages(Sub-Wikis)</label>
	<div class="on-off-checkbox-container">
			<input type="checkbox" class="on-off-checkbox" value="1" <?php echo (PacoWiki()->get_option('pacowiki_hierarchical') ? ' checked=""' : ''); ?> id="pacowiki_hierarchical" name="pacowiki_options[pacowiki_hierarchical]">
		<label for="pacowiki_hierarchical">
		</label>
	</div>
</div>
<div>
	<fieldset>
		<legend id="title5" class="desc">
			Enable Excerpt in lists and archives
		</legend>
		<div class="on-off-checkbox-container">
				<input type="checkbox" class="on-off-checkbox" value="1" <?php echo (PacoWiki()->get_option('pacowiki_enable_excerpt') ? ' checked=""' : ''); ?> id="pacowiki_enable_excerpt" name="pacowiki_options[pacowiki_enable_excerpt]">
			<label for="pacowiki_enable_excerpt">
			</label>
		</div>
	</fieldset>
</div>
<div>
	<label class="desc">Exclude from search results</label>
	<div class="on-off-checkbox-container">
			<input type="checkbox" class="on-off-checkbox" value="1" <?php echo (PacoWiki()->get_option('pacowiki_exclude_from_search') ? ' checked=""' : ''); ?> id="pacowiki_exclude_from_search" name="pacowiki_options[pacowiki_exclude_from_search]">
		<label for="pacowiki_exclude_from_search">
		</label>
	</div>
</div>
<h2>Categories</h2>
<div>
	<label class="desc">Enable Categories</label>
	<div class="on-off-checkbox-container">
			<input type="checkbox" class="on-off-checkbox" value="1" <?php echo (PacoWiki()->get_option('pacowiki_category_enable') ? ' checked=""' : ''); ?> id="pacowiki_category_enable" name="pacowiki_options[pacowiki_category_enable]">
		<label for="pacowiki_category_enable">
		</label>
	</div>
</div>
<div>
	<label class="desc">Category Slug</label>
	<div>
		<input id="category-slug" name="pacowiki_options[pacowiki_category_slug]" type="text" class="field text fn" value="<?php echo PacoWiki()->get_option('pacowiki_category_slug'); ?>" size="8" tabindex="1">
	</div>
</div>
<h2>Tags</h2>
<div>
	<label class="desc">Enable Tags</label>
	<div class="on-off-checkbox-container">
			<input type="checkbox" class="on-off-checkbox" value="1" <?php echo (PacoWiki()->get_option('pacowiki_tag_enable') ? ' checked=""' : ''); ?> id="pacowiki_tag_enable" name="pacowiki_options[pacowiki_tag_enable]">
		<label for="pacowiki_tag_enable">
		</label>
	</div>
</div>
<div>
	<label class="desc">Tag Slug</label>
	<div>
		<input id="pacowiki_tag_slug" name="pacowiki_options[pacowiki_tag_slug]" type="text" class="field text fn" value="<?php echo PacoWiki()->get_option('pacowiki_tag_slug'); ?>" size="8" tabindex="1">
	</div>
</div>
<div>
	<label class="desc">Include in Cloud Tags</label>
	<div class="on-off-checkbox-container">
			<input type="checkbox" class="on-off-checkbox" value="1" <?php echo (PacoWiki()->get_option('pacowiki_tag_tagcloud') ? ' checked=""' : ''); ?> id="pacowiki_tag_tagcloud" name="pacowiki_options[pacowiki_tag_tagcloud]">
		<label for="pacowiki_tag_tagcloud">
		</label>
	</div>
</div>