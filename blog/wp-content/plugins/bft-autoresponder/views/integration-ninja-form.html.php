<div class="wrap">
	<h1><?php _e('Integrate Your Mailing List With Nija Form', 'broadfast')?></h1>
	
	<p><?php printf(__('This page is available because you have installed and activated the plugin <a href="%s" target="_blank">Ninja Forms</a>. It lets you connect the form to your Arigato  mailing list so when the form is submitted the user is also subscribed to the mailing list.', 'broadfast'), 'https://ninjaforms.com/');?></p>
	
	<p><a href="admin.php?page=bft_list"><?php _e('Back to your mailing list', 'broadfast');?></a></p>
	
	<form method="post" class="bftpro">
		<div class="postbox wp-admin" style="padding:5px;">
			<p><label><?php _e('Select Ninja Form:', 'broadfast');?></label> <select name="form_id" onchange="this.form.submit();">
				<option value=""><?php _e('-Please select-', 'broadfast');?></option>
				<?php foreach($forms as $form):?>
					<option value="<?php echo $form['id']?>" <?php if($selected_form_id == $form['id']) echo 'selected'?>><?php echo stripslashes($form['data']['form_title'])?></option>
				<?php endforeach;?>
			</select></p>
			
			<h3><?php _e('Mailing list fields','broadfast');?></h3>
			
			<p><?php _e('You need to select which fields from the Ninja form correspond to the fields in the mailing list. The only mailing list field that requires a corresponding form field is email. All other fields can be omitted.', 'broadfast');?></p>
			
			<p><label><?php _e('Email field', 'broadfast');?></label> <select name="field_email">
				<option value=""><?php _e('- Please select -', 'broadfast')?></option>
				<?php foreach($ninja_fields as $ninja): 
					if(empty($ninja['data']['user_email'])) continue;?>
					<option value="<?php echo $ninja['id']?>" <?php if(!empty($integration['fields']['email']) and $integration['fields']['email'] == $ninja['id']) echo 'selected'?>><?php echo stripslashes($ninja['data']['label']);?></option>
				<?php endforeach;?>
			</select></p>
			
			<p><label><?php _e('Name field', 'broadfast');?></label> <select name="field_name">
				<option value=""><?php _e('- No matching field -', 'broadfast')?></option>
				<?php foreach($ninja_fields as $ninja): 
					if(!empty($ninja['data']['user_email']) or $ninja['type']=='_submit' or $ninja['type'] == '_spam') continue;?>
					<option value="<?php echo $ninja['id']?>" <?php if(!empty($integration['fields']['name']) and $integration['fields']['name'] == $ninja['id']) echo 'selected'?>><?php echo stripslashes($ninja['data']['label']);?></option>
				<?php endforeach;?>
			</select></p>
			
			
			<p><label><?php _e('Checkbox to confirm', 'broadfast');?></label> <select name="field_checkbox">
				<option value=""><?php _e('- No checkbox required -', 'broadfast')?></option>
				<?php foreach($ninja_fields as $ninja): 
					if($ninja['type'] != '_checkbox') continue;?>
					<option value="<?php echo $ninja['id']?>" <?php if(!empty($integration['fields']['checkbox']) and $integration['fields']['checkbox'] == $ninja['id']) echo 'selected'?>><?php echo stripslashes($ninja['data']['label']);?></option>
				<?php endforeach;?>
			</select> <br>
			<?php _e("You can choose a checkbox from the Ninja form that shoud be selected to subscribe the user in the list. If you don't select such checkbox, everyone who submits the associated Ninja form will be subscribed to the mailing list.", 'broadfast');?></p>
			
			<p><input type="submit" name="ok" value="<?php _e('Save integration','broadfast');?>"></p>
		</div>	
		<?php wp_nonce_field('bft_ninja');?>
	</form>
</div>