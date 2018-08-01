<?php 
$displayPage = 'infos';
if(isset($_GET['tab']))
{
	$displayPage = $_GET['tab'];
}
?>

<link rel="stylesheet" type="text/css" href="styles/home.css" />
<link rel="stylesheet" type="text/css" href="styles/userStyle.css" />
 
<div class="CMSContent">
	<div class="tabContent">
		<?php 
		$allowed = array('infos'
				, 'upgrades'
				, 'achievements'
				, 'configurations');
		if(in_array($displayPage,$allowed)){
			include('views/userTabs/'.$displayPage.'.php');
		}
		else
		{
			echo '<center> Not allowed ! </center>';
		}
		?>
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