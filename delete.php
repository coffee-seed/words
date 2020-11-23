<?php
	include_once('user.php');
	if($id=User::check_cookie()){
		$user=new User($id);
		if(isset($_GET['id'])){
			$user->delete_word($_GET['id']);
		}	
	}
?>