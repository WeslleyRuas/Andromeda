<?php 
$sth = $db->prepare("SELECT clanid
 FROM users WHERE id = :id LIMIT 1");
$sth->execute(array(
				':id' => $_SESSION['player_id']
			));
$datauser = $sth->fetchAll();
$bHasClan = $datauser[0]['clanid'] != 0; 

if($bHasClan)
{
	$displayTab = 'claninfos';
}
else
{
	$displayTab = 'joinclan';
}

if(isset($_GET['tab']))
{
	$displayTab = $_GET['tab'];
}
?>
<link rel="stylesheet" type="text/css" href="styles/home.css" />
<link rel="stylesheet" type="text/css" href="styles/clan.css?v=1" />
<div class="CMSContent">
		<?php 
		$allowed = array('createclan'
				, 'claninfos'
				, 'diplomacy_war'
				, 'diplomacy_alliance'
				, 'joinclan'
				, 'clanmembers');
		if(in_array($displayTab,$allowed)){
			include('views/clanTabs/'.$displayTab.'.php');
		}
		else
		{
			echo '<center> Not allowed ! </center>';
		}
		?>
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