<?php
if ( ! class_exists( 'PacoWikiPostType' ) ){
	class PacoWikiPostType {
		
		var $labels;
		var $permissions;
		var $post_type_options;
		
		function __construct() {
			$this->register_taxonomies();
			$this->CreatPostType();
		}
		
		function CreatPostType() {
			$slug = PacoWiki()->get_option('pacowiki_post_slug'); // Slug for post type

			$this->labels = array(
				'name' => __('Wiki Pages', 'pacowiki'),
				'singular_name' => __('Wiki Page', 'pacowiki'),
				'add_new' => __('Add Wiki', 'pacowiki'),
				'add_new_item' => __('Add New Wiki', 'pacowiki'),
				'edit_item' => __('Edit Wiki Page', 'pacowiki'),
				'new_item' => __('New Wiki', 'pacowiki'),
				'view_item' => __('View Wiki', 'pacowiki'),
				'search_items' => __('Search Wikis content', 'pacowiki'),
				'not_found' =>  __('No related Wikis were found', 'pacowiki'),
				'not_found_in_trash' => __('No related Wikis were found in Trash', 'pacowiki'), 
				'menu_name' => __('Wikis', 'pacowiki'),
				'parent_item_colon' => ''
			);
			
			$this->permissions = array(
				'edit_wiki'=>true,
				'edit_wiki_page'=>true,
				'edit_wiki_pages'=>true,
				'edit_others_wiki_pages'=>true,
				'publish_wiki_pages'=>true,
				'delete_wiki_page'=>true,
				'delete_others_wiki_pages'=>false
			);
			
			$this->post_type_options = array(
				'label'=> __('Wiki Page', $slug),
				'labels'=>$this->labels,
				'description'=>__('WIKI Page.', $slug),
				
				// Frontend
				'has_archive'        => true,
				'public'             => true,
				
				//'capability_type' => 'pacowiki',
				'hierarchical' => PacoWiki()->get_option('pacowiki_hierarchical'),
				'exclude_from_search' => PacoWiki()->get_option('pacowiki_exclude_from_search'),
				'map_meta_cap' => true,
				'query_var' => true,
				'supports' => array('title','editor','author','excerpt','comments','thumbnail','page-attributes','revisions','custom-fields'),
				'rewrite' => array('slug' => $slug, 'with_front' => false),
				'taxonomies' => array(
					'pacowiki_category',
					'pacowiki_tag'
				),
				'menu_position' => '15',
				'menu_icon' => PACOWIKI_PLUGIN_URL . 'assets/images/icon.png'
			);
			register_post_type('pacowiki', $this->post_type_options);
		}
		
		function register_taxonomies() {
			// register categories
			if(PacoWiki()->options['pacowiki_category_enable']){
				$category_slug = PacoWiki()->slug . '/' . PacoWiki()->options['pacowiki_category_slug']; // category slug
				register_taxonomy('pacowiki_category', 'pacowiki', array(
					'hierarchical' => true,
					'rewrite' => array(
						'slug' => $category_slug,
						'with_front' => false
					),

					'labels' => array(
						'name' => __( 'Wiki Categories', 'pacowiki' ),
						'singular_name' => __( 'Wiki Category', 'pacowiki' ),
						'search_items' => __( 'Search Wiki Categories', 'pacowiki' ),
						'all_items' => __( 'All Categories', 'pacowiki' ),
						'parent_item' => __( 'Parent Category', 'pacowiki' ),
						'parent_item_colon' => __( 'Parent Category:', 'pacowiki' ),
						'edit_item' => __( 'Edit Wiki Category', 'pacowiki' ),
						'update_item' => __( 'Update Wiki Category', 'pacowiki' ),
						'add_new_item' => __( 'Add New Wiki Category', 'pacowiki' ),
						'new_item_name' => __( 'New Wiki Category Name', 'pacowiki' ),
					),
					'show_admin_column' => true,
					'show_ui' => true,
					'query_var' => true,
				));
			}
			// register tags
			if(PacoWiki()->options['pacowiki_tag_enable']){
				$tag_slug = PacoWiki()->slug . '/' . PacoWiki()->options['pacowiki_tag_slug'];
				register_taxonomy('pacowiki_tag', 'pacowiki', array(
					'rewrite' => array(
						'slug' => $tag_slug,
						'with_front' => false
					),

					'labels' => array(
						'name'			=> __( 'Wiki Tags', 'pacowiki' ),
						'singular_name'	=> __( 'Wiki Tag', 'pacowiki' ),
						'search_items'	=> __( 'Search Wiki Tags', 'pacowiki' ),
						'popular_items'	=> __( 'Popular Wiki Tags', 'pacowiki' ),
						'all_items'		=> __( 'All Wiki Tags', 'pacowiki' ),
						'edit_item'		=> __( 'Edit Wiki Tag', 'pacowiki' ),
						'update_item'	=> __( 'Update Wiki Tag', 'pacowiki' ),
						'add_new_item'	=> __( 'Add New Wiki Tag', 'pacowiki' ),
						'new_item_name'	=> __( 'New Wiki Tag Name', 'pacowiki' ),
						'separate_items_with_commas'	=> __( 'Separate wiki tags with commas', 'pacowiki' ),
						'add_or_remove_items'			=> __( 'Add or remove wiki tags', 'pacowiki' ),
						'choose_from_most_used'			=> __( 'Choose from the most used wiki tags', 'pacowiki' ),
					),
					'show_admin_column' => true,
					'show_ui' => true,
					'show_tagcloud' => PacoWiki()->options['pacowiki_tag_tagcloud'],
				));
			}
		}
		function register_single_template($single_template)
		{
			global $post;
			if ( $post->post_type == 'pacowiki' ) {
				$single_template = PACOWIKI_PLUGIN_PATH . '/templates/default/pacowiki_template.php';
			}
			var_dump($single_template);
			return $single_template;
		}
	}
}