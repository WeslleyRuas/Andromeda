
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

$sth = $db->prepare("SELECT * FROM clan_diplomacy WHERE clan_id = :clanid AND type='war'");
$sth->execute(array(
				':clanid' => $clan_id 
			));
$datawarsFist = $sth->fetchAll();

$sth = $db->prepare("SELECT * FROM clan_diplomacy WHERE second_clan_id = :clanid AND type='war'");
$sth->execute(array(
				':clanid' => $clan_id
			));
$datawarsSec = $sth->fetchAll();

if($is_admin)
{	
	if (!empty($_GET['cancel']))
	{
		handleCancel($db,$clan_id);
	}
		
	$sth = $db->prepare("SELECT * FROM clan");
	$sth->execute();
	$clansdata = $sth->fetchAll();
	
	$errors = array();	
	$errors = handleNewWarForm($db, $clan_id);
}

?>
<script src="views/userTabs/jquery.min.js"></script>
<div class="box" style="margin-left: 100px;">
	<div class="title">Clan Wars</div>
	<div id="clan-wars">	
		<?php displayClanWarsFist($datawarsFist,$db,$is_admin); ?>
		<?php displayClanWarsSec($datawarsSec,$db); ?>	
	</div>
</div>	

<?php 
if($is_admin)
{
?>
	<div class="box" style="margin-left: 100px;">
	<div class="title">New Declaration of War</div>
	<div id="clan-new-war">	
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
		<form class="clan-form" action="view.php?page=clan&tab=diplomacy_war" method="post">
				<ul>
					
					<b>Clan</b><li><input id="filter-clan" type="text" /><select id="clan-war-form-clan" name="clan-war-form-clan">
						<?php
						foreach($clansdata as $clan)	
						{
						?>
						<option value="<?=$clan['id']?>">[<?=$clan['clan_tag']?>] <?=$clan['clan_name']?></option>
						<?php
						}	
						?>					  
					  </select></li>
					<b>Message</b> (12-120 characters):<li> <textarea name="clan-war-form-message" rows=3 cols=40></textarea></li>
					<li><input name="clan-war-form-submit" type="submit" value="Declare War" /></li>
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
        $('#clan-war-form-clan').filterByText($('#filter-clan'), false);
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
function handleNewWarForm($db, $clan_id)
{
	$errors = array();	
	if (empty($_POST['clan-war-form-submit']))
	{
		return $errors;
	}
	
	if (empty($_POST['clan-war-form-clan']))
	{
		$errors[] = "Clan selection required.";	
	}

	if (empty($_POST['clan-war-form-message']))
	{
		$errors[] = "Message required.";	
	}
	else if (strlen($_POST['clan-war-form-message']) > 120 || strlen($_POST['clan-war-form-message']) < 12) 
	{
		$errors[] = "Invalid Message (12-120 characters).";	
	}
	
	if (sizeof($errors) > 0) 
	{		
		return $errors;
	}
	
	$select = htmlentities($_POST['clan-war-form-clan']);
	$message = convertToNumericEntities(htmlentities($_POST['clan-war-form-message']));
	
	if($select == $clan_id)
	{
		$errors[] = "You can't declare war to your own clan.";
	}
	
	$sth = $db->prepare("SELECT * FROM clan WHERE id = :id");
	$sth->execute(array(
		':id' => $select			
	));
	$count = $sth->rowCount();
	if($count == 0)
	{
		$errors[] = "Selected clan does not exist.";	
	}
	
	$sth = $db->prepare("SELECT * FROM clan_diplomacy WHERE type='war' AND ((clan_id=:first and second_clan_id=:second) OR (clan_id=:second and second_clan_id=:first))");
	$sth->execute(array(
		':first' => $clan_id,
		':second' => $select	
	));
	$count = $sth->rowCount();
	if($count > 0)
	{
		$errors[] = "You already are at war with this clan";	
	}
	
	if (sizeof($errors) > 0) 
	{		
		return $errors;
	}
	else
	{
		$db->insert('clan_diplomacy', array(
			'clan_id' => $clan_id,
			'second_clan_id' => $select,
			'type' => 'war',
			'message' => $message
			));		
		
		header("Location: view.php?page=clan&tab=diplomacy_war");
		exit();
	}		
	return $errors;
}

function handleCancel($db,$clan_id)
{
	$id = htmlentities($_GET['cancel']);
	$sth = $db->prepare("DELETE FROM clan_diplomacy WHERE id=:id AND (clan_id=:clan_id OR (second_clan_id=:clan_id AND type='alliance'))");
	$sth->execute(array(':id' => $id, ':clan_id' => $clan_id));

	header("Location: view.php?page=clan&tab=diplomacy_war");
	exit();	
}

function displayClanWarsFist($datawars, $db, $is_admin)
{
	foreach($datawars as $war)	
	{
		$sth = $db->prepare("SELECT clan_name, clan_tag, admin_id FROM clan WHERE id = :clanid LIMIT 1");
		$sth->execute(array(
						':clanid' => $war['second_clan_id'] 
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
							<a class="leftbutton" href="view.php?page=clan&tab=diplomacy_war&cancel=<?=$war['id']?>">
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
						<?=$war['message']?>					
					</div>					
				</div>
		<?php
		}
	}
}

function displayClanWarsSec($datawars, $db)
{
	foreach($datawars as $war)	
	{
		$sth = $db->prepare("SELECT clan_name, clan_tag, admin_id FROM clan WHERE id = :clanid LIMIT 1");
		$sth->execute(array(
						':clanid' => $war['clan_id'] 
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
					</div>
				</div>
				<div class="stat" style="margin-left:-20px;margin-top:-4px;">
					<div class="stat-left">
						Message
					</div>
					<div class="stat-rightbis" style="padding-bottom:5px;">
						<?=$war['message']?>					
					</div>					
				</div>
		<?php
		}
	}
}
?>