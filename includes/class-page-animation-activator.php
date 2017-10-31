<?php

/**
 * Fired during plugin activation
 *
 * @link       www
 * @since      1.0.0
 *
 * @package    Page_animation
 * @subpackage Page_animation/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Page_animation
 * @subpackage Page_animation/includes
 * @author     Ram Segev <ramsegev@gmail.com>
 */
class Page_animation_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		$options = get_option('page-animation');
		if(!$options){
			$options = array(
							'post_id' => '0',
							'get_animation' =>  array(
								'index' => '0',
								'animation' => array(
									'attr_0' => '0',
									'action_0' => '0',
									'property_' => '0',
									'value_0' => '0',
									'unit_0'  => '0',
									'callback_0'  => '0'
									)
								)
						);
			update_option('page-animation', $options);
			//add_option('page-animation', $options);
		}
	}

}
