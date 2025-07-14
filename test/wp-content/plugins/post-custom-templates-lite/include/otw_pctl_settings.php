<?php

global $otw_pctl_plugin_id;


$db_values = array();
$db_values['otw_pct_promotions'] = get_option( $otw_pctl_plugin_id.'_dnms' );

if( empty( $db_values['otw_pct_promotions'] ) ){
	$db_values['otw_pct_promotions'] = 'on';
}


$message = '';
$massages = array();
$messages[1] = esc_html__( 'Options saved', 'otw_pctl' );

if( otw_get('message',false) && isset( $messages[ otw_get('message','') ] ) ){
	$message .= $messages[ otw_get('message','') ];
}
?>
<?php if ( $message ) : ?>
<div id="message" class="updated"><p><?php echo esc_html( $message ); ?></p></div>
<?php endif; ?>
<div class="wrap">
	<div id="icon-edit" class="icon32"><br/></div>
	<h2>
		<?php esc_html_e('Plugin Options', 'otw_pctl') ?>
	</h2>
	<?php include_once( 'otw_pctl_help.php' ); ?>
	<form name="otw-pctl-list-style" method="post" action="" class="validate otw-pctl-options-form">
		<h3><?php esc_html_e('Post Template Selection', 'otw_pctl'); ?></h3>
		<div class="otw_pctl_sp_settings" id="otw_pctl_settings">
			<table class="form-table">
				<tr>
					<th scope="row"><label for="otw_pct_template"><?php esc_html_e('Single post template', 'otw_pctl'); ?></label></th>
					<td>
						<select id="otw_pct_template" name="otw_pct_template">
						<?php foreach( $otw_pct_templates as $template_key => $template_name ){?>
							<?php
								$selected = '';
								if( isset( $otw_pct_plugin_options['otw_pct_template'] ) && ( $otw_pct_plugin_options['otw_pct_template'] == $template_key ) ){
									$selected = ' selected="selected"';
								}
							?>
							<?php if( $template_key == '-' ){ ?>
								<option disabled="disabled">------------------------------------------</option>
							<?php }else{ ?>
								<option value="<?php echo esc_attr( $template_key )?>"<?php echo $selected?>><?php echo esc_html( $template_name )?></option>
							<?php } ?>
						<?php }?>
						</select>
						<p class="description"><?php esc_html_e( 'This is the template that applies to all of your single posts.', 'otw_pctl' )?></p>
					</td>
				</tr>
			</table>
		</div>
		<div>
			<p class="submit">
				<input type="submit" value="<?php esc_html_e( 'Save', 'otw_pctl') ?>" name="submit" class="button button-primary button-hero"/>
			</p>
		</div>
		<h3><?php esc_html_e('Promotion messages', 'otw_pctl'); ?></h3>
		<div class="otw_pctl_sp_settings" >
			<div class="form-field">
				<label for="otw_pct_promotions"><?php esc_html_e('Show OTW Promotion Messages in my WordPress admin', 'otw_pctl'); ?></label>
				<select id="otw_pct_promotions" name="otw_pct_promotions">
					<option value="on" <?php echo ( isset( $db_values['otw_pct_promotions'] ) && ( $db_values['otw_pct_promotions'] == 'on' ) )? 'selected="selected"':''?>>on(default)</option>
					<option value="off"<?php echo ( isset( $db_values['otw_pct_promotions'] ) && ( $db_values['otw_pct_promotions'] == 'off' ) )? 'selected="selected"':''?>>off</option>
				</select>
			</div>
		</div>
		<h3><?php esc_html_e('Custom CSS', 'otw_pctl'); ?></h3>
		<p class="description"><?php esc_html_e('Adjust your own CSS for all of your templates. Please use with caution.', 'otw_pctl'); ?></p>
		<div>
			<textarea name="otw_pctl_custom_css" cols="100" rows="35" class="otw-pctl-custom-css"><?php echo otw_esc_text( $customCss );?></textarea>
			<p class="submit">
				<input type="hidden" name="otw_pctl_save_settings" value="1" />
				<input type="hidden" name="otw_pctl_action" value="manage_otw_pctl_options" />
				<?php wp_original_referer_field(true, 'previous'); wp_nonce_field('otw-pctl-options'); ?>
				<input type="submit" value="<?php esc_html_e( 'Save', 'otw_pctl') ?>" name="submit" class="button button-primary button-hero"/>
			</p>
		</div>
	</form>
</div>
