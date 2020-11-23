<?php
	const page_name='edit';
	include_once('header.php');
	if(!empty($_FILES)){
		$user->add_word($_FILES);
	}
	$user->get_words_js();
?>
	<form action="" method="post" enctype="multipart/form-data">
  		Выберите изображение:
  		<input type="file" name="img">
  		Выберите звук:
  		<input type="file" name="sound">
  		<input type="submit" value="Upload Image" name="submit">
	</form> 
	<div id="words_area" class="words_area">
	</div>
	<script type="text/javascript" src="js/edit.js">
	</script>
<?php
	include_once('footer.php');
?> 