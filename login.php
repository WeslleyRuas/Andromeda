<?php 
	session_start();
	if(isset($_SESSION['loggedIn']) and $_SESSION['loggedIn'] == "true") 
	{
		header('Location: view.php?page=home');
		exit();
	}
	if(!isset($_SESSION['terms_of_use']) || $_SESSION['terms_of_use'] != true)
	{
		header('Location: index.php');
		exit();
	}

	ob_start();

	include 'libs/database.php';
	include 'config/database.php';

	$db = new Database(DB_TYPE, DB_HOST, DB_NAME, DB_USER, DB_PASS);
	
	$errors = array();
	$logerr = handleLoginForm($db);
	$errors = array_merge($errors,$logerr);
	$regerrs = handleRegisterForm($db);
	$errors = array_merge($errors,$regerrs);
	
 ?>	
 <html>
	 <head>
		 <title>Login</title>
		 <link rel="stylesheet" type="text/css" href="styles/default.css" />
		 <link rel="stylesheet" type="text/css" href="styles/login.css" />
	 </head>
	 <body>
		<div id=login>
			<form id="login_form" action="login.php" method="post">
                    <input name="loginForm_login" type="text" placeholder="Pseudo" maxlength="32" />
                    <input name="loginForm_password" type="password" placeholder="Password" maxlength="32" />
                    <input name="connect" type="submit" value="Connexion" />
            </form>
		</div>	
		<div class="logo"></div>
		<?php
		
		if (sizeof($errors) > 0) 
		{
			echo '<div class="error">';
			echo '<p class="error">Error(s): <br>';
				foreach ($errors as $err_msg) {
					echo "&nbsp; &nbsp; - {$err_msg} <br>";
				}
				echo '</ul></p><br><br>';
			echo '</div>';
		}
		?>
		<div id=register>
			<form id="signup" action="login.php" method="post">
				<ul>
					<li><input name="signup_login" type="text" placeholder="Login" maxlength="32" /></li>
					<li><input name="signup_password" type="password" placeholder="Password" maxlength="32" /></li>
					<li><input name="signup_passwordRepeat" type="password" placeholder="Confirm password" maxlength="30" /></li>
					<li><input name="signup_email" type="text" placeholder="Email" /></li>
					<li><input name="signup_pseudo" type="text" placeholder="In-Game name" maxlength="32" /></li>
					<li><input name="signup_submit" type="submit" value="Sign up" /></li>
				</ul>
			</form>
		</div>
		<center><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- andromeda-server -->
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-5037168492183176"
     data-ad-slot="3196835078"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script></center>
		</body>
		</html>
		<?php 
	ob_end_flush();
	
	function convertToNumericEntities($string) {
	    $convmap = array(0x80, 0x10ffff, 0, 0xffffff);
	    return mb_encode_numericentity($string, $convmap, "UTF-8");
	}
	
	function handleLoginForm($db)
	{
		$errors = array();	
		if (empty($_POST['connect']))
		{
			return $errors;
		}
		if (!empty($_POST['loginForm_password']) && !empty($_POST['loginForm_login']))
		{
			$login = convertToNumericEntities(htmlentities($_POST['loginForm_login']));
			$password = md5($_POST['loginForm_password']);
			
			$sth = $db->prepare("SELECT id, is_verified, is_admin FROM users_infos WHERE login = :login AND password= :password");
			$sth->execute(array(
				':login' => $login,
				':password' => $password
			));
			$data = $sth->fetchAll();
			$count = $sth->rowCount();

			if ($count > 0)
			{
				if($data[0]['is_admin'] > 0)
				{
					$_SESSION['loggedIn'] = true; 
					$_SESSION['is_admin'] = true; 
					$_SESSION['player_id'] = $data[0]['id'];
					header('Location: view.php?page=home');
					exit();
				}
				else if($data[0]['is_verified'] > 0)
				{
					$_SESSION['loggedIn'] = true; 
					$_SESSION['player_id'] = $data[0]['id'];
					header('Location: view.php?page=home');
					exit();
				}
				else 
				{					
					$_SESSION['player_id'] = $data[0]['id'];
					header('Location: verify.php');
					exit();
				}			
			}
		}
		return $errors;
	}
	function handleRegisterForm($db)
	{		
		$errors = array();	
		/*$errors[] = 'Registration closed for maintenance.';		
		return $errors;*/
		
		if (empty($_POST['signup_submit'])) 
		{
			return $errors;
		}		
		if (empty($_POST['signup_login']))
		{
			$errors[] = 'Login required.';			
		}
		if ( !preg_match('/^[A-Za-z][A-Za-z0-9]{5,15}$/', $_POST['signup_login'])) 
		{
			$errors[] = 'Invalid login (6-16 characters, Letters and numbers only, Must start with letter).';		
		}
		
		if (empty($_POST['signup_password']))
		{
			$errors[] = 'Password required.';	
		}
		else if (strlen($_POST['signup_password']) < 8) 
		{
			$errors[] = 'Invalid password (8 characters minimum).';	
		}
		if (empty($_POST['signup_passwordRepeat']) || ($_POST['signup_passwordRepeat'] != $_POST['signup_password']))
		{
			$errors[] = 'Invalid password confirmation.';	
		}
		
		if (empty($_POST['signup_email']))
		{
			$errors[] = 'Email required.';	
		}
		else if (!filter_var($_POST['signup_email'], FILTER_VALIDATE_EMAIL)) 
		{
			$errors[] = 'Invalid email.';
		}
		
		if (empty($_POST['signup_pseudo']))
		{
			$errors[] = 'Pseudo required.';	
		}
		else if (strlen($_POST['signup_pseudo']) > 16 || strlen($_POST['signup_pseudo']) < 3) 
		{
			$errors[] = 'Invalid In-Game Name (3-16 characters).';	
		}
		
		if (sizeof($errors) > 0) 
		{		
			return $errors;
		}
		
		$formLogin = htmlentities($_POST['signup_login']);
		
		$sth = $db->prepare("SELECT id FROM users_infos WHERE login = :login");
		$sth->execute(array(
			':login' => $formLogin			
		));
		$count = $sth->rowCount();

		if($count > 0)
		{
			$errors[] = 'Login already used';	
			return $errors;
		}
				
		$formPseudo = convertToNumericEntities(htmlentities($_POST['signup_pseudo']));	
		
		$sth = $db->prepare("SELECT id FROM users WHERE username = :username");
		$sth->execute(array(
			':username' => $formPseudo			
		));
		$count = $sth->rowCount();

		if($count > 0)
		{
			$errors[] = 'In-Game Name already used';	
			return $errors;
		}
		
		$db->insert('users', array(
			'username' => $formPseudo		
		));
		
		$sth = $db->prepare("SELECT id FROM users WHERE username = :username ");
		$sth->execute(array(
			':username' => $formPseudo
		));
		$result = $sth->fetchAll();		
		
		$formPassword = md5($_POST['signup_password']);
		$formEmail = htmlentities($_POST['signup_email']);		
		
		$db->insert('users_infos', array(
			'id' => $result[0]['id'],
			'login' => $formLogin,
			'password' => $formPassword,
			'email' => $formEmail
			));			

		$db->insert('users_settings', array(
			'playerid' => $result[0]['id']
		));
		
		$db->insert('player_config', array(
			'player_id' => $result[0]['id'],
			'damage1' => 5,
			'shield1' => 5,
			'speed1' => 5,
			'damage2' => 5,
			'shield2' => 5,
			'speed2' => 5
			));
			
		$db->insert('users_npc_counts', array(
			'id' => $result[0]['id']
		));
		
		$db->insert('users_npc_lvl', array(
			'id' => $result[0]['id']
		));
		
		$db->insert('player_reff', array(
			'id' => $result[0]['id']
		));
		
		$db->insert('player_cargo', array(
			'id' => $result[0]['id']
		));
		
		$db->insert('users_log', array('playerid' => $result[0]['id'],'message' => "<b>Welcome on <font color='#0080FF'>Andromeda</font></b> (beta)<br/>Your firm gave you 10.000 U.<br/>Have fun !<br/>"));

		//$_SESSION['loggedIn'] = true; 
		$_SESSION['player_id'] = $result[0]['id'];
		header('Location: verify.php?action=send');
		exit();
	}
 ?>	
	