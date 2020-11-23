<?php
	class User{
		//private statis variables
		/*
		Параметры соединения с базой данных
		*/
		private static $mysql= array(
			'host' => '127.0.0.1',
			'user'=> 'words_user',
			'password'=>'pass123',
			'db'=>'words_db');
		//private variables
		/*
		Объект mysqli с подключением к базе данных для всего класса
		*/
		private $mysqli=false;
		//public variables
		/*
		id ползователя в экземпляре класса
		*/
		public $id;
		//Public static variables
		/*
		Переменная со статусом выполнения функции		
		*/
		public static $status;
		//construct, destruct
		function __construct($uid=false){
		/*
		Конструктор, принимает на вход необязательный параметр id пользователя
		Если есть id, присваивается параметр id
		Создаёт соединение с базой данных
		*/
			if($uid){
				$this->id=$uid;			
			}
			$this->mysqli=$this->my_connect();
  		}
  		function __destruct() {
  			/*
  			Деструктор, закрывает соединение с бд
  			*/
       	$this->mysqli->close();
   	}
   	//public functions
   	public function auth($email,$password){
   		/*
   		Авторизация пользователя: 
   		на вход email и пароль
   		Сохраняет в объекте id пользователя
   		вызывает функцию set_cookie, которая генерирует куки на стороне пользователя и сохраняет в бд
   		возвращает id пользователя, если авторизация удачная, иначе false
   		*/ 
			$email=$this->mysqli->real_escape_string($email);
			$query=$this->mysqli->query("
				SELECT `id` FROM `users` WHERE `email`='$email' AND `password`='".self::pass_hash($password)."';	
			");
			if($res=$query->fetch_assoc()){
				$this->id=$res['id'];
				$this->set_cookie();
				$query->close();
				return $this->id;						
			}
			$query->close();
			return false;
   	}
   	public function get_words(){
   		/*
   		Передаёт из базы данных список слов пользователя
   		*/
			$query=$this->mysqli->query("
				SELECT * FROM `words` WHERE `uid`='".$this->id."';	
			");
			$res=array();
			while($r=$query->fetch_assoc()){
				array_push($res,$r);			
			}
			$query->close();
			return $res;	   	
   	}
   	public function get_words_js(){
   		$r=$this->get_words();
			echo "<script>words=JSON.parse('".json_encode($r)."');</script>";
   	}
		public function delete_word($wid){
   		/*
   		Удалить слово по id
   		*/
			$wid=$this->mysqli->real_escape_string($wid);
			$query=$this->mysqli->query("
				SELECT * FROM `words` WHERE `uid`='".$this->id."' AND `id`=".$wid.";	
			");
			if($res=$query->fetch_assoc()){
				unlink($_SERVER['DOCUMENT_ROOT'].$res['image']);
				unlink($_SERVER['DOCUMENT_ROOT'].$res['sound']);
				$this->mysqli->query("
					DELETE FROM `words` WHERE `uid`='".$this->id."' AND `id`=".$wid.";	
				");
			}
			$query->close();
   	}
   	public function add_word($files){
			/*
			Добавить слово			  
			*/
			if($this->is_audio($files['sound']['name']) AND $this->is_image($files['img']['name'])){
			  $sound_name=$this->gen64().".".pathinfo($files['sound']['name'])['extension'];
			  $upload_sound = $_SERVER['DOCUMENT_ROOT'].'/uploaded/sound/'.$sound_name;
			  $img_name=$this->gen64().".".pathinfo($files['img']['name'])['extension'];
      	  $upload_img = $_SERVER['DOCUMENT_ROOT'].'/uploaded/img/'.$img_name;
      	  	if(move_uploaded_file($files['sound']['tmp_name'], $upload_sound) AND move_uploaded_file($files['img']['tmp_name'], $upload_img)){
          	  $this->mysqli->query("INSERT INTO `words` SET `uid`='".$this->id."', `image`='/uploaded/img/$img_name',`sound`='/uploaded/sound/$sound_name';");
          	  return true;
        		}
        		else{
          		return false;
        		}
      	}
      	else{
        		return false;
      	}
   	}
   	public static function reg($email,$password){
   		/*
   		Функция для регистрации пользователя
   		на вход принимает email и пароль
   		создаёт запись в базе данных
   		возвращает id пользователя, если не зарегестрировало, вовращает 0
   		*/
   		if(check_email($email)){ 
				$email=$this->mysqli->real_escape_string($email);			
				$this->mysqli->query("
					INSERT INTO `users` SET `email`='$email',`password`='".self::pass_hash($password)."';
				");
				self::status=true;
				return $this->mysqli->insert_id;
			}
			else{
				self::status=false;
				return false;
			}
  		}
  		public function check_email($email){
  			/*
  			Функция для проверки существования email в бд
  			Принимает на вход email
  			возвращает true, если email свободен и false если занят
  			*/ 
			$email=$this->mysqli->real_escape_string($email);			
			$query=$this->mysqli->query("
				SELECT `id` FROM `users` WHERE `email`='$email';
			");
			$ret=!$query->num_rows;
			$query->close();
			return $ret;
  		}
   	//private functions
   	private function gen64(){
  			/*
  				Генерирует случайную строку длиной 64 символа
  			*/
			return hash("sha256", random_bytes(64));
  		}
   	private function is_audio($file_name){
   		if(pathinfo($file_name)['extension']){
				
			}
			return true;
   	}
   	private function is_image($file_name){
			if(pathinfo($file_name)['extension']){
				
			}
			return true;
   	}
   	private function set_cookie(){
			/*
			Функция создаёт куки на клиенте и запись о них в базе данных, id пользователя берет из $this
			*/
			$this->mysqli->query("
				DELETE FROM `cookies` WHERE `uid`=".$this->id."; 
			");
			$ans=0;
			while(!$ans){
				$s=$this->gen64();
				$t=$this->gen64();
				$query=$this->mysqli->query("
					INSERT INTO `cookies` SET `uid`=".$this->id.", `session`='$s', `token`='$t'; 
				");
				$ans=$this->mysqli->insert_id;
			}
			$this->cookie_writer($s,$t);
   	}
   	private function cookie_writer($session,$token){
   		/*
   			Задаёт куки сессии и токена в браузере пользователя сроком действия 1 месяц
   		*/
   		setcookie("s", $session, time()+2592000, "/","words.newpage.xyz",1);
   		setcookie("t", $token, time()+2592000, "/","words.newpage.xyz",1);
   	}
   	//private static functions
 		private static function my_connect(){
 			/*
 				Функция создаёт объект mysqli и устанавливает кодировку
 				вовращает mysqli
 			*/
	  		$mysqli= new mysqli(self::$mysql['host'], self::$mysql['user'], self::$mysql['password'], self::$mysql['db']);
	  		if ($mysqli -> connect_errno) {
 				echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  				exit();
			}
	  		$mysqli->set_charset ("utf8_unicode_ci");
	  		return $mysqli;
  		}
  		private static function pass_hash($text){
  			/*
  				Функция для получения хеша от текста по заранее заданному алгоритму
  			*/
			return hash("sha256", $text."salt");
  		}
  		//Public static function
  		public static function check_cookie(){
			/*
  			Статическая функция для проверки валидности cookie
  			возвращает id пользователя, если пользователь авторизован через cookie, продлевает cookie на месяц и false если нет
  			*/
  			if(isset($_COOKIE['s']) and isset($_COOKIE['t'])){
				$connect=self::my_connect(); 
				$session=$connect->real_escape_string($_COOKIE['s']);
				$token=$connect->real_escape_string($_COOKIE['t']);			
				$query=$connect->query("
					SELECT `uid` FROM `cookies` WHERE `session`='$session' AND `token`='$token';
				");
				$ret=false;
				if($r=$query->fetch_assoc()){
					$ret=$r['uid'];
					setcookie("s", $_COOKIE['s'], time()+2592000, "/","words.newpage.xyz",1);
					setcookie("t", $_COOKIE['t'], time()+2592000, "/","words.newpage.xyz",1);			
				}
				$query->close();
				$connect->close();
				return $ret;  		
			}
			else{
				return false;			
			}
  		}
		public static function create_refer($refer_url){
  			/*
  			Создаёт cookie с адресом сраницы редиректа
  			*/
  			setcookie("r", base64_encode($refer_url), time()+2592000, "/","words.newpage.xyz",1);
  		}
  		public static function drop_cookie(){
			/*
			Стирает сессию в браузере и в базе данных
			*/
			if(isset($_COOKIE['s']) and isset($_COOKIE['t'])){
				$connect=self::my_connect(); 
				$session=$connect->real_escape_string($_COOKIE['s']);
				$token=$connect->real_escape_string($_COOKIE['t']);			
				$query=$connect->query("
					DELETE FROM `cookies` WHERE `session`='$session' AND `token`='$token';
				");
				setcookie("s", "", time()-60, "/","words.newpage.xyz",1);
				setcookie("t", "", time()-60, "/","words.newpage.xyz",1);
				$query->close();
				$connect->close();
				return $ret;  		
			}
			else{
				return false;			
			}  		
  		}
  		public static function go_to_refer(){
  			/*
  			Получает из cookie информацию о странице, требовавашей авторизации, если таковая отсутсвует -перекидывает на главную страницу
  			*/
  			if(isset($_COOKIE['r'])){
  				$r=$_COOKIE['r'];
  				setcookie("r", "", time()-60, "/","words.newpage.xyz",1);
				?>
					<script>
						window.location="<?php 
							echo base64_decode($r);						
						?>";
					</script>					
				<?php
  			}
  			else{
				?>
					<script>
						window.location="/";
					</script>					
				<?php	
  			}
  		} 
	}
?>
