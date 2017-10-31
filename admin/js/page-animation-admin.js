$(function(){
	'use strict';
	$("#post_type_list" ).change(function() {
		$('#frm_edit fieldset').remove();
		$("#show_page").empty();
		var post_type = $('#post_type_list option:selected').text();
		$.ajax({
		 method: "GET",
		 url: ajaxurl ,
		 page: "class-page-animation-admin.php" ,
		 action: "get_post_list",
		 data: { 
			 the_post_type:  post_type
		 },
		 success:function(response){
					$( "#post_type_list_posts" ).show();
					$( "#post_type_list_posts" ).html(response);
				   },
		error: function(errorThrown){
			alert(errorThrown);
				}
	  })
	});
	$("#post_type_list_posts" ).change(function(event) {
		var post_id = $('#post_type_list_posts option:selected').val();
		$('#accordion').empty();
		$("#show_page").empty();
		var choose_event = "";
		var selectAnimation = update_select_animation();
		$.ajax({
			method: "GET",
			url: ajaxurl ,
			page: "class-page-animation-admin.php" ,
			action: "get_animation_db",
			data: { 
				post_id:  post_id
			 },
			success:function(response){
				if (response == 0) {
					add_field_set("accordion");
					initialize_accordion(response);
				} else {
					initialize_accordion(response);
					$('.choose_event').each(function(key, data){	
						$('[name~=\"' +scriptParams.plugin_name + '\[choose_event_' + key +'\]\"] option[value=\"' + $(this).attr('data-choose-event') + '\"]').attr('selected', 'selected');
					});
					$(".cross_site").each(function(key, data){	
						$('[name~=\"' +scriptParams.plugin_name + '\[cross_site_' + key +'\]\"] option[value=\"' + $(this).attr('data-cross-site') + '\"]').attr('selected', 'selected');
					});
					$(".mobile").each(function(key, data){	
						$('[name~=\"' +scriptParams.plugin_name + '\[mobile_' + key +'\]\"] option[value=\"' + $(this).attr('data-mobile') + '\"]').attr('selected', 'selected');
					});
					$(".choose_attr").each(function(key, data){	
						var selected_attr = $(this).attr('data-attr').charAt(0);
						var selected_attr_any = $(this).attr('data-attr').indexOf('[');
						if(selected_attr == '#'){
							selected_attr = 'id';
						} else if(selected_attr == '.'){
							selected_attr = 'className';
						} else if(selected_attr_any > -1){
							selected_attr = 'any';
						} else {
							selected_attr = 'tagName';
						}
						$('[name~=\"' +scriptParams.plugin_name + '\[choose_attr_' + key +'\]\"] option[value=\"' + selected_attr + '\"]').attr('selected', 'selected');
						$('#index').val(key);
						$('#selector',parent.document).val(selected_attr);
					});
					
					$(".unit").each(function(key, data){	
						$('[name~=\"' +scriptParams.plugin_name + '\[unit_' + key +'\]\"] option[value=\"' + $(this).attr('data-unit') + '\"]').attr('selected', 'selected');
					});
					$('.showHide').each(function(key, data){	
						$('[name~=\"' +scriptParams.plugin_name + '\[showHide_' + key +'\]\"] option[value=\"' + $(this).attr('data-showHide') + '\"]').attr('selected', 'selected');
					});
					$('.select_animation').append(selectAnimation).each(function(key, data){	
						$('[name~=\"' +scriptParams.plugin_name + '\[select_animation_' + key +'\]\"] option[value=\"' + $(this).attr('data-select-animation') + '\"]').attr('selected', 'selected');
						var value = $('[name~=\"' +scriptParams.plugin_name + '\[select_animation_' + key +'\]\"]').find(":selected").val(); //$option.val();
						var parenId = $( '[name~=\"' +scriptParams.plugin_name + '\[select_animation_' + key +'\]\"]').closest('fieldset').attr('id');
						var selected_attr = $('[name~=\"' +scriptParams.plugin_name + '\[choose_event_' + key +'\]\"]').val();
						if(value === 'custom'){
							$("#" + parenId + " .css-field").hide();
							$("#" + parenId + " .scroll-magic-field").hide();
							$("#" + parenId + " .jq-field").show();
						}else if(selected_attr === 'scrollmagic'){
							$( "#" + parenId + " .jq-field").hide();
							$( "#" + parenId + " .scroll-magic-field").show();
							$( "#" + parenId + " .css-field").show(); 
						}else{
							$( "#" + parenId + " .jq-field").hide();
							$( "#" + parenId + " .scroll-magic-field").hide();
							$( "#" + parenId + " .css-field").show();
						}	
					});
					update_title();
					$('#post_id',parent.document).val(post_id);
					
				}	
				$("#index").val($(".group").length-1);
				add_sliders();
			 },
			error: function(errorThrown){
				alert(errorThrown);
			}
		});
		var page_iframe = "<div class=frame-wrapper>"
		+ "<div class=frame>"
		+ "<div class=camera></div>"
		+ "<iframe id=custom_ifram class='framesite' src="+scriptParams.site_url+"/?p="+post_id +" ></iframe>"
		+ "<div class=buttonframe></div>"
		+ "</div>"
		+ "</div>"
		+ "<div class=base></div>"
		+ "<div class=foot></div>";
		$("#show_page").html(page_iframe);
		$(".frm_submit").show();
	});
	$(document).on('mouseenter','.filedset_container', function(event) {
		event.preventDefault();
		$('.filedset_container').animate({opacity: '1'},1);
	});
	
	$(document).on('mouseleave','.filedset_container', function(event) {
		event.preventDefault();
		$('.filedset_container').animate({opacity: '0.3'},1000);
	});
	$(document).on('change','.choose_event', function(event) {
		event.preventDefault();
		var parenId = $(this).closest('fieldset').attr('id');
		var selected_event = $(this).find('option:selected').val();
		update_title();
		$(this).attr('data-choose-event', selected_event);
		if(selected_event == "scrollmagic"){
			$( "#" + parenId + " .scroll-magic-field").show();
			$( "#" + parenId + " .select_animation").find('option[value=custom]').hide();
			$("#" + parenId + " .select_animation option:eq(1)").attr('selected', 'selected');
			var value = $( "#" + parenId + " .select_animation").find('option:selected').val();
			$( "#" + parenId + " .jq-field").hide();
			$( "#" + parenId + " .css-field").show();
			$( "#" + parenId + " .scroll-magic-field").show();
		} else {
			$( "#" + parenId + " .select_animation").find('option[value=custom]').show();
			$( "#" + parenId + " .select_animation").trigger('change');
		}
	});
	$(document).on('change','.mobile', function(event) {
		event.preventDefault();
		var parenId = $(this).closest('fieldset').attr('id');
		var showHide = $(this).find('option:selected').val();
		$(this).attr('data-mobile', showHide);
	});
	$(document).on('change','.showHide', function(event) {
		event.preventDefault();
		var parenId = $(this).closest('fieldset').attr('id');
		var showHide = $(this).find('option:selected').val();
		$(this).attr('data-showHide', showHide);
	});
	$(document).on('click','#accordion', function(event) {
		event.preventDefault();
		var selected_attr = $(this).find('option:selected').val();
		update_title();
		$(this).attr('data-choose-event', selected_attr);
	}); 
	$(document).on('change','.select_animation', function(event) {
		event.preventDefault();

		var $selected_animation = $(this).find('option:selected');
		var fieldId = $(this).closest('fieldset').attr('id');
		var selected_event = $("#" + fieldId + " .choose_event").find('option:selected').val();
		var value = $(this).find(":selected").val(); //$option.val();
		$(this).attr('data-choose-event', value);
		if(selected_event == "scrollmagic"){
			$( "#" + fieldId + " .css-field").show();
			$( "#" + fieldId + " .scroll-magic-field").show();
			$( "#" + fieldId + " .jq-field").hide();
		}
		if((value == 'custom') && (selected_event !== "scrollmagic")){
			$( "#" + fieldId + " .jq-field").show();
			$( "#" + fieldId + " .scroll-magic-field").hide();
			$( "#" + fieldId + " .css-field").hide();
		}
		if((value != 'custom') && (selected_event != "scrollmagic")){
			$( "#" + fieldId + " .jq-field").hide();
			$( "#" + fieldId + " .scroll-magic-field").hide();
			$( "#" + fieldId + " .css-field").show();
		}
		update_title();
	});
	$(document).on('click', ' .group', function(event) {
		event.preventDefault();	
		var fieldId = $(this).closest('.group').attr('id');
		$(".group").removeClass("active-field");
		$("#" + fieldId).addClass("active-field");
		var property = $("#" + fieldId + " .attr").val();
		var custom_ifram = $(document.getElementById("custom_ifram").contentWindow).length;
		if(custom_ifram > 0){
			try{
				document.getElementById("custom_ifram").contentWindow.chosen_property(property);
			} catch(err){}
		}
	});
	$(document).on('click', '.clone', function(event) {
		event.preventDefault();
		var index = $('.page-animation-admin-effects').length;
		$('#index').val(index);
		var group_id = $(this).closest(".group").attr("id");
		add_field_set($(this).closest(".group").attr("id"));
		$('#accordion').accordion("refresh");
		update_index();
		update_title();
		add_sliders();
		openNextAccordionPanel();
	});
	function openNextAccordionPanel() {
		
		var current = $("#accordion").accordion("option","active"),
			maximum = $("#accordion").find("h3").length,
			next = current+1 === maximum ? 0 : current+1;
		$("#accordion").accordion("option","active",next);
	}
	function initialize_accordion(response){
		var isAccordion = $("#accordion").hasClass("ui-accordion");
		if(isAccordion){
			if(response != 0){
				$('#accordion').append(response);
				update_index();
				update_title();
				add_sliders();
			}				
			$('#accordion').accordion("refresh");
		} else {
			if(response != 0){
				$('#accordion').append(response);
			}
			$( "#accordion")
				.accordion({
				active: false,
				collapsible: true,
				header: " .group > h3",
				heightStyle: "content"
			})
			.sortable({
				axis: "y",
				handle: "h3",
				stop: function( event, ui ) {
					update_index();
					update_title();
					add_sliders();
					ui.item.children( "h3" ).triggerHandler( "focusout" );
				}
			});
		 }
	}
	$(document).on('click', '.remove', function(event) {
		event.preventDefault();
		var remove = $(this).closest('.group').attr('id');
		$('#' + remove).remove(); 
		index = $('.page-animation-admin-effects').length-1;
		$('#index').val(index);
		update_index();
		update_title();
		add_sliders();
	});
	function add_field_set(eventId){
		var index = 0;
		if($('.group').length > 0){
			$(".group").removeClass("active-field");
		}
		if($('.page-animation-admin-effects').length >=0)
			index = $('.page-animation-admin-effects').length;
		var addChildrenRowHTML = "<div id='group_" + index + "' class='group active-field'>"
					+ "<h3> " + (index + 1) + ") "
						+ "<div class=add-remove>"
							+ "<button class='field_set_item clone'>+</button>"
							+ "<button class='field_set_item remove'>-</button>"
						+ "</div>"	
					+ "</h3>"
					+ "<div>"
						+ "<fieldset id=page-animation_admin_effects_" + index + " class='page-animation-admin-effects'>"																
								+ "<div class=field_controllers>"
									+ "<div class='field_set_item field_set_choose_event'>"
										+ "<label for='choose_event_"+ index +"'>Choose Trigger: </label>"
										+ "<select id='choose_event_" +  index + "' class=' choose_event' name="+ scriptParams.plugin_name + "[choose_event_" + index + "] data-choose-event='load' >"
											+ "<option value = load>Page load</option>"
											+ "<option value = click>Click</option>"
											+ "<option value = mouseover>Mouse Over</option>"
											+ "<option value = scroll>Scroll</option>"
											+ "<option value = scrollmagic>Scroll Magic</option>"
										+ "</select>"
									+ "</div>"
									+ "<div class='field_set_item field_set_choose_attr'>"
										+ "<label for='choose_attr_"+ index +"'>Choose Attribute Type: </label>"
										+ "<select id='choose_attr_" +  index + "' class=' choose_attr' name="+ scriptParams.plugin_name + "[choose_attr_" + index + "] data-attr='tagName' >"
											+ "<option value = any>Any element</option>"
											+ "<option value = tagName>Tag Name</option>"
											+ "<option value = className>Class</option>"
											+ "<option value = id>ID</option>"
										+ "</select>"
									+ "</div>"
									+ "<div class='field_set_item field_set_select_animation'>"
										+ "<label for='select_animation_"+ index +"'>Choose Animation: </label>"
										+ "<select id='select_animation_" +  index + "' class=' select_start_animation select_animation' name= "+ scriptParams.plugin_name + "[select_animation_" + index + "] data-select-animation='custom'> </select>"
									+ "</div>"									
									+  "<div class='field_set_item field_set_mobile'>\n"
										+  "<label for='mobile_" +  index + "'>Show on mobile: </label>\n"
										+  "<select id='choose_attr_" +  index +  "' class='mobile' name='"+ scriptParams.plugin_name +  "[mobile_"  +  index  +  "]' data-mobile='' >\n"
											+  "<option value = false>No</option>\n"
											+  "<option value = true>Yes</option>\n"
										+  "</select>\n"
									+  "</div>\n"
									+ "<div class='field_set_item field_set_show_hide scroll-magic-field'>\n"
										+ "<label for='showHide_" + index +"'>Hide element</label>\n"
										+ "<select  id='showHide_" + index + "' class='showHide' name="+ scriptParams.plugin_name +"[showHide_"  + index + "]' data-showHide=''>\n"
											+ "<option value='no_hide'>Don't hide</option>\n"
											+ "<option value='hide_before'>Hide untill effect</option>\n"
											+ "<option value='hide_after'>Hide after effect</option>\n"
										+ "</select>\n"
									+ "</div>\n"
								+ "</div>"
								+ "<div class=slider_container>"
								+ "<div class='field_set_item field_set_action jq-field'>"
									+ "<div class=top_lable>"
										+ "<label for='action_"+ index +"'>Choose Action Type:</label>"
									+ "</div>"
									+ "<input type=text id='action_"+ index +"' class='action' name= "+ scriptParams.plugin_name + "[action_" + index + "] placeholder=action value='' />"
								+ "</div>"
								+ "<div class='field_set_item field_set_property jq-field'>"
									+ "<div class=top_lable>"
										+ "<label for='property_"+ index +"'>Choose CSS property:</label>"
									+ "</div>"
									+ "<input type=text id='property_"+ index +"' class='property' name= "+ scriptParams.plugin_name + "[property_" + index + "] placeholder=property value='' />"
								+ "</div>"																		
								+ "<div class='field_set_item field_set_value jq-field'>"
									+ "<div class=top_lable>"
										+ "<label for='value_"+ index +"'>Choose Value:</label>"
									+"</div>"
									+ "<input type=text id='value_"+ index +"' class='value' name= "+ scriptParams.plugin_name + "[value_" + index + "] placeholder=value value='0' />"
									+ "<div id='slider_horizontal_value_"+ index +"' class=slider_horizontal></div>"
								+ "</div>"																			
								+ "<div class='field_set_item field_set_unit jq-field'>"
									+ "<div class=top_lable>"
										+ "<label for='unit_"+ index +"'>Choose Unit:</label>"
									+ "</div>"
									+ "<select  id='unit_"+ index +"' class='unit' name="+ scriptParams.plugin_name + "[unit_" + index + "] class=unit data-unit=''>"
										+ "<option value = px>px</option>"
										+ "<option value = %>%</option>"
										+ "<option value = em>em</option>"
										+ "<option value = cm>cm</option>"
										+ "<option value = ex>ex</option>"
										+ "<option value = in>in</option>"
										+ "<option value = pc>pc</option>"
										+ "<option value = pt>pt</option>"		
									+ "</select>"
								+ "</div>"
								+ "<div class='field_set_item field_set_callback jq-field'>"
									+ "<div class=top_lable>"
										+ "<label for='callback_"+ index +"'>Choose Callback:</label>"
									+ "</div>"
									+ "<input id='callback_"+ index +"' type=text class='callback ' name= "+ scriptParams.plugin_name + "[callback_" + index + "] placeholder=callback value='0' />"
									+ "<div id='slider_horizontal_callback_"+ index +"' class=slider_horizontal ></div>"
								+ "</div>"																			
								+ "<div class='field_set_item field_set_delay css-field'>"
									+ "<div class=top_lable>"
										+ "<label for='delay_"+ index +"'>Choose Delay:</label>"
									+ "</div>"
									+ "<input type=text id='delay_"+ index +"' class='delay' name= "+ scriptParams.plugin_name + "[delay_" + index + "] placeholder=delay value='0' />"
									+ "<div id='slider_horizontal_delay_"+ index +"' class=slider_horizontal></div>"
								+ "</div>"
								+ "<div class='field_set_item field_set_duration css-field'>"
									+ "<div class=top_lable>"
										+ "<label for='duration_"+ index +"'>Choose Duration:</label>"
									+ "</div>"
									+ "<input id='duration_"+ index +"' type=text class='duration' name= "+ scriptParams.plugin_name + "[duration_" + index + "] placeholder=duration value='2' />"
									+ "<div id='slider_horizontal_duration_"+ index +"' class=slider_horizontal></div>"
								+ "</div>"																			
								+ "<div class='field_set_item field_set_repetition css-field'>"	
									+ "<div class=top_lable>"
										+ "<label for='repetition_"+ index +"'>Choose Repetition:</label>"
									+ "</div>"
									+ "<input type=text id='repetition_"+ index +"' class='repetition' name= "+ scriptParams.plugin_name + "[repetition_" + index + "] placeholder=repetition value='1' />"															
									+ "<div id='slider_horizontal_repetition_"+ index +"' class=slider_horizontal></div>"
								+ "</div>"
								+ "<div class='field_set_item field_set_offset scroll-magic-field'>\n"
									+ "<div class='top_lable'>\n"
										+ "<label for='offset_"+ index +"'>Effect start position:</label>\n"
									+ "</div>\n"
									+ "<div class=verticals>\n"
									+ "<input type='text' id='offset_"+ index +"' class='offset' name="+ scriptParams.plugin_name + "[offset_"  + index+ "] placeholder='offset' value='0' />\n"
									+ "</div>\n"
									+ "<div id='slider_vertical_offset_"+ index +"' class='slider_vertical'></div>\n"
								+ "</div>\n"
								+ "<div class='field_set_item field_set_triggerHook scroll-magic-field'>\n"
									+ "<div class='top_lable'>\n"
										+ "<label for='triggerHook_"+ index +"'>Scroll trigger position</label>\n"
									+ "</div>\n"
									+ "<div class=verticals>\n"
									+ "<input type='text' id='triggerHook_"+ index +"' class='triggerHook' name="+ scriptParams.plugin_name +"[triggerHook_"  + index+ "]' placeholder='triggerHook' value='0.1' />\n"
									+ "</div>\n"
									+ "<div id='slider_vertical_triggerHook_"+ index +"' class='slider_vertical'></div>\n"
								+ "</div>\n"		
								+ "<input type=hidden class='index_" + index +"' name= "+ scriptParams.plugin_name + "[index_" + index + "] value='"+  index  + "'  />"
								+ "<input type=hidden class='attr' name= "+ scriptParams.plugin_name + "[attr_" + index + "] value='not chosen yet' />"
							+ "</div>"
					+ "</fieldset>"
				+ "</div>"
			+ "</div>";
		if(eventId == "accordion"){
			$("#accordion").append(addChildrenRowHTML);
		} else {
			$("#" + eventId).after(addChildrenRowHTML);
		}
		var selectAnimation = update_select_animation();
		$("#page-animation_admin_effects_" + index + " .select_animation").append(selectAnimation);
		$("#page-animation_admin_effects_" + index + " .css-field").hide();
		$("#page-animation_admin_effects_" + index + " .scroll-magic-field").hide();
		$("#page-animation_admin_effects_" + index + " .jq-field").show();
		update_title();
		add_sliders();
	}
	function update_index(){
		$(".group").each(function(key, data){
			var	field_id = this.id;
			//name
			$('#' + field_id + ' .choose_event').attr('name',scriptParams.plugin_name + '[choose_event_' + key +']');
			$('#' + field_id + ' .choose_attr').attr('name',scriptParams.plugin_name + '[choose_attr_' + key +']');
			$('#' + field_id + ' .select_animation').attr('name',scriptParams.plugin_name + '[select_animation_' + key +']');
			$('#' + field_id + ' .cross_site').attr('name',scriptParams.plugin_name + '[cross_site_' + key +']');
			$('#' + field_id + ' .mobile').attr('name',scriptParams.plugin_name + '[mobile_' + key +']');
			$('#' + field_id + ' .action').attr('name',scriptParams.plugin_name + '[action_' + key +']');
			$('#' + field_id + ' .delay').attr('name',scriptParams.plugin_name + '[delay_' + key +']');
			$('#' + field_id + ' .duration').attr('name',scriptParams.plugin_name + '[duration_' + key +']');
			$('#' + field_id + ' .repetition').attr('name',scriptParams.plugin_name + '[repetition_' + key +']');
			$('#' + field_id + ' .property').attr('name',scriptParams.plugin_name + '[property_' + key +']');
			$('#' + field_id + ' .value').attr('name',scriptParams.plugin_name + '[value_' + key +']');
			$('#' + field_id + ' .unit').attr('name',scriptParams.plugin_name + '[unit_' + key +']');
			$('#' + field_id + ' .callback').attr('name',scriptParams.plugin_name + '[callback_' + key +']');
			$('#' + field_id + ' .index').attr('name',scriptParams.plugin_name + '[index_' + key +']');
			$('#' + field_id + ' .attr').attr('name',scriptParams.plugin_name + '[attr_' + key +']');
			$('#' + field_id + ' .offset').attr('name',scriptParams.plugin_name + '[offset_' + key +']');
			$('#' + field_id + ' .triggerHook').attr('name',scriptParams.plugin_name + '[triggerHook_' + key +']');
			$('#' + field_id + ' .showHide').attr('name',scriptParams.plugin_name + '[showHide_' + key +']');
			//id
			$('#' + field_id + ' .page-animation-admin-effects').attr('id','page-animation-admin-effects_' + key);
			$('#' + field_id + ' .choose_event').attr('id','choose_event_' + key);
			$('#' + field_id + ' .choose_attr').attr('id','choose_attr_' + key);
			$('#' + field_id + ' .select_animation').attr('id','select_animation_' + key);
			$('#' + field_id + ' .cross_site').attr('id','cross_site_' + key);
			$('#' + field_id + ' .mobile').attr('id','mobile_' + key);
			$('#' + field_id + ' .action').attr('id','action_' + key);
			$('#' + field_id + ' .delay').attr('id','delay_' + key);
			$('#' + field_id + ' .delay ~ div').attr('id','slider_horizontal_delay_' + key);
			$('#' + field_id + ' .duration').attr('id','duration_' + key);
			$('#' + field_id + ' .duration ~ div').attr('id','slider_horizontal_duration_' + key);
			$('#' + field_id + ' .repetition').attr('id','repetition_' + key);
			$('#' + field_id + ' .repetition ~ div').attr('id','slider_horizontal_repetition_' + key);
			$('#' + field_id + ' .property').attr('id','property_' + key);
			$('#' + field_id + ' .value').attr('id','value_' + key);
			$('#' + field_id + ' .value ~ div').attr('id','slider_horizontal_value_' + key);
			$('#' + field_id + ' .unit').attr('id','unit_' + key);
			$('#' + field_id + ' .callback').attr('id','callback_' + key);
			$('#' + field_id + ' .callback ~ div').attr('id','slider_horizontal_callback_' + key);
			$('#' + field_id + ' .index').attr('id','index_' + key);
			$('#' + field_id + ' .attr').attr('id','attr_' + key);
			$('#' + field_id + ' .offset').attr('id','offset_' + key);
			$('#' + field_id + ' .triggerHook').attr('id','triggerHook_' + key);
			$('#' + field_id + ' .showHide').attr('id','showHide_' + key);
			//for
			$('#' + field_id + ' .choose_event').prev('label').attr('for','choose_event_' + key);
			$('#' + field_id + ' .choose_attr').prev('label').attr('for','choose_attr_' + key);
			$('#' + field_id + ' .select_animation').prev('label').attr('for','select_animation_' + key);
			$('#' + field_id + ' .cross_site').prev('label').attr('for','cross_site_' + key);
			$('#' + field_id + ' .mobile').prev('label').attr('for','mobile_' + key);
			$('#' + field_id + ' .action').prev('label').attr('for','action_' + key);
			$('#' + field_id + ' .delay').prev('label').attr('for','delay_' + key);
			$('#' + field_id + ' .duration').prev('label').attr('for','duration_' + key);
			$('#' + field_id + ' .repetition').prev('label').attr('for','repetition_' + key);
			$('#' + field_id + ' .offset').prev('label').attr('for','offset_' + key);
			$('#' + field_id + ' .triggerHook').prev('label').attr('for','triggerHook_' + key);
			$('#' + field_id + ' .property').prev('label').attr('for','property_' + key);
			$('#' + field_id + ' .value').prev('label').attr('for','value_' + key);
			$('#' + field_id + ' .unit').prev('label').attr('for','unit_' + key);
			$('#' + field_id + ' .callback').prev('label').attr('for','callback_' + key);
		});
		update_groups();
	}
	function update_groups(){
		$(".group").each(function(key, data){
			$(this).attr('id','group_' + key);
		});
	}
	function update_title(){
		$(".group h3").each(function(key, data){
			var counter = key + 1;
			var newAttr = $('[name~=\"' +scriptParams.plugin_name + '\[attr_' + key +'\]\"]').val();
			var title = newAttr.replace(/"/g, "");
			var newTitle =  counter + " )  On " + $("#choose_event_" + key).val() + " attribute <p class=shortt_text >" + newAttr + "</p> will " + $("#select_animation_" + key).val();
			$("#group_" + key + ' h3').html("<span class='span_shortt_text' title='" + title + "'>" + newTitle + "</span>");
			$("#group_" + key + ' h3').append("<div class='add-remove'><button class='field_set_item clone'>+</button><button class='field_set_item remove'>-</button></div>");
		});
	}
	$(document).on('change', '[type~=text]', function(event) {
		$("#" + event.target.id + " ~ .slider_horizontal").slider( "value", $(this).val() );
	});
	function add_sliders(){
		//try{
			$(".group").each(function(key, data){	
				try{
					$('#slider_horizontal_value_' + key).slider({
						range: "min",
						min: 0,
						max: 1000,
						value: parseInt($('#value_' + key).val()),
						step: 1,
						slide: function( event, ui ) {
							$('#value_' + key).val( ui.value );
						 }
					});
					$('#slider_horizontal_callback_' + key).slider({
						range: "min",
						min: 0,
						max: 10000,
						value: parseInt($('#callback_' + key).val()),
						step: 100,
						slide: function( event, ui ) {
							$('#callback_' + key).val( ui.value );
						 }
					});
				} catch(err){console.log(err);}
				$('#slider_horizontal_delay_' + key).slider({
					range: "min",
					min: 0,
					max: 10,
					value: parseInt($('#delay_' + key).val()),
					step: 1,
					slide: function( event, ui ) {
						$('#delay_' + key).val( ui.value );
					 }
				});
				$('#slider_horizontal_duration_' + key).slider({
					range: "min",
					min: 0,
					max: 10,
					value: parseInt($('#duration_' + key).val()),
					step: 1,
					slide: function( event, ui ) {
						$('#duration_' + key).val( ui.value );
					 }
				});
				$('#slider_horizontal_repetition_' + key).slider({
					range: "min",
					min: 0,
					max: 20,
					value: parseInt($('#repetition_' + key).val()),
					step: 1,
					slide: function( event, ui ) {
						$('#repetition_' + key).val( ui.value );
					 }
				});
				$('#slider_vertical_offset_' + key).slider({
					orientation: "vertical",
					range: "min",
					min: -1000,
					max: 1000,
					value: parseInt($('#offset_' + key).val()),
					step: 1,
					slide: function( event, ui ) {
						$('#offset_' + key).val( ui.value );
						var value = $('#offset_' + key).val();
						document.getElementById("custom_ifram").contentWindow.trigger_offset(value);
					 }
				});
				$('#slider_vertical_triggerHook_' + key).slider({
					orientation: "vertical",
					range: "min",
					min: 0,
					max: 1,
					value: parseInt($('#triggerHook_' + key).val()),
					step: 0.01,
					slide: function( event, ui ) {
						$('#triggerHook_' + key).val( ui.value );
						var value = $('#triggerHook_' + key).val();
						document.getElementById("custom_ifram").contentWindow.trigger_position(value);
					 }
				});
			});
	}
});  // End of DOM Ready
function choose_edit(evt, type) {
		var i, tabcontent, tablinks;
		tabcontent = document.getElementsByClassName("tabcontent");
		for (i = 0; i < tabcontent.length; i++) {
			tabcontent[i].style.display = "none";
		}
		tablinks = document.getElementsByClassName("tablinks");
		for (i = 0; i < tablinks.length; i++) {
			tablinks[i].className = tablinks[i].className.replace(" active", "");
		}
		document.getElementById(type).style.display = "block";
		evt.currentTarget.className += " active";
		
}
function update_select_animation(){
	var allRules = [];
	var getOptionList = "<option value=custom>Custom</option>";
	var sSheetList = scriptParams.animation_admin_url;
	var prevRule  = '';
	var styleSheetList = document.styleSheets;
	//console.log(styleSheetList);
	for (var sSheet = 0; sSheet <= sSheetList.length; sSheet++)
	{
		try{
			var ruleList = document.styleSheets[sSheet].cssRules;
			var ruleListHref = document.styleSheets[sSheet].href;
			//console.log(ruleListHref);
			if (ruleListHref == scriptParams.animate_url){
				if(ruleList !== "undefined"){
					for (var rule = 0; rule < ruleList.length; rule++)
					{
						if(ruleList[rule].name){
							if(prevRule != ruleList[rule].name){
								var ruleName = ruleList[rule].name;
								allRules.push( ruleList[rule].name );
								getOptionList +=  "<option value='"+ ruleList[rule].name + "'>" + ruleList[rule].name + "</option>";
							}
							prevRule = ruleList[rule].name
						}
					}
				}
			}
		}
		catch(err){
		}
	}
	return getOptionList;
}
