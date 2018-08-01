<?php 
if(!empty($_POST['submit-booty']))
{
	$buymessage = handleSubmitBooty($db);
}
else if(!empty($_POST['submit-loguri']))
{
	$buymessage = handleSubmitLog($db);
}
else if(!empty($_POST['submit-logspecial']))
{
	$buymessage = handleSubmitLogSpecial($db);
}
else if(!empty($_POST['submit-ticketpalla']))
{
	$buymessage = handleticketpalla($db);
}
else if(!empty($_POST['submit-tickettoken']))
{
	$buymessage = handletickettoken($db);
}
?>

<div class="box" style="margin-top:50px;margin-left:140px;margin-bottom:20px;">
	<div class="title">Shop</div>
	<div id="shop">
		<div class="stat" style="width:550px;">
			<div class="stat-left">
				Booty keys
			</div>
			<div class="stat-right tooltip" style="width:390px;text-align:left; padding-left: 10px;">
				Booty keys
				<span style="text-align:center;">
					<img class="callout" src="img/callout.gif" />
					<strong>Booty keys</strong><br/>
					Credits: <font color='#00AAFF'>1,000,000</font>
				</span>
				<form action="view.php?page=shop&tab=items" method="post" style="margin-top:-16px;">
					<label for="" style="position:absolute;right:130px; color:#81B7F2;" >Amount :</label><input style="opacity:0.6;width:50px;position:absolute;right:80px;margin-top:-1px;text-align:center;" type="text" name="amount-a" value="1">
					<input name="submit-booty" class="buy-stat"  style="width:50px;height:20px;" type="submit" value="Buy">
				</form>
			</div>
		</div>		
		
		<div class="stat" style="width:550px;">
			<div class="stat-left">
				Logfiles
			</div>
			<div class="stat-right  tooltip" style="width:390px;text-align:left; padding-left: 10px;">
				Uridium
				<span style="text-align:center;">
					<img class="callout" src="img/callout.gif" />
					<strong>Logfiles</strong><br/>
					Uridium: <font color='magenta'>1,000</font>
				</span>
				<form action="view.php?page=shop&tab=items" method="post" style="margin-top:-16px;">
					<label for="" style="position:absolute;right:130px; color:#81B7F2;" >Amount :</label><input style="opacity:0.6;width:50px;position:absolute;right:80px;margin-top:-1px;text-align:center;" type="text" name="amount-b" value="1">
					<input name="submit-loguri" class="buy-stat"  style="width:50px;height:20px;" type="submit" value="Buy">
				</form>
			</div>
		</div>		

		<div class="stat" style="width:550px;">
			<div class="stat-left">
				Logfiles
			</div>
			<div class="stat-right tooltip" style="width:390px;text-align:left; padding-left: 10px;">
				Uridium + Credits
				<span style="text-align:center;">
					<img class="callout" src="img/callout.gif" />
					<strong>Logfiles</strong><br/>
					Credits: <font color='#00AAFF'>800,000</font>
					</br>Uridium: <font color='magenta'>700</font>
				</span>
				<form action="view.php?page=shop&tab=items" method="post" style="margin-top:-16px;">
					<label for="" style="position:absolute;right:130px; color:#81B7F2;" >Amount :</label><input style="opacity:0.6;width:50px;position:absolute;right:80px;margin-top:-1px;text-align:center;" type="text" name="amount-c" value="1">
					<input name="submit-logspecial" class="buy-stat"  style="width:50px;height:20px;" type="submit" value="Buy">
				</form>
			</div>
		</div>
		
		<div class="stat" style="width:550px;">
			<div class="stat-left">
				Lottery's ticket
			</div>
			<div class="stat-right tooltip" style="width:390px;text-align:left; padding-left: 10px;">
				Logfiles + Palladium
				<span style="text-align:center;">
					<img class="callout" src="img/callout.gif" />
					<strong>Lottery's ticket</strong><br/>
					Logfiles: <font color='#00AAFF'>100</font>
					</br>Palladium: <font color='magenta'>100</font>
				</span>
				<form action="view.php?page=shop&tab=items" method="post" style="margin-top:-16px;">
					<label for="" style="position:absolute;right:130px; color:#81B7F2;" >Amount :</label><input style="opacity:0.6;width:50px;position:absolute;right:80px;margin-top:-1px;text-align:center;" type="text" name="amount-d" value="1">
					<input name="submit-ticketpalla" class="buy-stat"  style="width:50px;height:20px;" type="submit" value="Buy">
				</form>
			</div>
		</div>
		
		<div class="stat" style="width:550px;">
			<div class="stat-left">
				Lottery's ticket
			</div>
			<div class="stat-right tooltip" style="width:390px;text-align:left; padding-left: 10px;">
				Uridium
				<span style="text-align:center;">
					<img class="callout" src="img/callout.gif" />
					<strong>Lottery's ticket</strong><br/>
					Uridium: <font color='magenta'>400.000</font>
				</span>
				<form action="view.php?page=shop&tab=items" method="post" style="margin-top:-16px;">
					<label for="" style="position:absolute;right:130px; color:#81B7F2;" >Amount :</label><input style="opacity:0.6;width:50px;position:absolute;right:80px;margin-top:-1px;text-align:center;" type="text" name="amount-e" value="1">
					<input name="submit-tickettoken" class="buy-stat"  style="width:50px;height:20px;" type="submit" value="Buy">
				</form>
			</div>
		</div>
		
		<br>
	</div>	
</div>


<?php
function handleticketpalla($db)
{
	if(empty($_POST['amount-d']))
	{
		$amount = 1;
	}
	else
	{
		$amount = (int)$_POST['amount-d'];
	}
	if($amount < 1)
	{
		$amount = 1;
	}
	
	$sth = $db->prepare("SELECT logfiles
	 FROM users WHERE id = :id LIMIT 1");
	$sth->execute(array(
					':id' => $_SESSION['player_id']
				));
	$datauser = $sth->fetchAll();

	$logfiles = $datauser[0]['logfiles'];
	
	$sth = $db->prepare("SELECT palladium
	 FROM player_cargo WHERE id = :id LIMIT 1");
	$sth->execute(array(
					':id' => $_SESSION['player_id']
				));
	$datauser = $sth->fetchAll();

	$palladium = $datauser[0]['palladium'];
	
	if($logfiles < (100*$amount))
	{
		return 'Not enough Logfiles';
	}
	if($palladium < (100*$amount))
	{
		return 'Not enough Palladium';
	}
	
	$req = $db->prepare('UPDATE users SET logfiles=logfiles-'.(100*$amount).' WHERE id='.$_SESSION['player_id']);
	$req->execute();
	
	$req = $db->prepare('UPDATE player_cargo SET palladium=palladium-'.(100*$amount).' WHERE id='.$_SESSION['player_id']);
	$req->execute();
	
	$req = $db->prepare('UPDATE users_infos SET tickets=tickets+'.$amount.' WHERE id='.$_SESSION['player_id']);
	$req->execute();
	
	return 'Tickets purchased'; 
}


function handletickettoken($db)
{
	if(empty($_POST['amount-e']))
	{
		$amount = 1;
	}
	else
	{
		$amount = (int)$_POST['amount-e'];
	}
	if($amount < 1)
	{
		$amount = 1;
	}
	
	$sth = $db->prepare("SELECT uridium
	 FROM users WHERE id = :id LIMIT 1");
	$sth->execute(array(
					':id' => $_SESSION['player_id']
				));
	$datauser = $sth->fetchAll();

	$uridium = $datauser[0]['uridium'];
	
	if($uridium < $amount*400000)
	{
		return 'Not enough Uridium';
	}
	
	$req = $db->prepare('UPDATE users SET uridium=uridium-'.($amount*400000).' WHERE id='.$_SESSION['player_id']);
	$req->execute();
	
	$req = $db->prepare('UPDATE users_infos SET tickets=tickets+'.$amount.' WHERE id='.$_SESSION['player_id']);
	$req->execute();
	
	return 'Tickets purchased'; 
}

function handleSubmitLog($db)
{
	$sth = $db->prepare("SELECT uridium
	FROM users WHERE id = :id LIMIT 1");
	$sth->execute(array(
					':id' => $_SESSION['player_id']
				));
	$datauser = $sth->fetchAll();
	
	if(empty($_POST['amount-b']))
	{
		$amount = 1;
	}
	else
	{
		$amount = (int)$_POST['amount-b'];
	}
	if($amount < 1)
	{
		$amount = 1;
	}
	$price = 1000*$amount;
	if($datauser[0]['uridium'] >= $price)
	{
		$req = $db->prepare('UPDATE users SET logfiles=logfiles+'.$amount.', uridium=uridium-'.$price.' WHERE id='.$_SESSION['player_id']);
		$req->execute();
		return 'Logfiles purchased'; 
	}
	else
	{
		return 'Not enough uridium';
	}
}

function handleSubmitLogSpecial($db)
{
	$sth = $db->prepare("SELECT uridium, credits
	FROM users WHERE id = :id LIMIT 1");
	$sth->execute(array(
					':id' => $_SESSION['player_id']
				));
	$datauser = $sth->fetchAll();
	
	if(empty($_POST['amount-c']))
	{
		$amount = 1;
	}
	else
	{
		$amount = (int)$_POST['amount-c'];
	}
	if($amount < 1)
	{
		$amount = 1;
	}	
	$priceC = 800000*$amount;
	$priceU = 700*$amount;
	if($datauser[0]['uridium'] >= $priceU && $datauser[0]['credits'] >= $priceC)
	{
		$req = $db->prepare('UPDATE users SET logfiles=logfiles+'.$amount.', uridium=uridium-'.$priceU.', credits=credits-'.$priceC.'  WHERE id='.$_SESSION['player_id']);
		$req->execute();
		return 'Logfiles purchased'; 
	}
	else
	{
		return 'Not enough credits or uridium';
	}
}

function handleSubmitBooty($db)
{
	$sth = $db->prepare("SELECT  credits
	FROM users WHERE id = :id LIMIT 1");
	$sth->execute(array(
					':id' => $_SESSION['player_id']
				));
	$datauser = $sth->fetchAll();
	
	if(empty($_POST['amount-a']))
	{
		$amount = 1;
	}
	else
	{
		$amount = (int)$_POST['amount-a'];
	}
	if($amount < 1)
	{
		$amount = 1;
	}
	$price = 1000000*$amount;
	if($datauser[0]['credits'] >= $price)
	{
		$req = $db->prepare('UPDATE users SET booty_keys=booty_keys+'.$amount.', credits=credits-'.$price.' WHERE id='.$_SESSION['player_id']);
		$req->execute();
		return 'Booty keys purchased'; 
	}
	else
	{
		return 'Not enough credits';
	}
}
?>


