<?php 
	session_start();
	
	include '../../libs/database.php';
	include '../../config/database.php';

	$db = new Database(DB_TYPE, DB_HOST, DB_NAME, DB_USER, DB_PASS);
	
	$sth = $db->prepare("SELECT tickets 
	 FROM users_infos WHERE id = :id LIMIT 1");
	$sth->execute(array(
					':id' => $_SESSION['player_id']
				));
	$datauser = $sth->fetchAll();
	
	$tickets = $datauser[0]['tickets'];
	
	if($tickets < 1)
	{
		echo "You need a lottery ticket to play, you can buy them in the shop";
		exit();
	}

	$req = $db->prepare('UPDATE users_infos SET tickets=tickets-1 WHERE id='.$_SESSION['player_id']);
	$req->execute();
	
	$rand = mt_rand(0, 100);
	if($rand < 5)
	{
		$req = $db->prepare('UPDATE users_infos SET tokens=tokens+1 WHERE id='.$_SESSION['player_id']);
		$req->execute();
		echo 'You won a Token';
	}
	else if($rand < 25)
	{
		$sth = $db->prepare("SELECT  booster_npc_time
		 FROM users WHERE id = :id LIMIT 1");
		$sth->execute(array(
						':id' => $_SESSION['player_id']
					));
		$datauser = $sth->fetchAll();
		
		if($datauser[0]['booster_npc_time'] > time()) # if the user already have a booster, we increase the booster time by 3600 sec (=1hour)
		{
			$booster_npc_time = $datauser[0]['booster_npc_time'] + (3600*2); # 
		}
		else # else we set it to time() + 3600 (= current time + 1 hour)
		{
			$booster_npc_time = time() + (3600*2); # 
		}
		
		$req = $db->prepare('UPDATE users SET booster_npc_time='.$booster_npc_time.' WHERE id='.$_SESSION['player_id']);
		$req->execute();
		
		echo 'You won 2h of NPC booster';
	}
	else if($rand < 45)
	{
		$amount =  mt_rand(80, 120);
		
		$req = $db->prepare('UPDATE player_cargo SET xenomit=xenomit+'.$amount.' WHERE id='.$_SESSION['player_id']);
		$req->execute();
		
		echo 'You won '.$amount.' Xenomits';
	}
	else if($rand < 70)
	{
		$sth = $db->prepare("SELECT  booster_dmg_time, booster_shd_time, booster_hp_time
		 FROM users WHERE id = :id LIMIT 1");
		$sth->execute(array(
						':id' => $_SESSION['player_id']
					));
		$datauser = $sth->fetchAll();
		
		if($datauser[0]['booster_dmg_time'] > time()) # if the user already have a booster, we increase the booster time by 3600 sec (=1hour)
		{
			$booster_dmg_time = $datauser[0]['booster_dmg_time'] + (3600*4); # 
		}
		else # else we set it to time() + 3600 (= current time + 1 hour)
		{
			$booster_dmg_time = time() + (3600*4); # 
		}
		
		if($datauser[0]['booster_shd_time'] > time()) # if the user already have a booster, we increase the booster time by 3600 sec (=1hour)
		{
			$booster_shd_time = $datauser[0]['booster_shd_time'] + (3600*4); # 
		}
		else # else we set it to time() + 3600 (= current time + 1 hour)
		{
			$booster_shd_time = time() + (3600*4); # 
		}
		
		if($datauser[0]['booster_hp_time'] > time()) # if the user already have a booster, we increase the booster time by 3600 sec (=1hour)
		{
			$booster_hp_time = $datauser[0]['booster_hp_time'] + (3600*4); # 
		}
		else # else we set it to time() + 3600 (= current time + 1 hour)
		{
			$booster_hp_time = time() + (3600*4); # 
		}
		
		$req = $db->prepare('UPDATE users SET booster_dmg_time='.$booster_dmg_time.',booster_shd_time='.$booster_shd_time.',booster_hp_time='.$booster_hp_time.' WHERE id='.$_SESSION['player_id']);
		$req->execute();
		
		echo 'You won 4h of health/damage and shield booster';
	}
	else if($rand < 80)
	{
		$req = $db->prepare('UPDATE player_cargo SET promerium=promerium+500 WHERE id='.$_SESSION['player_id']);
		$req->execute();
		
		echo 'You won 500 Promeriums';
	}
	else if($rand <= 100)
	{
		$sth = $db->prepare("SELECT  booster_spd_time
		 FROM users WHERE id = :id LIMIT 1");
		$sth->execute(array(
						':id' => $_SESSION['player_id']
					));
		$datauser = $sth->fetchAll();
		
		if($datauser[0]['booster_spd_time'] > time()) # if the user already have a booster, we increase the booster time by 3600 sec (=1hour)
		{
			$booster_spd_time = $datauser[0]['booster_spd_time'] + (3600*2); # 
		}
		else # else we set it to time() + 3600 (= current time + 1 hour)
		{
			$booster_spd_time = time() + (3600*2); # 
		}
		
		$req = $db->prepare('UPDATE users SET booster_spd_time='.$booster_spd_time.' WHERE id='.$_SESSION['player_id']);
		$req->execute();
		
		echo 'You won 2h of speed booster';
	}
	
	echo '('.($tickets-1).' tickets left)';
?>	
	
	