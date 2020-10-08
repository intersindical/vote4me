/*vote4me_js*/
jQuery.noConflict();
jQuery(document).ready(function($) {

	jQuery('.vote4me_sys_show_voter_table tr').each(function(){
		var vote4me_tbl = jQuery(this).find('.vote4me_sys_show_voter');
		jQuery(this).find('.vote4me_sys_show_voter_btn').on('click',function(){
			jQuery(vote4me_tbl+' tr').each(function(){
				jQuery(this).slideToggle();
			});
		});
	});

	jQuery('.vote4me_color-field').wpColorPicker();
	jQuery('#vote4me_append_option_filed .vote4me_append_option_filed_tr').each(function(){
		var it_ele_container = jQuery(this);
		jQuery(this).find('#vote4me_poll_option_rm_btn').click(function() {
			jQuery(it_ele_container).remove();
		});
	});	
	jQuery('.vote4me_add_option_btn').click(function()
	{	
		var date = new Date();
		var components = [
			date.getYear(),
			date.getMonth(),
			date.getDate(),
			date.getHours(),
			date.getMinutes(),
			date.getSeconds(),
			date.getMilliseconds()
		];

		var uniqid = components.join("");
		
		jQuery('#vote4me_append_option_filed').append(' \
			<tr class="vote4me_append_option_filed_tr"> \
			<td> \
			<table class="form-table"> \
			<tr><td>Name</td><td><input type="text" class="widefat" id="vote4me_poll_option" name="vote4me_poll_option[]" required/></td></tr> \
			<tr><td>Imatge</td> \
				<td><input type="url" class="widefat" id="vote4me_poll_option_img" name="vote4me_poll_option_img[]"/> \
					<input type="hidden" name="vote4me_poll_option_id[]" id="vote4me_poll_option_id" value="'+uniqid+'"/></td> \
				<td><input type="button" class="button" id="vote4me_poll_option_btn" name="vote4me_poll_option_btn" value="Upload"></td></tr> \
			<tr><td>Imatge de fons</td> \
				<td><input type="url" class="widefat" id="vote4me_poll_option_cover_img" name="vote4me_poll_option_cover_img[]" value=""/></td> \
				<td><input type="button" class="button" id="vote4me_poll_option_ci_btn" name="vote4me_poll_option_ci_btn" value="Upload"></td></tr> \
			<tr><td>Sexe</td> \
				<td><select class="widefat" id="vote4me_poll_option_sex" name="vote4me_poll_option_sex[]" value="" required> \
					<option value="male">Male</option> \
					<option value="female">Female</option> \
					<option value="other">Other</option> \
					</select></td></tr> \
			<tr><td>Territorial</td> \
				<td><select class="widefat" id="vote4me_poll_option_territorial" name="vote4me_poll_option_territorial[]" value="" required> \
					<option value="Baix LLobregat">Baix LLobregat</option> \
					<option value="Barcelona comarques">Barcelona comarques</option> \
					<option value="Catalunya central">Catalunya central</option> \
					<option value="Girona">Girona</option> \
					<option value="Lleida">Lleida</option> \
					<option value="Maresme / Vallès Oriental">Maresme / Vallès Oriental</option> \
					<option value="Tarragona" >Tarragona</option> \
					<option value="Terres de l\'Ebre">Terres de l\'Ebre</option> \
					<option value="Vallès Occidental">Vallès Occidental</option> \
					<option value="Barcelona">Barcelona</option> \
					</select></td></tr> \
			<tr><td>Secretaria</td> \
				<td><select class="widefat" id="vote4me_poll_option_secretaria" name="vote4me_poll_option_secretaria[]" value="" required> \
					<option value="Organització">Organització</option> \
					<option value="Acció sindical">Acció sindical</option> \
					<option value="Comunicació">Comunicació</option> \
					<option value="Política educativa i igualtat">Política educativa i igualtat</option> \
					<option value="Formació">Formació</option> \
					<option value="Ensenyament concertat i privat">Ensenyament concertat i privat</option> \
					<option value="Juntes de personal">Juntes de personal</option> \
					</select></td></tr> \
			<tr><td colspan="2"><input type="button" class="button" id="vote4me_poll_option_rm_btn" name="vote4me_poll_option_rm_btn" value="Remove This Option"></td></tr></table></td></tr>');
		
		jQuery('#vote4me_append_option_filed .vote4me_append_option_filed_tr').each(function(){
		var it_ele_container = jQuery(this);
			jQuery(this).find('#vote4me_poll_option_rm_btn').click(function() {
				jQuery(it_ele_container).remove();
			});
		});	
		jQuery('#vote4me_append_option_filed .vote4me_append_option_filed_tr').each(function(){
	
		jQuery(this).find('#vote4me_poll_option_btn').click(function(e) {

			var img_val = jQuery(this).parent().parent().find('#vote4me_poll_option_img');
			var image = wp.media({ 
				title: 'Upload Image',
				// mutiple: true if you want to upload multiple files at once
				multiple: false
			}).open()
			.on('select', function(e){
				// This will return the selected image from the Media Uploader, the result is an object
				var uploaded_image = image.state().get('selection').first();
				// We convert uploaded_image to a JSON object to make accessing it easier
				// Output to the console uploaded_image
		 
				var image_url = uploaded_image.toJSON().url;
				// Let's assign the url value to the input field
				//console.log(img_val);
				
				img_val.val(image_url);
			});
		});


		jQuery(this).find('#vote4me_poll_option_ci_btn').click(function(e) {
			var img_val = jQuery(this).parent().parent().find('#vote4me_poll_option_cover_img');
			var image = wp.media({ 
				title: 'Upload Image',
				// mutiple: true if you want to upload multiple files at once
				multiple: false
			}).open()
			.on('select', function(e){
				// This will return the selected image from the Media Uploader, the result is an object
				var uploaded_image = image.state().get('selection').first();
				// We convert uploaded_image to a JSON object to make accessing it easier
				// Output to the console uploaded_image
		 
				var image_url = uploaded_image.toJSON().url;
				// Let's assign the url value to the input field
				//console.log(img_val);
				
				img_val.val(image_url);
			});
		});
	});
	});



		jQuery('#vote4me_append_option_filed .vote4me_append_option_filed_tr').each(function(){
	
		jQuery(this).find('#vote4me_poll_option_btn').click(function(e) {

			var img_val = jQuery(this).parent().parent().find('#vote4me_poll_option_img');
			var image = wp.media({ 
				title: 'Upload Image',
				// mutiple: true if you want to upload multiple files at once
				multiple: false
			}).open()
			.on('select', function(e){
				// This will return the selected image from the Media Uploader, the result is an object
				var uploaded_image = image.state().get('selection').first();
				// We convert uploaded_image to a JSON object to make accessing it easier
				// Output to the console uploaded_image
		 
				var image_url = uploaded_image.toJSON().url;
				// Let's assign the url value to the input field
				//console.log(img_val);
				
				img_val.val(image_url);
			});
		});


		jQuery(this).find('#vote4me_poll_option_ci_btn').click(function(e) {
			var img_val = jQuery(this).parent().parent().find('#vote4me_poll_option_cover_img');
			var image = wp.media({ 
				title: 'Upload Image',
				// mutiple: true if you want to upload multiple files at once
				multiple: false
			}).open()
			.on('select', function(e){
				// This will return the selected image from the Media Uploader, the result is an object
				var uploaded_image = image.state().get('selection').first();
				// We convert uploaded_image to a JSON object to make accessing it easier
				// Output to the console uploaded_image
		 
				var image_url = uploaded_image.toJSON().url;
				// Let's assign the url value to the input field
				//console.log(img_val);
				
				img_val.val(image_url);
			});
		});
	});
});
