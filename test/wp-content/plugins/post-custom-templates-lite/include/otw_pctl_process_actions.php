<?php
/**
 * Process otw actions
 *
 */
if( otw_post( 'otw_pctl_action', false ) && current_user_can( 'manage_options' ) ){

	switch( otw_post( 'otw_pctl_action', '' ) ){
		
		case 'manage_otw_pctl_custom_templates':
				
				$otw_pctl_validate_messages = array();
				$valid_page = true;
				
				if( check_admin_referer( 'otw-pctl-manage' ) ){
					
					if( $valid_page ){
						$otw_custom_templates = otw_get_post_custom_templates();
						
						if( otw_get( 'custom_template', false ) && isset( $otw_custom_templates[ otw_get( 'custom_template', '' ) ] ) ){
							$otw_custom_template_id = otw_get( 'custom_template', '' );
							$db_custom_template = $otw_custom_templates[ otw_get( 'custom_template', '' ) ];
						}else{
							$db_custom_template = array();
							$otw_custom_template_id = false;
						}
						
						$db_custom_template['title'] = (string) otw_stripslashes( otw_post( 'pctl_custom_template_title', '' ) );
						$db_custom_template['grid_content'] = otw_post( array( '_otw_post_template_grid_manager_content', 'code' ), '' );
						$db_custom_template['cs'] = array();
						$db_custom_template['options'] = array();
						
						$otw_pct_plugin_custom_template_options = otw_pctl_get_custom_template_settings();
						
						foreach( $otw_pct_plugin_custom_template_options as $setting_key => $value ){
						
							$db_custom_template['options'][ $setting_key ] = $value;
							
							if( otw_post( $setting_key, false ) ){
								$db_custom_template['options'][ $setting_key ] = otw_post( $setting_key, '' );
							}elseif( preg_match( "/otw_pct_show_social_icons_/", $setting_key ) ){
								$db_custom_template['options'][ $setting_key ] = 0;
							}
						}
						
						$content_sidebars_variables = otw_pctl_get_content_sidebars_settings();
						
						foreach( $content_sidebars_variables['cs'] as $cs_key => $cs_value ){
							
							$db_custom_template['cs'][ $cs_key ] = $cs_value;
							
							if( otw_post( 'otw_cs_'.$cs_key.'_pct', false ) ){
								$db_custom_template['cs'][ $cs_key ] = otw_post( 'otw_cs_'.$cs_key.'_pct', '' );
							}
						}
						
						if( $otw_custom_template_id === false ){
							
							$otw_custom_template_id = otw_get_next_post_custom_template_id();
							$db_custom_template['id'] = $otw_custom_template_id;
						}
						$otw_custom_templates[ $otw_custom_template_id ] = $db_custom_template;
						
						global $wpdb;
						
						if( !update_option( 'otw_mb_custom_templates', $otw_custom_templates ) && $wpdb->last_error ){
							$valid_page = false;
							$otw_pctl_validate_messages[] = esc_html__( 'DB Error: ', 'otw_pctl' ).$wpdb->last_error.'. Tring to save '.strlen( maybe_serialize( $otw_custom_templates ) ).' bytes.';
						}else{
							wp_redirect( 'admin.php?page=otw-pctl-custom-templates-edit&custom_template='.$otw_custom_template_id.'&message=1' );
						}
					}
				}
			break;
		case 'delete_otw_pctl_custom_template':
				
				if( otw_post( 'cancel', false ) ){
					wp_redirect( 'admin.php?page=otw-pctl' );
				
				}elseif( check_admin_referer( otw_post( 'otw_pctl_action', '' )  ) ){
					
					$otw_custom_templates = otw_get_post_custom_templates();
					
					if( otw_get( 'custom_template', false ) && isset( $otw_custom_templates[ otw_get( 'custom_template', '' ) ] ) ){
						
						$otw_custom_template_id = otw_get( 'custom_template', '' );
						
						$new_custom_templates = array();
						
						//remove the detail from otw_details
						foreach( $otw_custom_templates as $custom_template_key => $custom_template ){
							
							if( $custom_template_key != $otw_custom_template_id ){
								
								$new_custom_templates[ $custom_template_key ] = $custom_template;
							}
						}
						
						update_option( 'otw_mb_custom_templates', $new_custom_templates );
						wp_redirect( 'admin.php?page=otw-pctl&message=2' );
					}
				}
			break;
		case 'manage_otw_pctl_options':
				
				if( check_admin_referer( 'otw-pctl-options' ) ){
					
					global $wp_filesystem;
					
					$upload_dir = wp_upload_dir();
					
					if( otw_init_filesystem() ){
						
						if( isset( $upload_dir['basedir'] ) && $wp_filesystem->is_writable( $upload_dir['basedir'] ) ){
							
							global $otw_pctl_custom_css_path;
							
							if( otw_post( 'otw_pctl_custom_css', false ) && strlen( trim( otw_post( 'otw_pctl_custom_css', '' ) ) ) ){
							
								if( isset( $upload_dir['basedir'] ) && $wp_filesystem->is_writable( $upload_dir['basedir'] ) ){
									
									if( !$wp_filesystem->is_dir( $upload_dir['basedir'].'/otwpct' ) ){
										$wp_filesystem->mkdir( $upload_dir['basedir'].'/otwpct' );
									}
								}
								
								if( $wp_filesystem->is_dir( $upload_dir['basedir'].'/otwpct' ) && $wp_filesystem->is_writable( $upload_dir['basedir'].'/otwpct' ) ){
								
									$wp_filesystem->put_contents( $otw_pctl_custom_css_path, (string) otw_stripslashes( otw_post( 'otw_pctl_custom_css', '' ) ) );
								}
								
							}elseif( $wp_filesystem->exists( $otw_pctl_custom_css_path ) ){
								$wp_filesystem->delete( $otw_pctl_custom_css_path );
							}
						}
					}
					
					if( !empty( $_POST ) && otw_post( 'otw_pctl_save_settings', false ) && ( otw_post( 'otw_pctl_save_settings', '' ) == 1 ) ){
					
						$otw_pct_plugin_options_db = get_option( 'otw_mb_plugin_options' );
						
						if( !is_array( $otw_pct_plugin_options_db ) ){
							$otw_pct_plugin_options = array();
						}else{
							$otw_pct_plugin_options = array();
							
							foreach( $otw_pct_plugin_options_db as $setting_key => $setting_value ){
								$otw_pct_plugin_options[ preg_replace( "/^otw_pct_/", 'otw_mb_', $setting_key ) ] = $setting_value;
							}
						}
						
						$pct_settings = otw_pctl_get_settings();
						
						foreach( $pct_settings as $setting_key => $default_value )
						{
							$otw_pct_plugin_options[ $setting_key ] = $default_value;
							
							if( otw_post( $setting_key, false ) ){
								$otw_pct_plugin_options[ $setting_key ] = otw_post( $setting_key, '' );
							}
						}
						
						update_option( 'otw_mb_plugin_options', $otw_pct_plugin_options );
					
					}
					
					if( otw_post( 'otw_pct_promotions', false ) && !empty( otw_post( 'otw_pct_promotions', '' ) ) ){
						
						global $otw_pctl_factory_object, $otw_pctl_plugin_id;
						
						update_option( $otw_pctl_plugin_id.'_dnms', otw_post( 'otw_pct_promotions', '' ) );
						
						if( is_object( $otw_pctl_factory_object ) ){
							$otw_pctl_factory_object->retrive_plungins_data( true );
						}
					}
					
					wp_redirect( admin_url( 'admin.php?page=otw-pctl-settings&message=1' ) );
				}
			break;
	}
}