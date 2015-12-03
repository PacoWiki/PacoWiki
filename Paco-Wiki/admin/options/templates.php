<h2>Template Options</h2>
<div>
	<label class="desc" for="field-template">
		Choose Template
	</label>
	<div>
		<select id="field-template" name="pacowiki_options[field-template]" class="field select medium" tabindex="11"> 
                <?php
					$active_template = get_option( 'pacowiki_template' );
                    $templates_folder_dir = PACOWIKI_PLUGIN_PATH . '/templates';
                    if ( $templates_dir = opendir( $templates_folder_dir ) ) {
                            while ( false !== ( $template = readdir($templates_dir) ) ) {
                                    if('.' != $template && '..' != $template) {
                                        $selected = ($template == $active_template) ? 'selected="selected"' : '';
                                        echo '<option value="' . esc_attr($template) . '"'. $selected .'>' . esc_attr($template) . '</option>';
                                    }
                            }
                    }
                    closedir($templates_dir);
                ?>
		</select>
	</div>
</div>
<div>
	<fieldset>
		<legend id="title5" class="desc">
			Load Default Styling
		</legend>
		<div class="on-off-checkbox-container">
				<input type="checkbox" class="on-off-checkbox" value="1" <?php echo (PacoWiki()->get_option('pacowiki_load_styles') ? ' checked=""' : ''); ?> id="pacowiki_load_styles" name="pacowiki_options[pacowiki_load_styles]">
			<label for="pacowiki_load_styles">
			</label>
		</div>
	</fieldset>
</div>  

<h2>Custom CSS</h2>
<div>
	<p>the css code bellow will only be added to wiki posts.</p>
	<textarea rows="6" name="pacowiki_options[pacowiki_custom_css]"><?php echo PacoWiki()->get_option('pacowiki_custom_css'); ?></textarea>
</div> 

<h2>Custom JavaScript</h2>
<div>
	<p>the JavaScript code bellow will only be added to wiki post's header.</p>
	<textarea rows="6" name="pacowiki_options[pacowiki_custom_js]"><?php echo PacoWiki()->get_option('pacowiki_custom_js'); ?></textarea>
</div>