<?php
if ( ! class_exists( 'PacoWikiPostTemplateLoader' ) ){
	class PacoWikiPostTemplateLoader {

		/**
		 * Constructor
		 */
		public function __construct() {
			add_filter( 'comments_template', array( $this, 'comments_template_loader' ) );
			add_filter( 'template_include', array( $this, 'template_loader' ) );
		}

		/**
		 * Load a template.
		 */
		public function template_loader( $template ) {
			$find = array( 'pacowiki.php' );
			$file = '';

			if ( is_single() && get_post_type() == 'pacowiki' ) {
				$action = @$_REQUEST['action'];
				
				if(! $action){
					$file 	= 'single-pacowiki.php';
					$find[] = $file;
					$find[] = PacoWiki()->template_path . $file;
				}else{
					$file 	= 'single-pacowiki-' . $action . '.php';
					$find[] = $file;
					$find[] = PacoWiki()->template_path . $file;
				}
			} elseif ( is_tax( 'pacowiki_category' )  ) { // || is_tax( 'product_tag' )

				$term = get_queried_object();

				$file 		= 'taxonomy-' . $term->taxonomy . '.php';
				$find[] 	= 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
				$find[] 	= PacoWiki()->template_path . 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
				$find[] 	= $file;
				$find[] 	= PacoWiki()->template_path . $file;

			} elseif ( is_post_type_archive( 'pacowiki' ) ) {

				$file 	= 'archive-pacowiki.php';
				$find[] = $file;
				$find[] = PacoWiki()->template_path . $file;

			}

			if ( $file ) {
				$template       = locate_template( $find );
				if ( ! $template )
					$template = PacoWiki()->template_path . $file;
			}
			return $template;
		}

		/**
		 * comments_template_loader function.
		 */
		public function comments_template_loader( $template ) {

			if ( get_post_type() !== 'pacowiki' )
				return $template;
			if ( file_exists( STYLESHEETPATH . '/pacowiki/single-pacowiki-discussion.php' ))
				return STYLESHEETPATH . '/pacowiki/single-pacowiki-discussion.php';
			elseif ( file_exists( TEMPLATEPATH . '/pacowiki/single-pacowiki-discussion.php' ))
				return TEMPLATEPATH . '/pacowiki/single-pacowiki-discussion.php';
			else
				return PacoWiki()->template_path . 'single-pacowiki-discussion.php';
				
		}
	}
}
new PacoWikiPostTemplateLoader();