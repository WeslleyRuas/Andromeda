
<?php 

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

$sth = $db->prepare("SELECT * FROM clan where id = $clan_id");
$sth->execute();
$clansdata = $sth->fetchAll();

$admin_id = $clansdata[0]['admin_id'];
$is_admin = $admin_id  == $_SESSION['player_id'];

$sth = $db->prepare("SELECT username, id, grade FROM users WHERE clanid = :clanid");
$sth->execute(array(
				':clanid' => $clan_id 
			));
$datamembers = $sth->fetchAll();

if($is_admin)
{	
	if (!empty($_GET['kick']))
	{
		handleKickClan($db,$clan_id);
	}
	else if (!empty($_GET['accept']))
	{
		handleAcceptRequest($db,$clan_id);
	}
	else if (!empty($_GET['refuse']))
	{
		handleRefuseRequest($db,$clan_id);
	}
	
	$sth = $db->prepare("SELECT * FROM clan_request WHERE clan_id=:clanid");
	$sth->execute(array(':clanid' => $clan_id));
	$datarequest = $sth->fetchAll();
}

?>
<div class="box" style="margin-left: 100px;">
	<div class="title">Clan members</div>
	<div id="clan-members">	
		<?php displayClanMember($datamembers,$is_admin); ?>		
	</div>
</div>	

<?php 
if($is_admin)
{
?>
	<div class="box" style="margin-left: 100px;">
	<div class="title">Membership Requests</div>
	<div id="clan-members-requests">	
		<?php displayClanRequest($datarequest,$db); ?>		
	</div>
</div>	
<?php 
}
?>	

<?php 
function displayClanMember($clanmembers,$is_admin)
{
	foreach($clanmembers as $member)	
	{
		?>
				<div class="stat" style="margin-left:-20px;">
					<div class="stat-left">
						<img src="img/ranks/<?=$member['grade']?>.png">
					</div>
					<div class="stat-right" >
						<?=$member['username']?>
						<?php if($is_admin == true && $_SESSION['player_id'] != $member['id'])
						{ ?>
							<a class="leftbutton" href="view.php?page=clan&tab=clanmembers&kick=<?=$member['id']?>">
								Kick
							</a>
						<?php } ?>
					</div>
				</div>
		<?php
	}
}

function handleKickClan($db, $clan_id)
{
	$id = htmlentities($_GET['kick']);
	
	if($_SESSION['player_id'] != $id)
	{
		$sth = $db->prepare("UPDATE `users` SET `clanid`=0 WHERE id=:id AND clanid=$clan_id");
		$sth->execute(array(':id' => $id));
	}	
	
	header("Location: view.php?page=clan&tab=clanmembers");
	exit();
}

function displayClanRequest($datarequest, $db)
{
	foreach($datarequest as $request)	
	{
		$sth = $db->prepare("SELECT username, grade, id FROM users WHERE id = :id LIMIT 1");
		$sth->execute(array(
						':id' => $request['player_id'] 
					));
		$datamember = $sth->fetchAll();
		?>
				<div class="stat" style="margin-left:-20px;">
					<div class="stat-left">
						<img src="img/ranks/<?=$datamember[0]['grade']?>.png">
					</div>
					<div class="stat-right">
						<?=$datamember[0]['username']?>
						<a class="leftbutton" href="view.php?page=clan&tab=clanmembers&refuse=<?=$request['id'] ?>">
							Refuse
						</a>
						<a class="leftbutton" href="view.php?page=clan&tab=clanmembers&accept=<?=$request['id'] ?>">
							Accept
						</a>
					</div>					
				</div>
				<div class="stat" style="margin-left:-20px;margin-top:-4px;">
					<div class="stat-left">
						Message
					</div>
					<div class="stat-rightbis" style="padding-bottom:5px;">
						<?=$request['message']?>					
					</div>					
				</div>
		<?php
	}
}

function handleAcceptRequest($db,$clanid)
{
	$id = htmlentities($_GET['accept']);
	
	$sth = $db->prepare("SELECT * FROM clan_request WHERE id = :id LIMIT 1");
	$sth->execute(array(
						':id' => $id 
					));
	$request_data = $sth->fetchAll();
	if(count($request_data) != 1)
	{
		return;
	}
	
	$request = $request_data[0];
	
	if($request['clan_id'] != $clanid)	
	{
		return;
	}
		
	$sth = $db->prepare("UPDATE `users` SET `clanid`=:clan_id WHERE id=:id");
	$sth->execute(array('id' => $request['player_id'],':clan_id' => $clanid));
	
	$sth = $db->prepare("DELETE FROM `clan_request` WHERE player_id=:player_id");
	$sth->execute(array(':player_id' => $request['player_id']));
	
	header("Location: view.php?page=clan&tab=clanmembers");
	exit();
}

function handleRefuseRequest($db,$clanid)
{	
	$id = htmlentities($_GET['refuse']);
	$sth = $db->prepare("DELETE FROM `clan_request` WHERE id=:id AND `clan_id`=:clan_id");
	$sth->execute(array(':id' => $id,':clan_id' => $clanid));
	
	header("Location: view.php?page=clan&tab=clanmembers");
	exit();
}
?>