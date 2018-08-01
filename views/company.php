<?php 
if(!empty($_GET['factionid']) && !empty($_GET['type']))
{
	$buymessage = handleCompanyChange($db,(int)$_GET['factionid'],(int)$_GET['type']);
}
?>

<link rel="stylesheet" type="text/css" href="styles/home.css" />
<link rel="stylesheet" type="text/css" href="styles/company.css" />
<link rel="stylesheet" type="text/css" href="styles/userStyle.css" />
<div class="CMSContent">
	<div class="box" style="margin-left:220px;margin-bottom:20px;">
		<div class="title">
			Company Change
			</br>
			Cost: 250,000 uridium, 30% rankpoints
		</div>
		<div id="company">
			<center>
			<a href="view.php?page=company&factionid=1&type=1">
				<img class="company_logo" src="img/mmo.jpg">
			</a>
			
			<a href="view.php?page=company&factionid=2&type=1">
				<img class="company_logo" src="img/eic.jpg">
			</a>
			
			<a href="view.php?page=company&factionid=3&type=1">
				<img class="company_logo" src="img/vru.jpg">
			</a>
			</center>
		</div>
	</div>	
	<div class="box" style="margin-left:220px;margin-bottom:20px;">
		<div class="title">
			Company Change
			</br>
			Cost: 5 tokens, 250,000 uridium, 15% rankpoints
		</div>
		<div id="company" style="padding-left:1px;">
			<center>
			<a href="view.php?page=company&factionid=1&type=2">
				<img class="company_logo" src="img/mmo.jpg">
			</a>
			
			<a href="view.php?page=company&factionid=2&type=2">
				<img class="company_logo" src="img/eic.jpg">
			</a>
			
			<a href="view.php?page=company&factionid=3&type=2">
				<img class="company_logo" src="img/vru.jpg">
			</a>
			</center>
		</div>
	</div>	
	<div class="box" style="margin-left:220px;margin-bottom:20px;">
		<div class="title">
			Company Change
			</br>
			Cost: 10 tokens, 250,000 uridium, 5% rankpoints
		</div>
		<div id="company" style="padding-left:1px;">
			<center>
				<a href="view.php?page=company&factionid=1&type=3">
					<img class="company_logo" src="img/mmo.jpg">
				</a>
				
				<a href="view.php?page=company&factionid=2&type=3">
					<img class="company_logo" src="img/eic.jpg">
				</a>
				
				<a href="view.php?page=company&factionid=3&type=3">
					<img class="company_logo" src="img/vru.jpg">
				</a>
			</center>
		</div>
	</div>	
</div>	

<?php
if(isset($buymessage))
{
?>
	<div id="popup_box">    <!-- OUR PopupBox DIV-->
		<div id="popupContent"> 
		<?=$buymessage?>
		</div>
		<a id="popupBoxClose"  >Close</a>    
	</div>

	<script src="http://jqueryjs.googlecode.com/files/jquery-1.2.6.min.js" type="text/javascript"></script>
	<script type="text/javascript">
		
		$(document).ready( function() {
		
			// When site loaded, load the Popupbox First
			loadPopupBox();
		
			$('#popupBoxClose').click( function() {            
				unloadPopupBox();
			});
			
			$('#container').click( function() {
				unloadPopupBox();
			});

			function unloadPopupBox() {    // TO Unload the Popupbox
				$('#popup_box').fadeOut("slow");
				$("#container").css({ // this is just for style        
					"opacity": "1"  
				}); 
			}    
			
			function loadPopupBox() {    // To Load the Popupbox
				$('#popup_box').fadeIn("slow");
				$("#container").css({ // this is just for style
					"opacity": "0.3"  
				});         
			}        
		});
	</script>  
<?php
}
?>
	
<?php
function handleCompanyChange($db, $factionid, $type)
{
	
	$sth = $db->prepare("SELECT tokens
	 FROM users_infos WHERE id = :id LIMIT 1");
	$sth->execute(array(
					':id' => $_SESSION['player_id']
				));
	$datauser = $sth->fetchAll();

	$tokens = $datauser[0]['tokens'];
	
	$sth = $db->prepare("SELECT  uridium, rankpoints, clanid
	 FROM users WHERE id = :id LIMIT 1");
	$sth->execute(array(
					':id' => $_SESSION['player_id']
				));
	$datauser = $sth->fetchAll();
	if($datauser[0]['uridium'] <= 250000)
	{
		return "Not enough uridium";
	}
	
	if($type == 1)
	{
		$newRankpoints = floor($datauser[0]['rankpoints'] * 0.70);
	}
	else if($type == 2)
	{
		if($tokens >= 5)
		{
			$newRankpoints = floor($datauser[0]['rankpoints'] * 0.85);
			$req = $db->prepare('UPDATE users_infos SET tokens=tokens-5 WHERE id='.$_SESSION['player_id']);
			$req->execute();
		}
		else
		{
			return "Not enough tokens";
		}
	}
	else if($type == 3)
	{
		if($tokens >= 10)
		{
			$newRankpoints = floor($datauser[0]['rankpoints'] * 0.95);
			$req = $db->prepare('UPDATE users_infos SET tokens=tokens-10 WHERE id='.$_SESSION['player_id']);
			$req->execute();
		}
		else
		{
			return "Not enough tokens";
		}
	}
	else
	{
		return "Type is not valid";
	}
	
	switch($factionid)
	{
		case 1:
			$req = $db->prepare('UPDATE users SET factionid=1, uridium=uridium-250000, rankpoints='.$newRankpoints.', locx=2000, locy=1100, mapid=1 WHERE id='.$_SESSION['player_id']);
			$req->execute();
			break;
		case 2:
			$req = $db->prepare('UPDATE users SET factionid=2, uridium=uridium-250000, rankpoints='.$newRankpoints.', locx=18500, locy=1100, mapid=5  WHERE id='.$_SESSION['player_id']);
			$req->execute();
			break;
		case 3:
			$req = $db->prepare('UPDATE users SET factionid=3, uridium=uridium-250000, rankpoints='.$newRankpoints.', locx=19200, locy=11300, mapid=9 WHERE id='.$_SESSION['player_id']);
			$req->execute();
			break;			
		default:
			return "Company is not valid";
			break;
	}
	
	$req = $db->prepare('UPDATE users SET clanid=0 WHERE id='.$_SESSION['player_id']);
	$req->execute();
	
	$clan_id = $datauser[0]['clanid'];
	
	$sth = $db->prepare("SELECT * FROM clan where id = $clan_id");
	$sth->execute();
	$clansdata = $sth->fetchAll();

	$admin_id = $clansdata[0]['admin_id'];

	$is_admin = ($admin_id  == $_SESSION['player_id']);
	
	if($is_admin)
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
	}	
	
	header('Location: view.php');
	break;
}
?>
	