
<?php 

$sth = $db->prepare("SELECT clanid
 FROM users WHERE id = :id LIMIT 1");
$sth->execute(array(
				':id' => $_SESSION['player_id']
			));
$datauser = $sth->fetchAll();

if($datauser[0]['clanid'] == 0)
{
	header("Location: view.php?page=clan");
	exit();
}
$clan_id = $datauser[0]['clanid'];

$sth = $db->prepare("SELECT * FROM clan where id = $clan_id");
$sth->execute();
$clansdata = $sth->fetchAll();

$admin_id = $clansdata[0]['admin_id'];

$sth = $db->prepare("SELECT username FROM users where id = $admin_id ");
$sth->execute();
$admin_name_data = $sth->fetchAll();
$admin_name = $admin_name_data[0]['username'];

$is_admin = $admin_id  == $_SESSION['player_id'];

$sth = $db->prepare("SELECT COUNT(id) as nbmembers FROM users WHERE clanid = $clan_id");
$sth->execute();
$memData = $sth->fetchAll();
$nbmembers = $memData[0]['nbmembers'];

$sth = $db->prepare("SELECT * FROM clan_messages WHERE clanid = :clanid ORDER BY timestamp DESC LIMIT 0, 15");
$sth->execute(array(
				':clanid' => $clan_id
			));
$clan_messages = $sth->fetchAll();

$errors = array();	
$errors = handleNewMessageForm($db);
$errors_edit = array();	
if($is_admin)
{
	$errors_edit = handleEditDescriptionForm($db,$clan_id);	
	handleDeleteClanForm($db,$clan_id);
}
else
{
	handleLeaveClanForm($db);
}

?>
<link rel="stylesheet" type="text/css" href="styles/achievements.css" />
<div class="box" style="margin-left: 100px;">
	<div class="title">Clan informations</div>
	<div id="clan-infos">	
		<?php 
			if($is_admin)
			{							
				if (sizeof($errors_edit) > 0) 
				{
					echo '<div class="error">';
					echo '<p class="error">Error(s): <br>';
						foreach ($errors_edit as $err_msg) {
							echo "&nbsp; &nbsp; - {$err_msg} <br>";
						}
						echo '</ul></p><br><br>';
					echo '</div>';
				}	
			}				
		?>	
		<div class="stat"><div class="stat-left">Clan's Company</div><div class="stat-right"><img src="img/ranks/company/<?=$clansdata[0]['clan_company']?>.png"></div></div>
		<div class="stat"><div class="stat-left">Clan's Tag</div><div class="stat-right"><?=$clansdata[0]['clan_tag']?></div></div>
		<div class="stat"><div class="stat-left">Clan's Name</div><div class="stat-right"><?=$clansdata[0]['clan_name']?></div></div>
		<div class="stat"><div class="stat-left">Marshal</div><div class="stat-right"><?=$admin_name?></div></div>
		<div class="stat"><div class="stat-left">Number of members</div><div class="stat-right"><?=$nbmembers?></div></div>
		<div class="stat"><div class="stat-left">Number of kills</div><div class="stat-right"><?=$clansdata[0]['kill_count']?></div></div>
		<div class="stat"><div class="stat-left">Clan's Description</div>
			<div class="stat-rightbis">
				<form class="clan-form" action="view.php?page=clan&tab=claninfos" method="post">
					<textarea name="clan-edit-form-description" rows=3 cols=40><?=$clansdata[0]['clan_description']?></textarea>
					<?php 
					if($is_admin)
					{	
					?>					
						<input name="clan-edit-form-submit" type="submit" value="Save" />
					<?php 
					}
					?>
				</form>	
			</div>
		</div>		
	</div>
</div>	

<div class="box" style="margin-left: 100px;">
	<div class="title">Clan messages</div>
	<div id="clan-messages">
		<div id="clan-messages-messages">
		<?php 	
			foreach ($clan_messages as $message)
			{
				$pid = $message['player_id'];
				$sth = $db->prepare("SELECT username FROM users WHERE id = $pid");
				$sth->execute();
				$nameData = $sth->fetchAll();
				$name = $nameData[0]['username'];
				?>	
				<font color='#00AAFF'><?=$name?></font>(<font color='#FFFFFF'><?=$message['timestamp']?></font>)<br>
				<?=$message['message']?><br>
				<?php
			}
		?>
		</div>
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
		<form class="clan-form" action="view.php?page=clan&tab=claninfos" method="post">
				<ul>
					<b>New Message</b> (12-120 characters):<li> <textarea name="clan-newmessage-message" rows=3 cols=40></textarea></li>
					<li><input name="clan-newmessage-submit" type="submit" value="Post New Message" /></li>
				</ul>
		</form>	
	</div>
</div>


<div class="box" style="margin-left: 100px;">
	<div class="title">Clan Administration</div>
		<div id="clan-admin">	
					<form class="clan-form" action="view.php?page=clan&tab=claninfos" method="post">
					<?php 
					if($is_admin)
					{	
					?>	
						<input name="clan-delete-form-submit" type="submit" value="Close and Delete Clan" />
					<?php 
					}
					else
					{
					?>
						<input name="clan-leave-form-submit" type="submit" value="Leave Clan" />
					<?php 
					}
					?>
					</form>	
		</div>
	</div>
</div>

	
<?php 
function convertToNumericEntities($string) 
{
	$convmap = array(0x80, 0x10ffff, 0, 0xffffff);
	return mb_encode_numericentity($string, $convmap, "UTF-8");
}

function handleLeaveClanForm($db)
{
	if (empty($_POST['clan-leave-form-submit']))
	{
		return;
	}
	else
	{
		$sth = $db->prepare("UPDATE users SET clanid=0 WHERE id=:id");
		$sth->execute(array(':id' => $_SESSION['player_id']));	
		
		header("Location: view.php?page=clan&tab=joinclan");
		exit();
	}	
}

function handleDeleteClanForm($db,$clan_id)
{
	if (empty($_POST['clan-delete-form-submit']))
	{
		return;
	}
	else
	{
		$db->update('users', array(
			'clanid' => 0
			),
			'clanid='.$clan_id
			);		
			
		$sth = $db->prepare("DELETE FROM `clan` WHERE id=:clanid");
		$sth->execute(array(':clanid' => $clan_id));
		
		$sth = $db->prepare("DELETE FROM clan_messages WHERE clanid=:clanid");
		$sth->execute(array(':clanid' => $clan_id));

		$sth = $db->prepare("DELETE FROM `clan_request` WHERE clan_id=:clanid");
		$sth->execute(array(':clanid' => $clan_id));
		
		$sth = $db->prepare("DELETE FROM `clan_diplomacy` WHERE clan_id=:clanid OR second_clan_id=:clanid");
		$sth->execute(array(':clanid' => $clan_id));
		
		$sth = $db->prepare("DELETE FROM `clan_diplomacy_request` WHERE clan_id=:clanid OR second_clan_id=:clanid");
		$sth->execute(array(':clanid' => $clan_id));

		$sth = $db->prepare("UPDATE `users` SET `clanid`=0 WHERE clanid=:clanid");
		$sth->execute(array(':clanid' => $clan_id));			
		
		header("Location: view.php?page=clan&tab=createclan");
		exit();		
	}
}

function handleEditDescriptionForm($db,$clan_id)
{
	$errors = array();	
	if (empty($_POST['clan-edit-form-submit']))
	{
		return $errors;
	}
	
	if (empty($_POST['clan-edit-form-description']))
	{
		$errors[] = "Clan's Description required.";	
	}
	else if (strlen($_POST['clan-edit-form-description']) > 120 || strlen($_POST['clan-edit-form-description']) < 12) 
	{
		$errors[] = "Invalid Clan's Description (12-120 characters).";	
	}
	
	if (sizeof($errors) > 0) 
	{		
		return $errors;
	}
	else
	{
		$description = convertToNumericEntities(htmlentities($_POST['clan-edit-form-description']));
		$db->update('clan', array(
			'clan_description' => $description
			),
			'id='.$clan_id
			);
			
		header("Location: view.php?page=clan&tab=claninfos");
		exit();
	}	
}
function handleNewMessageForm($db)
{
	$errors = array();	
	if (empty($_POST['clan-newmessage-submit']))
	{
		return $errors;
	}
	
	if (empty($_POST['clan-newmessage-message']))
	{
		$errors[] = "Message required.";	
	}
	else if (strlen($_POST['clan-newmessage-message']) > 120 || strlen($_POST['clan-newmessage-message']) < 12) 
	{
		$errors[] = "Invalid Message (12-120 characters).";	
	}
	
	if (sizeof($errors) > 0) 
	{		
		return $errors;
	}
	else
	{
		$sth = $db->prepare("SELECT clanid
		 FROM users WHERE id = :id LIMIT 1");
		$sth->execute(array(
						':id' => $_SESSION['player_id']
					));
		$datauser = $sth->fetchAll();

		if($datauser[0]['clanid'] == 0)
		{
			header("Location: view.php?page=clan&tab=createclan");
			exit();
		}
		$clan_id = $datauser[0]['clanid'];
		
		$message = convertToNumericEntities(htmlentities($_POST['clan-newmessage-message']));
		
		$db->insert('clan_messages', array(
			'clanid' => $clan_id,
			'player_id' => $_SESSION['player_id'],
			'message' => $message
			));
			
		header("Location: view.php?page=clan&tab=claninfos");
		exit();
	}
}
?>