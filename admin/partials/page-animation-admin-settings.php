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
	<div id="pa_right">
		<div class="tab">
			<button class="tablinks" onclick="choose_edit(event, 'pa_activate')">Activte</button>
			<button class="tablinks" onclick="choose_edit(event, 'pa_settings')">Settings</button>
			<button class="tablinks" onclick="choose_edit(event, 'pa_dashbord')">Dashbord</button>
		</div>
		<div id="pa_activate" class="tabcontent">
			<h2>Activate Page Animation</h2>
		<?php
			$secret_key = '59463bf989df04.78313112';
			$license_server_url = 'http://www.webcontrol.co.il/tests';
			$item_reference = 'Page Animation';
			if (isset($_REQUEST['activate_license'])) {
				$license_key = $_REQUEST['sample_license_key'];
				// API query parameters
				$api_params = array(
					'slm_action' => 'slm_activate',
					'secret_key' => $secret_key,
					'license_key' => $license_key,
					'registered_domain' => $_SERVER['SERVER_NAME'],
					'item_reference' => urlencode($item_reference),
				);
				// Send query to the license manager server
				$query = esc_url_raw(add_query_arg($api_params, $license_server_url));
				$response = wp_remote_get($query, array('timeout' => 20, 'sslverify' => false));
				// Check for error in the response
				if (is_wp_error($response)){
					echo "Unexpected Error! The query returned with an error.";
				}
				//var_dump($response);//uncomment it if you want to look at the full response     
				// License data.
				$license_data = json_decode(wp_remote_retrieve_body($response));
				// TODO - Do something with it.
				//var_dump($license_data);//uncomment it to look at the data
				if($license_data->result == 'success'){//Success was returned for the license activation
					//Uncomment the followng line to see the message that returned from the license server
				   echo '<br />The following message was returned from the server: '.$license_data->message;
					//Save the license key in the options table
					update_option('pa_license_key', $license_key); 
				}
				else{
					//Show error to the user. Probably entered incorrect license key.
					//Uncomment the followng line to see the message that returned from the license server
					echo '<br />The following message was returned from the server: '.$license_data->message;
				}
			}
			/*** End of license activation ***/
			/*** License activate button was clicked ***/
			if (isset($_REQUEST['deactivate_license'])) {
				$license_key = $_REQUEST['sample_license_key'];
				// API query parameters
				$api_params = array(
					'slm_action' => 'slm_deactivate',
					'secret_key' => $secret_key,
					'license_key' => $license_key,
					'registered_domain' => $_SERVER['SERVER_NAME'],
					'item_reference' => urlencode($item_reference),
				);
				// Send query to the license manager server
				$query = esc_url_raw(add_query_arg($api_params, $license_server_url));
				$response = wp_remote_get($query, array('timeout' => 20, 'sslverify' => false));
				// Check for error in the response
				if (is_wp_error($response)){
					echo "Unexpected Error! The query returned with an error.";
				}
				//var_dump($response);//uncomment it if you want to look at the full response
				// License data.
				$license_data = json_decode(wp_remote_retrieve_body($response));
				// TODO - Do something with it.
				//var_dump($license_data);//uncomment it to look at the data
				if($license_data->result == 'success'){//Success was returned for the license activation
				//Uncomment the followng line to see the message that returned from the license server
					echo '<br />The following message was returned from the server: '.$license_data->message;
					//Remove the licensse key from the options table. It will need to be activated again.
					update_option('pa_license_key', '');
				}
				else{
					//Show error to the user. Probably entered incorrect license key.
					//Uncomment the followng line to see the message that returned from the license server
					echo '<br />The following message was returned from the server: '.$license_data->message;
				}
			}
			/*** End of sample license deactivation ***/
			?>
			<p>Please enter the license key for Page Animation to activate it. You were given a license key when you purchased this item.</p>
			<form  method="post" action="<?php $license_server_url ?>">
				<table class="form-table">
					<tr>
						<th style="width:100px;"><label for="sample_license_key">License Key</label></th>
						<td ><input class="regular-text" type="text" id="sample_license_key" name="sample_license_key"  value="<?php echo get_option('pa_license_key'); ?>" ></td>
					</tr>
				</table>
				<p class="submit">
					<input type="submit" name="activate_license" value="Activate" class="button-primary" />
					<input type="submit" name="deactivate_license" value="Deactivate" class="button" />
				</p>
			</form>
		</div>
	</div>
</div>