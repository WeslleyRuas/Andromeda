<?php session_start(); 
if(isset($_SESSION['terms_of_use']) and $_SESSION['terms_of_use'] == "true") 
{
	header('Location: login.php');
	exit();
}
if(isset($_REQUEST['agree']) and $_REQUEST['agree'] == "true") 
{ 
	$_SESSION['terms_of_use'] = true; 
	header('Location: login.php');
	exit();
}
?>
<!DOCTYPE HTML>
<html>
<head>
<style>
body
{
	background-color:black;
	background:url('img/bg.jpg');
	background-size:cover;
	color:lightgray;
	font-family:Georgia;
}



.continueButton{
  width:140px;
  	margin-left: auto;
    margin-right: auto;
  padding:15px;
  border-radius:5px;
  background-image: linear-gradient(#223548 0%, #97abbf 100%);
  font:14px Oswald;
  color:#FFF;
  text-transform:uppercase;
  text-shadow:#000 0px 1px 5px;
  border:1px solid #000;
  opacity:0.7;
	-webkit-box-shadow: 0 8px 6px -6px rgba(0,0,0,0.7);
  -moz-box-shadow: 0 8px 6px -6px rgba(0,0,0,0.7);
	box-shadow: 0 8px 6px -6px rgba(0,0,0,0.7);
  border-top:1px solid rgba(255,255,255,0.8)!important;
  -webkit-box-reflect: below 0px -webkit-gradient(linear, left top, left bottom, from(transparent), color-stop(50%, transparent), to(rgba(255,255,255,0.2)));
}
.continueButton:hover{
  opacity:1;
  cursor:pointer;
}

</style>
<title>Andromeda</title>
</head>
<body>
<center>
	<img style="height:165px;" src="img/logo.png">
	<div style="margin-left:auto;opacity:0.90; height:20px; background-color: #223548;border-top-left-radius: 25px;border-top-right-radius: 25px; color:white; margin-right:auto; display:block; width:600px; margin-top:25px; font-size:13px;">
	In order to continue, you must agree with the following rules:
	</div>
	<div style="margin-left:auto;opacity:0.90; height:250px; overflow:auto;background-color: white; color:black; margin-right:auto; display:block; width:600px; margin-top:0px; font-size:13px;">
		<h3>
		1. Terms
		</h3>
		<p>
		By accessing this web site, you are agreeing to be bound by these
		web site Terms and Conditions of Use, all applicable laws and regulations,
		and agree that you are responsible for compliance with any applicable local
		laws. If you do not agree with any of these terms, you are prohibited from
		using or accessing this site. The materials contained in this web site are not
		protected by applicable copyright and trade mark law. By surfing on this
		website, you agree that your the only one who would be punished for breaking copyright laws.
		</p>
		<h3>
		2. Use License
		</h3>
		<ol type="a">
		<li>
		Permission is granted to temporarily download one copy of the materials
		(information or software) on Andromeda's web site for personal,
		non-commercial transitory viewing only.
		</li>
		</ol>
		<h3>
		3. Disclaimer
		</h3>
		<ol type="a">
		<li>
		The materials on Andromeda's web site are provided "as is". Andromeda makes no warranties, expressed or implied, and hereby disclaims and negates all other warranties, including without limitation, implied warranties or conditions of merchantability, fitness for a particular purpose, or non-infringement of intellectual property or other violation of rights. Further, Andromeda does not warrant or make any representations concerning the accuracy, likely results, or reliability of the use of the materials on its Internet web site or otherwise relating to such materials or on any sites linked to this site.
		</li>
		</ol>
		<h3>
		4. Limitations
		</h3>
		<p>
		In no event shall Andromeda or its suppliers be liable for any damages (including, without limitation, damages for loss of data or profit, or due to business interruption,) arising out of the use or inability to use the materials on Andromeda's Internet site, even if Andromeda or a Andromeda authorized representative has been notified orally or in writing of the possibility of such damage. Because some jurisdictions do not allow limitations on implied warranties, or limitations of liability for consequential or incidental damages, these limitations may not apply to you.
		</p>
		<h3>
		5. Revisions and Errata
		</h3>
		<p>
		The materials appearing on Andromeda's web site could include technical, typographical, or photographic errors. Andromeda does not warrant that any of the materials on its web site are accurate, complete, or current. Andromeda may make changes to the materials contained on its web site at any time without notice. Andromeda does not, however, make any commitment to update the materials.
		</p>
		<h3>
		6. Links
		</h3>
		<p>
		Andromeda has not reviewed all of the sites linked to its Internet web site and is not responsible for the contents of any such linked site. The inclusion of any link does not imply endorsement by Andromeda of the site. Use of any such linked web site is at the user's own risk.
		</p>
		<h3>
		7. Site Terms of Use Modifications
		</h3>
		<p>
		Andromeda may revise these terms of use for its web site at any time without notice. By using this web site you are agreeing to be bound by the then current version of these Terms and Conditions of Use.
		</p>
		<h3>
		8. Governing Law
		</h3>
		<p>
		Any claim relating to Andromeda's web site shall be governed by the laws of the State of Swiss without regard to its conflict of law provisions.
		</p>
		<p>
		General Terms and Conditions applicable to Use of a Web Site.
		</p>
		<h2>
		Copyright Protection
		</h2>
		<p>
		If you believe any materials accessible on or from the Site infringe your copyright, you may request removal
		of those materials (or access thereto) from this web site by contacting us and providing the
		following information:
		<li> Identification of the copyrighted work that you believe to be copied. Please describe the work,
		and where possible, include a copy or the location of an authorized version of the work.</li>
		<li> Your name, address, telephone number, and e-mail address.</li>
		<li> A statement that you have a good faith belief that the complained of use of the materials is not
		authorized by the copyright owner, its agent, or the law.</li>
		<li> A statement that the information that you have supplied is accurate, and indicating that "under
		penalty of perjury," you are the copyright owner or are authorized to act on the copyright owner’s
		behalf.</li>
		<li> A signature or the electronic equivalent from the copyright holder or authorized representative.</li>
		</p>
		<h2>
		Privacy Policy
		</h2>
		<p>
		Your privacy is very important to us. Accordingly, we have developed this Policy in order for you to understand how we collect, use, communicate and disclose and make use of personal information. The following outlines our privacy policy.
		</p>
		<ul>
		<li>
		Before or at the time of collecting personal information, we will identify the purposes for which information is being collected.
		</li>
		<li>
		We will collect and use of personal information solely with the objective of fulfilling those purposes specified by us and for other compatible purposes, unless we obtain the consent of the individual concerned or as required by law.
		</li>
		<li>
		We will only retain personal information as long as necessary for the fulfillment of those purposes.
		</li>
		<li>
		We will collect personal information by lawful and fair means and, where appropriate, with the knowledge or consent of the individual concerned.
		</li>
		<li>
		Personal data should be relevant to the purposes for which it is to be used, and, to the extent necessary for those purposes, should be accurate, complete, and up-to-date.
		</li>
		<li>
		We will protect personal information by reasonable security safeguards against loss or theft, as well as unauthorized access, disclosure, copying, use or modification.
		</li>
		<li>
		We will make readily available to customers information about our policies and practices relating to the management of personal information.
		</li>
		</ul>
		<p>
		We are committed to conducting our business in accordance with these principles in order to ensure that the confidentiality of personal information is protected and maintained.
		</p>
	</div>
	<form action="index.php" style="margin-top:0px;" method="post">	
		<div style="margin-left:auto;opacity:0.90; height:20px; background-color: #223548;border-bottom-left-radius: 25px;border-bottom-right-radius: 25px; color:white; margin-right:auto; display:block; width:600px; margin-top:0px; font-size:13px;">
			<label style="cursor:pointer; color:white;"><input type="checkbox" value="true" name="agree">I have read, and agree to abide by the Andromeda rules.</label>
		</div>
		<br/>
		<div style="margin-left:auto;">	
		<input type="submit" value="Continue" class="continueButton">
		</div>
	</form>
	
	<div style="margin-left:auto; height:70px; background-color: #223548;opacity:0.90;border-radius: 25px;border-bottom-right-radius: 25px; color:black; margin-right:auto; display:block; width:600px; margin-top:25px; font-size:13px;">
	Andromeda is an independent project (nonprofit goal) © 2016.
	<br/>
	<a target="_blank" href="http://darkorbit.com/">Dark Orbit</a> is a registered trademark of <a target="_blank" href="http://bigpoint.com/">BigPoint GmbH</a>. 
	 <br/>
	All rights reserved to their respective owner(s).
	<br/>
	We are not endorsed, affiliated or offered by <a target="_blank" href="http://bigpoint.com/">BigPoint GmbH</a>.
	</div>
</center>
</body>
</html>