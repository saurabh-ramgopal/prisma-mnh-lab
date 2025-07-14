<?php
/**
 * Init function
 */
if( !function_exists( 'otw_pctl_init' ) ){
	
	function otw_pctl_init(){
		
		global $otw_pctl_grid_manager_component, $otw_pctl_grid_manager_object, $otw_pctl_shortcode_component, $otw_pctl_shortcode_object, $otw_pctl_js_version, $otw_pctl_css_version, $otw_pctl_plugin_url, $otw_pctl_form_component, $otw_pctl_form_object, $otw_pctl_image_component, $otw_pctl_image_profile, $otw_pctl_image_object, $otw_pctl_content_sidebars_component, $otw_pctl_content_sidebars_object, $otw_pctl_factory_component, $otw_pctl_factory_object, $otw_pctl_plugin_id;
		
		if( is_admin() ){
			
			add_action( 'save_post', 'otw_pctl_save_meta_box' );
			
			add_action('admin_menu', 'otw_pctl_init_admin_menu' );
			
			add_action('admin_print_styles', 'otw_pctl_enqueue_admin_styles' );
			
			add_action('admin_enqueue_scripts', 'otw_pctl_enqueue_admin_scripts');
			
			add_filter('otwfcr_notice', 'otw_pctl_factory_message' );
			
			otw_pctl_register_predefined_templates();
		}else{
			add_action('wp_enqueue_scripts', 'otw_pctl_enqueue_styles');
		}
		
		otw_pctl_register_custom_sidebars();
		
		$otw_pctl_grid_manager_component = otw_load_component( 'otw_post_template_grid_manager' );
		$otw_pctl_grid_manager_object = otw_get_component( $otw_pctl_grid_manager_component );
		$otw_pctl_grid_manager_object->js_version = $otw_pctl_js_version;
		$otw_pctl_grid_manager_object->css_version = $otw_pctl_css_version;
		$otw_pctl_grid_manager_object->text_info = esc_html__( 'Add some rows and columns in the rows. Then you will be able to add your post elements and sidebars in the columns. Once you build your layout you can save it so you can use it for another page.', 'otw_pctl' );
		
		include_once( plugin_dir_path( __FILE__ ).'otw_labels/otw_pctl_grid_manager_object.labels.php' );
		$otw_pctl_grid_manager_object->init();
		
		$otw_pctl_shortcode_component = otw_load_component( 'otw_post_template_shortcode' );
		$otw_pctl_shortcode_object = otw_get_component( $otw_pctl_shortcode_component );
		$otw_pctl_shortcode_object->js_version = $otw_pctl_js_version;
		$otw_pctl_shortcode_object->css_version = $otw_pctl_css_version;
		
		$otw_pctl_shortcode_object->add_default_external_lib( 'css', 'style', get_stylesheet_directory_uri().'/style.css', 'live_preview', 10 );
		
		$otw_pctl_shortcode_object->shortcodes['post_item_title'] = array( 'title' => esc_html__('Post Title', 'otw_pctl'), 'options' => false, 'enabled' => true,'children' => false,'order' => 100,'parent' => false, 'path' => dirname( __FILE__ ).'/otw_components/otw_post_template_shortcode/', 'url' => $otw_pctl_plugin_url.'include/otw_components/otw_post_template_shortcode/' );
		$otw_pctl_shortcode_object->shortcodes['post_item_meta'] = array( 'title' => esc_html__('Meta Items', 'otw_pctl' ), 'options' => true, 'enabled' => true,'children' => false,'order' => 101,'parent' => false, 'path' => dirname( __FILE__ ).'/otw_components/otw_post_template_shortcode/', 'url' => $otw_pctl_plugin_url.'include/otw_components/otw_post_template_shortcode/' );
		$otw_pctl_shortcode_object->shortcodes['post_item_media'] = array( 'title' => esc_html__('Post Media', 'otw_pctl'), 'options' => false, 'enabled' => true,'children' => false,'order' => 102,'parent' => false, 'path' => dirname( __FILE__ ).'/otw_components/otw_post_template_shortcode/', 'url' => $otw_pctl_plugin_url.'include/otw_components/otw_post_template_shortcode/' );
		$otw_pctl_shortcode_object->shortcodes['post_item_description'] = array( 'title' => esc_html__('Post Description', 'otw_pctl'), 'options' => false, 'enabled' => true,'children' => false,'order' => 103,'parent' => false, 'path' => dirname( __FILE__ ).'/otw_components/otw_post_template_shortcode/', 'url' => $otw_pctl_plugin_url.'include/otw_components/otw_post_template_shortcode/' );
		$otw_pctl_shortcode_object->shortcodes['post_item_social_icons'] = array( 'title' => esc_html__('Post Social Icons', 'otw_pctl'), 'options' => false, 'enabled' => true,'children' => false,'order' => 104,'parent' => false, 'path' => dirname( __FILE__ ).'/otw_components/otw_post_template_shortcode/', 'url' => $otw_pctl_plugin_url.'include/otw_components/otw_post_template_shortcode/' );
		$otw_pctl_shortcode_object->shortcodes['post_item_prev_next_navigation'] = array( 'title' => esc_html__('Post Prev/Next Nav', 'otw_pctl'), 'options' => false, 'enabled' => true,'children' => false,'order' => 106,'parent' => false, 'path' => dirname( __FILE__ ).'/otw_components/otw_post_template_shortcode/', 'url' => $otw_pctl_plugin_url.'include/otw_components/otw_post_template_shortcode/' );
		$otw_pctl_shortcode_object->shortcodes['post_item_related_posts'] = array( 'title' => esc_html__('Related Posts', 'otw_pctl'), 'options' => false, 'enabled' => true,'children' => false,'order' => 107,'parent' => false, 'path' => dirname( __FILE__ ).'/otw_components/otw_post_template_shortcode/', 'url' => $otw_pctl_plugin_url.'include/otw_components/otw_post_template_shortcode/' );
		$otw_pctl_shortcode_object->shortcodes['post_item_comments'] = array( 'title' => esc_html__('Post Comments', 'otw_pctl'), 'options' => false, 'enabled' => true,'children' => false,'order' => 108,'parent' => false, 'path' => dirname( __FILE__ ).'/otw_components/otw_post_template_shortcode/', 'url' => $otw_pctl_plugin_url.'include/otw_components/otw_post_template_shortcode/' );
		$otw_pctl_shortcode_object->shortcodes['post_item_author'] = array( 'title' => esc_html__('Post Author Info', 'otw_pctl'), 'options' => false, 'enabled' => true,'children' => false,'order' => 111,'parent' => false, 'path' => dirname( __FILE__ ).'/otw_components/otw_post_template_shortcode/', 'url' => $otw_pctl_plugin_url.'include/otw_components/otw_post_template_shortcode/' );
		$otw_pctl_shortcode_object->shortcodes['post_item_facebook_comments'] = array( 'title' => esc_html__('Post Facebook Comments', 'otw_pctl'), 'options' => true, 'enabled' => true, 'children' =>false, 'order' => 112,'parent' => false, 'path' => dirname( __FILE__ ).'/otw_components/otw_post_template_shortcode/', 'url' => $otw_pctl_plugin_url.'include/otw_components/otw_post_template_shortcode/' );
		$otw_pctl_shortcode_object->shortcodes['divider'] = array('title' => esc_html__('Divider', 'otw_pctl' ), 'enabled' => true, 'children' => false, 'parent' => false, 'order' => 130, 'path' => dirname(__FILE__) . '/otw_components/otw_post_template_shortcode/', 'url' => $otw_pctl_plugin_url . 'include/otw_components/otw_post_template_shortcode/');
		$otw_pctl_shortcode_object->shortcodes['sidebars'] = array( 'title' => esc_html__('Sidebars', 'otw_pctl' ),'enabled' => true,'children' => false,'order' => 132,'parent' => false, 'path' => dirname( __FILE__ ).'/otw_components/otw_post_template_shortcode/', 'url' => $otw_pctl_plugin_url.'include/otw_components/otw_post_template_shortcode/' );
		$otw_pctl_shortcode_object->shortcodes['ads'] = array( 'title' => esc_html__('Ads Shortcode', 'otw_pctl' ),'enabled' => true,'children' => false,'order' => 137,'parent' => false, 'path' => dirname( __FILE__ ).'/otw_components/otw_post_template_shortcode/', 'url' => $otw_pctl_plugin_url.'include/otw_components/otw_post_template_shortcode/' );
		$otw_pctl_shortcode_object->shortcodes['breadcrumbs'] = array( 'title' => esc_html__('Breadcrumbs', 'otw_pctl'), 'options' => false, 'enabled' => true,'children' => false,'order' => 138,'parent' => false, 'path' => dirname( __FILE__ ).'/otw_components/otw_post_template_shortcode/', 'url' => $otw_pctl_plugin_url.'include/otw_components/otw_post_template_shortcode/' );
		
		include_once( plugin_dir_path( __FILE__ ).'otw_labels/otw_pctl_shortcode_object.labels.php' );
		$otw_pctl_shortcode_object->init();
		
		//form component
		$otw_pctl_form_component = otw_load_component( 'otw_form' );
		$otw_pctl_form_object = otw_get_component( $otw_pctl_form_component );
		$otw_pctl_form_object->js_version = $otw_pctl_js_version;
		$otw_pctl_form_object->css_version = $otw_pctl_css_version;
		
		global $wp_filesystem;
		
		if( otw_init_filesystem() ){
			
			if( $wp_filesystem->is_file( plugin_dir_path( __FILE__ ).'otw_labels/otw_pctl_form_object.labels.php' ) ){
				include_once( plugin_dir_path( __FILE__ ).'otw_labels/otw_pctl_form_object.labels.php' );
			}
		}
		$otw_pctl_form_object->init();
		
		//content sidebars component
		$otw_pctl_content_sidebars_component = otw_load_component('otw_content_sidebars');
		$otw_pctl_content_sidebars_object = otw_get_component($otw_pctl_content_sidebars_component);
		$otw_pctl_content_sidebars_object->admin_menu = false;
		$otw_pctl_content_sidebars_object->meta_name = 'otw_content_sidebars_settings';
		$otw_pctl_content_sidebars_object->js_version = $otw_pctl_js_version;
		$otw_pctl_content_sidebars_object->css_version = $otw_pctl_css_version;
		
		include_once( plugin_dir_path(__FILE__) . 'otw_labels/otw_pctl_content_sidebars_object.labels.php' );
		$otw_pctl_content_sidebars_object->init();
		
		$otw_pctl_image_component = otw_load_component( 'otw_image' );
		
		$otw_pctl_image_object = otw_get_component( $otw_pctl_image_component );
		
		$otw_pctl_image_object->init();
		
		$img_location = wp_upload_dir();
		
		$otw_pctl_image_profile = $otw_pctl_image_object->add_profile( $img_location['basedir'].'/', $img_location['baseurl'].'/', 'otwpct' );
		
		$otw_pctl_factory_component = otw_load_component( 'otw_factory' );
		$otw_pctl_factory_object = otw_get_component( $otw_pctl_factory_component );
		$otw_pctl_factory_object->add_plugin( $otw_pctl_plugin_id, dirname( dirname( __FILE__ ) ).'/otw_post_custom_templates_lite.php', array( 'menu_parent' => 'otw-pctl', 'lc_name' => esc_html__( 'License Manager', 'otw_pctl' ), 'menu_key' => 'otw-pctl' ) );
		
		include_once( plugin_dir_path( __FILE__ ).'otw_labels/otw_pctl_factory_object.labels.php' );
		$otw_pctl_factory_object->init();
		
		include_once( 'otw_pctl_process_actions.php' );
	}
}
/**
 * Register custom sidebars
 */
if( !function_exists( 'otw_pctl_register_predefined_templates' ) ){
	
	function otw_pctl_register_predefined_templates(){
		
		include_once( 'otw_pctl_predefined_templates.php' );
		
		if( is_array( $otw_pctl_predefined_templates ) && count( $otw_pctl_predefined_templates ) ){
		
			global $otw_pctl_template_custom_css_path;
			
			$custom_template_mapping = array();
			
			$otw_custom_templates = get_option( 'otw_mb_custom_templates' );
			
			if( is_array( $otw_custom_templates ) && count( $otw_custom_templates ) ){
				
				foreach( $otw_custom_templates as $template ){
					
					if( isset( $template['pdf_id'] ) && strlen( $template['pdf_id'] ) ){
						
						$custom_template_mapping[ $template['pdf_id'] ] = $template['id'];
					}
				}
			}
			
			global $wp_filesystem;
			
			foreach( $otw_pctl_predefined_templates as $pp_id => $pp_template ){
			
				$decoded = unserialize( base64_decode( $pp_template ) );
				
				if( isset( $decoded['grid_content'] ) ){
					
					$decoded['grid_content'] = addslashes( $decoded['grid_content'] );
					
					if( isset( $custom_template_mapping[ $pp_id ] ) ){
					
					}else{
						
						$otw_custom_template_id = otw_get_next_post_custom_template_id();
						
						$otw_custom_templates[ $otw_custom_template_id ] = $decoded;
						$otw_custom_templates[ $otw_custom_template_id ]['id'] = $otw_custom_template_id;
						$otw_custom_templates[ $otw_custom_template_id ]['pdf_id'] = $pp_id;
						
						if( isset( $decoded['_custom_css'] ) && strlen( $decoded['_custom_css'] ) ){
						
							$decoded['_custom_css'] = str_replace( 'otw_pct_ct_'.$decoded['id'], 'otw_pct_ct_'.$otw_custom_template_id, $decoded['_custom_css'] );
							
							$file_name = $otw_pctl_template_custom_css_path.'otw_pct_ct_'.$otw_custom_template_id.'.css';
							
							if( otw_init_filesystem() ){
								$wp_filesystem->put_contents( $file_name, $decoded['_custom_css'] );
							}
							
							unset( $otw_custom_templates[ $otw_custom_template_id ]['_custom_css'] );
						}
						update_option( 'otw_mb_custom_templates', $otw_custom_templates );
					}
				}
			}
		}
	}
}
/**
 * Register custom sidebars
 */
if( !function_exists( 'otw_pctl_register_custom_sidebars' ) ){
	
	function otw_pctl_register_custom_sidebars(){
		
		$args = array();
		$args['id'] = 'otw_pctp_sidebar_0';
		$args['name'] = esc_html__( 'OTW Post Custom Templates', 'otw_pctl' );
		$args['description'] = '';
		$args['before_widget'] = '<div id="%1$s" class="widget %2$s">';
		$args['after_widget'] = '</div>';
		
		register_sidebar( $args );
	}
}

/**
 * Init admin menu
 */
if( !function_exists( 'otw_pctl_init_admin_menu' ) ){
	
	function otw_pctl_init_admin_menu(){
		
		global $otw_pctl_plugin_url;
		
		$menu_parent = 'otw-pctl';
		
		add_menu_page(__('Post Custom Templates Lite', 'otw_pctl'), esc_html__('Post Custom Templates Lite', 'otw_pctl'), 'manage_options', 'otw-pctl', 'otw_pctl_custom_templates', $otw_pctl_plugin_url.'/images/otw-menu-icon.png');
		
		add_submenu_page( $menu_parent, esc_html__('Post Custom Templates Lite | Single Posts Templates Lite', 'otw_pctl'), esc_html__('Single Posts Templates', 'otw_pctl'), 'manage_options', 'otw-pctl', 'otw_pctl_custom_templates' );
		add_submenu_page( $menu_parent, esc_html__('Post Custom Templates Lite | Add Single Post Template', 'otw_pctl'), esc_html__('Add Template', 'otw_pctl'), 'manage_options', 'otw-pctl-custom-templates-add', 'otw_pctl_custom_templates_edit' );
		$hook_suffix_0 = add_submenu_page( __FILE__, esc_html__('Post Custom Templates Lite | Single Posts Templates Lite| Edit', 'otw_pctl'), esc_html__('Edit Single Posts Templates', 'otw_pctl'), 'manage_options', 'otw-pctl-custom-templates-edit', 'otw_pctl_custom_templates_edit' );
		$hook_suffix_1 = add_submenu_page( __FILE__, esc_html__('Post Custom Templates Lite | Single Posts Templates Lite | Confirmation', 'otw_pctl'), esc_html__('Action Single Posts Templates', 'otw_pctl'), 'manage_options', 'otw-pctl-custom-templates-action', 'otw_pctl_custom_templates_action' );
		$hook_suffix_2 = add_submenu_page( __FILE__, esc_html__('Post Custom Templates Lite | Single Posts Templates Lite | Edit default', 'otw_pctl'), esc_html__('Edit Single Posts Templates', 'otw_pctl'), 'manage_options', 'otw-pctl-default-templates-edit', 'otw_pctl_default_templates_edit' );
		
		add_submenu_page( $menu_parent, esc_html__('Post Custom Templates Lite | Options', 'otw_pctl'), esc_html__('Options', 'otw_pctl'), 'manage_options', 'otw-pctl-settings', 'otw_pctl_settings' );
		
		add_action( 'load-' . $hook_suffix_0 , 'otw_pctl_open_admin_menu' );
		add_action( 'load-' . $hook_suffix_1 , 'otw_pctl_open_admin_menu' );
		add_action( 'load-' . $hook_suffix_2 , 'otw_pctl_open_admin_menu' );
	
	}
}

if( !function_exists( 'otw_pctl_open_admin_menu' ) ){
	
	function otw_pctl_open_admin_menu(){
		
		global $menu, $submenu;
		
		foreach( $menu as $key => $item ){
			
			if( $item[2] == 'otw-pctl' ){
				$menu[ $key ][4] = $menu[ $key ][4].' wp-has-submenu wp-has-current-submenu wp-menu-open menu-top otw-pctl-menu-open current';
				
				if( function_exists( 'get_current_screen' ) ){
				
					$screen = get_current_screen();
					
					if( preg_match( "/otw\-pctl\-add$/", $screen->base ) && isset( $submenu[ 'otw-pctl' ] ) && otw_get('action',false) && ( otw_get('action','') == 'edit' ) ){
						foreach( $submenu[ 'otw-pctl' ] as $s_key => $s_data ){
							
							if( $s_data[2] == 'otw-pctl' ){
								$submenu[ 'otw-pctl' ][ $s_key ][4] = 'current';
							}
						}
					}
				}
			}
		}
	}
}


/** get settings
  *
  */
if( !function_exists( 'otw_pctl_get_settings' ) ){
	
	function otw_pctl_get_settings( $otw_pct_plugin_options = array() ){
		
		if( !isset( $otw_pct_plugin_options['otw_pct_template'] ) ){
			
			$otw_pct_plugin_options['otw_pct_template'] = 'default';
		}
		
		if( !isset( $otw_pct_plugin_options['otw_pct_sidebar'] ) ){
			
			$otw_pct_plugin_options['otw_pct_sidebar'] = '';
		}
		
		if( !isset( $otw_pct_plugin_options['otw_pct_category_template'] ) ){
			$otw_pct_plugin_options['otw_pct_category_template'] = '';
		}
		
		return $otw_pct_plugin_options;
	}
}

/** get custom template settings
  *
  */
if( !function_exists( 'otw_pctl_get_content_sidebars_settings' ) ){
	
	function otw_pctl_get_content_sidebars_settings( $otw_pct_plugin_options = array() ){
		
		if( !isset( $otw_pct_plugin_options['cs'] ) || !isset( $otw_pct_plugin_options['cs']['layout'] ) ){
			
			$otw_pct_plugin_options['cs']['layout'] = '1c';
		}
		
		if( !isset( $otw_pct_plugin_options['cs'] ) || !isset( $otw_pct_plugin_options['cs']['sidebar1'] ) ){
			
			$otw_pct_plugin_options['cs']['sidebar1'] = '';
		}
		
		if( !isset( $otw_pct_plugin_options['cs'] ) || !isset( $otw_pct_plugin_options['cs']['sidebar2'] ) ){
			
			$otw_pct_plugin_options['cs']['sidebar2'] = '';
		}
		
		if( !isset( $otw_pct_plugin_options['cs'] ) || !isset( $otw_pct_plugin_options['cs']['sidebar1_size'] ) ){
			
			$otw_pct_plugin_options['cs']['sidebar1_size'] = '6';
		}
		
		if( !isset( $otw_pct_plugin_options['cs'] ) || !isset( $otw_pct_plugin_options['cs']['sidebar2_size'] ) ){
			
			$otw_pct_plugin_options['cs']['sidebar2_size'] = '6';
		}
		
		return $otw_pct_plugin_options;
	}
}

/** get custom template settings
  *
  */
if( !function_exists( 'otw_pctl_get_custom_template_settings' ) ){
	
	function otw_pctl_get_custom_template_settings( $otw_pct_plugin_options = array() ){
		
		if( !isset( $otw_pct_plugin_options['otw_pct_prev_next_nav'] ) ){
			
			$otw_pct_plugin_options['otw_pct_prev_next_nav'] = 'yes';
		}
		
		if( !isset( $otw_pct_plugin_options['otw_pct_related_posts'] ) ){
			$otw_pct_plugin_options['otw_pct_related_posts'] = 'yes';
		}
		
		if( !isset( $otw_pct_plugin_options['otw_pct_title_link'] ) ){
			$otw_pct_plugin_options['otw_pct_title_link'] = 'single';
		}
		
		if( !isset( $otw_pct_plugin_options['otw_pct_meta_items'] ) ){
			$otw_pct_plugin_options['otw_pct_meta_items'] = 'author,date,category,tags,comments';
		}
		
		if( !isset( $otw_pct_plugin_options['otw_pct_meta_icons'] ) ){
			$otw_pct_plugin_options['otw_pct_meta_icons'] = '';
		}
		
		if( !isset( $otw_pct_plugin_options['otw_pct_meta_type_align'] ) ){
			$otw_pct_plugin_options['otw_pct_meta_type_align'] = 'horizontal';
		}
		
		if( !isset( $otw_pct_plugin_options['otw_pct_related_posts_criteria'] ) ){
			$otw_pct_plugin_options['otw_pct_related_posts_criteria'] = 'category';
		}
		
		if( !isset( $otw_pct_plugin_options['otw_pct_related_posts_number'] ) ){
			$otw_pct_plugin_options['otw_pct_related_posts_number'] = '4';
		}
		
		if( !isset( $otw_pct_plugin_options['otw_pct_social_title_text'] ) ){
			$otw_pct_plugin_options['otw_pct_social_title_text'] = '';
		}
		
		if( !isset( $otw_pct_plugin_options['otw_pct_post_fields_title_text'] ) ){
			$otw_pct_plugin_options['otw_pct_post_fields_title_text'] = '';
		}
		
		if( !isset( $otw_pct_plugin_options['otw_pct_post_fields_delimiters'] ) ){
			$otw_pct_plugin_options['otw_pct_post_fields_delimiters'] = 'yes';
		}
		
		if( !isset( $otw_pct_plugin_options['otw_pct_show_social_icons'] ) ){
			$otw_pct_plugin_options['otw_pct_show_social_icons'] = 'share_icons';
		}
		
		if( !isset( $otw_pct_plugin_options['otw_pct_show_social_icons_facebook'] ) ){
			$otw_pct_plugin_options['otw_pct_show_social_icons_facebook'] = '1';
		}
		
		if( !isset( $otw_pct_plugin_options['otw_pct_show_social_icons_twitter'] ) ){
			$otw_pct_plugin_options['otw_pct_show_social_icons_twitter'] = '1';
		}
		
		if( !isset( $otw_pct_plugin_options['otw_pct_show_social_icons_googleplus'] ) ){
			$otw_pct_plugin_options['otw_pct_show_social_icons_googleplus'] = '1';
		}
		
		if( !isset( $otw_pct_plugin_options['otw_pct_show_social_icons_linkedin'] ) ){
			$otw_pct_plugin_options['otw_pct_show_social_icons_linkedin'] = '1';
		}
		
		if( !isset( $otw_pct_plugin_options['otw_pct_show_social_icons_pinterest'] ) ){
			$otw_pct_plugin_options['otw_pct_show_social_icons_pinterest'] = '1';
		}
		
		if( !isset( $otw_pct_plugin_options['otw_pct_show_social_icons_custom'] ) ){
			$otw_pct_plugin_options['otw_pct_show_social_icons_custom'] = '';
		}
		
		if( !isset( $otw_pct_plugin_options['otw_pct_item_media_width'] ) ){
			$otw_pct_plugin_options['otw_pct_item_media_width'] = '';
		}
		if( !isset( $otw_pct_plugin_options['otw_pct_item_media_height'] ) ){
			$otw_pct_plugin_options['otw_pct_item_media_height'] = '';
		}
		if( !isset( $otw_pct_plugin_options['otw_pct_item_media_format'] ) ){
			$otw_pct_plugin_options['otw_pct_item_media_format'] = '';
		}
		if( !isset( $otw_pct_plugin_options['otw_pct_item_media_title_attr'] ) ){
			$otw_pct_plugin_options['otw_pct_item_media_title_attr'] = 'no';
		}
		if( !isset( $otw_pct_plugin_options['otw_pct_item_media_alt_attr'] ) ){
			$otw_pct_plugin_options['otw_pct_item_media_alt_attr'] = 'no';
		}
		if( !isset( $otw_pct_plugin_options['otw_pct_archive_media_ns_width'] ) ){
			$otw_pct_plugin_options['otw_pct_archive_media_ns_width'] = '';
		}
		if( !isset( $otw_pct_plugin_options['otw_pct_archive_media_ns_height'] ) ){
			$otw_pct_plugin_options['otw_pct_archive_media_ns_height'] = '';
		}
		if( !isset( $otw_pct_plugin_options['otw_pct_archive_media_ws_width'] ) ){
			$otw_pct_plugin_options['otw_pct_archive_media_ws_width'] = '';
		}
		if( !isset( $otw_pct_plugin_options['otw_pct_archive_media_ws_height'] ) ){
			$otw_pct_plugin_options['otw_pct_archive_media_ws_height'] = '';
		}
		if( !isset( $otw_pct_plugin_options['otw_pct_related_media_width'] ) ){
			$otw_pct_plugin_options['otw_pct_related_media_width'] = '';
		}
		
		if( !isset( $otw_pct_plugin_options['otw_pct_related_media_height'] ) ){
			$otw_pct_plugin_options['otw_pct_related_media_height'] = '';
		}
		
		if( !isset( $otw_pct_plugin_options['otw_pct_related_media_format'] ) ){
			$otw_pct_plugin_options['otw_pct_related_media_format'] = '';
		}
		
		if( !isset( $otw_pct_plugin_options['otw_pct_details_title_text'] ) ){
			$otw_pct_plugin_options['otw_pct_details_title_text'] = '';
		}
		
		if( !isset( $otw_pct_plugin_options['otw_pct_moreinfo_title_text'] ) ){
			$otw_pct_plugin_options['otw_pct_moreinfo_title_text'] = '';
		}
		
		if( !isset( $otw_pct_plugin_options['otw_pct_related_title_text'] ) ){
			$otw_pct_plugin_options['otw_pct_related_title_text'] = '';
		}
		
		if( !isset( $otw_pct_plugin_options['otw_pct_reviews_title_text'] ) ){
			$otw_pct_plugin_options['otw_pct_reviews_title_text'] = '';
		}
		
		if( !isset( $otw_pct_plugin_options['otw_pct_media_lightbox'] ) ){
			$otw_pct_plugin_options['otw_pct_media_lightbox'] = 'no';
		}
		
		if( !isset( $otw_pct_plugin_options['otw_pct_item_media_lightbox_width'] ) ){
			$otw_pct_plugin_options['otw_pct_item_media_lightbox_width'] = '';
		}
		if( !isset( $otw_pct_plugin_options['otw_pct_item_media_lightbox_height'] ) ){
			$otw_pct_plugin_options['otw_pct_item_media_lightbox_height'] = '';
		}
		if( !isset( $otw_pct_plugin_options['otw_pct_item_media_lightbox_format'] ) ){
			$otw_pct_plugin_options['otw_pct_item_media_lightbox_format'] = '';
		}
		
		if( !isset( $otw_pct_plugin_options['otw_pct_item_title'] ) ){
			$otw_pct_plugin_options['otw_pct_item_title'] = 'yes';
		}
		return $otw_pct_plugin_options;
	}
}
/**
  * settings page
  */
 if( !function_exists( 'otw_pctl_settings' ) ){
	
	function otw_pctl_settings(){
		
		global $otw_pctl_custom_css_path, $wp_registered_sidebars;
		
		$otw_pct_plugin_options = otw_pctl_get_settings();
		
		$db_otw_pct_plugin_options = get_option( 'otw_mb_plugin_options' );
		
		foreach( $otw_pct_plugin_options as $setting_key => $value ){
		
			if( is_array( $db_otw_pct_plugin_options ) && isset( $db_otw_pct_plugin_options[ $setting_key ] ) ){
			
				$otw_pct_plugin_options[ $setting_key ] = $db_otw_pct_plugin_options[ $setting_key ];
			}
		}
		
		global $wp_filesystem;
		
		$customCss = '';
		
		if( otw_init_filesystem() ){
			
			if( $wp_filesystem->exists( $otw_pctl_custom_css_path ) ){
				$customCss = $wp_filesystem->get_contents( $otw_pctl_custom_css_path );
			}
		}
		
		$otw_pct_templates = array( 'default' => esc_html__( 'Theme\'s default template (default)', 'otw_pctl' ) ) + otw_get_post_templates();
		
		require_once( 'otw_pctl_settings.php' );
	}
}

/** custom templates
  *
  */
 if( !function_exists( 'otw_pctl_custom_templates' ) ){
	
	function otw_pctl_custom_templates(){
		
		$otw_custom_templates = otw_get_post_custom_templates();
		
		require_once('otw_pctl_custom_templates.php');
	}
}

/** edit custom templates
  *
  */
 if( !function_exists( 'otw_pctl_custom_templates_edit' ) ){
	
	function otw_pctl_custom_templates_edit(){
		
		global $otw_pctl_grid_manager_object, $otw_pctl_content_sidebars_object;
		
		$page_title = 'Add Single Posts Template';
		
		$custom_templates = otw_get_post_custom_templates();
		
		$otw_pctl_custom_template_values = array();
		$otw_pctl_custom_template_values['title'] = '';
		$otw_pctl_custom_template_values['grid_content'] = '';
		$cs_settings = otw_pctl_get_content_sidebars_settings();
		$otw_pctl_custom_template_values['cs'] = $cs_settings['cs'];
		
		$default_options = otw_pctl_get_custom_template_settings();
		$otw_pctl_custom_template_values['options'] = $default_options;
		
		if( otw_get( 'custom_template', false ) && preg_match( "/^\d+$/", otw_get( 'custom_template', '' ) ) && intval( otw_get( 'custom_template', '' ) ) && array_key_exists( otw_get( 'custom_template', '' ), $custom_templates ) ){
			
			$page_title = 'Edit Single Posts Template';
			
			$otw_pctl_custom_template_values['title'] = $custom_templates[ otw_get( 'custom_template', '' ) ]['title'];
			$otw_pctl_custom_template_values['grid_content'] = otw_stripslashes( $custom_templates[ otw_get( 'custom_template', '' ) ]['grid_content'] );
			
			if( isset( $custom_templates[ otw_get( 'custom_template', '' ) ]['options'] ) && is_array( $custom_templates[ otw_get( 'custom_template', '' ) ]['options'] ) ){
				$otw_pctl_custom_template_values['options'] = $custom_templates[ otw_get( 'custom_template', '' ) ]['options'];
			}
			
			if( isset( $custom_templates[ otw_get( 'custom_template', '' ) ]['cs'] ) && is_array( $custom_templates[ otw_get( 'custom_template', '' ) ]['cs'] ) ){
				$otw_pctl_custom_template_values['cs'] = $custom_templates[ otw_get( 'custom_template', '' ) ]['cs'];
			}
			
			foreach( $default_options as $key => $value ){
				
				if( !isset( $otw_pctl_custom_template_values['options'][ $key ] ) ){
					$otw_pctl_custom_template_values['options'][ $key ] = $value;
				}
			}
		}
		
		if( otw_post( 'otw_pctl_action', false ) && ( otw_post( 'otw_pctl_action', '' ) == 'manage_otw_pctl_custom_templates' ) ){
		
			$otw_pctl_custom_template_values['title'] = otw_stripslashes( otw_post( 'pctl_custom_template_title', '' ) );
			$otw_pctl_custom_template_values['grid_content'] = otw_post( array( '_otw_post_template_grid_manager_content', 'code' ), '' );
			
			
			foreach( $otw_pctl_custom_template_values['options'] as $key => $value ){
			
				if( otw_post( $key, false ) ){
					$otw_pctl_custom_template_values['options'][ $key ] = otw_post( $key, '' );
				}
			}
		}
		
		$otw_pct_related_posts_criteria_options = array(
			'category' => esc_html__( 'Category (default)', 'otw_pctl' ),
			'post_tag' => esc_html__( 'Tag', 'otw_pctl' )
		);
		
		$otw_pct_related_posts_options = array(
			'' => esc_html__( 'No', 'otw_pctl' ),
			'yes' => esc_html__( 'Yes (default)', 'otw_pctl' )
		);
		
		$otw_pct_prev_next_nav_options = array(
			'' => esc_html__( 'No', 'otw_pctl' ),
			'yes' => esc_html__( 'Yes (default)', 'otw_pctl' )
		);
		
		$otw_pct_social_icons_options =array(
			array( 'value' => '0', 'text' => esc_html__('None', 'otw_pctl') ),
			array( 'value' => 'share_icons', 'text' => esc_html__('Share Icons (default)', 'otw_pctl') ),
			array( 'value' => 'share_btn_small', 'text' => esc_html__('Share Buttons Small', 'otw_pctl') ),
			array( 'value' => 'share_btn_large', 'text' => esc_html__('Share Buttons Large', 'otw_pctl') ),
			array( 'value' => 'like_buttons', 'text' => esc_html__('Like Buttons', 'otw_pctl') ),
			array( 'value' => 'custom_icons', 'text' => esc_html__('Custom Social Icons', 'otw_pctl') )
		);
		
		$total_meta_elements = 6;
		
		$meta_elements = array();
		
		$meta_elements_height = ( ( $total_meta_elements + 2 ) * 22 );
		
		$selectMetaData = array(
			array( 'value' => 'horizontal', 'text' => esc_html__('Horizontal (default)', 'otw_pctl') ),
			array( 'value' => 'vertical', 'text' => esc_html__('Vertical', 'otw_pctl') ),
		);
		
		require_once('otw_pctl_custom_templates_manage.php');
	}
}

/** get custom templates
  *
  */
 if( !function_exists( 'otw_get_post_templates' ) ){
	
	function otw_get_post_templates(){
	
		$custom_templates = otw_get_post_custom_templates();
		
		$post_templates = array();
		
		if( is_array( $custom_templates ) && count( $custom_templates ) ){
		
			$post_templates['-'] = '-';
			
			foreach( $custom_templates as $custom_template ){
				$post_templates['otw_custom_template_'.$custom_template['id'] ] = $custom_template['title'];
				
				if( empty( $custom_template['title'] ) ){
					$post_templates['otw_custom_template_'.$custom_template['id'] ] = esc_html__( 'No title', 'otw_pctl' );
				}
			}
		}
		
		return $post_templates;
	}
	
}
/** get custom templates
  *
  */
 if( !function_exists( 'otw_get_post_custom_templates' ) ){
	
	function otw_get_post_custom_templates(){
		
		$otw_custom_templates = get_option( 'otw_mb_custom_templates' );
		
		if( !is_array( $otw_custom_templates ) || !count( $otw_custom_templates ) ){
			
			$otw_custom_templates = array();
			
		}
		return $otw_custom_templates;
	}
}

/** get custom templates next id
  *
  */
 if( !function_exists( 'otw_get_next_post_custom_template_id' ) ){
	
	function otw_get_next_post_custom_template_id(){
		
		$next_id = 1;
		$existing_custom_templates = otw_get_post_custom_templates();
		
		if( is_array( $existing_custom_templates ) && count( $existing_custom_templates ) ){
		
			foreach( $existing_custom_templates as $key => $s_data ){
				
				if( preg_match( "/^([0-9]+)$/", $key, $matches ) ){
					
					if( $matches[1] > $next_id ){
						$next_id = $matches[1];
					}
				}
			}
		}else{
			$next_id = 0;
		}
		return $next_id + 1;
		
	}
}

/**
 * Include admin scripts
 */
if( !function_exists( 'otw_pctl_enqueue_admin_scripts' ) ){
	
	function otw_pctl_enqueue_admin_scripts(){
		
		global $otw_pctl_grid_manager_object, $otw_pctl_shortcode_object, $otw_pctl_plugin_url, $otw_pctl_js_version;
		
		if( function_exists( 'get_current_screen' ) ){
			
			$screen = get_current_screen();
			
			if( isset( $screen->id ) && strlen( $screen->id ) ){
				$requested_page = $screen->id;
			}
		}
		
		if( preg_match( "/otw\-pctl\-(default|custom)\-templates\-(add|edit)$/", $requested_page ) || preg_match( "/otw\-pctl\-settings$/", $requested_page ) || ( $requested_page == 'post' ) ){
			
			$messages = array(
				'delete_confirm'  => esc_html__('Are you sure you want to delete ', 'otw_pctl'),
				'modal_title'     => esc_html__('Select Images', 'otw_pctl'),
				'modal_btn'       => esc_html__('Add Image', 'otw_pctl')
			);
			
			$otw_pctl_grid_manager_object->include_admin_scripts();
			$otw_pctl_shortcode_object->include_admin_scripts();
			wp_enqueue_style('thickbox');
			wp_enqueue_script('thickbox');
			wp_enqueue_script('media-models');
			wp_enqueue_script('media-upload');
			wp_enqueue_media();
			wp_enqueue_script( 'otw_ptp_admin', $otw_pctl_plugin_url.'js/otw_pctl_admin.js'  , array( 'jquery', 'jquery-ui-sortable' ), $otw_pctl_js_version );
			
			wp_add_inline_script( 'otw_ptp_admin', 'var messages = "'.addslashes( json_encode( $messages ) ).'";', 'before' );
		}
	}
}

/**
 * include frontend styles
 */
if( !function_exists( 'otw_pctl_enqueue_styles' ) ){
	function otw_pctl_enqueue_styles(){
		global $otw_pctl_plugin_url, $otw_pctl_css_version, $otw_pctl_custom_css_path,  $otw_pctl_custom_css_url, $otw_pctl_js_version, $post, $otw_pctl_dispatcher;
		
		if( !is_admin() ){
			
			if( is_object( $otw_pctl_dispatcher ) && isset( $otw_pctl_dispatcher->is_used ) && $otw_pctl_dispatcher->is_used ){
				
				$count_views = false;
				
				if( is_object( $post ) && !wp_is_post_revision( $post ) && is_single() && !is_archive() && isset( $post->post_type ) && ( $post->post_type == 'post' ) ){
					$count_views = true;
				}
				
				global $wp_filesystem;
				
				if( otw_init_filesystem() ){
					
					if( $wp_filesystem->exists( $otw_pctl_custom_css_path ) ){
						wp_register_style('otw_pctl_custom.css', $otw_pctl_custom_css_url );
						wp_enqueue_style('otw_pctl_custom.css');
					}
				}
				wp_enqueue_style( 'otw-pct-font-awesome',  $otw_pctl_plugin_url.'css/font-awesome.min.css', array(), $otw_pctl_css_version );
				wp_enqueue_style( 'otw_pct', $otw_pctl_plugin_url.'css/otw_pctl.css', array(), $otw_pctl_css_version );
				
				$uniqueHash = wp_create_nonce("otw_pctl_social_share");
				$socialShareLink = admin_url( 'admin-ajax.php?action=otw_pctl_social_share&nonce='. $uniqueHash );
				
				wp_register_script( 'otw-pctl-flexslider', $otw_pctl_plugin_url.'js/jquery.flexslider.min.js', array( 'jquery' ), $otw_pctl_js_version );
				wp_register_script( 'otw-pctl-imagesloaded', $otw_pctl_plugin_url.'js/imagesloaded.pkgd.min.js', array( 'jquery' ), $otw_pctl_js_version );
                
				wp_register_script( 'otw-pctl', $otw_pctl_plugin_url.'js/otw_pctl.js'  , array( 'jquery', 'jquery-ui-tabs' ), $otw_pctl_js_version );
				wp_add_inline_script( 'otw-pctl', 'var socialShareURL="'.esc_attr( $socialShareLink ).'";', 'before' );
				if( $count_views ){
					wp_register_script( 'otw-pctl-counter', $otw_pctl_plugin_url.'js/otw_pctl_counter.js', array( 'jquery' ), '', true );
					wp_add_inline_script( 'otw-pctl-counter', 'var otw_counter=', json_encode( array( 'url' => admin_url( 'admin-ajax.php' ), 'post_id' => $post->ID ) ).";", 'before' );
				}
				
				wp_enqueue_script( 'otw-pctl-flexslider' );
				wp_enqueue_script( 'otw-pctl-imagesloaded' );
				wp_enqueue_script( 'otw-pctl' );
				
				if( $count_views ){
					wp_enqueue_script( 'otw-pctl-counter' );
				}
			}
		}
	}
}

/**
 * Include admin styles
 */
if( !function_exists( 'otw_pctl_enqueue_admin_styles' ) ){
	
	function otw_pctl_enqueue_admin_styles(){
	
		global $otw_pctl_grid_manager_object, $otw_pctl_shortcode_object, $otw_pctl_css_version, $otw_pctl_plugin_url;
		
		if( function_exists( 'get_current_screen' ) ){
			
			$screen = get_current_screen();
			
			if( isset( $screen->id ) && strlen( $screen->id ) ){
				$requested_page = $screen->id;
			}
		}
		
		if( preg_match( "/otw\-pctl\-(default|custom)\-templates\-(add|edit)$/", $requested_page ) || preg_match( "/otw\-pctl\-settings$/", $requested_page ) || preg_match( "/otw\-pctl$/", $requested_page ) || ( $requested_page == 'post' ) ){
			
			wp_enqueue_style( 'otw_pctl_admin', $otw_pctl_plugin_url.'css/otw_pctl_admin.css', array( 'thickbox' ), $otw_pctl_css_version );
		}
	}
}

/**
 * Custom templates action
 */
if( !function_exists( 'otw_pctl_custom_templates_action' ) ){
	
	function otw_pctl_custom_templates_action(){
	
		$page_title = '';
		
		$otw_action = '';
		$custom_templates = otw_get_post_custom_templates();
		
		$confirm_text = '';
		$otw_custom_template_values = array();
		$otw_custom_template_values['title'] = esc_html__( 'N/A', 'otw_pctl' );
		
		if( otw_get('action',false) ){
			
			switch( otw_get('action','') ){
			
				case 'delete':
						$otw_action = 'delete_otw_pctl_custom_template';
						$page_title = esc_html__( 'Delete custom template', 'otw_pctl' );
						$confirm_text = esc_html__( 'Please confirm to delete the custom template', 'otw_pctl' );
						
						if( otw_get( 'custom_template', false ) && preg_match( "/^\d+$/", otw_get( 'custom_template', '' ) ) && intval( otw_get( 'custom_template', '' ) ) && array_key_exists( otw_get( 'custom_template', '' ), $custom_templates ) ){
							$otw_custom_template_values['title'] = $custom_templates[ otw_get( 'custom_template', '' ) ]['title'];
						}
					break;
				default:
					    $page_title = esc_html__( 'Invalid action', 'otw_pctl' );
					break;
			}
		}else{
			$page_title = esc_html__( 'Invalid action', 'otw_pctl' );
		}
		
		require_once( 'otw_pctl_custom_templates_action.php');
	}
	
}


/**
 * post category table
 */
if( !function_exists( 'otw_pctl_category_template_table' ) ){
	
	function otw_pctl_category_template_table(){
		
		global $wp_registered_sidebars;
		
		$html = '';
		
		if( otw_post( 'object', false ) && otw_post( 'action', false ) && otw_post( 'action', '' ) == 'otw_pctl_category_template_table' ){
		
			$json_object = json_decode( otw_stripslashes( otw_post( 'object', '' ) ) );
			
			if( gettype( $json_object ) == 'array' ){
			
				$templates = array( 'default' => esc_html__( 'Theme\'s default template (default)', 'otw_pctl' ) ) + otw_get_post_templates();
			
				$html .= "\n<div class=\"otw_ct_table_holder\">";
				
				foreach( $json_object as $row_id => $row ){
				
					if( intval( $row->category ) && isset( $templates[ $row->template ] ) ){
						
						$cat_info = get_category( $row->category );
						
						if( $cat_info->term_id ){
							$html .= "\n<div id=\"otw_ct_row_".$row_id."\" class=\"otw_ct_row\">";
							$html .= "\n<div class=\"otw_ct_cell1\">".$cat_info->name."</div>";
							$html .= "\n<div class=\"otw_ct_cell2\">".$templates[ $row->template ]."</div>";
							$html .= "\n<div class=\"otw_ct_cell3\">";
							
							if( isset( $row->sidebar ) && isset( $wp_registered_sidebars[ $row->sidebar ] ) ){
								$html .= $wp_registered_sidebars[ $row->sidebar ]['name'];
							}else{
								$html .= '&nbsp;';
							}
							$html .= "</div>";
							$html .= "\n<div class=\"otw_ct_cell4\"><a href=\"javascript:;\" class=\"otw_ct_delete\">".__( 'Delete', 'otw_pctl' )."</a></div>";
							$html .= "\n</div>";
						}
					}
				}
				
				$html .= "\n</table>";
			}
		}
		
		echo $html;
		die;
	}
}

function otw_pctl_post_template(){

	global $post, $otw_pctl_grid_manager_object, $otw_pctl_dispatcher;
	
	if( is_single() && !is_archive() && isset( $post->post_type ) && ( $post->post_type == 'post' ) ){
	
		include_once( 'classes/otw_pctl_dispatcher.class.php' );
		
		$otw_pctl_dispatcher = new otw_pctl_Dispatcher();
		$otw_pctl_dispatcher->views_path = dirname(  __FILE__ ).'/views/';
		$otw_pctl_dispatcher->grid_manager_component_object = $otw_pctl_grid_manager_object;
		$otw_pctl_dispatcher->buildPostTemplate( $post );
	}
}

/**
 * media meta boxes
 */
if( !function_exists( 'otw_pctl_media_meta_box' ) ){
	
	function otw_pctl_media_meta_box( $post ){
	    
		$otw_meta_data = get_post_meta( $post->ID, 'otw_bm_meta_data', true );
		
		require_once( 'otw_pctl_media_meta_box.php');
	}
}

/**
 * Add related posts metabox
 */
if( !function_exists( 'otw_pctl_related_posts_meta_box' ) ){
	
	function otw_pctl_related_posts_meta_box( $post ){
		
		$otw_related_posts = otw_pctl_get_post_related_posts( $post->ID );
		
		require_once( 'otw_pctl_related_posts_meta_box.php' );
	}
}

/**
 * Get related posts
 */
if( !function_exists( 'otw_pctl_get_post_related_posts' ) ){
	
	function otw_pctl_get_post_related_posts( $post_id ){
		
		$related_posts = '';
		
		if( $post_id ){
			
			$db_related_posts = get_post_meta( $post_id, 'otw_mb_related_posts', true );
			$related_posts_array = array();
			
			if( strlen( trim( $db_related_posts ) )  ){
				
				$db_related_posts_array = explode( ',', $db_related_posts );
				
				foreach( $db_related_posts_array as $r_post ){
					
					if( intval( $r_post ) ){
						$related_posts_array[ $r_post ] = $r_post;
					}
				}
			}
			
			if( count( $related_posts_array  ) ){
				$related_posts = implode( ',', $related_posts_array  );
			}
		}
		return $related_posts;
	}
}

/**
 * reviews meta boxes
 */
if( !function_exists( 'otw_pctl_reviews_meta_box' ) ){
	
	
	function otw_pctl_reviews_meta_box( $post ){
		
		$otw_mb_reviews_meta_data = get_post_meta( $post->ID, 'otw_mb_reviews_meta_data', true );
		
		$total_reviews = 0;
		
		if( is_array( $otw_mb_reviews_meta_data ) ){
		
			ksort( $otw_mb_reviews_meta_data );
			
			foreach( $otw_mb_reviews_meta_data as $key => $value ){
			
				if( otw_post( 'otw_mb_review_title', false ) && otw_post( array( 'otw_mb_review_title', $key ), false ) ){
					$otw_mb_reviews_meta_data[ $key ]['title'] = otw_post( array( 'otw_mb_review_title', $key ), array() );
				}
				if( otw_post( 'otw_mb_review_rate', false ) && otw_post( array( 'otw_mb_review_rate', $key ), false ) ){
					$otw_mb_reviews_meta_data[ $key ]['rate'] = otw_post( array( 'otw_mb_review_rate', $key ), array() );
				}
			}
			
			$total_reviews = count( $otw_mb_reviews_meta_data );
		}
		
		require_once( 'otw_pctl_reviews_meta_box.php' );
	}
	
}

/**
 * custom fields meta boxes
 */
if( !function_exists( 'otw_pctl_custom_fields_meta_box' ) ){
	
	function otw_pctl_custom_fields_meta_box( $post ){
		
		$otw_post_custom_fields_meta_data = get_post_meta( $post->ID, 'otw_post_custom_fields_meta_data', true );
		
		$total_post_custom_fields = 0;
		
		
		if( is_array( $otw_post_custom_fields_meta_data ) && count( $otw_post_custom_fields_meta_data ) ){
			
			uasort( $otw_post_custom_fields_meta_data, 'otw_pctl_sort_custom_fields' );
			
			foreach( $otw_post_custom_fields_meta_data as $key => $value ){
			
				if( otw_post( 'otw_post_custom_field_title', false ) && otw_post( array( 'otw_post_custom_field_title', $key ), false ) ){
					$otw_post_custom_fields_meta_data[ $key ]['title'] = otw_post( array( 'otw_post_custom_field_title', $key ), array() );
				}
				if( otw_post( 'otw_post_custom_field_description', false ) && otw_post( array( 'otw_post_custom_field_description', $key ), false ) ){
					$otw_post_custom_fields_meta_data[ $key ]['description'] = otw_post( array( 'otw_post_custom_field_description', $key ), array() );
				}
			}
			
			$total_post_custom_fields = count( $otw_post_custom_fields_meta_data );
		}
		
		require_once( 'otw_pctl_custom_fields_meta_box.php' );
	}
	
}

/**
 * custom fields meta boxes
 */
if( !function_exists( 'otw_pctl_sort_custom_fields' ) ){
	
	function otw_pctl_sort_custom_fields( $a, $b ){
		
		if( $a['order'] > $b['order'] ){
			return 1;
		}
		elseif( $a['order'] < $b['order'] ){
			return -1;
		}
		return 0;
	}
	
}

/**
 * tabs meta boxes
 */
if( !function_exists( 'otw_pctl_tabs_meta_box' ) ){
	
	function otw_pctl_tabs_meta_box( $post ){
		
		$otw_pct_tabs_meta_data = get_post_meta( $post->ID, 'otw_mb_tabs_meta_data', true );
		
		$total_tabs = 0;
		
		if( is_array( $otw_pct_tabs_meta_data ) ){
		
			ksort( $otw_pct_tabs_meta_data );
			
			foreach( $otw_pct_tabs_meta_data as $key => $value ){
			
				if( otw_post( 'otw_mb_tab_title', false ) && otw_post( array( 'otw_mb_tab_title', $key ), false ) ){
					$otw_pct_tabs_meta_data[ $key ]['title'] = otw_post( array( 'otw_mb_tab_title', $key ), array() );
				}
				if( otw_post( 'otw_mb_tab_content', false ) && otw_post( array( 'otw_mb_tab_content', $key ), false ) ){
					$otw_pct_tabs_meta_data[ $key ]['content'] = otw_post( array( 'otw_mb_tab_content', $key ), array() );
				}
			}
			
			$total_tabs = count( $otw_pct_tabs_meta_data );
		}
		
		require_once( 'otw_pctl_tabs_meta_box.php');
	}
}

/**
 * options meta box
 */
if( !function_exists( 'otw_pctl_options_meta_box' ) ){
	
	function otw_pctl_options_meta_box( $post ){
		
		$otw_pct_options_meta_data_db = get_post_meta( $post->ID, 'otw_mb_options_meta_data', true );
		
		if( !is_array( $otw_pct_options_meta_data_db ) || !isset( $otw_pct_options_meta_data_db ) ){
			$otw_pct_options_meta_data = array();
			$otw_pct_options_meta_data['otw_pct_options_type'] = 'default';
			$otw_pct_options_meta_data['options'] = array();
		}else{
			$otw_pct_options_meta_data = array();
			$otw_pct_options_meta_data['otw_pct_options_type'] = $otw_pct_options_meta_data_db['otw_pct_options_type'];
			$otw_pct_options_meta_data['options'] = array();
			
			foreach( $otw_pct_options_meta_data_db['options'] as $setting_key => $setting_value )
			{
				$otw_pct_options_meta_data['options'][ $setting_key ] = $setting_value;
			}
		}
		
		$otw_pct_templates = array( 'default' => esc_html__( 'Default Theme\'s Post Template', 'otw_pctl' ) ) + otw_get_post_templates();
		
		$otw_pct_options_meta_data['options'] = otw_pctl_get_settings( $otw_pct_options_meta_data['options'] );
		
		$otw_pct_social_icons = array(
			'' => esc_html__( 'None (default)', 'otw_pctl' ),
			'share_icons' => esc_html__( 'Share Icons', 'otw_pctl' ),
			'share_btn_small' => esc_html__( 'Share Buttons Small', 'otw_pctl' ),
			'share_btn_large' => esc_html__( 'Share Buttons Large', 'otw_pctl' ),
			'like_buttons' => esc_html__( 'Like Buttons', 'otw_pctl' )
		);
		
		$otw_pct_prev_next_nav_options = array(
			'' => esc_html__( 'No (default)', 'otw_pctl' ),
			'yes' => esc_html__( 'Yes', 'otw_pctl' )
		);
		
		$otw_pct_related_posts_options = array(
			'' => esc_html__( 'No (default)', 'otw_pctl' ),
			'yes' => esc_html__( 'Yes', 'otw_pctl' )
		);
		
		$otw_pct_related_posts_criteria_options = array(
			'category' => esc_html__( 'Category (default)', 'otw_pctl' ),
			'post_tag' => esc_html__( 'Tag', 'otw_pctl' )
		);
		
		$otw_pct_social_icons_options =array(
			array( 'value' => '0', 'text' => esc_html__('None (default)', 'otw_pctl') ),
			array( 'value' => 'share_icons', 'text' => esc_html__('Share Icons', 'otw_pctl') ),
			array( 'value' => 'share_btn_small', 'text' => esc_html__('Share Buttons Small', 'otw_pctl') ),
			array( 'value' => 'share_btn_large', 'text' => esc_html__('Share Buttons Large', 'otw_pctl') ),
			array( 'value' => 'like_buttons', 'text' => esc_html__('Like Buttons', 'otw_pctl') ),
			array( 'value' => 'custom_icons', 'text' => esc_html__('Custom Social Icons', 'otw_pctl') )
		);
		
		$total_meta_elements = 6;
		
		$meta_elements = array();
		$meta_elements['author']  = esc_html__( 'author', 'otw_pctl' );
		$meta_elements['date'] = esc_html__( 'date', 'otw_pctl' );
		$meta_elements['category'] = esc_html__( 'category', 'otw_pctl' );
		$meta_elements['tags'] = esc_html__( 'tags', 'otw_pctl' );
		$meta_elements['comments'] = esc_html__( 'comments', 'otw_pctl' );
		$meta_elements['views'] = esc_html__( 'Post Visits', 'otw_pctl' );
		
		$meta_elements_height = ( ( $total_meta_elements + 2 ) * 22 );
		
		$selectMetaData = array(
			array( 'value' => 'horizontal', 'text' => esc_html__('Horizontal (default)', 'otw_pctl') ),
			array( 'value' => 'vertical', 'text' => esc_html__('Vertical', 'otw_pctl') ),
		);
		
		require_once( 'otw_pctl_options_meta_box.php');
	}
}

/**
 * save media meta boxes
 */
if( !function_exists( 'otw_pctl_save_meta_box' ) ){
	
	function otw_pctl_save_meta_box( $post_id ){
	
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
			return;
		}
		
		if( !empty( $_POST ) && !empty( otw_post( 'otw-bm-list-media_type', '' )) ){
			
			$otw_meta_data = array(
				'media_type'      => otw_post( 'otw-bm-list-media_type', '' ),
				'youtube_url'     => otw_post( 'otw-bm-list-youtube_url', '' ),
				'vimeo_url'       => otw_post( 'otw-bm-list-vimeo_url', '' ),
				'soundcloud_url'  => otw_post( 'otw-bm-list-soundcloud_url', '' ),
				'img_url'         => otw_post( 'otw-bm-list-img_url', '' ),
				'slider_url'      => otw_post( 'otw-bm-list-slider_url', '' )
			);
			
			/**
				* Add Custom POST Meta Data
				* If POST is found in the DB it will just be ignored and return FALSE
			*/
			add_post_meta($post_id, 'otw_bm_meta_data', $otw_meta_data, true);
			
			// If POST is in the DB update it
			update_post_meta($post_id, 'otw_bm_meta_data', $otw_meta_data);
		}elseif( !empty( $_POST ) &&  otw_post( 'otw-bm-list-media_type', false )  ){
			delete_post_meta($post_id, 'otw_bm_meta_data');
		}
		
		//save options metaboxs
		if( !empty( $_POST ) && !empty( otw_post( 'otw_pctl_meta_options', '' )) ){
			
			$otw_options_meta = array();
			
			$otw_options_meta['otw_pct_options_type'] = @otw_post( 'otw_pct_options_type', '' );
			$otw_options_meta['options'] = array();
			
			$mb_settings = otw_pctl_get_settings();
			
			foreach( $mb_settings as $otw_setting_key => $default_setting_value )
			{
				if( otw_post( $otw_setting_key, false ) ){
					$otw_options_meta['options'][ $otw_setting_key ] = otw_post( $otw_setting_key, '' );
				}
			}
			
			add_post_meta($post_id, 'otw_mb_options_meta_data', $otw_options_meta, true);
			
			update_post_meta($post_id, 'otw_mb_options_meta_data', $otw_options_meta );
		}
		
		//save tabs meta box
		if( !empty( $_POST ) && !empty( otw_post( 'otw_pct_meta_tabs', '' ) ) ){
			
			$otw_tabs_meta = array();
			
			if( otw_post( 'otw_mb_tab_title', false ) ){
				
				foreach( otw_post( 'otw_mb_tab_title', array() ) as $new_id => $new_title ){
				
					if( strlen( trim( $new_title ) ) || strlen( trim( otw_post( array( 'otw_mb_tab_content', $new_id ), array() ) ) ) ){
					
						$otw_tabs_meta[] = array( 
							'title' => $new_title,
							'content' => otw_post( array( 'otw_mb_tab_content', $new_id ), array() )
						);
					}
				}
			}
			
			add_post_meta($post_id, 'otw_mb_tabs_meta_data', $otw_tabs_meta, true);
			
			update_post_meta($post_id, 'otw_mb_tabs_meta_data', $otw_tabs_meta );
		}
		
		//reviews
		if( !empty( $_POST ) && !empty( otw_post( 'otw_pct_meta_reviews', '' )) ){
			
			$otw_reviews_meta = array();
			
			if( otw_post( 'otw_mb_review_title', false ) ){
				
				foreach( otw_post( 'otw_mb_review_title', array() ) as $new_id => $new_title ){
				
					if( strlen( trim( $new_title ) ) && strlen( trim( otw_post( array( 'otw_mb_review_rate', $new_id ), array() ) ) ) ){
					
						$otw_reviews_meta[] = array( 
							'title' => $new_title,
							'rate' => otw_post( array( 'otw_mb_review_rate', $new_id ), array() )
						);
					}
				}
			}
			
			add_post_meta($post_id, 'otw_mb_reviews_meta_data', $otw_reviews_meta, true);
			
			update_post_meta($post_id, 'otw_mb_reviews_meta_data', $otw_reviews_meta );
		}
		
		//post custom fields
		if( !empty( $_POST ) && !empty( otw_post( 'otw_pct_meta_post_custom_fields', '' )) ){
			
			$otw_post_custom_fields_meta = array();
			
			if( otw_post( 'otw_post_custom_field_title', false ) ){
				
				foreach( otw_post( 'otw_post_custom_field_title', array() ) as $new_id => $new_title ){
					
					if( strlen( trim( $new_title ) ) || strlen( trim( otw_post( array( 'otw_post_custom_field_description', $new_id ), array() ) ) ) ){
						$new_key = count( $otw_post_custom_fields_meta );
						$otw_post_custom_fields_meta[ $new_key ] = array();
						$otw_post_custom_fields_meta[ $new_key ]['title'] = $new_title;
						
						$otw_post_custom_fields_meta[ $new_key ]['description'] = otw_post( array( 'otw_post_custom_field_description', $new_id ), array() );
						$otw_post_custom_fields_meta[ $new_key ]['order'] = otw_post( array( 'otw_post_custom_field_order', $new_id ), array() );
					}
				}
			}
			
			add_post_meta($post_id, 'otw_post_custom_fields_meta_data', $otw_post_custom_fields_meta, true);
			
			update_post_meta($post_id, 'otw_post_custom_fields_meta_data', $otw_post_custom_fields_meta );
		}
		
		//related posts
		if( !empty( $_POST ) && !empty( otw_post( 'otw_pctl_otw_related_posts', '' )) ){
			
			$key_name = 'otw_mb_related_posts';
			
			if( otw_post( 'otw_replated_posts', false ) && strlen( trim( otw_post( 'otw_replated_posts', '' ) ) ) ){
				add_post_meta($post_id, $key_name, otw_post( 'otw_replated_posts', '' ), true);
				update_post_meta($post_id, $key_name, otw_post( 'otw_replated_posts', '' ) );
			}else{
				delete_post_meta($post_id, $key_name );
			}
		}
	}
}

if( !function_exists( 'otw_pctl_social_share' ) ){
	
	function otw_pctl_social_share(){
		
		include( 'otw_pctl_social_shares.php' );
		
		if( otw_post( 'url', false )  && otw_post( 'url', '' ) != '' && filter_var(otw_post( 'url', '' ), FILTER_VALIDATE_URL) ){
			$url = otw_post( 'url', '' );
			$otw_social_shares = new otw_social_shares($url);
			
			echo $otw_social_shares->otw_get_shares();
		} else {
			echo json_encode(array('info' => 'error', 'msg' => 'URL is not valid!'));
		}
		exit;
	}
}

if( !function_exists( 'otw_pctl_get_video' ) ){
	
	function otw_pctl_get_video(){
		
		$post_id = 0;
		
		if( otw_get( 'post_id', false ) && intval( otw_get( 'post_id', '' ) ) ){
			$post_id = otw_get( 'post_id', '' );
			$list_id = 0;
			
			$view_type = '';
			
			if( otw_get( 'vr', false ) && (  otw_get( 'vr', '' ) == 'item_media' ) ){
				$view_type =  otw_get( 'vr', '' );
			}
			elseif( otw_get( 'vr', false ) && (  otw_get( 'vr', '' ) == 'list_media' ) ){
			
				if( otw_get( 'list_id', false ) && intval( otw_get( 'list_id', '' ) ) ){
					$list_id = otw_get( 'list_id', '' );
					$view_type =  otw_get( 'vr', '' );
				}
			}
			
			if( $post_id ){
				$post = get_post( $post_id );
				
				if( isset( $post->ID ) && ( $post->post_type == 'post' ) ){
					
					$postMetaData = get_post_meta( $post->ID, 'otw_bm_meta_data', true );
					
					if( $view_type == 'item_media' ){
						
						include_once( 'classes/otw_pctl_dispatcher.class.php' );
						$otw_pctl_dispatcher = new otw_pctl_Dispatcher();
						
						$videoWidth = '1024';
						$videoHeight = '640';
						
						$item_options = get_post_meta( $post->ID, 'otw_pct_options_meta_data', true );
						
						if( isset( $item_options['otw_pct_options_type'] ) && ( $item_options['otw_pct_options_type'] == 'custom' ) ){
							
							if( isset( $item_options['options'] ) ){
							
								if( isset( $item_options['options']['otw_pct_item_media_lightbox_width'] ) && strlen( $item_options['options']['otw_pct_item_media_lightbox_width'] ) ){
									$videoWidth = $item_options['options']['otw_pct_item_media_lightbox_width'];
								}
								
								if( isset( $item_options['options']['otw_pct_item_media_lightbox_height'] ) && strlen( $item_options['options']['otw_pct_item_media_lightbox_height'] ) ){
									$videoHeight = $item_options['options']['otw_pct_item_media_lightbox_height'];
								}
							}
						}else{
							$plugin_options = otw_pctl_get_options();
							
							if( isset( $plugin_options['otw_pct_item_media_lightbox_width'] ) && strlen( $plugin_options['otw_pct_item_media_lightbox_width'] ) ){
								$videoWidth = $plugin_options['otw_pct_item_media_lightbox_width'];
							}
							
							if( isset( $plugin_options['otw_pct_item_media_lightbox_height'] ) && strlen( $plugin_options['otw_pct_item_media_lightbox_height'] ) ){
								$videoHeight = $plugin_options['otw_pct_item_media_lightbox_height'];
							}
						}
					}
					
					
					if( isset( $postMetaData['media_type'] ) ){
					
						switch( $postMetaData['media_type'] ){
						
							case 'youtube':
									if( !empty( $postMetaData['youtube_url'] ) ){
										
										if( in_array( $view_type, array( 'item_media', 'list_media' ) ) ){
											echo $otw_pctl_dispatcher->otwEmbedResize( wp_oembed_get($postMetaData['youtube_url'], array('width' => $videoWidth)), $videoWidth, $videoHeight, 'center_center' );
										}else{
											echo wp_oembed_get( $postMetaData['youtube_url'] );
										}
										die;
									}
								break;
							case 'vimeo':
									if( !empty( $postMetaData['vimeo_url'] ) ){
										
										if( in_array( $view_type, array( 'item_media', 'list_media' ) ) ){
											echo $otw_pctl_dispatcher->otwEmbedResize( wp_oembed_get($postMetaData['vimeo_url'], array('width' => $videoWidth)), $videoWidth, $videoHeight, 'center_center' );
										}else{
											echo wp_oembed_get( $postMetaData['vimeo_url'] );
										}
										die;
									}
								break;
							case 'soundcloud':
									if( !empty( $postMetaData['soundcloud_url'] ) ){
										
										if( in_array( $view_type, array( 'item_media', 'list_media' ) ) ){
											echo $otw_pctl_dispatcher->otwEmbedResize( wp_oembed_get($postMetaData['soundcloud_url'], array('width' => $videoWidth)), $videoWidth, $videoHeight, 'center_center' );
										}else{
											echo wp_oembed_get( $postMetaData['soundcloud_url'] );
										}
										die;
									}
								break;
						}
					}
				}
			}
			
		}
		_e( 'Video not found', 'otw_pctl' );
		die;
	}
}
if( !function_exists( 'otw_pctl_get_options' ) ){
	
	function otw_pctl_get_options(){
		
		$otw_pct_plugin_options = otw_pctl_get_settings();
		
		$db_otw_pct_plugin_options = get_option( 'otw_mb_plugin_options' );
		
		
		foreach( $otw_pct_plugin_options as $key => $value ){
		
			if( is_array( $db_otw_pct_plugin_options ) && isset( $db_otw_pct_plugin_options[ $key ] ) ){
			
				$otw_pct_plugin_options[ $key ] = $db_otw_pct_plugin_options[ $key ];
			}
		}
		
		return $otw_pct_plugin_options;
	}
}


/**
 * Count post previews
 */
if( !function_exists( 'otw_pctl_count_post_previews' ) ){
	
	function otw_pctl_count_post_previews(){
		
		if( otw_post( 'post_id', false ) && intval( otw_post( 'post_id', '' ) ) ){
			
			$post = get_post( otw_post( 'post_id', '' ) );
			
			if( isset( $post->ID ) && ( $post->ID ) ){
				
				$current_count = get_post_meta( $post->ID, 'otw_cpp', true );
				
				if( !$current_count || !intval( $current_count ) ){
					$current_count = 0;
				}
				$current_count++;
				
				update_post_meta( $post->ID, 'otw_cpp', $current_count );
				
			}
		}
		
		die;
	}
}

/**
 * factory messages
 */
if( !function_exists( 'otw_pctl_factory_message' ) ){
	function otw_pctl_factory_message( $params ){
		
		global $otw_pctl_plugin_id;
		
		if( isset( $params['plugin'] ) && $otw_pctl_plugin_id == $params['plugin'] ){
			
			//filter out some messages if need it
		}
		if( isset( $params['message'] ) )
		{
			return $params['message'];
		}
		return $params;
	}
}