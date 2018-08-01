<?php 
	session_start();
	if(!isset($_SESSION['terms_of_use']) || $_SESSION['terms_of_use'] != true)
	{
		header('Location: index.php');
		exit();
	}
	if(!isset($_SESSION['loggedIn']) || $_SESSION['terms_of_use'] != true)
	{
		header('Location: login.php');
		exit();
	}
	
	ob_start();

	include 'libs/database.php';
	include 'config/database.php';

	$db = new Database(DB_TYPE, DB_HOST, DB_NAME, DB_USER, DB_PASS);
	
	$sth = $db->prepare("SELECT factionid, clanid
	 FROM users WHERE id = :id LIMIT 1");
	$sth->execute(array(
					':id' => $_SESSION['player_id']
				));
	$datauser = $sth->fetchAll();

	if($datauser[0]['factionid'] == 0)
	{
		header('Location: company.php');
		exit();
	}
	
	$bHasClan = $datauser[0]['clanid'] != 0; 

	local_entete();
	
	$displayPage = 'home';
	if(isset($_GET['page']))
	{
		$displayPage = $_GET['page'];
	}
	
	/* <div id="bar" >

<p>   IP: ts3.andromeda-server.com </p>

<a id="yt" href="/views/video.php"><img src="img/youtube-icone-8942-48.png"/> </a>

<a id="fb" href="/views/facebook.php"><img src="img/facebook-icone-8470-48.png"/> </a>

<iframe src="https://www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2FAndromeda-Private-Server-PvEPvP-459750167520220%2F%3Ffref%3Dnf&width=250&layout=button_count&action=like&show_faces=true&share=true&height=46&appId" width="250" height="46" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
</div>*/
 ?>	


 
<div id=top-menu>
	<div id="home"><a href="view.php?page=home"><img src="img/home.png" width="15" height="15" style="position:absolute; top: 7px; left: 10px;">Home</a></div>
	<div id="logo"><img src="img/logo.png" width="250" height="90" style="margin-top: -10px;"></div>
	<div id="logout"><a href="logout.php"><img src="img/exit.png" width="15" height="15" style="position:absolute; top: 7px; right: 10px;">Logout</a></div>

</div>
<div id=menu>
<nav id="primary_nav_wrap">
<ul>
  <li class="current-menu-item liLeft"><a href="view.php?page=user">User<img src="img/down.png" width="15" height="15" style="position:absolute; top: 17px; right: 5px;"></a>
    <ul>
	  <li><a href="view.php?page=user&tab=infos">Account<img src="img/right.png" width="15" height="15" style="position:absolute; top: 10px; right: 5px;"></a>
	   <ul>          
          <li><a href="view.php?page=user&tab=infos">Informations</a></li>
		  <li><a href="view.php?page=company">Company Change</a></li>
		  <li><a href="view.php?page=settings">Settings</a></li>
       </ul>
	  </li>
	   <li><a href="view.php?page=user&tab=upgrades">Ship<img src="img/right.png" width="15" height="15" style="position:absolute; top: 10px; right: 5px;"></a>
	   <ul>          
          <li><a href="view.php?page=user&tab=upgrades">Upgrades</a></li>
		  <li><a href="view.php?page=user&tab=configurations">Configurations</a></li> 
       </ul>
	  </li>   
	   <li><a href="view.php?page=user&tab=achievements">Achievements</a></li>
    </ul>
  </li>
  <li><a href="view.php?page=clan">Clan</a>
  <?php
  if($bHasClan)
  {
	?>
	<ul>          
          <li><a href="view.php?page=clan&tab=claninfos">Informations</a></li>
		  <li><a href="view.php?page=clan&tab=clanmembers">Members</a></li>
		  <li><a href="view.php?page=clan&tab=diplomacy_alliance">Diplomacy<img src="img/right.png" width="15" height="15" style="position:absolute; top: 10px; right: 5px;"></a>
			<ul>          
			  <li><a href="view.php?page=clan&tab=diplomacy_alliance">Clan Alliances</a></li>
			  <li><a href="view.php?page=clan&tab=diplomacy_war">Clan Wars</a></li> 
		   </ul>
		  </li>
    </ul>
	<?php  
  }
  else
  {
	?>
	 <ul>          
          <li><a href="view.php?page=clan&tab=joinclan">Join Clan</a></li>
		  <li><a href="view.php?page=clan&tab=createclan">Create Clan</a></li>		 
    </ul>
	<?php  
  }
  ?>
  </li>
  <li><a href="view.php?page=shop">Shop<img src="img/down.png" width="15" height="15" style="position:absolute; top: 17px; right: 5px;"></a>
    <ul>
      <li><a href="view.php?page=shop&tab=boosters">Boosters</a></li>
      <li><a href="view.php?page=shop&tab=designs">Ship Designs</a></li>
      <li><a href="view.php?page=shop&tab=items">Items</a></li>
    </ul>
  </li>
  <li class="liRight"><a href="view.php?page=lottery">Lottery</a></li>
  </li>
  <a id="play" href="spacemap.php" target="_blank">Play</a>
   <li class="liLeft"><a href="view.php?page=store">Store</a></li>
  <li><a href="view.php?page=rules">Rules</a></li>
  <li><a href="view.php?page=contact">Contacts</a></li>
  <li class="liRight"><a href="forum">Forum</a></li>
</ul>
</nav>
</div>
<?php 
	$allowed = array('clan'
			, 'company'
			, 'contact'
			, 'home'
			, 'rules'
			, 'settings'
			, 'shop'
			, 'store'
			, 'user'
			, 'top100'
			, 'lottery');
	if(in_array($displayPage,$allowed)){
	    include('views/'.$displayPage.'.php');
	}
	else
	{
		echo '<center> Not allowed ! </center>';
	}
	
	local_pied() ;
	
	ob_end_flush();

	function local_entete() 
	{
		echo '<html>',
			'<head>',
			'<title>Andromeda</title>',
			'<link rel="stylesheet" type="text/css" href="styles/default.css" />',
			'<link rel="stylesheet" type="text/css" href="styles/mainStyles.css" />',
			'</head>',
			'<body>';
	}
	
	function local_pied() 
	{
		echo '</body>',
			'</html>';
	}
 ?>
 
