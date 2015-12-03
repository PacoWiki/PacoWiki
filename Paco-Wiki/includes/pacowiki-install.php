<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'PacoWiki_Install' ) ){
	class PacoWiki_Install {

		public function __construct() {
			// activation, deactivation, installation, and un-install
			register_activation_hook( PACOWIKI_PLUGIN_FILE , array( $this, 'activationHandler' ) );
			register_deactivation_hook( PACOWIKI_PLUGIN_FILE , array( $this, 'deactivate' ) );
			add_action( 'admin_init', array( $this, 'check_version' ), 5 );
			register_uninstall_hook( PACOWIKI_PLUGIN_FILE , array( 'PacoWiki_Install', 'uninstall' ) );

		}
		/*
		* 		 plugin activation tasks
		*/
		public function activationHandler() {
			// installation
			$db_version = PacoWiki()->get_option('pacowiki_version');
			//if(! $db_version) // if not installed
				$this->install();
			// set up user roles with add_role and add_cap 
			if ( function_exists( 'is_multisite' ) && is_multisite() ) {
				if ( $network_wide ) {
					// Get all blog ids
					$blog_ids = self::get_blog_ids();
					foreach ( $blog_ids as $blog_id ) {
						switch_to_blog( $blog_id );
						self::single_activate();
						restore_current_blog();
					}
				} else {
					self::single_activate();
				}
			} else {
				self::single_activate();
			}
		}

		/**
		* 		install for first time
		*/
		public function install() {
		// create tables
			// nothing yet, decided to use WP's revisions instead of using new data tables
				
		// Set options	
			// plugin
			$options['pacowiki_version']='1.0.0';
			// post types
			$options['pacowiki_post_slug']='pacowiki';
			$options['pacowiki_hierarchical'] = true;
			$options['pacowiki_exclude_from_search'] = false; // include posts in WP's default search
			$options['pacowiki_category_enable']=true;
			$options['pacowiki_category_slug']='pacowikicategories';
			$options['pacowiki_tag_enable']=true;
			$options['pacowiki_tag_slug']='pacowikitags';
			$options['pacowiki_tag_tagcloud']=true;
			// post
			$options['pacowiki_show_table_of_contents'] = true;
			$options['pacowiki_enable_excerpt'] = true;
			// template
			$options['pacowiki_template'] = 'default';
			$options['pacowiki_load_styles'] = true;
			// we don't have any custom css or js codes here so there is no need to load them
			// content of custom css and js codes are saved in a separate option fields(pacowiki_custom_css & pacowiki_custom_js) for size control
			$options['pacowiki_custom_css_enabled'] = false;
			$options['pacowiki_custom_js_enabled'] = false;
		// set up two default wiki roles
			remove_role( 'wiki_editor' );
			$result = add_role(
				'wiki_editor',
				__( 'Wiki Editor' ),
				array(
					'publish_posts'				=> true,
					'edit_others_posts'		=> true,
					'delete_others_posts'	=> true,
					'edit_published_posts'	=> true,
					'delete_published_posts'=> true,
					'edit_posts'			=> true,
					'delete_posts'			=> true,
					'read'					=> true,
				)
			);
			remove_role( 'wiki_author' );
			$result = add_role(
				'wiki_author',
				__( 'Wiki Author' ),
				array(
					'publish_posts'				=> true,
					'edit_others_posts'		=> false,
					'delete_others_posts'	=> false,
					'edit_published_posts'	=> true,
					'delete_published_posts'=> true,
					'edit_posts'			=> true,
					'delete_posts'			=> true,
					'read'					=> true,
				)
			);
			remove_role( 'wiki_contributor' );
			$result = add_role(
				'wiki_contributor',
				__( 'Wiki Contributor' ),
				array(
					'publish_posts'				=> true,
					'edit_others_posts'		=> false,
					'delete_others_posts'	=> false,
					'edit_published_posts'	=> false,
					'delete_published_posts'=> false,
					'edit_posts'			=> true,
					'delete_posts'			=> true,
					'read'					=> true,
				)
			);
		// set up user role capabilities
			$roles = pacowiki_get_all_roles();
			foreach($roles as $key => $role){
				$capabilities['publish_posts'] = @$role["capabilities"]["publish_posts"];
				$capabilities['edit_others_posts'] = @$role["capabilities"]["edit_others_posts"];
				$capabilities['delete_others_posts'] = @$role["capabilities"]["delete_others_posts"];
				$capabilities['edit_published_posts'] = @$role["capabilities"]["edit_published_posts"];
				$capabilities['delete_published_posts'] = @$role["capabilities"]["delete_published_posts"];
				$capabilities['edit_posts'] = @$role["capabilities"]["edit_posts"];
				$capabilities['delete_posts'] = @$role["capabilities"]["delete_posts"];
				$capabilities['read'] = @$role["capabilities"]["read"];
				update_option('pacowiki_cap_' . $key , $capabilities, false);
			}

		// update option
			update_option('pacowiki_custom_css','');
			update_option('pacowiki_custom_js','');
			update_option('pacowiki_options', $options, false);
			PacoWiki()->load_options();
		}
		/**
		* 		Check to see if plugin is updated
		*/
		public function check_version() {
			if ( ! defined( 'IFRAME_REQUEST' ) && ( PacoWiki()->get_option( 'pacowiki_version' ) != PacoWiki()->version ) ) {
				$this->update();
			}
		}
		/**
		* 		Update plugin
		*/
		public function update() {

		}
		/**
		* 		Place code that runs at plugin deactivation here.
		*/
		private function deactivate() {
			// remove corn jobs and user roles
		}
		
		/**
		* The code in this file runs when a plugin is uninstalled from the WordPress dashboard.
		*/
		private static function uninstall(){
			defined( 'WP_UNINSTALL_PLUGIN' ) OR exit; /* If uninstall is not called from WordPress exit. */
			global $wpdb;

			if ( function_exists( 'is_multisite' ) && is_multisite() ) {

					/* 
					
					// remove plugin options for WPMU
					delete_option( $option_name );
					delete_site_option( $option_name );  
					//delete all transient, options and files you may have added
					delete_transient( 'TRANSIENT_NAME' );
					delete_option('OPTION_NAME');
					//info: remove custom file directory for main site
					$upload_dir = wp_upload_dir();
					$directory = $upload_dir['basedir'] . DIRECTORY_SEPARATOR . "CUSTOM_DIRECTORY_NAME" . DIRECTORY_SEPARATOR;
					if (is_dir($directory)) {
						foreach(glob($directory.'*.*') as $v){
							unlink($v);
						}
						rmdir($directory);
					}
					$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE 'woocommerce_%';");
					*/

			} else {
					/* 
					
					// remove plugin options 
					delete_option( $option_name );
					//delete all transient, options and files you may have added
					delete_transient( 'TRANSIENT_NAME' );
					delete_option('OPTION_NAME');
					//info: remove custom file directory for main site
					//info: remove and optimize tables
					$wpdb->query("DROP TABLE `".$GLOBALS['wpdb']->prefix."TABLE_NAME`");
					$wpdb->query("OPTIMIZE TABLE `" .$GLOBALS['wpdb']->prefix."options`");
					$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE 'woocommerce_%';");
					*/
			}
		}
		/**
		* 		Fired for each blog when the plugin is activated.
		*/
		private static function single_activate() {
		
		}

	}
}