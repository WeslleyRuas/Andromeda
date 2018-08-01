<?php
$sth = $db->prepare("SELECT username, grade, factionid, clanid, credits, uridium, rankpoints, user_kill, npc_kill, max_hp, speed, damages, 
max_shield, drones, apis_built, zeus_built, dmg_lvl, hp_lvl, shd_lvl, speed_lvl, logfiles, booty_keys, drone_parts, skilltree, booster_dmg_time,
booster_shd_time, booster_spd_time, booster_npc_time, shipId 
 FROM users WHERE id = :id LIMIT 1");
$sth->execute(array(
				':id' => $_SESSION['player_id']
			));
$datauser = $sth->fetchAll();

$user_iris = substr_count($datauser[0]['drones'], "-") -2;
if($datauser[0]['apis_built'] == 1)
{
	$user_iris--;
}
if($datauser[0]['zeus_built'] == 1)
{
	$user_iris--;
}

require_once('./libs/Laboratory.php');
$lab = new Laboratory($_SESSION['player_id'], $datauser[0]['skilltree'], $datauser[0]['logfiles'], $db );

if(isset($_GET['buy']))
{
	$buymessage = buy($_GET['buy'],$datauser,$lab,$db);
	//update infos
	$sth = $db->prepare("SELECT username, grade, factionid, clanid, credits, uridium, user_kill, npc_kill, max_hp, speed, damages, 
	max_shield, drones, apis_built, zeus_built, dmg_lvl, hp_lvl, shd_lvl, speed_lvl, logfiles, booty_keys, drone_parts, skilltree, booster_dmg_time,
	booster_shd_time, booster_spd_time, booster_npc_time, shipId
	 FROM users WHERE id = :id LIMIT 1");
	$sth->execute(array(
					':id' => $_SESSION['player_id']
				));
	$datauser = $sth->fetchAll();
	$user_iris = substr_count($datauser[0]['drones'], "-") -2;
	if($datauser[0]['apis_built'] == 1)
	{
		$user_iris--;
	}
	if($datauser[0]['zeus_built'] == 1)
	{
		$user_iris--;
	}
	$lab->userlogfiles = $datauser[0]['logfiles'];
	$lab->skills = $lab->load_skills($datauser[0]['skilltree']);	
}
?>
	
<div class="box" style="margin-left: -10px;">
	<div class="title">Ship upgrades</div>
	<div id="user-upgrades">
		<div class="bar-stat">
			<div class="bar-stat-title tooltip">
				Damage
				<span>
					<img class="callout" src="img/callout.gif" />
					<strong>Damage upgrade:</strong><br />
						Increase damage by <font color='red'>200</font> per point
				</span>
			</div>
			<div class="bar-stat-content">
				<div class="bar-stat-content-bar"><?php create_bar(25, $datauser[0]['dmg_lvl'], 10, 10); ?></div>
				<div class="bar-stat-content-number"><?=number_format($datauser[0]['dmg_lvl'])?>/25</div>
				<div class="bar-stat-content-buy">
					<a class="buy tooltip" href="view.php?page=user&tab=upgrades&buy=damage_upgrade">
						Buy
						<span>
							<img class="callout" src="img/callout.gif" />
							<strong>Damage upgrade(next point):</strong><br />
								Uridium: <font color='magenta'><?=number_format(pow($datauser[0]['dmg_lvl']*20, 2) +100)?></font>
						</span>
					</a>
				</div>
			</div>
		</div>	
		<div class="bar-stat">
			<div class="bar-stat-title tooltip">
				Health
				<span>
					<img class="callout" src="img/callout.gif" />
					<strong>Health upgrade:</strong><br />
						Increase health by <font color='green'>5,000</font> per point
				</span>
			</div>
			<div class="bar-stat-content">
				<div class="bar-stat-content-bar"><?php create_bar(20, $datauser[0]['hp_lvl'], 13, 10); ?></div>
				<div class="bar-stat-content-number"><?=number_format($datauser[0]['hp_lvl'])?>/20</div>
				<div class="bar-stat-content-buy">
					<a class="buy tooltip" href="view.php?page=user&tab=upgrades&buy=healt_upgrade">
						Buy
						<span>
							<img class="callout" src="img/callout.gif" />
							<strong>Health upgrade(next point):</strong><br />
								Uridium: <font color='magenta'><?=number_format(pow($datauser[0]['hp_lvl']*20, 2) + 100)?></font>
						</span>
					</a>
				</div>
			</div>
		</div>	
		<div class="bar-stat">
			<div class="bar-stat-title tooltip">
				Shield
				<span>
					<img class="callout" src="img/callout.gif" />
					<strong>Shield upgrade:</strong><br />
						Increase shield by <font color='#00AAFF'>4,000</font> per point
				</span>
			</div>
			<div class="bar-stat-content">
				<div class="bar-stat-content-bar"><?php create_bar(20, $datauser[0]['shd_lvl'], 13, 10); ?></div>
				<div class="bar-stat-content-number"><?=number_format($datauser[0]['shd_lvl'])?>/20</div>
				<div class="bar-stat-content-buy">
					<a class="buy tooltip" href="view.php?page=user&tab=upgrades&buy=shield_upgrade">
						Buy
						<span>
							<img class="callout" src="img/callout.gif" />
							<strong>Shield upgrade(next point):</strong><br />
								Uridium: <font color='magenta'><?=number_format(pow($datauser[0]['shd_lvl']*4, 3) + 100)?></font>
						</span>
					</a>
				</div>
			</div>
		</div>	
		<div class="bar-stat">
			<div class="bar-stat-title tooltip">
				Speed
				<span>
					<img class="callout" src="img/callout.gif" />
					<strong>Speed upgrade:</strong><br />
						Increase Speed by <font color='magenta'>7</font> per point
				</span>
			</div>
			<div class="bar-stat-content">
				<div class="bar-stat-content-bar"><?php create_bar(5, $datauser[0]['speed_lvl'], 58, 10); ?></div>
				<div class="bar-stat-content-number"><?=number_format($datauser[0]['speed_lvl'])?>/5</div>
				<div class="bar-stat-content-buy">
					<a class="buy tooltip" href="view.php?page=user&tab=upgrades&buy=speed_upgrade">
						Buy
						<span>
							<img class="callout" src="img/callout.gif" />
							<strong>Speed upgrade(next point):</strong><br />
								Uridium: <font color='magenta'><?=number_format(pow($datauser[0]['speed_lvl']*40, 2) + 1000)?></font>
						</span>
					</a>
				</div>
			</div>
		</div>
		<div class="bar-stat">
			<div class="bar-stat-title tooltip">
				Iris
				<span class="fix3">
					<img class="callout" src="img/callout.gif" />
					<strong>Iris:</strong><br />
						Increase damages by <font color='red'>150</font> per Iris
						<br/>Increase health by <font color='green'>1,250</font> per Iris
						<br/>Increase shield by <font color='#00AAFF'>1,250</font> per Iris
				</span>
			</div>
			<div class="bar-stat-content">
				<div class="bar-stat-content-bar"><?php create_bar(8, $user_iris, 35, 10); ?></div>
				<div class="bar-stat-content-number"><?=$user_iris?>/8</div>
				<div class="bar-stat-content-buy">
					<a class="buy tooltip" href="view.php?page=user&tab=upgrades&buy=iris">
						Buy
						<span>
							<img class="callout" src="img/callout.gif" />
							<strong>Iris:</strong><br />
								Credits: <font color='#00AAFF'><?=number_format((pow($user_iris + 1, 2)) * 1000000)?></font>
						</span>
					</a>
				</div>
			</div>
		</div>
		
		<div class="stat">
			<div class="stat-left tooltip">
				<span>
					<img class="callout" src="img/callout.gif" />
						<strong>Apis:</strong><br />
							Increase damages by <font color='red'>400</font>
							</br>Increase X4 damages by <font color='red'>15%</font>
				</span>
				Apis
			</div>
			<div class="stat-right">
				<?=number_format($datauser[0]['apis_built'])?>
				<a class="buy-stat tooltip" href="view.php?page=user&tab=upgrades&buy=apis">
					Buy
					<span>
					<img class="callout" src="img/callout.gif" />
						<strong>Apis:</strong><br />
							Credits: <font color='#00AAFF'>100,000,000</font>
							</br> Drone parts: <font color='green'>30</font>
					</span>
				</a>
			</div>
		</div>
		<div class="stat">
			<div class="stat-left tooltip">
				<span>
					<img class="callout" src="img/callout.gif" />
						<strong>Zeus:</strong><br />
							Increase damages by <font color='red'>400</font>
							</br>Increase SAB damages by <font color='red'>20%</font>
				</span>
				Zeus
			</div>
			<div class="stat-right">
				<?=number_format($datauser[0]['zeus_built'])?>
				<a class="buy-stat tooltip" href="view.php?page=user&tab=upgrades&buy=zeus">
					Buy
					<span>
					<img class="callout" src="img/callout.gif" />
						<strong>Zeus:</strong><br />
							Credits: <font color='#00AAFF'>100,000,000</font>
							</br> Drone parts: <font color='green'>30</font>
					</span>
				</a>
			</div>
		</div>
		
		<div class="bar-stat">
			<div class="bar-stat-title tooltip">
				Lazer skill
				<span>
					<img class="callout" src="img/callout.gif" />
					<strong>Lazer skill:</strong><br />
						<?= $lab->get_skill_description("dmg"); ?>
				</span>
			</div>
			<div class="bar-stat-content">
				<div class="bar-stat-content-bar"><?php create_bar(5, $lab->skills["dmg"], 58, 10); ?></div>
				<div class="bar-stat-content-number"><?=$lab->skills["dmg"]?>/5</div>
				<div class="bar-stat-content-buy">
					<a class="buy tooltip" href="view.php?page=user&tab=upgrades&buy=dmgskill">
						Buy
						<span>
							<img class="callout" src="img/callout.gif" />
							<strong>Lazer skill:</strong><br />
								Logfiles: <font color='magenta'><?=number_format($lab->get_skill_Prix('dmg'))?></font>
						</span>
					</a>
				</div>
			</div>
		</div>
		<div class="bar-stat">
			<div class="bar-stat-title tooltip">
				Rocket skill
				<span>
					<img class="callout" src="img/callout.gif" />
					<strong>Rocket skill:</strong><br />
						<?= $lab->get_skill_description("rck"); ?>
				</span>
			</div>
			<div class="bar-stat-content">
				<div class="bar-stat-content-bar"><?php create_bar(5, $lab->skills["rck"], 58, 10); ?></div>
				<div class="bar-stat-content-number"><?=$lab->skills["rck"]?>/5</div>
				<div class="bar-stat-content-buy">
					<a class="buy tooltip" href="view.php?page=user&tab=upgrades&buy=rckskill">
						Buy
						<span>
							<img class="callout" src="img/callout.gif" />
							<strong>Rocket skill:</strong><br />
								Logfiles: <font color='magenta'><?=number_format($lab->get_skill_Prix('rck'))?></font>
						</span>
					</a>
				</div>
			</div>
		</div>
		<div class="bar-stat">
			<div class="bar-stat-title tooltip">
				Hull skill
				<span>
					<img class="callout" src="img/callout.gif" />
					<strong>Hull skill:</strong><br />
						<?= $lab->get_skill_description("hp"); ?>
				</span>
			</div>
			<div class="bar-stat-content">
				<div class="bar-stat-content-bar"><?php create_bar(3, $lab->skills["hp"], 98, 10); ?></div>
				<div class="bar-stat-content-number"><?=$lab->skills["hp"]?>/3</div>
				<div class="bar-stat-content-buy">
					<a class="buy tooltip" href="view.php?page=user&tab=upgrades&buy=hpskill">
						Buy
						<span>
							<img class="callout" src="img/callout.gif" />
							<strong>Hull skill:</strong><br />
								Logfiles: <font color='magenta'><?=number_format($lab->get_skill_Prix('hp'))?></font>
						</span>
					</a>
				</div>
			</div>
		</div>
		<div class="bar-stat">
			<div class="bar-stat-title tooltip">
				Shield skill
				<span>
					<img class="callout" src="img/callout.gif" />
					<strong>Shield skill:</strong><br />
						<?= $lab->get_skill_description("shd_abs"); ?>
				</span>
			</div>
			<div class="bar-stat-content">
				<div class="bar-stat-content-bar"><?php create_bar(3, $lab->skills["shd_abs"], 98, 10); ?></div>
				<div class="bar-stat-content-number"><?=$lab->skills["shd_abs"]?>/3</div>
				<div class="bar-stat-content-buy">
					<a class="buy tooltip" href="view.php?page=user&tab=upgrades&buy=shd_absskill">
						Buy
						<span>
							<img class="callout" src="img/callout.gif" />
							<strong>Shield skill:</strong><br />
								Logfiles: <font color='magenta'><?=number_format($lab->get_skill_Prix('shd_abs'))?></font>
						</span>
					</a>
				</div>
			</div>
		</div>	
		<div class="bar-stat">
			<div class="bar-stat-title tooltip">
				Regeneration skill
				<span>
					<img class="callout" src="img/callout.gif" />
					<strong>Shield regeneration skill:</strong><br />
						<?= $lab->get_skill_description("shreg"); ?>
				</span>
			</div>
			<div class="bar-stat-content">
				<div class="bar-stat-content-bar"><?php create_bar(5, $lab->skills["shreg"], 58, 10); ?></div>
				<div class="bar-stat-content-number"><?=$lab->skills["shreg"]?>/5</div>
				<div class="bar-stat-content-buy">
					<a class="buy tooltip" href="view.php?page=user&tab=upgrades&buy=shregskill">
						Buy
						<span>
							<img class="callout" src="img/callout.gif" />
							<strong>Shield regeneration skill:</strong><br />
								Logfiles: <font color='magenta'><?=number_format($lab->get_skill_Prix('shreg'))?></font>
						</span>
					</a>
				</div>
			</div>
		</div>
		<div class="bar-stat">
			<div class="bar-stat-title tooltip">
				Repair skill
				<span>
					<img class="callout" src="img/callout.gif" />
					<strong>Repair skill:</strong><br />
						<?= $lab->get_skill_description("rep"); ?>
				</span>
			</div>
			<div class="bar-stat-content">
				<div class="bar-stat-content-bar"><?php create_bar(3, $lab->skills["rep"], 98, 10); ?></div>
				<div class="bar-stat-content-number"><?=$lab->skills["rep"]?>/3</div>
				<div class="bar-stat-content-buy">
					<a class="buy tooltip" href="view.php?page=user&tab=upgrades&buy=repskill">
						Buy
						<span>
							<img class="callout" src="img/callout.gif" />
							<strong>Repair skill:</strong><br />
								Logfiles: <font color='magenta'><?=number_format($lab->get_skill_Prix('rep'))?></font>
						</span>
					</a>
				</div>
			</div>
		</div>	
		<div class="bar-stat">
			<div class="bar-stat-title tooltip">
				Smartbomb skill
				<span>
					<img class="callout" src="img/callout.gif" />
					<strong>Smartbomb skill:</strong><br />
						<?= $lab->get_skill_description("smb"); ?>
				</span>
			</div>
			<div class="bar-stat-content">
				<div class="bar-stat-content-bar"><?php create_bar(2, $lab->skills["smb"], 147, 10); ?></div>
				<div class="bar-stat-content-number"><?=$lab->skills["smb"]?>/2</div>
				<div class="bar-stat-content-buy">
					<a class="buy tooltip" href="view.php?page=user&tab=upgrades&buy=smbskill">
						Buy
						<span>
							<img class="callout" src="img/callout.gif" />
							<strong>Smartbomb skill:</strong><br />
								Logfiles: <font color='magenta'><?=number_format($lab->get_skill_Prix('smb'))?></font>
						</span>
					</a>
				</div>
			</div>
		</div>			
	</div>
</div>	

<?php 
function create_bar($size, $progress, $elementWidth, $elementHeight)
{
	$i=0;
	while($i < $progress)
	{
		echo '<div class="barUp" style="width: '.$elementWidth.'px; height: '.$elementHeight.'px;"></div>';
		$i++;
	}
	while($i < $size)
	{
		echo '<div class="barDown" style="width: '.$elementWidth.'px; height: '.$elementHeight.'px;"></div>';
		$i++;
	}
}
function buy($item,$datauser,$lab,$db)
{
	if($item == 'damage_upgrade')
	{
		$damages_upgrade = array("level" => $datauser[0]['dmg_lvl'], "price" => pow($datauser[0]['dmg_lvl']*20, 2) +100);
		if($datauser[0]['uridium'] >= $damages_upgrade["price"] and $damages_upgrade["level"] < 25)
		{
			$req = $db->prepare('UPDATE users SET uridium=uridium-'.$damages_upgrade["price"].' WHERE id='.$_SESSION['player_id']);
			if($req->execute())
			{
				$req = $db->prepare('UPDATE users SET damages=damages+50, dmg_lvl=dmg_lvl+1 WHERE id='.$_SESSION['player_id']); // +50 dmg x1 = +200dmg x4
				if($req->execute())
				{
					return "Purchase success !";
				}
			}
		}
		else
		{
			return "Error : Not enough uridium or maximum level reached !";
		}
	}
	else if($item == 'healt_upgrade')
	{
		$hp_upgrade = array("level" => $datauser[0]['hp_lvl'], "price" => pow($datauser[0]['hp_lvl']*20, 2) + 100);
		if($datauser[0]['uridium'] >= $hp_upgrade["price"] and $hp_upgrade["level"] < 20)
		{
			$req = $db->prepare('UPDATE users SET uridium=uridium-'.$hp_upgrade["price"].' WHERE id='.$_SESSION['player_id']);
			if($req->execute())
			{
				$req = $db->prepare('UPDATE users SET max_hp=max_hp+5000, hp_lvl=hp_lvl+1 WHERE id='.$_SESSION['player_id']);
				if($req->execute())
				{
					return "Purchase success !";
				}
			}
		}
		else
		{
			return "Error : Not enough uridium or maximum level reached !";
		}  
	}
	else if($item == 'shield_upgrade')
	{
		$shield_upgrade = array("level" => $datauser[0]['shd_lvl'], "price" => pow($datauser[0]['shd_lvl']*4, 3) + 100);
		if($datauser[0]['uridium'] >= $shield_upgrade["price"] and $shield_upgrade["level"] < 20)
		{
			$req = $db->prepare('UPDATE users SET uridium=uridium-'.$shield_upgrade["price"].' WHERE id='.$_SESSION['player_id']);
			if($req->execute())
			{
				$req = $db->prepare('UPDATE users SET max_shield=max_shield+4000, shd_lvl=shd_lvl+1 WHERE id='.$_SESSION['player_id']);
				if($req->execute())
				{
					return "Purchase success !";
				}
			}
		}
		else
		{
			return "Error : Not enough uridium or maximum level reached !";
		}          
	}
	else if($item == 'speed_upgrade')
	{
		$speed_upgrade = array("level" => $datauser[0]['speed_lvl'], "price" => pow($datauser[0]['speed_lvl']*40, 2) + 1000);
		if($datauser[0]['uridium'] >= $speed_upgrade["price"] and $speed_upgrade["level"] < 5)
		{
			$req = $db->prepare('UPDATE users SET uridium=uridium-'.$speed_upgrade["price"].' WHERE id='.$_SESSION['player_id']);
			if($req->execute())
			{
				$req = $db->prepare('UPDATE users SET speed=speed+7, speed_lvl=speed_lvl+1 WHERE id='.$_SESSION['player_id']);
				if($req->execute())
				{
					return "Purchase success !";
				}
			}
		}
		else
		{
			return "Error : Not enough uridium or maximum level reached !";
		}   
	}
	else if($item == 'iris')
	{
		$user_drones = substr_count($datauser[0]['drones'], "-") -2;
		if($datauser[0]['apis_built'] == 1)
		{
			$user_drones--;
		}
		if($datauser[0]['zeus_built'] == 1)
		{
			$user_drones--;
		}
		$price = (pow($user_drones + 1, 2)) * 1000000;

		if($datauser[0]['credits'] >= $price)
		{
			if($user_drones < 8)
			{
				switch ($user_drones)
				{
					case 0:
						$drones_str = "3/0-3/1-25-3/0";
						break;
					case 1:
						$drones_str = "3/0-3/2-25-25-3/0";
						break;
					case 2:
						$drones_str = "3/0-3/3-25-25-25-3/0";
						break;
					case 3:
						$drones_str = "3/0-3/4-25-25-25-25-3/0";
						break;
					case 4:
						$drones_str = "3/1-25-3/4-25-25-25-25-3/0";
						break;
					case 5:
						$drones_str = "3/1-25-3/4-25-25-25-25-3/1-25";
						break;
					case 6:
						$drones_str = "3/2-25-25-3/4-25-25-25-25-3/1-25";
						break;
					case 7:
						$drones_str = "3/2-25-25-3/4-25-25-25-25-3/2-25-25";
						break;
					default:
						$drones_str = "3/0-3/1-25-3/0";
						break;
				}
				$req = $db->prepare('UPDATE users SET drones="'.$drones_str.'", credits=credits-'.$price.' WHERE id='.$_SESSION['player_id']);
				$req->execute();
				return "Purchase success !";
			}
			else
			{
				return "Error : You already have 8 Iris !";
			}
		}
		else
		{
			return "Error : Not enough credits !";
		}
	}
	
	else if($item == 'apis')
	{
		if($datauser[0]['credits'] >= 100000000 && $datauser[0]['drone_parts'] >= 30)
		{
			if($datauser[0]['apis_built']) 
			{ 	
				return "Error : Apis already owned !";
			}
			$user_drones = substr_count($datauser[0]['drones'], '-') -2; // the amount of drones possessed by the user (nombre de tirets dans les drones -2 = nombre de drones)
			if($user_drones == 8)
			{
				$req = $db->prepare('UPDATE users SET drone_parts=drone_parts-30, credits=credits-100000000, apis_built=1, drones="3/3-25-25-25-3/3-25-25-25-3/3-25-25-25" WHERE id='.$_SESSION['player_id']);
			}
			else if($user_drones == 9)
			{
				$req = $db->prepare('UPDATE users SET drone_parts=drone_parts-30, credits=credits-100000000, apis_built=1, drones="3/3-25-25-25-3/4-25-25-25-25-3/3-25-25-25" WHERE id='.$_SESSION['player_id']);
			}
			else
			{
				return "Error : You don't have all iris !";
			}
			$req->execute();
			return "Purchase success !";
		}
		else
		{
			return "Error : Not enough credits or drone parts !";
		}
	}
	else if($item == 'zeus')
	{
		if($datauser[0]['credits'] >= 100000000 && $datauser[0]['drone_parts'] >= 30)
		{
			if($datauser[0]['zeus_built']) 
			{ 		
				return "Error : Zeus already owned !";
			}

			$user_drones = substr_count($datauser[0]['drones'], '-') -2; // the amount of drones possessed by the user (nombre de tirets dans les drones -2 = nombre de drones)
			if($user_drones == 8)
			{
				$req = $db->prepare('UPDATE users SET drone_parts=drone_parts-30, credits=credits-100000000, zeus_built=1, drones="3/3-25-25-25-3/3-25-25-25-3/3-25-25-25" WHERE id='.$_SESSION['player_id']);
			}
			else if($user_drones == 9)
			{
				$req = $db->prepare('UPDATE users SET drone_parts=drone_parts-30, credits=credits-100000000, zeus_built=1, drones="3/3-25-25-25-3/4-25-25-25-25-3/3-25-25-25" WHERE id='.$_SESSION['player_id']);
			}
			else
			{
				return "Error : You don't have all iris !";
			}
			$req->execute();
			return "Purchase success !";
		}
		else
		{
			return "Error : Not enough credits or drone parts !";
		}
	}
	else if($item == 'dmgskill')
	{
		if($lab->buy_skill('dmg'))
		{
			return "Purchase success !";
		}
		else
		{
			return "Error : Not enough logfiles or maximum level reached!";
		}
	}
	else if($item == 'hpskill')
	{
		if($lab->buy_skill('hp'))
		{
			return "Purchase success !";
		}
		else
		{
			return "Error : Not enough logfiles or maximum level reached!";
		}
	}
	else if($item == 'shd_absskill')
	{
		if($lab->buy_skill('shd_abs'))
		{
			return "Purchase success !";
		}
		else
		{
			return "Error : Not enough logfiles or maximum level reached!";
		}
	}
	else if($item == 'repskill')
	{
		if($lab->buy_skill('rep'))
		{
			return "Purchase success !";
		}
		else
		{
			return "Error : Not enough logfiles or maximum level reached!";
		}
	}
	else if($item == 'smbskill')
	{
		if($lab->buy_skill('smb'))
		{
			return "Purchase success !";
		}
		else
		{
			return "Error : Not enough logfiles or maximum level reached!";
		}
	}
	else if($item == 'rckskill')
	{
		if($lab->buy_skill('rck'))
		{
			return "Purchase success !";
		}
		else
		{
			return "Error : Not enough logfiles or maximum level reached!";
		}
	}
	else if($item == 'shregskill')
	{
		if($lab->buy_skill('shreg'))
		{
			return "Purchase success !";
		}
		else
		{
			return "Error : Not enough logfiles or maximum level reached!";
		}
	}
}
?>