<?php
/*
	Plugin Name: Paco WIKI
	Plugin URI: http://TowhidN.com
	Description: Free and Open Source WIKI & knowledge management plugin for WordPress.
	Author: Towhid
	Version: 1.0
	Author URI: http://Towhidn.com
	License: GPLv2
*/

/**********************************
note that calling PacoWiki() from any code which might be called from __construct can cause an infinit loop and white screen
*/

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}
function v($val){
	echo "<pre>"; var_dump($val); echo "</pre>";
}
function x($val){
	ob_start();
	echo "<pre>"; var_dump($val); echo "</pre>";
	$myStr = ob_get_contents();
	ob_end_clean();

	$file = 'c:\log.html';
	file_put_contents($file, $myStr."\n");
	
}
if ( ! class_exists( 'wp_pacowiki' ) ){
	class wp_pacowiki {
		/*
		* 		Plugin data
		*/
		var $version = '1.0.0';
		var $options = array();
		protected $loaded_textdomain = false;
		protected static $instance = NULL;
		var $slug;
		var $category_slug;
		var $tag_slug;
		var $template_path;
		var $prefix = 'pacowiki_';
		var $wiki_post;

		/**
		*		 Creates or returns an instance of this class.
		*/
		public static function instance() {
			// If an instance hasn't been created and set to $instance create an instance and set it to $instance.
			if ( self::$instance == null ) {
				self::$instance = new self;
			}			
			return self::$instance;
		}
		/**
		*		 Prevent cloning.
		*/	
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, __( 'Do not clone WIKI class!', 'pacowiki' ), '1.0' );
		}
		/*
		* 		__construct() function to initalize plugin
		*/
		function __construct() {
			// plugin info
			define( 'PACOWIKI_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
			define( 'PACOWIKI_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			define( 'PACOWIKI_PLUGIN_DIRECTORY', dirname( plugin_basename( __FILE__ ) ) );
			define( 'PACOWIKI_PLUGIN_FILE', __FILE__ );
			$this->template_path = PACOWIKI_PLUGIN_PATH . 'templates/default/';

			// language & localizations
			add_action( 'plugins_loaded', array($this, 'load_pacowiki_textdomain') );

			// Get Options
			$this->load_options();

			// includes
			$this->includes();
			
			// security feature
			add_action('wp_loaded', array(&$this, 'create_nonce'));
			
			// Front-End form handling
			add_action('wp', array(&$this, 'save_forms'));
			
			// Back-End
			if ( is_admin() ){
				add_action('admin_menu', array($this, 'add_option_submenu'));
				
				add_filter( 'manage_edit-pacowiki_columns', array( $this, 'add_table_columns' ) );
				add_action( 'manage_pacowiki_posts_custom_column', array( $this, 'output_table_columns_data'), 10, 2 );
				
			}

			// hooks and actions
			add_action( 'init', array( $this, 'initialize' ), 0 );
			add_action( 'init', array( $this, 'include_template_functions' ) );

			// static files
			add_action('wp_enqueue_scripts', array( &$this, 'include_styline_js'));
			
			//
			add_filter('get_comment_link', array( &$this, 'get_discussion_link'),2, 3);
			
			// finished
			do_action( 'pacowiki_loaded' );
			
		}


public function my_cool_plugin_settings_page() {
?>
<div class="wrap">
<h2>Your Plugin Name</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'my-cool-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'my-cool-plugin-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">New Option Name</th>
        <td><input type="text" name="new_option_name" value="<?php echo esc_attr( get_option('new_option_name') ); ?>" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Some Other Option</th>
        <td><input type="text" name="some_other_option" value="<?php echo esc_attr( get_option('some_other_option') ); ?>" /></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Options, Etc.</th>
        <td><input type="text" name="option_etc" value="<?php echo esc_attr( get_option('option_etc') ); ?>" /></td>
        </tr>
    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php }
		/*
		*   include plugin files
		*/
		private function includes() {
			// plugin library files
			include_once(PACOWIKI_PLUGIN_PATH . '/functions.php' );
			include_once(PACOWIKI_PLUGIN_PATH . '/includes/pacowiki-post-type.php');
			// admin files
			if ( is_admin() ) {
				include_once(PACOWIKI_PLUGIN_PATH . '/includes/pacowiki-install.php' );
				$installer = new PacoWiki_Install;
			}
			// ajax files
			if ( defined( 'DOING_AJAX' ) ) {
				$this->ajax_includes();
			}
			// frontend files
			include_once( 'includes/class-pacowiki-template-loader.php' );		// Template Loader
		}
		/*
		*   include Ajax functions
		*/
		public function ajax_includes() {
			include_once( 'includes/class-pacowiki-ajax.php' );
		}
		/*
		*   load plugin option's page
		*/
		public function options_init() {
			//register our settings
			register_setting('pacowiki_option_fields', 'pacowiki_options');
			if ( @$_GET['page'] == 'pacowiki' ) {
				wp_enqueue_style("pacowiki_admin_style", plugins_url("/assets/css/admin_options.css", __FILE__), false, "1.0", "all");
				add_action( 'admin_enqueue_scripts', array( $this, 'pacowiki_admin_script' ) );
			}
		}
		
		function pacowiki_admin_script( $hook ) {
			wp_enqueue_script("pacowiki_admin_script", plugins_url("/assets/js/admin_options.js", __FILE__), array( 'jquery' ));
		}
		public function add_option_submenu() {
			add_submenu_page( 'edit.php?post_type=pacowiki', __( 'Wiki Options', 'pacowiki' ), __( 'Options', 'pacowiki' ), 'manage_options', 'pacowiki', array($this, 'pacowiki_options_page') );
			add_action('admin_init', array($this, 'options_init'));
		}
		public function pacowiki_options_page() {
			$page = include( 'admin/pacowiki-options.php' );
			$page->output();
		}
		/*
		*   A security feature: http://codex.wordpress.org/WordPress_Nonces
		*/
		public function create_nonce() {
			$this->nonce = wp_create_nonce($this->slug);
		}
		/*
		*   save front-end forms
		*/
		function save_forms() {
			if ( @$_POST['submitted'] && wp_verify_nonce( @$_POST['pacowiki_nonce_field'], 'pacowiki_nonce' ) ){
				global $wp_pacowiki, $post;
				setup_postdata( $post ); 
				

				$post_information = array(
					'ID' => $post->ID,
					'post_title' =>  wp_strip_all_tags(esc_sql( $_POST['post_title'] )),
					'post_content' => esc_sql($_POST['pacowiki_editor']),
					'post_type' => 'pacowiki',
					'post_status' => 'pending'
				);
				$post_id = wp_update_post( $post_information );
				wp_redirect( get_permalink( $post->ID ));
				exit;
			}
		}
		/**
		* Get user capabilities based on it's role
		*/
		public function get_capabilities($role) {
			if($role=='administrator'){
				$capabilities['publish_posts'] = true;
				$capabilities['edit_others_posts'] = true;
				$capabilities['delete_others_posts'] = true;
				$capabilities['edit_published_posts'] = true;
				$capabilities['delete_published_posts'] = true;
				$capabilities['edit_posts'] = true;
				$capabilities['delete_posts'] = true;
				$capabilities['read'] = true;
			}else{
				$options = get_option( 'pacowiki_options', array() );
				$capabilities = $options['pacowiki_cap_' . $role];
				if(empty($capabilities)){
				$capabilities['publish_posts'] = false;
				$capabilities['edit_others_posts'] = false;
				$capabilities['delete_others_posts'] = false;
				$capabilities['edit_published_posts'] = false;
				$capabilities['delete_published_posts'] = false;
				$capabilities['edit_posts'] = false;
				$capabilities['delete_posts'] = false;
				$capabilities['read'] = false;
				}
			}
			return $capabilities;
		}
		/**
		* Get plugin options and save them in an array for easy access
		* use it to refresh settings if neccessary
		*/
		public function load_options() {
			$this->options = get_option( 'pacowiki_options', array() );
		}
		/*
		* Get options
		*/
		function get_option($option, $default = false) {
			if(isset($this->options[$option]))
				return $this->options[$option];
			else
				return $default;
		}
		/**
		* A centralized way to load the plugin's textdomain for internationalization
		*/
		public function include_template_functions() {
			include_once( 'includes/class-pacowiki-template-loader.php' );
		}
		/**
		* A centralized way to load the plugin's textdomain for internationalization
		*/
		public function load_pacowiki_textdomain() {
			if (!$this->loaded_textdomain) {
				load_plugin_textdomain('pacowiki', false, PACOWIKI_PLUGIN_DIRECTORY . '/languages');
				$this->loaded_textdomain = true;
			}
		}
		/**
		*		Get all blog ids of blogs in the current network that are:
		*		not archived, not spam, not deleted
		*		@return array|false The blog ids, false if no matches.
		*/
		private static function get_blog_ids() {
			global $wpdb;
			// get an array of blog ids
			$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";
			return $wpdb->get_col( $sql );
		}
		/**
		*		Back-End wiki table
		*		Generate Culumns with caption
		*/
		function add_table_columns( $columns ) {
			$columns['revisions'] = __( 'Revisions', 'pacowiki' );
			return $columns;
		}
		/**
		*		Back-End wiki table
		*		Fill columns with data
		*/
		function output_table_columns_data( $columnName, $post_id ) {
			$args = array(
				'post_parent' => $post_id, // id
				'post_type' => 'revision',
				'post_status' => 'inherit'
			);
			$query = get_children($args);
			echo count($query);
		}

		/**
		*		table of contents
		*		add anchors to headers and generate a table of content
		*		http://www.westhost.com/contest/php/function/create-table-of-contents/124
		*/
		function get_table_of_contents($content){
			preg_match_all( '/<h([1-6])(.*)>([^<]+)<\/h[1-6]>/i', $content, $matches, PREG_SET_ORDER );
		 
			global $anchors;
		 
			$anchors = array();
			$toc 	 = '<ol class="wiki-table-of-contents-list" id="wiki-table-of-contents-list">'."\n";
			$i 		 = 0;
			if(count($matches)){
				foreach ( $matches as $heading ) {
			 
					if ($i == 0)
						$startlvl = $heading[1];
					$lvl 		= $heading[1];
			 
					$ret = preg_match( '/id=[\'|"](.*)?[\'|"]/i', stripslashes($heading[2]), $anchor );
					if ( $ret && $anchor[1] != '' ) {
						$anchor = stripslashes( $anchor[1] );
						$add_id = false;
					} else {
						$anchor = preg_replace( '/\s+/', '-', preg_replace('/[^a-z\s]/', '', strtolower( $heading[3] ) ) );
						$add_id = true;
					}
			 
					if ( !in_array( $anchor, $anchors ) ) {
						$anchors[] = $anchor;
					} else {
						$orig_anchor = $anchor;
						$i = 2;
						while ( in_array( $anchor, $anchors ) ) {
							$anchor = $orig_anchor.'-'.$i;
							$i++;
						}
						$anchors[] = $anchor;
					}
			 
					if ( $add_id ) {
						$content = substr_replace( $content, '<h'.$lvl.' id="'.$anchor.'"'.$heading[2].'>'.$heading[3].'</h'.$lvl.'>', strpos( $content, $heading[0] ), strlen( $heading[0] ) );
					}
			 
					$ret = preg_match( '/title=[\'|"](.*)?[\'|"]/i', stripslashes( $heading[2] ), $title );
					if ( $ret && $title[1] != '' )
						$title = stripslashes( $title[1] );
					else	
						$title = $heading[3];
					$title 		= trim( strip_tags( $title ) );
			 
					if ($i > 0) {
						if ($prevlvl < $lvl) {
							$toc .= "\n"."<ol>"."\n";
						} else if ($prevlvl > $lvl) {
							$toc .= '</li>'."\n";
							while ($prevlvl > $lvl) {
								$toc .= "</ol>"."\n".'</li>'."\n";
								$prevlvl--;
							}
						} else {
							$toc .= '</li>'."\n";
						}
					}
			 
					$j = 0;
					$toc .= '<li><a href="#'.$anchor.'">'.$title.'</a>';
					$prevlvl = $lvl;
			 
					$i++;
				}
			 
				unset( $anchors );
			 
				while ( $lvl > $startlvl ) {
					$toc .= "\n</ol>";
					$lvl--;
				}
			 
				$toc .= '</li>'."\n";
				$toc .= '</ol>'."\n";
			 
				return array( 
					'toc' => $toc,
					'content' => $content
				);
			}else{
				return array( 
					'toc' => false,
					'content' => $content
				);
			}
		}
		function get_discussion_link( $link, $comment, $args) {
			if( isset($comment->post_type) && $comment->post_type=='pacowiki'){
				// start of get_comment_link() with add_query_arg() to add action=discussion to arguments
				global $wp_rewrite, $in_comment_loop;

				$comment = get_comment($comment);

				// Backwards compat
				if ( ! is_array( $args ) ) {
					$args = array( 'page' => $args );
				}

				$defaults = array( 'type' => 'all', 'page' => '', 'per_page' => '', 'max_depth' => '' );
				$args = wp_parse_args( $args, $defaults );

				if ( '' === $args['per_page'] && get_option('page_comments') )
					$args['per_page'] = get_option('comments_per_page');

				if ( empty($args['per_page']) ) {
					$args['per_page'] = 0;
					$args['page'] = 0;
				}

				if ( $args['per_page'] ) {
					if ( '' == $args['page'] )
						$args['page'] = ( !empty($in_comment_loop) ) ? get_query_var('cpage') : get_page_of_comment( $comment->comment_ID, $args );

					if ( $wp_rewrite->using_permalinks() )
						$link = user_trailingslashit( trailingslashit( get_permalink( $comment->comment_post_ID ) ) . 'comment-page-' . $args['page'], 'comment' );
					else
						$link = add_query_arg( 'cpage', $args['page'], get_permalink( $comment->comment_post_ID ) );
				} else {
					$link = get_permalink( $comment->comment_post_ID );
				}
					$link = add_query_arg( 'action', 'discussion', get_permalink( $comment->comment_post_ID ) );

				$link = $link . '#comment-' . $comment->comment_ID;

				return $link;
			}
			return $link;
		}

		/**
		* wiki page tabs
		* get list of tabs for wiki's frontend
		*/
		function get_wiki_tabs(){
			$permalink = get_permalink();
			$action = @$_REQUEST['action'];
			$nav = array();
			$nav['article']['class'] = 'article';
			$nav['article']['url'] = $permalink;
			$nav['article']['text'] = __('Article', 'pacowiki' );
			if (comments_open()) {
				$nav['discussion']['class'] = 'discussion';
				$nav['discussion']['url'] = add_query_arg('action', 'discussion', $permalink);
				$nav['discussion']['text'] = __('Discussion', 'pacowiki' );
			}
			$nav['history']['class'] = 'history';
			$nav['history']['url'] = add_query_arg('action', 'history', $permalink);
			$nav['history']['text'] = __('History', 'pacowiki' );
			$nav['edit']['class'] = 'edit';
			$nav['edit']['url'] = add_query_arg('action', 'edit', $permalink);
			$nav['edit']['text'] = __('Edit', 'pacowiki' );
			$nav['subwiki']['class'] = 'subwiki';
			$nav['subwiki']['url'] = add_query_arg('action', 'subwiki', $permalink);
			$nav['subwiki']['text'] = __('Add sub wiki', 'pacowiki' );
			if($action)
				$nav[$action]['class'] = 'wiki-active ' . $nav[$action]['class'];
			else
				$nav['article']['class'] = 'wiki-active ' . $nav['article']['class'];
			return $nav;
		}
		/**
		* 		Register style sheet
		*/
		function include_styline_js() {
			wp_register_style( 'pacowiki-css', PACOWIKI_PLUGIN_URL . 'assets/css/wiki.css' );
			wp_register_script('pacowiki-js', PACOWIKI_PLUGIN_URL . 'assets/js/wiki.js');
			if (get_query_var('post_type') == 'pacowiki') {
				wp_enqueue_style('pacowiki-css');
				wp_enqueue_script('pacowiki-js');
			}
		}
		/*
		*		Run during the initialization of Wordpress
		*/
		function initialize() {
			do_action( 'before_pacowiki_initialize' );

			// Load class instances
			$this->wiki_post = new PacoWikiPostType();

			do_action( 'pacowiki_initialize' );
		}
	}
	// Initalize the your plugin
	//$wp_pacowiki = new wp_pacowiki_class();
	//$wp_pacowiki = wp_pacowiki::get_instance();


	//add_action('init', 'pacowiki_initialize');
}

// Return main instance
function PacoWiki() {
	return wp_pacowiki::instance();
}
// Global for backwards compatibility.
$GLOBALS['PacoWiki'] = PacoWiki();