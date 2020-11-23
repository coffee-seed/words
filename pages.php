<?php
	$page=array(
		'reg'=>array(
			'title'			=>'Регистрация',
			'auth'			=>false,
			'web_location'	=>"/reg.php",
			'head_add'	=>'<link rel="stylesheet" href="/css/reg.css">'	
		),
		'auth'=>array(
			'title'			=>'Авторизация',
			'auth'			=>false,
			'web_location'	=>"/auth.php",
			'head_add'	=>'<link rel="stylesheet" href="/css/reg.css">'	
		),
		'index'=>array(
			'title'			=>'Главная',
			'auth'			=>true,
			'web_location'	=>"/",
			'head_add'	=>'<link rel="stylesheet" href="/css/index.css">'	
		),
		'edit'=>array(
			'title'			=>'Панель управления',
			'auth'			=>true,
			'web_location'	=>"/edit.php",
			'head_add'	=>'<link rel="stylesheet" href="/css/edit.css">'	
		)
	);
?> 
