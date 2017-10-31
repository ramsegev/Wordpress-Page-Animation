<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       www
 * @since      1.0.0
 *
 * @package    Page_animation
 * @subpackage Page_animation/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Page_animation
 * @subpackage Page_animation/admin
 * @author     Ram Segev <ramsegev@gmail.com>
 */
class Page_animation_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function pa_rs() {
		global $pa_rs;

		if ( ! isset( $pa_rs ) ) {
			// Include Freemius SDK.
			require_once plugin_dir_path(dirname(__FILE__)) . '/freemius/start.php';

			$pa_rs = fs_dynamic_init( array(
				'id'                  => '1236',
				'slug'                => 'page-animation',
				'type'                => 'plugin',
				'public_key'          => 'pk_05df13536c5abf27a78fff2510b33',
				'is_premium'          => false,
				'has_addons'          => false,
				'has_paid_plans'      => false,
				'menu'                => array(
					'slug'           => page-animation,
					'account'        => false,
					'support'        => false,
				),
			) );
		}

		return $pa_rs;
	}
	public function enqueue_styles() {
		wp_register_style( 'page_animation-admin.css',  plugin_dir_url( __FILE__ ) . 'css/page_animation-admin.css', array(), null, 'all' );
		wp_register_style( 'animate.css',  plugin_dir_url( __FILE__ ) . 'css/animate.css', array(), null, 'all' );
		wp_register_style( 'jquery-ui.css', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css', array(), null, 'all' );
		wp_enqueue_style( 'animate.css' );
		wp_enqueue_style( 'jquery-ui.css' );
		wp_enqueue_style( 'page_animation-admin.css' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		
		wp_deregister_script('jquery');
        wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js', false, '3.1.0');
		wp_enqueue_script('jquery');
		wp_deregister_script('jquery-ui');
		wp_deregister_script('jquery-ui-core');
        wp_register_script('jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js', false, '1.12.1');
        wp_enqueue_script('jquery-ui');
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/page_animation-admin.js', array( 'jquery' ), $this->version, true );
		$script_params = array(
		   'post_obj' => 'post',
		   'plugin_name' => $this->plugin_name,
		   'site_url' => site_url(),
		   'animation_admin_url' => plugin_dir_url( __FILE__ ) . 'css/page_animation-admin.css',
		   'animate_url' => plugin_dir_url( __FILE__ ) . 'css/animate.css'
        );
        wp_localize_script($this->plugin_name, 'scriptParams', $script_params );
	}
	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	 
	public function add_plugin_admin_menu() {
		add_menu_page( 'Page Animation  Options  Setup', 'Page Animation', 'Menu Display', $this->plugin_name, array($this, 'display_plugin_setup_page_display'),'dashicons-admin-generic',10);
		add_submenu_page( $this->plugin_name, 'Page Animation Menu Display', 'Animations', 'manage_options', $this->plugin_name.'_display', array($this, 'display_plugin_setup_page_display') );
		add_submenu_page( $this->plugin_name, 'Page Animation Settings', 'Settings', 'manage_options', $this->plugin_name.'_settings', array($this, 'display_plugin_setup_page_settings') );
	}
	public function add_action_links( $links ) {
	   $settings_link = array(
		'<a href="' . admin_url( 'admin.php?page='.$this->plugin_name.'_display') . '">' . __('Settings', $this->plugin_name) . '</a>',
		'<a href="' . admin_url( 'admin.php?page='.$this->plugin_name.'_settings') . '">' . __('Settings', $this->plugin_name) . '</a>',
	   );
	   return array_merge(  $settings_link, $links );

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	 
    public function display_plugin_setup_page_display(){
        include_once( 'partials/page_animation-admin-display.php' );
    }
	public function display_plugin_setup_page_settings(){
        include_once( 'partials/page_animation-admin-settings.php' );
    }
	public function options_update() {
		register_setting($this->plugin_name, $this->plugin_name, array($this, 'validate'));
	}
	public function validate($input) {
		$options = get_option($this->plugin_name);
		$post_id = '';
		$get_animation = '';
		$animations = array(array());
		if ((isset($input['post_id'])) && (isset($input['get_animation']))){
			$post_id = sanitize_text_field($input['post_id']);
			$get_animation = sanitize_text_field($input['get_animation']);
			$index = sanitize_text_field($input['index']);			
			for ($i = 0; $i <= $index; $i++) {
				if ((isset($input['choose_event_'.$i]))){
					$choose_event = sanitize_text_field($input['choose_event_'.$i]);
				} else {
					$choose_event = '';
				}
				if ((isset($input['attr_'.$i]))){
					$attr = sanitize_text_field($input['attr_'.$i]);
				} else {
					$attr = '';
				}
				if ((isset($input['select_animation_'.$i]))){
					$select_animation = sanitize_text_field($input['select_animation_'.$i]);
				} else {
					$select_animation = '';
				}
				/* if ((isset($input['cross_site_'.$i]))){
					$cross_site = sanitize_text_field($input['cross_site_'.$i]);
				} else {
					$cross_site = '';
				} */
				if ((isset($input['mobile_'.$i]))){
					$mobile = sanitize_text_field($input['mobile_'.$i]);
				} else {
					$mobile = '';
				}
				if ((isset($input['action_'.$i]))){
					$action = sanitize_text_field($input['action_'.$i]);
				} else {
					$action = '';
				}
				if ((isset($input['delay_'.$i]))){
					$delay = sanitize_text_field($input['delay_'.$i]);
				} else {
					$delay = '';
				}
				if ((isset($input['duration_'.$i]))){
					$duration = sanitize_text_field($input['duration_'.$i]);
				} else {
					$duration = '';
				}
				if ((isset($input['repetition_'.$i]))){
					$repetition = sanitize_text_field($input['repetition_'.$i]);
				} else {
					$repetition = '';
				}
				if ((isset($input['offset_'.$i]))){
					$offset = sanitize_text_field($input['offset_'.$i]);
				} else {
					$offset = '';
				}
				if ((isset($input['triggerHook_'.$i]))){
					$triggerHook = sanitize_text_field($input['triggerHook_'.$i]);
				} else {
					$triggerHook = '';
				}
				if ((isset($input['showHide_'.$i]))){
					$showHide = sanitize_text_field($input['showHide_'.$i]);
				} else {
					$showHide = '';
				}
				if ((isset($input['property_'.$i]))){
					$property = sanitize_text_field($input['property_'.$i]);
				} else {
					$property = '';
				}
				if ((isset($input['value_'.$i]))){
					$value = sanitize_text_field($input['value_'.$i]);
				} else {
					$value = '';
				}
				if ((isset($input['unit_'.$i]))){
					$unit = sanitize_text_field($input['unit_'.$i]);
				} else {
					$unit = '';
				}
				if ((isset($input['callback_'.$i]))){
					$callback = sanitize_text_field($input['callback_'.$i]);
				} else {
					$callback = '';
				}
				if ($i >= 0){
					if($select_animation == "custom"){
						$animation = 
							array( 
								array(
									'index' => $i,
									'animation' => array(
										'choose_event' => $choose_event,
										'attr' => $attr,
										'select_animation' => $select_animation,
										//'cross_site' => $cross_site,
										'mobile' => $mobile,
										'action' => $action,
										'property' => $property,
										'value' => $value,
										'unit' => $unit,
										'callback' => $callback,
										'offset' => $offset,
										'triggerHook' => $triggerHook,
										'showHide' => $showHide										
									)
								)
							);
					} else {
							$animation = 
							array( 
								array(
									'index' => $i,
									'animation' => array(
										'choose_event' => $choose_event,
										'attr' => $attr,
										'select_animation' => $select_animation,
										//'cross_site' => $cross_site,
										'mobile' => $mobile,
										'delay' => $delay,
										'duration' => $duration,
										'repetition' => $repetition,
										'offset' => $offset,
										'triggerHook' => $triggerHook,
										'showHide' => $showHide

									)
								)
							);
					}
					array_push($animations, $animation);
				}
			}
			if (!empty($options)) {
				$notindb = false;
				foreach ($options as $key=>$posts){
					if(isset($posts[0]['post_id'])){
						$i=0;
						if(($posts[0]['post_id'] == $post_id)){
							$notindb = true;
							foreach($posts[0]['get_animation'] as $count=>$post_animation){
							} 
							array_splice($options[$key][0]['get_animation'],0,$count+1,$animations);
							$i++;
						}
					}else if(isset($posts[$key]['post_id'])){
						$i=0;
						if(($posts[$key]['post_id'] == $post_id)){
							$notindb = true;
							foreach($posts[$key]['get_animation'] as $count=>$post_animation){
							} 
							array_splice($options[$key][$key]['get_animation'],0,$count+1,$animations);
							$i++;
						}
					}
				}
				if($notindb == false) {
						$valid = array(
							array(
								'post_id' => $post_id,
								'get_animation' => $animations	
							)						
						);
						array_push($options, $valid);
					} 
			} else {
				$options = array(
					array(
						'post_id' => $post_id,
						'get_animation' => $animations	
					)
				);
			}
			return $options;
		}
	}
	public function get_post_list(){
		$get_post_type = '';
		if(isset($_GET['the_post_type'])){
			$get_post_type = sanitize_text_field($_GET['the_post_type']);
			$args = array(
				'sort_order' => 'asc',
				'sort_column' => 'post_title',
				'post_type' => $get_post_type,
				'post_status' => 'publish',
				'posts_per_page' => -1,
			); 
			$myposts = get_posts( $args );
			$print_option = "<option selected=selected>Choose post</option>";
			foreach($myposts as $mypost) {
				if(empty($title)){
					$title = 'no title';
				}
				else{
					$title = $mypost->post_title;
				}
					$print_option = $print_option . "<option value=". $mypost->ID .">". $title ."</option>";
			}

			
			wp_die($print_option);
		}
	 }
	 //run amination in frontend
	 public function run_animation() {
		$current_post_id = get_the_ID();
		$id_name = array (
			'id' => get_the_ID(),
			'func_name' => 'run_animation'
			);
		echo $this->query_string($id_name);	
	}
	//laod animation fom db in backend
	public function get_animation_db() {
		$current_post_id = '';
		if(isset($_GET['post_id'])){
			$current_post_id = sanitize_text_field($_GET['post_id']);
			$id_name = array (
				'id' => $current_post_id,
				'func_name' => 'get_animation_db'
				);
			wp_die($this->query_string($id_name));	
		}		
	}

	public function query_string($id_name){
		$options = get_option($this->plugin_name);
		$query_string = null;	
		$i=0;
		$print = '';
		$attr = '';
		$query_string_load='';		
		if (!empty($options)) {
/* 							echo str_replace(array('&lt;?php&nbsp;','?&gt;'), '', highlight_string( '<?php ' .     var_export($options, true) . ' ?>', true ) );
 */
			foreach ($options as $key=>$post_id){
				if(isset($post_id[$i]['get_animation'])){
				//	var_dump($i);
					/* foreach($post_id[$i]['get_animation'] as $index){
						if(isset($index)){
							if(isset($post_id[$i]['get_animation'])){
								foreach($post_id[$i]['get_animation'] as $animations => $animation){
									if(isset($animation)){ */
										//var_dump($post_id[$i]['post_id']);
					if((isset($post_id[$i]['post_id'])) && (isset($id_name['id']))){
						if(($post_id[$i]['post_id'] == $id_name["id"])){// || ($cross_site  == "all_pages")){
							foreach($post_id[$i]['get_animation'] as $index){
								if(isset($index)){
									if(isset($post_id[$i]['get_animation'])){
										foreach($post_id[$i]['get_animation'] as $animations => $animation){
											if(isset($animation)){												
												if((isset($animation['animation']['choose_event'])) && (isset($animation['animation']['attr' ])) && (isset($animation['animation']['select_animation' ])) && (isset($animation['animation']['mobile' ]))){
													$choose_event = $animation['animation']['choose_event' ];
													$attr = $animation['animation']['attr' ];
													$select_animation = $animation['animation']['select_animation' ];						
													$mobile = $animation['animation']['mobile' ];
													if($select_animation == "custom"){
														if ((isset($animation['animation']['action' ])) && (isset($animation['animation']['property' ])) && (isset($animation['animation']['value' ])) && (isset($animation['animation']['unit' ])) && (isset($animation['animation']['callback' ]))){
															$action = $animation['animation']['action' ];
															$property = $animation['animation']['property' ];
															$value = $animation['animation']['value' ];
															$unit = $animation['animation']['unit' ];
															$callback = $animation['animation']['callback' ];
														}
													} else {
														if ((isset($animation['animation']['delay' ])) && (isset($animation['animation']['duration' ])) && (isset($animation['animation']['repetition' ]))){
															$delay = $animation['animation']['delay' ];
															$duration = $animation['animation']['duration' ];
															$repetition = $animation['animation']['repetition' ];
															$repetition = $animation['animation']['repetition' ];
														}
													}
													if ((isset($animation['animation']['offset'])) && (isset($animation['animation']['triggerHook'])) && (isset($animation['animation']['showHide']))){
														$offset = $animation['animation']['offset'];
														$triggerHook = $animation['animation']['triggerHook'];
														$showHide = $animation['animation']['showHide'];
													}
												}												
												if((isset($animation[0]['animation']['choose_event' ])) && (isset($animation[0]['animation']['attr' ])) && (isset($animation[0]['animation']['select_animation' ])) && (isset($animation[0]['animation']['mobile' ]))){
													$choose_event = $animation[0]['animation']['choose_event' ];
													$attr = $animation[0]['animation']['attr' ];
													$select_animation = $animation[0]['animation']['select_animation' ];												
													$mobile = $animation[0]['animation']['mobile' ];
													if($select_animation == "custom"){
														if ((isset($animation[0]['animation']['action' ])) && (isset($animation[0]['animation']['property' ])) && (isset($animation[0]['animation']['value' ])) && (isset($animation[0]['animation']['unit' ])) && (isset($animation[0]['animation']['callback' ]))){
															$action = $animation[0]['animation']['action' ];
															$property = $animation[0]['animation']['property' ];
															$value = $animation[0]['animation']['value' ];
															$unit = $animation[0]['animation']['unit' ];
															$callback = $animation[0]['animation']['callback' ];
														}
													} else {
														if ((isset($animation[0]['animation']['delay' ])) && (isset($animation[0]['animation']['duration' ])) && (isset($animation[0]['animation']['repetition' ]))){
															$delay = $animation[0]['animation']['delay'];
															$duration = $animation[0]['animation']['duration'];
															$repetition = $animation[0]['animation']['repetition'];
														}
													}			
													if ((isset($animation[0]['animation']['offset'])) && (isset($animation[0]['animation']['triggerHook'])) && (isset($animation[0]['animation']['showHide']))){
														$offset = $animation[0]['animation']['offset'];
														$triggerHook = $animation[0]['animation']['triggerHook'];
														$showHide = $animation[0]['animation']['showHide'];
													}
												}
												
												if((isset($choose_event)) && (isset($attr)) && (isset($select_animation)) && ((isset($action)) && (isset($property)) && (isset($value)) && (isset($unit)) && (isset($callback)) || ((isset($delay)) && (isset($duration)) && (isset($repetition))))){
													$animations--;
													if($id_name['func_name'] == "run_animation"){
														$query_string = null;
														if($select_animation == "custom"){
															if($choose_event == "load")
															{
																$query_string .= "$('" . $attr . "'). " . $action . "({" . $property . " : '" . $value . $unit . "'}," . $callback . ", function(){});";
															} else {
																if($choose_event == "scroll"){
																	$query_string .= "$(window).on('" . $choose_event . "', function(){";
																} else{
																	$query_string .= "$('" . $attr . "').on('" . $choose_event . "', function(){";
																}															
																$query_string .= "$('" . $attr . "'). " . $action . "({" . $property . " : '" . $value . $unit . "'}," . $callback . ", function(){});";
																$query_string .= "});";
															}
														} else {
															if($choose_event == "load")
															{
																$timeOut = (intval($delay) + intval($duration)) * intval($repetition) * 1000;
																$query_string .= "$('" . $attr . "').css({'animation-delay':'"  . $delay .  "s', 'animation-duration':'" . $duration . "s', 'animation-iteration-count':'" . $repetition . "'});";
																$query_string .= "$('" . $attr . "').addClass('" . $select_animation . "');";					
																$query_string .= "setTimeout(function(){ $('" . $attr . "').removeClass('" . $select_animation . "'); }, " . $timeOut . ");";	
																
															} else if($choose_event =='scrollmagic') {
																$query_string .= "$(document).ready(function(){";
																if($showHide == 'hide_before'){
																	$query_string_load .= "<style class=tempStype_" . $animations . ">" . $attr . "{visibility:hidden;}</style>";
																	$query_string .= "$('" . $attr . "').addClass('effect_hide');";
																} 
																$query_string .= "});";
																$query_string .= "if(controller){";
																$query_string .= "controller.destroy(true);";
																$query_string .= "controller=null";
																$query_string .= "}";
																$query_string .= "if(scene_" . $animations . "){";
																$query_string .= "scene_" . $animations . ".destroy(true);";
																$query_string .= "scene_" . $animations . "=null;";
																$query_string .= "}";
																$query_string .= "var controller = new ScrollMagic.Controller();";
																$query_string .= "var scene_" . $animations . " = new ScrollMagic.Scene({";
																$query_string .= "triggerElement: '" . $attr . "' ,";
																$query_string .= "offset: " . $offset . ",";
																$query_string .= "})";
																$query_string .= ".setClassToggle('" . $attr . "', '" . $select_animation . "')";
																if($showHide == "hide_before"){
																	$query_string .= ".on('start', function (event) {";
																	$query_string .= "$('.tempStype_" . $animations . "').remove();";
																	$query_string .= "$('" . $attr . "').removeClass('effect_hide');";
																	$query_string .= "})";
																}
																if($showHide == "hide_after"){
																	$query_string .= ".on('start', function (event) {";
																	$query_string .= "$('" . $attr . "').addClass('effect_hide');";
																	$query_string .= "})";
																}
																$query_string .= ".addTo(controller);";
																$query_string .= "scene_" . $animations . ".triggerHook('" . $triggerHook ."');";
																$query_string .= "scene_" . $animations . ".reverse(false);";
																$query_string .= "$('" . $attr . "').css({'animation-delay':'" . $delay . "s', 'animation-duration':'" . $duration ."s', 'animation-iteration-count':'" . $repetition ."'});";				
															} else {
																$timeOut = (intval($delay) + intval($duration)) * intval($repetition) * 1000;
																if($choose_event == "scroll"){
																	$query_string .= "$(window).on('" . $choose_event . "', function(){";
																} else{
																	$query_string .= "$('" . $attr . "').on('" . $choose_event . "', function(){";
																}
																$query_string .= "$('" . $attr . "').css({'animation-delay':'"  . $delay .  "s', 'animation-duration':'" . $duration . "s', 'animation-iteration-count':'" . $repetition . "'});";
																$query_string .= "$('" . $attr . "').addClass('" . $select_animation . "');";					
																$query_string .= "setTimeout(function(){ $('" . $attr . "').removeClass('" . $select_animation . "'); }, " . $timeOut . ");";	
																$query_string .= "});";
															}
														}
														if(($attr != '') && ($attr != '/')){
															if($id_name['func_name'] == "run_animation"){
																if ($mobile == "false"){
																	$visible = "$(document).ready(function(){";
																	$visible .= "$('" . $attr . "').css('visibility', 'visible');";
																	$visible .= "});";
																	$print .= $query_string_load . "<script id='effects_" . $animations . "'>if($(window).width() > 739){ $(document).ready(function(){if($('" . $attr . "').length > 0){" . $query_string . "}});}else{" . $visible . "}</script>";
																}else
																{
																	$print .= $query_string_load . "<script id='effects_" . $animations . "'>$(document).ready(function(){if($('" . $attr . "').length > 0){" . $query_string . "}});</script>";

																}
															}
														}
													}
													if(($id_name['func_name'] == "get_animation_db")){
														$query_string .= "<div id='group_" . $animations . "' class='group'> \n"
															. "<h3> " . ($animations + 1) . ")  On ". $choose_event . " attribute " . $attr ." will " . $select_animation . "\n"
																. "<div class='add-remove'>"
																	. "<button class='field_set_item clone'>+</button>\n"
																	. "<button class='field_set_item remove'>-</button>\n"									
																. "</div>\n"
															. "</h3>\n"
															. "<fieldset id='page_animation_admin_effects_" . $animations . "' class='page-animation-admin-effects'>\n"
																. "<div class='field_controllers'>\n"
																	. "<div class='field_set_item field_set_choose_event'>\n"
																		. "<label for='choose_event_". $animations ."'>Choose Trigger: </label>\n"
																		. "<select id='choose_event_" .  $animations . "' class='choose_event' name='". $this->plugin_name . "[choose_event_" . $animations . "]' data-choose-event='" . $choose_event . "' >\n"
																			. "<option value = 'load'>Page load</option>\n"
																			. "<option value = 'click'>Click</option>\n"
																			. "<option value = 'mouseover'>Mouse Over</option>\n"
																			. "<option value = 'scroll'>Scroll</option>\n"
																			. "<option value = 'scrollmagic'>Scroll Magic</option>\n"
																		. "</select>\n"
																	. "</div>\n"
																	. "<div class='field_set_item field_set_choose_attr'>\n"
																		. "<label for='choose_attr_". $animations ."'>Choose Attribute Type: </label>\n"
																		. "<select id='choose_attr_" .  $animations . "' class='choose_attr' name='". $this->plugin_name . "[choose_attr_" . $animations . "]' data-attr='" . $attr . "' >\n"
																			. "<option value = any>Any element</option>\n"
																			. "<option value = tagName>Tag Name</option>\n"
																			. "<option value = className>Class</option>\n"
																			. "<option value = id>ID</option>\n"
																		. "</select>\n"
																	. "</div>\n"
																	. "<div class='field_set_item field_set_select_animation'>\n"
																		. "<label for='select_animation_". $animations ."'>Choose Animation: </label>\n"
																		. "<select id='select_animation_" .  $animations . "' class='select_start_animation select_animation' name='". $this->plugin_name . "[select_animation_" . $animations . "]' data-select-animation='" . $select_animation . "'> </select>\n"
																	. "</div>\n"
																	/* . "<div class='field_set_item field_set_cross_site'>\n"
																		. "<label for=cross_site_". $animations ."'>Animated page(s): </label>\n"
																		. "<select id='cross_site_" .  $animations . "' class='cross_site' name='". $this->plugin_name . "[cross_site_" . $animations . "]' data-cross-site='" . $cross_site . "' >\n"
																			. "<option value = this_page>This Page</option>\n"
																			. "<option value = all_pages>All Pages</option>\n"
																		. "</select>\n"
																	. "</div>\n"	 */
																	. "<div class='field_set_item field_set_mobile'>\n"
																		. "<label for='mobile_". $animations ."'>Show on mobile: </label>\n"
																		. "<select id='mobile_" .  $animations . "' class='mobile' name='". $this->plugin_name . "[mobile_" . $animations . "]' data-mobile='" . $mobile . "' >\n"
																			. "<option value = false>No</option>\n"
																			. "<option value = true>Yes</option>\n"
																		. "</select>\n"
																	. "</div>\n"																
																	. "<div class='field_set_item field_set_show_hide scroll-magic-field'>\n"
																			. "<label for='showHide_". $animations ."'>Hide element</label>\n"															
																		. "<select  id='showHide_". $animations ."' class='showHide' name='". $this->plugin_name . "[showHide_" . $animations . "]'  data-showHide=" . $showHide . ">\n"
																			. "<option value='no_hide'>Don't hide</option>\n"
																			. "<option value='hide_before'>Hide untill effect</option>\n"
																			. "<option value='hide_after'>Hide after effect</option>\n"
																		. "</select>\n"
																	. "</div>\n"
																. "</div>\n";
																if($select_animation == "custom"){
																	$delay = "";
																	$duration = "";
																	$repetition = "";
																} else {
																	$property = "";
																	$action = "";
																	$value = "";
																	$unit = "";
																	$callback = "";
																}
																$query_string .= "<div class='slider_container'>\n"
																	. "<div class='field_set_item field_set_action jq-field'>\n"
																		. "<div class='top_lable'>\n"
																			. "<label for='action_". $animations ."'>Choose Action Type:</label>\n"
																		. "</div>\n"
																		. "<input type=text id='action_". $animations ."' class='action' name='". $this->plugin_name . "[action_" . $animations . "]' placeholder='action' value='" . $action . "' />\n"
																	. "</div>\n"
																	. "<div class='field_set_item field_set_property jq-field'>\n"
																		. "<div class=top_lable>\n"
																			. "<label for='property_". $animations ."'>Choose CSS property:</label>\n"
																		. "</div>\n"
																		. "<input type=text id='property_". $animations ."' class='property' name='". $this->plugin_name . "[property_" . $animations . "]' placeholder='property' value='" . $property . "' />\n"
																	. "</div>\n"
																	. "<div class='field_set_item field_set_value jq-field'>\n"
																		. "<div class='top_lable'>\n"
																			. "<label for='value_". $animations ."'>Choose Value:</label>\n"
																		."</div>\n"
																		. "<input type=text id='value_". $animations ."' class='value' name='". $this->plugin_name . "[value_" . $animations . "]' placeholder='value' value='" . $value . "' />\n"
																		. "<div id='slider_horizontal_value_". $animations ."' class='slider_horizontal'></div>\n"
																	. "</div>\n"
																	. "<div class='field_set_item field_set_unit jq-field'>\n"
																		. "<div class='top_lable'>\n"
																			. "<label for='unit_". $animations ."'>Choose Unit:</label>\n"
																		. "</div>\n"
																		. "<select  id='unit_". $animations ."' class='unit' name='". $this->plugin_name . "[unit_" . $animations . "]'  data-unit=" . $unit . ">\n"
																			. "<option value='px'>px</option>\n"
																			. "<option value='%'>%</option>\n"
																			. "<option value='em'>em</option>\n"
																			. "<option value='cm'>cm</option>\n"
																			. "<option value='ex'>ex</option>\n"
																			. "<option value='in'>in</option>\n"
																			. "<option value='pc'>pc</option>\n"
																			. "<option value='pt'>pt</option>\n"
																		. "</select>\n"
																	. "</div>\n"
																	. "<div class='field_set_item field_set_callback jq-field'>\n"
																		. "<div class='top_lable'>\n"
																			. "<label for='callback_". $animations ."'>Choose Callback:</label>\n"
																		. "</div>\n"
																		. "<input id='callback_". $animations ."' type=text class='callback ' name='". $this->plugin_name . "[callback_" . $animations . "]' placeholder='callback' value='" . $callback . "' />\n"
																		. "<div id='slider_horizontal_callback_". $animations ."' class='slider_horizontal' ></div>\n"
																	. "</div>\n"
																	. "<div class='field_set_item field_set_delay css-field'>\n"
																		. "<div class='top_lable'>\n"
																			. "<label for='delay_". $animations ."'>Choose Delay:</label>\n"
																		. "</div>\n"
																		. "<input type=text id='delay_". $animations ."' class='delay' name='". $this->plugin_name . "[delay_" . $animations . "]' placeholder='delay' value='" . $delay . "' />\n"
																		. "<div id='slider_horizontal_delay_". $animations ."' class='slider_horizontal'></div>\n"
																	. "</div>\n"
																	. "<div class='field_set_item field_set_duration css-field'>\n"
																		. "<div class='top_lable'>\n"
																			. "<label for='duration_". $animations ."'>Choose Duration:</label>\n"
																		. "</div>\n"
																		. "<input id='duration_". $animations ."' type=text class='duration' name='". $this->plugin_name . "[duration_" . $animations . "]' placeholder='duration' value='" . $duration . "' />\n"
																		. "<div id='slider_horizontal_duration_". $animations ."' class='slider_horizontal'></div>\n"
																	. "</div>\n"
																	. "<div class='field_set_item field_set_repetition css-field'>\n"
																		. "<div class='top_lable'>\n"
																			. "<label for='repetition_". $animations ."'>Choose Repetition:</label>\n"
																		. "</div>\n"
																		. "<input type=text id='repetition_". $animations ."' class='repetition' name='". $this->plugin_name . "[repetition_" . $animations . "]' placeholder='repetition' value='" . $repetition . "' />\n"
																		. "<div id='slider_horizontal_repetition_". $animations ."' class='slider_horizontal'></div>\n"
																	. "</div>\n"
																	. "<div class='field_set_item field_set_offset scroll-magic-field'>\n"
																		. "<div class='top_lable'>\n"
																			. "<label for='offset_". $animations ."'>Effect start position:</label>\n"
																		. "</div>\n"
																		. "<div class=verticals>\n"
																		. "<input type='text' id='offset_". $animations ."' class='offset' name='". $this->plugin_name . "[offset_" . $animations . "]' placeholder='offset' value='" . $offset . "' />\n"
																		. "</div>\n"
																		. "<div id='slider_vertical_offset_". $animations ."' class='slider_vertical'></div>\n"
																	. "</div>\n"
																	. "<div class='field_set_item field_set_triggerHook scroll-magic-field'>\n"
																		. "<div class='top_lable'>\n"
																			. "<label for='triggerHook_". $animations ."'>Scroll trigger position</label>\n"
																		. "</div>\n"
																		. "<div class=verticals>\n"
																		. "<input type='text' id='triggerHook_". $animations ."' class='triggerHook' name='". $this->plugin_name . "[triggerHook_" . $animations . "]' placeholder='triggerHook' value='" . $triggerHook . "' />\n"
																		. "</div>\n"
																		. "<div id='slider_vertical_triggerHook_". $animations ."' class='slider_vertical'></div>\n"
																	. "</div>\n"
																	. "<input type=hidden class='index_" . $animations . "' name='". $this->plugin_name . "[index_" . $animations . "]' value='".  $animations  . "'  />\n"
																	. "<input type=hidden class='attr' name='". $this->plugin_name . "[attr_" . $animations . "]' value='" .  $attr  . "' />\n"
																. "</div>\n"
															. "</fieldset>\n"
														. "</div>\n";
													}
												}
											}
										}
									}							  
								}								
								$i++;
							}
							if(($attr != '') && ($attr != '/')){
								if($id_name['func_name'] == "get_animation_db"){
									$print = $query_string;
								}
							}						
							return $print;
						}
					}
				}
			} 
		}
	}	
}
