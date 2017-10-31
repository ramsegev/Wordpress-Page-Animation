<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       www
 * @since      1.0.0
 *
 * @package    Page_animation
 * @subpackage Page_animation/admin/partials
 */
 ?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	<p>Choose a page and add animation</p>
	<div id="pa_left">
		<div id="Posts">
			<?php 	
			global $wp_post_types;
			$post_types =  array_keys( $wp_post_types );
			add_action('admin_init', function(){
				$post_types = get_post_types( array( 'public' => true ), 'names' ); 
				//var_dump($post_types);
			});
			if ( $post_types ) { // If there are any custom public post types.
			?>
			<fieldset class="page-animation-admin-post-list">
			<select name="post_types" id="post_type_list">
				<option selected="selected">Choose post type</option>
			<?php
			foreach ( $post_types  as $post_type ) {
				?><option value="<?= $post_type ?>"><?= $post_type ?></option>
			<?php } ?>
			</select> 
			<?php } 
				$options = get_option($this->plugin_name);     
			?>
			<select name="post_types_post" id="post_type_list_posts" ></select>
			
			</fieldset>
			<div class="animation_fields">
				<form id="frm_edit" method="post" name="save_animation" action="options.php">
					<?php
						settings_fields( $this->plugin_name );
						do_settings_sections( $this->plugin_name );					
					?>
					<input type="hidden" id="post_id" name=<?php echo $this->plugin_name;?>[post_id] />
					<input type="hidden" id="get_animation" name=<?php echo $this->plugin_name;?>[get_animation] />
					<input type="hidden" id="index" name=<?php echo $this->plugin_name;?>[index] value="0" />
					<input type="hidden" id="selector" name=<?php echo $this->plugin_name;?>[selector] />
					<div class="frm_submit">
					<?php
						/* $options_li = get_option('pa_license_key');
						if($options_li){ */
							submit_button(__('Save Animations', $this->plugin_name), 'primary','submit_animation', TRUE);
						//}
					?>
					</div>
					<div id="accordion" class="accordion filedset_container"></div>
						<div id="show_page"></div>
						<div class="frm_submit">
					<?php
						/* $options_li = get_option('pa_license_key');
						if($options_li){ */
							submit_button(__('Save Animations', $this->plugin_name), 'primary','submit_animation', TRUE);
						//}
					?>
					</div>

				</form>
			</div>
		</div>
	</div>
</div>
<?php 
	/* 	$get_page_animation_Admin = new Page_animation_Admin();
	//$Page_animation_Admin->pa_rs();
	$get_page_animation_Admin->pa_rs()->add_filter( 'connect_url', 'pa_rs_settings_url' );
	$get_page_animation_Admin->this->pa_rs()->add_filter( 'after_skip_url', 'pa_rs_settings_url' );
	$get_page_animation_Admin->pa_rs()->add_filter( 'after_connect_url', 'pa_rs_settings_url' );
	$get_page_animation_Admin->pa_rs()->add_filter( 'after_pending_connect_url', 'pa_rs_settings_url' ); */

?>