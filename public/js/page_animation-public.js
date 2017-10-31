$(function(){
	'use strict';
	 var print, sign_type;
	if(top != self){
		window.onload = function (event) {
			event.preventDefault();
			var effects = "function chosen_property(property){";
				//effects += "$('.effects').remove();";
				//effects += 	"console.log('before click: ' + property);";
				effects += "$(property).trigger('click');";
				effects += "}";
			$('head').append("<script id='blueFrame'>" + effects + "</script>");
		}
		window.onmouseover = function (event) {
			event.preventDefault();
			try{
				var target = event.target || event.srcElement;
				var selected_attribute = $('.active-field .choose_attr option:selected',parent.document).val();
				if(selected_attribute == "any"){
					var targetTagName = target.tagName;
					var selector = target;
				} else {
					var index = $('#index', parent.document).val();
					var selector = get_attr(index, target);
				}
				
				$(selector).addClass('red_hover');
			} catch(err){}
		}
		window.onmouseout = function (event) {
			event.preventDefault();
			/* var target = event.target || event.srcElement;
			var selected_attribute = $('.active-field .choose_attr option:selected',parent.document).val();
			if(selected_attribute == "any"){
				var targetTagName = target.tagName;
				var selector = targetTagName + target;
			} else {
				var index = $('#index', parent.document).val();
				var selector = get_attr(index, target);
			} */
			/* var targetTagName = target.tagName;
			var index = $('#index', parent.document).val();
			var target = event.target;
			var selector = get_attr(index, target); */
			$(".red_hover").removeClass('red_hover');
		}
		window.onclick = function (event) {
			event.preventDefault();
			var index = $('#index', parent.document).val();
			var target = event.target  || event.srcElement;
			var obj = {};
			var selected = '';
			var selected_attrs = '';
			var selected_class = '';
			$('.red_hover').removeClass('red_hover');
			$('.blue_click ').removeClass('blue_click');
			var selected_attribute = $('.active-field .choose_attr option:selected',parent.document).val();
			if(selected_attribute == "any"){
				$.each(target.attributes, function() {
					if(this.specified) {
						if(this.name != "style"){
					//	if((this.name != "style") && ((this.name == "id") || (this.name == "class") || (this.name == "data") || (this.name == "name") || (this.name == "role"))){
							if(this.value.length > 0){
								obj[this.name] = this.value;
								if(this.name == "id"){
									selected_attrs += '#' + this.value.replace(/\s+/g, '#');
								} else if(this.name == "class"){
									selected_class += '.' + this.value.replace(/\s+/g, '.');
									//selected_attrs += '.' + this.value.replace(/\s+/g, '.');
								} else {
									selected_attrs += '[' + this.name + '*="' + this.value + '"]';
								}
							}
						}
					}
				});
				//console.log(selected_class);
				selected += event.target.tagName + selected_attrs;
				var selector = selected;
			} else {
				var selector = get_attr(index, target);
			}
						//console.log("after click: " + selector);

			$(selector).addClass('blue_click');
			var prev_select = selector;
			var index = $('#index',parent.document).val();
			var post_id = $('#post_type_list_posts option:selected',parent.document).val();
			$("#selector", parent.document).val(selector);
			$(".active-field .attr", parent.document).val(selector);
			$(".active-field .choose_attr", parent.document).attr("data-attr", selector);
			var effect_array = {};
			$('#post_id',parent.document).val(post_id);
			var effects = '';
			var event_start = '';
			var event_end = '';
			for(var i = 0; i <= index; i++){
				if($("#group_" + i , parent.document).hasClass('active-field')){
					//$("#effects_" + i).remove();
					$('#selector',parent.document).val(selector);
					effect_array[i] = {
						choose_event : $('.active-field .choose_event option:selected',parent.document).val(),
						selectAnimation : $('.active-field .select_animation option:selected',parent.document).val(),
						action : $('.active-field .action',parent.document).val(),
						delay : $('.active-field .delay',parent.document).val(),
						duration : $('.active-field .duration',parent.document).val(),
						repetition : $('.active-field .repetition',parent.document).val(),
						property : $('.active-field .property',parent.document).val(),
						value : $('.active-field .value',parent.document).val(),
						unit : $('.active-field .unit option:selected',parent.document).val(),
						callback : $('.active-field .callback',parent.document).val(),
						selector : $("#selector", parent.document).val(),
						offset : $(".active-field .offset", parent.document).val(),
						triggerHook : $(".active-field .triggerHook", parent.document).val(),
						showHide : $(".active-field .showHide", parent.document).val(),
						mobile : $(".active-field .mobile", parent.document).val(),
					};
					/* if (selected_attribute == "any") {
						effect_array[i].selector = selected_class;
					} */
					
					effects += "if(controller){";
							effects += "controller.destroy(true);";
							effects += "controller=null";
							effects += "}";
							effects += "if(scene_" + i + "){";
							effects += "scene_" + i + ".destroy(true);";
							effects += "scene_" + i + "=null;";
							effects += "}";
					effects += "var controller = new ScrollMagic.Controller();";
					effects += "var scene_" + i + " = new ScrollMagic.Scene({});";
					if(effect_array[i].choose_event == "load"){
						effect_array[i].choose_event = "click";
					}
					if(effect_array[i].choose_event == "scroll"){
						if(effect_array[i].selectAnimation =='custom'){
							effects += "$(window).on('" + effect_array[i].choose_event + "', function(){";
							effects += "$('" + effect_array[i].selector + "')." + effect_array[i].action + "({" + effect_array[i].property + " : '"+ effect_array[i].value + effect_array[i].unit + "'}, " + effect_array[i].callback +" , function(){});";
							effects += "});";
						} else {
							var timeOut = (parseInt(effect_array[i].delay) + parseInt(effect_array[i].duration)) * parseInt(effect_array[i].repetition) * 1000;
							effects += "$(window).on('" + effect_array[i].choose_event + "', function(){";
							effects += "$('" + effect_array[i].selector + "').css({'animation-delay':'" + effect_array[i].delay + "s', 'animation-duration':'" + effect_array[i].duration +"s', 'animation-iteration-count':'" + effect_array[i].repetition +"'});";
							effects += "$('" + effect_array[i].selector + "').addClass('" + effect_array[i].selectAnimation + "');";					
							//effects += "setTimeout(function(){ $('" + effect_array[i].selector + "').removeClass('" + effect_array[i].selectAnimation + "');$('" + effect_array[i].selector + "').removeAttr('animation-delay animation-duration animation-iteration-count')'); }, " + timeOut + ");";
							effects += "});";
						}
					} else {
						if(effect_array[i].choose_event == "scrollmagic") {
							effects += "$(window).on('load', function(){";
							//effects += "console.log($('" + effect_array[i].showHide + "'));";
							if(effect_array[i].showHide == 'hide_before'){
								effects += "$('" + effect_array[i].selector + "').addClass('effect_hide');";
							} 
							effects += "});";
							var timeOut = (parseInt(effect_array[i].delay) + parseInt(effect_array[i].duration)) * parseInt(effect_array[i].repetition) * 1000;
							effects += "if(controller){";
							effects += "controller.destroy(true);";
							effects += "controller=null";
							effects += "}";
							effects += "if(scene_" + i + "){";
							effects += "scene_" + i + ".destroy(true);";
							effects += "scene_" + i + "=null;";
							effects += "}";
							effects += "var controller = new ScrollMagic.Controller();";
							effects += "var scene_" + i + " = new ScrollMagic.Scene({";
							effects += "triggerElement: '" + effect_array[i].selector + "' ,";
							effects += "offset: " + effect_array[i].offset + ",";
							effects += "})";
							effects += ".setClassToggle('" + selected_class + "', '" + effect_array[i].selectAnimation + "')";
							effects += ".addIndicators({name:'" + selected_class + "'} )";
							if(effect_array[i].showHide == "hide_before"){
								effects += ".on('start', function (event) {";
								effects += "$('" + effect_array[i].selector + "').removeClass('effect_hide');";
								effects += "})";
							}
							if(effect_array[i].showHide == "hide_after"){
								effects += ".on('start', function (event) {";
								effects += "$('" + effect_array[i].selector + "').addClass('effect_hide');";
								effects += "})";
							}
							effects += ".addTo(controller);";	
							effects += "scene_" + i + ".triggerHook('" + effect_array[i].triggerHook +"');";
							effects += "scene_" + i + ".reverse(false);";	
							effects += "$('" + effect_array[i].selector + "').css({'animation-delay':'" + effect_array[i].delay + "s', 'animation-duration':'" + effect_array[i].duration +"s', 'animation-iteration-count':'" + effect_array[i].repetition +"'});";
							effects += "function trigger_position(value){";
								effects += "if(value === ''){";
									effects += "scene_" + i + ".triggerHook(null);";
								effects += "} else if (value.length > 0){";
									effects += "scene_" + i + ".triggerHook(value);";
								effects += "}";
							effects += "}";
							effects += "function trigger_offset(value){";
								effects += "if(value === ''){";
									effects += "scene_" + i + ".offset(null);";
								effects += "} else if (value.length > 0){";
									effects += "scene_" + i + ".offset(value);";
								effects += "}";
							effects += "}";							
						} else{
							event_start = "$('" + effect_array[i].selector + "').on('" + effect_array[i].choose_event + "', function(){";
							event_end = "});";						
							if(effect_array[i].selectAnimation =='custom'){
								effects += event_start;
								effects += "$('" + effect_array[i].selector + "')." + effect_array[i].action + "({" + effect_array[i].property + " : '"+ effect_array[i].value + effect_array[i].unit + "'}, " + effect_array[i].callback +" , function(){});";
								effects += event_end;
							} else{
								var timeOut = (parseInt(effect_array[i].delay) + parseInt(effect_array[i].duration)) * parseInt(effect_array[i].repetition) * 1000;
								effects += event_start;
								effects += "$('" + effect_array[i].selector + "').css({'animation-delay':'" + effect_array[i].delay + "s', 'animation-duration':'" + effect_array[i].duration +"s', 'animation-iteration-count':'" + effect_array[i].repetition +"'});";
								effects += "$('" + effect_array[i].selector + "').addClass('" + effect_array[i].selectAnimation + "');";					
								effects += "setTimeout(function(){ $('" + effect_array[i].selector + "').removeClass('" + effect_array[i].selectAnimation + "'); }, " + timeOut + ");";
								effects += event_end;
							}
						}
					}	
					if($("#effects_" + i).length > 0){
						/* console.log($("#effects_" + i).length);
						$("#custom_ifram").contents().find("#effects_" + i).remove(); */
						$("#effects_" + i).replaceWith("<script id=effects_" + i + " class=effects>" + effects + "</script>");
					} else {
						$('head').append("<script id=effects_" + i + " class=effects>" + effects + "</script>");
					}
				}
			}
		} 
		
	}
	function get_attr(index,target){
			var selected_attribute = $('.active-field .choose_attr option:selected',parent.document).val();
			var sign_type =''
			if(selected_attribute === "tagName"){
				print = target.tagName;
				sign_type = '';
			}
			if(selected_attribute === "className"){
				if(target.className){
					if(target.className){
						print = target.className.split(' ')[0];
						sign_type = '.';
					}
				}
			}
			if(selected_attribute === "id"){
				if(target.id){
					print = target.id;
					sign_type = '#';
				}
			}
			return sign_type + print;
	 }
});  // End of DOM Ready
		