<?php
	include_once('user.php');
	if($id=User::check_cookie()){
		$user=new User($id);
		$r=$user->get_words();
		echo "<script>words=JSON.parse('".json_encode($r)."');</script>";
	}
?> 
