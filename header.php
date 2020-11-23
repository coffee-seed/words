<?php
	//Debug
	
		ini_set('error_reporting', E_ALL);
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
	
	include_once('user.php');
	include_once('pages.php');
	if($id=User::check_cookie()){
		$user=new User($id);	
	}
	if(!$id AND $page[page_name]['auth']){
		User::create_refer($page[page_name]['web_location']);
		?>
			<script>
				window.location="/auth.php";
			</script>					
		<?php
		exit;	
	}
?>
<!DOCTYPE html>
<html lang="ru">
	<head>
		<title><?php echo $page[page_name]['title']; ?></title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="/css/header.css">
		<meta name="viewport" content="width=device-width, user-scalable=no">
		<link rel="shortcut icon" href="/logo.png" type="image/x-icon">
		<?php echo $page[page_name]['head_add']; ?>
	</head>
	<body>
		<header>
			<a href="/"><h1>Слова</h1></a>
			<ul>
				<?php
					if($id){
						?>
							<li>
								<a href="#" id='exit'>Выход</a>
							</li>
							<script type="text/javascript" src="/js/header.js">
							</script>
							<li>
								<a href="/edit.php">Управление</a>
							</li>
						<?php
					}
					else{
						?>
							<li>
								<a href="/reg.php">Регистрация</a>
							</li>
							<li>
								<a href="/auth.php">Вход</a>
							</li>
						<?php					
					}
				?>
			</ul>
		</header>
		<main>