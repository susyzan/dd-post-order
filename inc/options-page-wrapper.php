<?php
/**
 * Created by PhpStorm.
 * User: susannazanatta
 * Date: 8/09/2016
 * Time: 9:33 AM
 */

?>

<div class="wrap">

	<div id="icon-options-general" class="icon32"></div>
	<h1>Manage Custom Post Ordering Options</h1>


	<?php if( get_option( 'dd_custom_post_order_on_off' ) === 'on' ) : ?>
		<div class="notice notice-info inline"><p><?php printf( esc_attr__( 'Custom order is on. Your posts are displayed according to your custom order' ) ); ?></p></div>
	<?php else : ?>
		<div class="notice notice-warning"><p><?php printf( esc_attr__( 'Custom order is off. Your posts are displayed as WordPress default' ) ); ?></p></div>
	<?php endif; ?>



	<div id="poststuff">

		<div id="post-body" class="metabox-holder columns-2">

			<!-- main content -->
			<div id="post-body-content">

				<div class="meta-box-sortables ui-sortable">

					<div class="postbox">

						<div class="handlediv" title="Click to toggle"><br></div>
						<!-- Toggle -->

						<h2 class="hndle"><span>Turn on/on drag and drop post ordering</span>
						</h2>


						<div class="inside">
							<p>When this is off,  you will be able to drag and drop to reorder but post will display as default</p>

							<form method="post" action="">
								<?php if( get_option( 'dd_custom_post_order_on_off' ) === 'on' ) :
									submit_button( 'Turn OFF custom order', 'save_custom_order_off', 'save_custom_order_on_off' );
								else :
									submit_button( 'Turn ON custom order', 'save_custom_order_on', 'save_custom_order_on_off' );
								endif;
								?>
							</form>

						</div>
						<!-- .inside -->

					</div>
					<!-- .postbox -->

				</div>
				<!-- .meta-box-sortables .ui-sortable -->

				<div class="meta-box-sortables ui-sortable">

					<div class="postbox">

						<div class="handlediv" title="Click to toggle"><br></div>
						<!-- Toggle -->

						<h2 class="hndle"><span>Manage who can access the post ordering functionality</span>
						</h2>

						<div class="inside">


							<form method="post" action="">
								<fieldset id="users_can_custom_post_order">
									<?php $roles  = get_editable_roles();
									$current_user = wp_get_current_user();

									foreach( $roles as $key => $role ) :

										$checked = isset( $role[ 'capabilities' ][ 'order_posts' ] ) ? 'checked="checked"' : '';
										$disabled = ( $key == 'administrator' || in_array( $key, $current_user->roles ) ) ? 'disabled' : '';
										?>

								<legend class="screen-reader-text"><span><?php echo $role['name'] ?></span></legend>
								<label for="users_can_custom_post_order">
									<input name="" type="checkbox" class="users_can_custom_post_order" value="<?php echo $key; ?>" <?echo $checked . ' ' . $disabled; ?>/>
									<span><?php esc_attr_e(  $role['name'] ); ?></span>
								</label>
									</br>

									<?php endforeach; ?>
								</fieldset>
							<br>
								<?php submit_button( 'Save Settings', 'button-secondary', 'dd-custom-order-save-capability_settings', true ); ?>
								</form>
						</div>
						<!-- .inside -->

					</div>
					<!-- .postbox -->

				</div>
				<!-- .meta-box-sortables .ui-sortable -->

			</div>
			<!-- post-body-content -->

			<!-- sidebar -->
			<div id="postbox-container-1" class="postbox-container">

				<div class="meta-box-sortables">

					<div class="postbox">

						<div class="handlediv" title="Click to toggle"><br></div>
						<!-- Toggle -->

						<h2 class="hndle"><span>How to use this plugin</span></h2>

						<div class="inside">
							<p>Some instruction on how to use this plugin</p>
						</div>
						<!-- .inside -->

					</div>
					<!-- .postbox -->

				</div>
				<!-- .meta-box-sortables -->

			</div>
			<!-- #postbox-container-1 .postbox-container -->

		</div>
		<!-- #post-body .metabox-holder .columns-2 -->

		<br class="clear">
	</div>
	<!-- #poststuff -->

</div> <!-- .wrap -->