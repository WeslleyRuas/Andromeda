
<?php 

$sth = $db->prepare("SELECT clanid
 FROM users WHERE id = :id LIMIT 1");
$sth->execute(array(
				':id' => $_SESSION['player_id']
			));
$datauser = $sth->fetchAll();

if($datauser[0]['clanid'] == 0)
{
	header("Location: view.php?page=clan&tab=joinclan");
	exit();
}
$clan_id = $datauser[0]['clanid'];

$sth = $db->prepare("SELECT * FROM clan where id = $clan_id");
$sth->execute();
$clandata = $sth->fetchAll();

$admin_id = $clandata[0]['admin_id'];
$is_admin = $admin_id  == $_SESSION['player_id'];

$clan_company = $clandata[0]['clan_company'];

$sth = $db->prepare("SELECT * FROM clan_diplomacy WHERE clan_id = :clanid AND type='alliance'");
$sth->execute(array(
				':clanid' => $clan_id 
			));
$dataAlliancesFist = $sth->fetchAll();

$sth = $db->prepare("SELECT * FROM clan_diplomacy WHERE second_clan_id = :clanid AND type='alliance'");
$sth->execute(array(
				':clanid' => $clan_id
			));
$dataAlliancesSec = $sth->fetchAll();

if($is_admin)
{		
	if (!empty($_GET['cancelAR']))
	{
		handleCancelAR($db,$clan_id);
	}
	
	if (!empty($_GET['acceptAR']))
	{
		handleAcceptAR($db,$clan_id);
	}
	
	$sth = $db->prepare("SELECT * FROM clan");
	$sth->execute();
	$clansdata = $sth->fetchAll();
	
	$sth = $db->prepare("SELECT * FROM clan WHERE clan_company=$clan_company");
	$sth->execute();
	$clansdataAR = $sth->fetchAll();
	
	$errorsAR = array();	
	$errorsAR = handleNewAllianceForm($db, $clan_id, $clan_company);
	
	$sth = $db->prepare("SELECT * FROM clan_diplomacy_request WHERE clan_id = :clanid AND type='alliance'");
	$sth->execute(array(
					':clanid' => $clan_id 
				));
	$pendingdataAlliancesFist = $sth->fetchAll();

	$sth = $db->prepare("SELECT * FROM clan_diplomacy_request WHERE second_clan_id = :clanid AND type='alliance'");
	$sth->execute(array(
					':clanid' => $clan_id
				));
	$pendingdataAlliancesSec = $sth->fetchAll();
}

?>

<div class="box" style="margin-left: 100px;">
	<div class="title">Clan Alliances</div>
	<div id="clan-alliances">	
		<?php displayClanAlliancesFirst($dataAlliancesFist,$db,$is_admin); ?>
		<?php displayClanAlliancesSec($dataAlliancesSec,$db,$is_admin); ?>	
	</div>
</div>	

<?php 
if($is_admin)
{
?>
	<div class="box" style="margin-left: 100px;">
	<div class="title">Pending Alliance Requests</div>
	<div id="clan-pending-alliance">	
		<?php displayClanPendingAlliancesFirst($pendingdataAlliancesFist,$db,$is_admin); ?>
		<?php displayClanPendingAlliancesSec($pendingdataAlliancesSec,$db,$is_admin); ?>	
	</div>
</div>	
<?php 
}
?>		

<?php 
if($is_admin)
{
?>
	<div class="box" style="margin-left: 100px;">
	<div class="title">New Alliance Request</div>
	<div id="clan-new-alliance">	
		<?php		
		if (sizeof($errorsAR) > 0) 
		{
			echo '<div class="error">';
			echo '<p class="error">Error(s): <br>';
				foreach ($errorsAR as $err_msg) {
					echo "&nbsp; &nbsp; - {$err_msg} <br>";
				}
				echo '</ul></p><br><br>';
			echo '</div>';
		}
		?>
		<form class="clan-form" action="view.php?page=clan&tab=diplomacy" method="post">
				<ul>
					
					<b>Clan</b><li><input id="filter-clanAR" type="text" /><select id="clan-alliance-form-clan" name="clan-alliance-form-clan">
						<?php
						foreach($clansdataAR as $clan)	
						{
						?>
						<option value="<?=$clan['id']?>">[<?=$clan['clan_tag']?>] <?=$clan['clan_name']?></option>
						<?php
						}	
						?>					  
					  </select></li>
					<b>Message</b> (12-120 characters):<li> <textarea name="clan-alliance-form-message" rows=3 cols=40></textarea></li>
					<li><input name="clan-alliance-form-submit" type="submit" value="Request Alliance" /></li>
				</ul>
		</form>	
	</div>
</div>	
<?php 
}
?>	

<script type="text/javascript">
	jQuery.fn.filterByText = function(textbox, selectSingleMatch) {
        return this.each(function() {
            var select = this;
            var options = [];
            $(select).find('option').each(function() {
                options.push({value: $(this).val(), text: $(this).text()});
            });
            $(select).data('options', options);
            $(textbox).bind('change keyup', function() {
                var options = $(select).empty().data('options');
                var search = $(this).val().trim();
                var regex = new RegExp(search,"gi");
              
                $.each(options, function(i) {
                    var option = options[i];
                    if(option.text.match(regex) !== null) {
                        $(select).append(
                           $('<option>').text(option.text).val(option.value)
                        );
                    }
                });
                if (selectSingleMatch === true && $(select).children().length === 1) {
                    $(select).children().get(0).selected = true;
                }
            });            
        });
    };
	
	$(function() {
        $('#clan-alliance-form-clan').filterByText($('#filter-clanAR'), false);
      $("select option").click(function(){
        alert(1);
      });
    });
</script>

<?php 
function convertToNumericEntities($string) 
{
	$convmap = array(0x80, 0x10ffff, 0, 0xffffff);
	return mb_encode_numericentity($string, $convmap, "UTF-8");
}

function handleNewAllianceForm($db, $clan_id, $clan_company)
{
	$errors = array();	
	if (empty($_POST['clan-alliance-form-submit']))
	{
		return $errors;
	}
	
	if (empty($_POST['clan-alliance-form-clan']))
	{
		$errors[] = "Clan selection required.";	
	}

	if (empty($_POST['clan-alliance-form-message']))
	{
		$errors[] = "Message required.";	
	}
	else if (strlen($_POST['clan-alliance-form-message']) > 120 || strlen($_POST['clan-alliance-form-message']) < 12) 
	{
		$errors[] = "Invalid Message (12-120 characters).";	
	}
	
	if (sizeof($errors) > 0) 
	{		
		return $errors;
	}
	
	$select = htmlentities($_POST['clan-alliance-form-clan']);
	$message = convertToNumericEntities(htmlentities($_POST['clan-alliance-form-message']));
	
	if($select == $clan_id)
	{
		$errors[] = "You can't request an alliance to your own clan.";
	}
	
	$sth = $db->prepare("SELECT * FROM clan WHERE id = :id AND clan_company=:clan_company");
	$sth->execute(array(
		':id' => $select,
		':clan_company' => $clan_company		
	));
	$count = $sth->rowCount();
	if($count == 0)
	{
		$errors[] = "Selected clan does not exist.";	
	}
	
	$sth = $db->prepare("SELECT * FROM clan_diplomacy_request WHERE type='alliance' AND ((clan_id=:first and second_clan_id=:second) OR (clan_id=:second and second_clan_id=:first))");
	$sth->execute(array(
		':first' => $clan_id,
		':second' => $select	
	));
	$count = $sth->rowCount();
	if($count > 0)
	{
		$errors[] = "Alliance request already pending.";	
	}
	
	if (sizeof($errors) > 0) 
	{		
		return $errors;
	}
	else
	{
		$db->insert('clan_diplomacy_request', array(
			'clan_id' => $clan_id,
			'second_clan_id' => $select,
			'type' => 'alliance',
			'message' => $message
			));		
		
		header("Location: view.php?page=clan&tab=diplomacy");
		exit();
	}		
	return $errors;
}

function handleCancel($db,$clan_id)
{
	$id = htmlentities($_GET['cancel']);
	$sth = $db->prepare("DELETE FROM clan_diplomacy WHERE id=:id AND (clan_id=:clan_id OR (second_clan_id=:clan_id AND type='alliance'))");
	$sth->execute(array(':id' => $id, ':clan_id' => $clan_id));

	header("Location: view.php?page=clan&tab=diplomacy");
	exit();	
}

function handleCancelAR($db,$clan_id)
{
	$id = htmlentities($_GET['cancelAR']);
	$sth = $db->prepare("DELETE FROM clan_diplomacy_request WHERE id=:id AND (clan_id=:clan_id OR second_clan_id=:clan_id)");
	$sth->execute(array(':id' => $id, ':clan_id' => $clan_id));

	header("Location: view.php?page=clan&tab=diplomacy");
	exit();	
}

function handleAcceptAR($db,$clan_id)
{
	$id = htmlentities($_GET['acceptAR']);
	
	$sth = $db->prepare("SELECT * FROM clan_diplomacy_request WHERE id=:id AND type='alliance' AND second_clan_id=:clan_id");
	$sth->execute(array(
		':id' => $id,
		':clan_id' => $clan_id	
	));
	$requests_data = $sth->fetchAll();
	if(count($requests_data) > 0)
	{
		$otherclan = $requests_data[0]['clan_id'];
		
		$sth = $db->prepare("DELETE FROM clan_diplomacy_request WHERE id=:id AND type='alliance' AND (clan_id=:clan_id OR second_clan_id=:second_clan_id)");
		$sth->execute(array(':id' => $id, ':clan_id' => $otherclan, ':second_clan_id' => $clan_id));	
		
		$db->insert('clan_diplomacy', array(
			'clan_id' => $otherclan,
			'second_clan_id' => $clan_id,
			'type' => 'alliance',
			'message' => $requests_data[0]['message']
			));			
	}
	header("Location: view.php?page=clan&tab=diplomacy");
	exit();	
}

function displayclanAlliancesFirst($dataAlliances, $db, $is_admin)
{
	foreach($dataAlliances as $Alliance)	
	{
		$sth = $db->prepare("SELECT clan_name, clan_tag, admin_id FROM clan WHERE id = :clanid LIMIT 1");
		$sth->execute(array(
						':clanid' => $Alliance['second_clan_id'] 
					));
		$dataclan = $sth->fetchAll();
		$count = $sth->rowCount();

		if ($count > 0)
		{
		?>
				<div class="stat" style="margin-left:-20px;">
					<div class="stat-left">
						[<?=$dataclan[0]['clan_tag']?>]
					</div>
					<div class="stat-right">
						<?=$dataclan[0]['clan_name']?>
						
						<?php 
						if($is_admin)
						{
						?>
							<a class="leftbutton" href="view.php?page=clan&tab=diplomacy&cancel=<?=$Alliance['id']?>">
								Cancel
							</a>
						<?php 
						}
						?>
					</div>
				</div>
				<div class="stat" style="margin-left:-20px;margin-top:-4px;">
					<div class="stat-left">
						Message
					</div>
					<div class="stat-rightbis" style="padding-bottom:5px;">
						<?=$Alliance['message']?>					
					</div>					
				</div>
		<?php
		}
	}
}
function displayclanAlliancesSec($dataAlliances, $db, $is_admin)
{
	foreach($dataAlliances as $Alliance)	
	{
		$sth = $db->prepare("SELECT clan_name, clan_tag, admin_id FROM clan WHERE id = :clanid LIMIT 1");
		$sth->execute(array(
						':clanid' => $Alliance['clan_id'] 
					));
		$dataclan = $sth->fetchAll();
		$count = $sth->rowCount();

		if ($count > 0)
		{
		?>
				<div class="stat" style="margin-left:-20px;">
					<div class="stat-left">
						[<?=$dataclan[0]['clan_tag']?>]
					</div>
					<div class="stat-right">
						<?=$dataclan[0]['clan_name']?>
						
						<?php 
						if($is_admin)
						{
						?>
							<a class="leftbutton" href="view.php?page=clan&tab=diplomacy&cancel=<?=$Alliance['id']?>">
								Cancel
							</a>
						<?php 
						}
						?>
					</div>
				</div>
				<div class="stat" style="margin-left:-20px;margin-top:-4px;">
					<div class="stat-left">
						Message
					</div>
					<div class="stat-rightbis" style="padding-bottom:5px;">
						<?=$Alliance['message']?>					
					</div>					
				</div>
		<?php
		}
	}
}

function displayclanpendingAlliancesFirst($dataAlliances, $db, $is_admin)
{
	foreach($dataAlliances as $Alliance)	
	{
		$sth = $db->prepare("SELECT clan_name, clan_tag, admin_id FROM clan WHERE id = :clanid LIMIT 1");
		$sth->execute(array(
						':clanid' => $Alliance['second_clan_id'] 
					));
		$dataclan = $sth->fetchAll();
		$count = $sth->rowCount();

		if ($count > 0)
		{
		?>
				<div class="stat" style="margin-left:-20px;">
					<div class="stat-left">
						[<?=$dataclan[0]['clan_tag']?>]
					</div>
					<div class="stat-right">
						<?=$dataclan[0]['clan_name']?>
						
						<?php 
						if($is_admin)
						{
						?>
							<a class="leftbutton" href="view.php?page=clan&tab=diplomacy&cancelAR=<?=$Alliance['id']?>">
								Cancel
							</a>
						<?php 
						}
						?>
					</div>
				</div>
				<div class="stat" style="margin-left:-20px;margin-top:-4px;">
					<div class="stat-left">
						Message
					</div>
					<div class="stat-rightbis" style="padding-bottom:5px;">
						<?=$Alliance['message']?>					
					</div>					
				</div>
		<?php
		}
	}
}
function displayclanpendingAlliancesSec($dataAlliances, $db, $is_admin)
{
	foreach($dataAlliances as $Alliance)	
	{
		$sth = $db->prepare("SELECT clan_name, clan_tag, admin_id FROM clan WHERE id = :clanid LIMIT 1");
		$sth->execute(array(
						':clanid' => $Alliance['clan_id'] 
					));
		$dataclan = $sth->fetchAll();
		$count = $sth->rowCount();

		if ($count > 0)
		{
		?>
				<div class="stat" style="margin-left:-20px;">
					<div class="stat-left">
						[<?=$dataclan[0]['clan_tag']?>]
					</div>
					<div class="stat-right">
						<?=$dataclan[0]['clan_name']?>
						
						<?php 
						if($is_admin)
						{
						?>							
							<a class="leftbutton" href="view.php?page=clan&tab=diplomacy&cancelAR=<?=$Alliance['id']?>">
								Refuse
							</a>
							<a class="leftbutton" href="view.php?page=clan&tab=diplomacy&acceptAR=<?=$Alliance['id']?>">
								Accept
							</a>
						<?php 
						}
						?>
					</div>
				</div>
				<div class="stat" style="margin-left:-20px;margin-top:-4px;">
					<div class="stat-left">
						Message
					</div>
					<div class="stat-rightbis" style="padding-bottom:5px;">
						<?=$Alliance['message']?>					
					</div>					
				</div>
		<?php
		}
	}
}
?>