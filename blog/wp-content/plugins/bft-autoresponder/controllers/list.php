<?php
/* Manages the mailing list */
function bft_list(){
	global $wpdb;
	
	$action = empty($_GET['action']) ? 'list' : $_GET['action'];
	
	if(isset($_POST['email'])) $email=esc_sql($_POST['email']);
	if(isset($_POST['user_name'])) $name=esc_sql($_POST['user_name']);
	if(isset($_POST['id'])) $id=esc_sql($_POST['id']);
	$status=@$_POST['status'];
	$dateformat = get_option('date_format');
	
	switch($action) {
		case 'edit':
			if(!empty($_POST['save_user']) and check_admin_referer('bft_list_user')) {
				$sql=$wpdb->prepare("UPDATE ".BFT_USERS." SET 
				date=%s, name=%s, email=%s, status=%d
				WHERE id=%d", $_POST['date'], $name, $email, $status, $_GET['id']);
				$wpdb->query($sql);
				
				$url = "admin.php?page=bft_list&offset=".$_GET['offset']."&ob=".$_GET['ob'];
				echo "<meta http-equiv='refresh' content='0;url=$url' />"; 
				exit;
			}
			
			// select user
			$user = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . BFT_USERS . " WHERE id=%d", $_GET['id']));
			
			// enqueue datepicker
			wp_enqueue_script('jquery-ui-datepicker');
			wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');			
			require(BFT_PATH."/views/list-user.html.php");	
		break;		
		
		case 'list':
		default:
			$per_page = 20;
			$offset = empty($_GET['offset']) ? 0 : intval($_GET['offset']);		
			
		  $error=false;

			if(!empty($_POST['add_user']) and check_admin_referer('bft_list_user')) {
		        // user exists?
		        $exists=$wpdb->get_row($wpdb->prepare("SELECT *
		                FROM ".BFT_USERS." WHERE email=%s", $email));
		
		        if(empty($exists->id)) {
		            $sql="INSERT IGNORE INTO ".BFT_USERS." (name,email,status,date,ip)
		            VALUES (\"$name\",\"$email\",\"$status\",CURDATE(),'$_SERVER[REMOTE_ADDR]')";       
		            $wpdb->query($sql);
		            
		            if($status) bft_welcome_mail($wpdb->insert_id);    
		        }		
		        else {
		            $error=true;
		            $err_msg="User with this email address already exists.";
		        }
			}
			
			if(!empty($_POST['del_user']) and check_admin_referer('bft_del_user')) {
				$sql="DELETE FROM ".BFT_USERS." WHERE id='$id'";
				$wpdb->query($sql);
			}
			
			// mass delete
			if(!empty($_POST['mass_delete']) and !empty($_POST['del_ids']) and check_admin_referer('bft_mass_del')) {		
				$wpdb->query("DELETE FROM ".BFT_USERS." WHERE id IN (".$_POST['del_ids'].")");
			}
			
			// select users from the mailing list
			$ob = in_array(@$_GET['ob'], array("tU.email","tU.name","tU.ip","tU.date","tU.status,tU.email","cnt_mails"))? $_GET['ob'] : 'email';
			$dir = 'ASC';
			if($ob == 'cnt_mails') $dir = 'DESC';
			$sql="SELECT SQL_CALC_FOUND_ROWS tU.*, COUNT(tS.id) as cnt_mails FROM ".BFT_USERS." tU
				LEFT JOIN ".BFT_SENTMAILS." tS ON tS.user_id=tU.id  
				GROUP BY tU.id ORDER BY $ob $dir LIMIT $offset, $per_page";
			$users=$wpdb->get_results($sql);
			
			$count = $wpdb->get_var("SELECT FOUND_ROWS()");
			
			require(BFT_PATH."/views/bft_list.html.php");	
		break; // end list / add
	}
}

// auto-subscribe user
function bft_auto_subscribe($user_login, $user) {
	global $wpdb;
	
	// only do this if the setting is selected
	if(!get_option('bft_auto_subscribe')) return false;
		
	// if already logged in, return false to avoid needless queries
	if(get_user_meta($user->ID, 'bft_logged_in', true)) return false;
		
	add_user_meta( $user->ID, 'bft_logged_in', 1, true);
	
	$code = substr(md5(microtime().$user_login), 0, 8);	
	$sql=$wpdb->prepare("INSERT IGNORE INTO ".BFT_USERS." (name,email,status,code,date,ip)
	VALUES (%s,%s,1,%s,CURDATE(),%s)", $user_login, $user->user_email, $code, $_SERVER['REMOTE_ADDR']);		
	$wpdb->query($sql);
	$mid = $wpdb->insert_id;
	
	if(empty($mid)) return true;
	
	bft_welcome_mail($mid);
	
	// notify admin?			
	if(get_option('bft_subscribe_notify')) {				
		bft_subscribe_notify($mid);
	}	
} // end auto-subscribe