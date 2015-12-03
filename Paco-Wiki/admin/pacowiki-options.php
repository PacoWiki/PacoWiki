<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( ! class_exists( 'pacowiki_options' ) ){
	class pacowiki_options {
		public function output() {
			?>
				<div class="wraper">
					<h1>Options</h1>
					<form method="POST" action="options.php">
						<?php 
							settings_fields( 'pacowiki_option_fields' );
						?>
						<div id="option_tabs">
							<ul class="resp-tabs-list hor_1">
								<li>General</li>
								<li>Permissions</li>
								<li>Templates</li>
								<li>About</li>
							</ul>
							<div class="resp-tabs-container hor_1">
								<div>
									<?php
										include(PACOWIKI_PLUGIN_PATH . 'admin/options/general.php' );
									?>	
								</div>
								<div>
									<?php
										include(PACOWIKI_PLUGIN_PATH . 'admin/options/permission.php' );
									?>	
								</div>
								<div>
									<?php
										include(PACOWIKI_PLUGIN_PATH . 'admin/options/templates.php' );
									?>	
								</div>
								<div>
									<?php
										include(PACOWIKI_PLUGIN_PATH . 'admin/options/about.php' );
									?>	
								</div>
							</div>
						</div>
		<?php	submit_button( __('Save Settings'), 'primary', 'pacowiki-save-settings', false, null ); ?>
		</form>
		<form method="post" id="pacowiki-reset-form">
		<?php	submit_button( __('Reset Settings'), 'secondary', 'pacowiki-reset-settings', false, null ); ?>
		</form>

			<?php
		}
	}

}

return new pacowiki_options();