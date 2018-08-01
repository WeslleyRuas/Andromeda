<link rel="stylesheet" type="text/css" href="styles/home.css" />
<link rel="stylesheet" type="text/css" href="styles/lottery.css?v=1" />
<div class="CMSContent">
	<div class="box" style="margin-left:140px;margin-bottom:20px;">
		<div class="title">Andromeda's Lottery</div>
		<div id="lottery">	
				<div id="rewards">	
				
				</div>
				
				<button id="bgen" onclick="Gen()">Play lottery</button>
				<br>
				
		</div>
	</div>	
	<div class="box" style="margin-left:140px;margin-bottom:20px;">
		<div class="title">Reward's Probabilities</div>
		<div id="lottery">	
				<center><div id="piechart" style="width: 300px; height: 300px;"></div></center>
				<br>
				
		</div>
	</div>	
</div>	
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
  google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChart);
  function drawChart() {

	var data = google.visualization.arrayToDataTable([
	  ['Reward', 'Probability'],
	  ['a Token',     5],
	  ['NPC booster(2h)',      20],
	  ['80-120 Xenomits',  20],
	  ['Health/Damage/Shield booster(4h)', 25],
	  ['500 promeriums',    10],
	  ['Speed booster(2h)',    20]
	]);

	var options = {
	  backgroundColor: '#223548',
	  title: 'Lottery rewards:',
	  pieSliceBorderColor: '#97ABBF',
	  titleTextStyle: {color: 'white', fontSize: 14},
	  width: 300,
	  height: 300,
	  chartArea: {'width': '90%', 'height': '90%'},
      legend: {position: 'none'}
	};

	var chart = new google.visualization.PieChart(document.getElementById('piechart'));

	chart.draw(data, options);
  }
</script>
	
<script>
function httpGet(theUrl)
{
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open( "GET", theUrl, false ); // false for synchronous request
    xmlHttp.send( null );
    return xmlHttp.responseText;
}

function Gen() {
    var element = document.createElement("div");
	var ret = httpGet("http://andromeda-server.com/views/lottery/generate.php");
    element.appendChild(document.createTextNode(ret));
    document.getElementById('rewards').appendChild(element);
	document.getElementById('rewards').scrollTop = document.getElementById('rewards').scrollHeight;
	
}
</script>
