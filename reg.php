<?php
	const page_name='reg';
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
						<input type="submit" value='Зарегистрироваться'>
					</td>
				</tr>		
			</table>
		</form>
	</div>
<?php	
	}
	else{
		if(isset($_POST['email']) AND isset($_POST['password'])){
			$user=new User;
			if($user->reg($_POST['email'],$_POST['password'])){
				?>
					<script>
						window.location="/auth.php";
					</script>					
				<?php
			}
			else{
				if($user->status!==false){
					?>				
						<script>
							alert("Ошибка регистрации");
						</script>
					<?php
				}
				else{
					?>				
						<script>
							alert("Данный email уже занят!");
						</script>
					<?php	
				}	
			}
		}
	}
	include_once('footer.php');
?> 