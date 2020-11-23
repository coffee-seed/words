<?php
	const page_name='auth';
	include_once('header.php');
	if(empty($_POST)){
?>
	<div>
		<form method="post" action="">
			<table>
				<tr>
					<td>
						Email:
					</td>
					<td>
						<input type="email" placeholder="mail@example.com" name='email'>
					</td>
				</tr>
				<tr>
					<td>
						Пароль:
					</td>
					<td>
						<input type="password" placeholder="пароль" name='password'>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<input type="submit" value='Войти'>
					</td>
				</tr>		
			</table>
		</form>
	</div>
<?php	
	}
	else{
		if(isset($_POST['email']) AND isset($_POST['password'])){
			$user= new User();
			if($user->auth($_POST['email'],$_POST['password'])){
				User::go_to_refer();			
			}
			else{
				?>				
					<script>
						alert("Неверный email или пароль!");
					</script>
				<?php	
			}
		}
	}
	include_once('footer.php');
?> 