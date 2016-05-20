<div class="wrap">
	<h1><?php _e('Edit Subscriber', 'broadfast');?></h1>
	
	<p><a href="admin.php?page=bft_list&ob=<?php echo $_GET['ob']?>&offset=<?php echo $_GET['offset']?>"><?php _e('Back to all subscribers', 'broadfast');?></a></p>
	
	<form method="post">
		<p><label><?php _e('Email address:', 'broadfast');?></label> <br> <input type="text" name="email" value="<?php echo $user->email?>" size="30"></p>
		<p><label><?php _e('User name:', 'broadfast');?></label> <br> <input type="text" name="user_name" value="<?php echo $user->name?>" size="30"></p>
		<p><label><?php _e('Status', 'broadfast');?></label> <br> <select name="status">
			<option value="1" <?php if(!empty($user->status)) echo 'selected'?>><?php _e('Active', 'broadfast');?></option>
			<option value="0" <?php if(empty($user->status)) echo 'selected'?>><?php _e('Inactive', 'broadfast');?></option>
		</select></p>
		<p><label><?php _e('Date subscribed:', 'broadfast');?></label> <br> <input type="text" name="date" class="datepicker" value="<?php echo $user->date;?>"></p>
		
		<p><input type="hidden" name="save_user" value="1">
		<input type="submit" value="<?php _e('Save subscriber', 'broadfast');?>" class="button-primary"></p>
		<?php wp_nonce_field('bft_list_user');?>
	</form>
</div>

<script type="text/javascript" >
jQuery(function(){
	jQuery('.datepicker').datepicker({dateFormat: "yy-m-d"});
});
</script>