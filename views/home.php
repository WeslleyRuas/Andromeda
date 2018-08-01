<?php 
$sth = $db->prepare("SELECT username, grade, factionid, clanid, credits, uridium, rankpoints FROM users WHERE id = :id LIMIT 1");
$sth->execute(array(
				':id' => $_SESSION['player_id']
			));
$datauser = $sth->fetchAll();
if($datauser[0]['clanid'] != 0)
{
	$sth = $db->prepare("SELECT clan_tag FROM clan WHERE id = :clanid LIMIT 1");
	$sth->execute(array(
					':clanid' => $datauser[0]['clanid'] 
				));
	$dataclan = $sth->fetchAll();
	$userclanag = '['.$dataclan[0]['clan_tag'].']';
}
else
{
	$userclanag = '';
}
$sth = $db->prepare("SELECT timestamp,message FROM users_log WHERE playerid = :playerid ORDER BY timestamp DESC LIMIT 0, 10");
$sth->execute(array(
				':playerid' => $_SESSION['player_id']
			));
$userlog = $sth->fetchAll();

$last_active_limit = time() - (3600*14*24);
$sth = $db->prepare("SELECT users.username, users.rankpoints, users.factionid, users.grade FROM users WHERE lastlogin > $last_active_limit AND (SELECT count(id) AS is_ban FROM bans WHERE bans.user_id = users.id AND bans.timestamp_expire > UNIX_TIMESTAMP() ) = 0  ORDER BY users.rankpoints DESC LIMIT 0, 10");
$sth->execute();
$top10 = $sth->fetchAll();
$sth = $db->prepare("SELECT users.username, (users.rankpoints + users.legend_rankpoints) as rankpoints, users.factionid, users.grade FROM users WHERE lastlogin > $last_active_limit AND (SELECT count(id) AS is_ban FROM bans WHERE bans.user_id = users.id AND bans.timestamp_expire > UNIX_TIMESTAMP() ) = 0  ORDER BY rankpoints DESC LIMIT 0, 10");
$sth->execute();
$legendtop10 = $sth->fetchAll();

$sth = $db->prepare("SELECT COUNT(*) as regplayer FROM users;");
$sth->execute();
$regplayer = $sth->fetchAll();

$sth = $db->prepare("SELECT sval as onlineplayer FROM server_statistics WHERE skey='active_connections';");
$sth->execute();
$onlineplayer = $sth->fetchAll();

$sth = $db->prepare("SELECT sval as mmoplayer FROM server_statistics WHERE skey='active_MMO';");
$sth->execute();
$mmoplayer = $sth->fetchAll();

$sth = $db->prepare("SELECT sval as eicplayer FROM server_statistics WHERE skey='active_EIC';");
$sth->execute();
$eicplayer = $sth->fetchAll();

$sth = $db->prepare("SELECT sval as vruplayer FROM server_statistics WHERE skey='active_VRU';");
$sth->execute();
$vruplayer = $sth->fetchAll();

?>
<script src="views/userTabs/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="styles/home.css?v=1" />
<div class="CMSContent">
	<div class="box">
		<div class="title">User </div>
		<div id="user-small">
			<div class="stat"><div class="stat-left">Username</div><div class="stat-right"><?=$userclanag,$datauser[0]['username']?></div></div>
			<div class="stat"><div class="stat-left">Company</div><div class="stat-right"><img src="img/ranks/company/<?=$datauser[0]['factionid']?>.png"></div></div>
			<div class="stat"><div class="stat-left">Grade</div><div class="stat-right"><img src="img/ranks/<?=$datauser[0]['grade']?>.png"></div></div>
			<div class="stat"><div class="stat-left">Rankpoints</div><div class="stat-right"><?=number_format($datauser[0]['rankpoints'])?></div></div>
			<div class="stat"><div class="stat-left">Credits</div><div class="stat-right"><?=number_format($datauser[0]['credits'])?></div></div>
			<div class="stat"><div class="stat-left">Uridium</div><div class="stat-right"><?=number_format($datauser[0]['uridium'])?></div></div>
		</div>
	</div>	
	<div class="box">
		<div class="title">Andromeda</div>
		<div id="andromeda-small">
			<div class="stat"><div class="stat-left">Status</div><div class="stat-right"><img src="img/Tick.png" style="margin-bottom:-5px;" width="20" height="20"><?=date('H:i:s T')?></div></div>
			<div class="stat"><div class="stat-left">Active players</div><div class="stat-right"><img src="img/ranks/company/1.png"> <?=$mmoplayer[0]['mmoplayer']?> <img src="img/ranks/company/2.png"> <?=$eicplayer[0]['eicplayer']?> <img src="img/ranks/company/3.png"> <?=$vruplayer[0]['vruplayer']?></div></div>
			<div class="stat"><div class="stat-left">Online players</div><div class="stat-right"><?=$onlineplayer[0]['onlineplayer']?></div></div>
			<div class="box">
			<div class="title">News</div>
				<div id="news-small">	
					 <div class="news-date">16/12/2016:</div>
					 <div class="news-content">TS Andromeda(unofficial) : eu187.ts3.cloud:24163</div>
                     <div class="news-date">10/05/2016:</div>
					 <div class="news-content">Andromeda is proud to celebrate its first birthday !</div>
					 <div class="news-date">S15 Awards:</div>
					 <div class="news-content">Congratulation to blueassasin_Halil_TR (1), *TheDefencer* (2) and &#9733;&fnof;&alpha;i&#8467;&epsilon;d&#9733; (3) !</div>
				</div>						
			</div>	
		</div>
	</div>	
	<div class="box">
		<div class="title">Hall of fame(<a href="view.php?page=top100">Top 100</a>)</div>
		<div id="hof-small">
			<ul class="tabs">
				<li class="tab-link current" data-tab="tab-1">Season 2</li>
				<li class="tab-link" data-tab="tab-2">Legends</li>
			</ul>
			<div id="tab-1" class="tab-content current">
				<?php 	
					$i = 0;
					foreach ($top10 as $player)
					{
						$i++;
						$j = $i % 2;
						?>	
						<div class="top10item<?=$j?>"><div class="top10index"><?=$i?>.</div><div class="top10company"><img src="img/ranks/company/<?=$player['factionid']?>.png"></div><div class="top10grade"><img src="img/ranks/<?=$player['grade']?>.png"></div><div class="top10username"><?=$player['username']?></div><div class="top10points"><?=number_format($player['rankpoints'])?></div></div>
						<?php
					}
				?>
			</div>
			<div id="tab-2" class="tab-content">	
				<?php 	
					$i = 0;
					foreach ($legendtop10 as $player)
					{
						$i++;
						$j = $i % 2;
						?>	
						<div class="top10item<?=$j?>"><div class="top10index"><?=$i?>.</div><div class="top10company"><img src="img/ranks/company/<?=$player['factionid']?>.png"></div><div class="top10grade"><img src="img/ranks/<?=$player['grade']?>.png"></div><div class="top10username"><?=$player['username']?></div><div class="top10points"><?=number_format($player['rankpoints'])?></div></div>
						<?php
					}
				?>
			</div>
		</div>
	</div>	
	<div class="box">
		<div class="title">Log</div>
		<div id="log-small">
		<?php 	
			foreach ($userlog as $log)
			{
				?>	
				<div class="logitem"><div class="logtime"><?=$log['timestamp']?>:</div><div class="logmessage">+<?=$log['message']?></div></div>
				<?php
			}
		?>
		</div>
	</div>	
</div>	
<script>
$(document).ready(function(){
	$('ul.tabs li').click(function(){
		var tab_id = $(this).attr('data-tab');

		$('ul.tabs li').removeClass('current');
		$('.tab-content').removeClass('current');

		$(this).addClass('current');
		$("#"+tab_id).addClass('current');
	})
})
</script>

	