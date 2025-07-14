<div class="otw-cs-admin_content">
	<div class="updated"><p><?php echo $this->get_label( 'OTW Content Sidebars are positions that you can set and use to add sidebars to your pages/posts. Bellow you set the defaults for you post and pages. If you want a different configuration for a certain page or post you can override defaults from the OTW Content Sidebar metabox when you edit that page or post.' )?></p></div>
	<?php if ( $message ) : ?>
		<br />
		<div id="message" class="updated"><p><?php echo esc_html( $message ); ?></p></div>
	<?php endif; ?>
	<div class="form-wrap" id="poststuff">
		<form method="post" action="" class="validate">
			<input type="hidden" name="otw_cs_action" value="save_default_settings" />
			<?php wp_original_referer_field(true, 'previous'); wp_nonce_field('otw-cs-default-settings'); ?>
			<div id="post-body">
				<div id="post-body-content">
					<div class="meta-box-sortables">
						<?php foreach( $this->item_types as $item_type => $type_info ){?>
						<div class="postbox">
							<div title="<?php esc_html_e('Click to toggle')?>" class="handlediv sitem_toggle"><br></div>
							<h3 class="hndle sitem_header"><span><?php echo esc_html( $type_info[0] )?> <?php echo $this->get_label( 'default settings for OTW Content Sidebars')?></span></h3>
							<div class="inside otw-cs-content">
								<div class="otw-cs-layout-selection">
									<div class="otw-form-control">
										<label for=""><?php echo $this->get_label('OTW Sidebars Configuration')?></label>
										<ul class="otw-cs-layout-type">
											<li><img src="<?php echo $this->component_url ?>img/layout-1c.png" alt="" id="<?php echo $item_type?>_1c" <?php echo ( $default_values[$item_type]['layout'] == '1c' )?'class="otw-selected"':''?>/></li>
											<li><img src="<?php echo $this->component_url ?>img/layout-2cl.png" alt="" id="<?php echo $item_type?>_2cl" <?php echo ( $default_values[$item_type]['layout'] == '2cl' )?'class="otw-selected"':''?>/></li>
											<li><img src="<?php echo $this->component_url ?>img/layout-2cr.png" alt="" id="<?php echo $item_type?>_2cr" <?php echo ( $default_values[$item_type]['layout'] == '2cr' )?'class="otw-selected"':''?>/></li>
											<li><img src="<?php echo $this->component_url ?>img/layout-3cl.png" alt="" id="<?php echo $item_type?>_3cl" <?php echo ( $default_values[$item_type]['layout'] == '3cl' )?'class="otw-selected"':''?>/></li>
											<li><img src="<?php echo $this->component_url ?>img/layout-3cm.png" alt="" id="<?php echo $item_type?>_3cm" <?php echo ( $default_values[$item_type]['layout'] == '3cm' )?'class="otw-selected"':''?>/></li>
											<li><img src="<?php echo $this->component_url ?>img/layout-3cr.png" alt="" id="<?php echo $item_type?>_3cr" <?php echo ( $default_values[$item_type]['layout'] == '3cr' )?'class="otw-selected"':''?>/></li>
										</ul>
										<input type="hidden" name="otw_cs_layout_<?php echo esc_attr( $item_type )?>" id="otw_cs_layout_<?php echo esc_attr( $item_type )?>" value="<?php echo esc_attr( $default_values[$item_type]['layout'] )?>" />
										<span class="otw-form-hint"><?php echo $this->get_label( 'Choose sidebar configuration.' )?></span>
									</div>
								</div>
								<div class="otw-cs-sidebars-selection">
									<div id="otw_cs_sidebar1_container_<?php echo esc_attr( $item_type ) ?>">
										<?php echo OTW_Form::select( array( 'id' => 'otw_cs_sidebar1_'.$item_type, 'label' => $this->get_label( 'OTW Primary Sidebar' ), 'name' => 'otw_cs_sidebar1_'.$item_type, 'value' => $default_values[ $item_type ]['sidebar1_id'], 'options' => $available_sidebars, 'description' => $this->get_label( 'Choose what sidebar should be displayed as OTW Primary sidebar.' ) ) ); ?>
									</div>
									<div id="otw_cs_sidebar2_container_<?php echo esc_attr( $item_type ) ?>">
										<?php echo OTW_Form::select( array( 'id' => 'otw_cs_sidebar2_'.$item_type, 'name' => 'otw_cs_sidebar2_'.$item_type, 'value' => $default_values[ $item_type ]['sidebar2_id'], 'options' => $available_sidebars, 'label' => $this->get_label( 'OTW Secondary Sidebar' ), 'description' => $this->get_label( 'OTW Secondary Sidebar' ) ) ); ?>
									</div>
								</div>
								<div class="otw-cs-sidebar-width-selection">
									<div id="otw_cs_sidebar-width1_container_<?php echo esc_attr( $item_type ) ?>">
										<?php echo OTW_Form::select( array( 'id' => 'otw_cs_sidebar1_size_'.$item_type, 'name' => 'otw_cs_sidebar1_size_'.$item_type, 'value' => $default_values[ $item_type ]['sidebar1_size'], 'options' => $available_sidebar_sizes,  'label' => $this->get_label( 'OTW Primary Sidebar Width' ), 'description' => $this->get_label( 'Choose the width for your Primary sidebar in columns. The whole content area including sidebars is 24 columns.' ) ) ); ?>
									</div>
									<div id="otw_cs_sidebar-width2_container_<?php echo esc_attr( $item_type ) ?>">
										<?php echo OTW_Form::select( array( 'id' => 'otw_cs_sidebar2_size_'.$item_type, 'name' => 'otw_cs_sidebar2_size_'.$item_type, 'value' => $default_values[ $item_type ]['sidebar2_size'], 'options' => $available_sidebar_sizes, 'label' => $this->get_label( 'OTW Secondary Sidebar Width' ), 'description' => $this->get_label( 'Choose the width for your Secondary sidebar in columns. The whole content area including sidebars is 24 columns.' ) ) ); ?>
									
									</div>
								</div>
							</div>
						</div>
						<?php }?>
						<p class="submit">
							<input type="submit" value="<?php echo $this->get_label( 'Save Default Settings' ) ?>" name="submit" class="button"/>
						</p>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>