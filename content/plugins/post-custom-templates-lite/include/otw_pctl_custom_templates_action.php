<div class="wrap">
	<div id="icon-options-general" class="icon32"></div>
	<h2><?php echo esc_html( $page_title ) ?>
		<a class="button add-new-h2" href="admin.php?page=otw-pctl"><?php esc_html_e('Back to custom templates list', 'otw_pctl') ?></a>
	</h2>
	<div class="form-wrap" id="poststuff">
		<form method="post" action="" class="validate">
			<input type="hidden" name="otw_pctl_action" value="<?php echo esc_attr( $otw_action )?>" />
			<?php wp_original_referer_field(true, 'previous'); wp_nonce_field( $otw_action ); ?>
			<div id="post-body">
				<div id="post-body-content">
					<div id="col-right">
						<div class="form-field form-required">
							<?php esc_html_e( 'Single Posts Template title', 'otw_pctl' );?>:
							<strong><?php echo esc_html( $otw_custom_template_values['title'] )?></strong>
						</div>
					</div>
					<div id="col-left">
						<p>
							<?php echo $confirm_text;?>
						</p>
						<p class="submit">
							<input type="submit" value="<?php esc_html_e( 'Confirm', 'otw_pctl') ?>" name="submit" class="button"/>
							<input type="submit" value="<?php esc_html_e( 'Cancel', 'otw_pctl' ) ?>" name="cancel" class="button"/>
						</p>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>