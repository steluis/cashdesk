<?
/*******************************************************************************
* CASH DESK - CENTRAL FRAME                                                    *
*                                                                              *
* Version: 1.0                                                                 *
* Date:    21.11.2018                                                          *
* Author:  Stefano LUISE                                                       *
*******************************************************************************/

//Controllo accesso
include("session_exists.php");
// viene sostituita la funzione di session_start();
 if(!session_exists())
 {
 	header('location:login.php');
	exit;
 }

/*Ulteriore controllo sulla sessione attivata dall'utente*/
if(!isset($_SESSION['userid']))
{
	header('location:login.php');
	exit;
}

//Definizione dei ruoli 
include("role_check.php"); 
define ("ROLE_ADMIN", 0);
define ("ROLE_POWERUSER", 1);
define ("ROLE_A_MANAGER", 2);
define ("ROLE_BASIC", 3);

?>
<!DOCTYPE html>
<html>
<head>

<link href="cssnn.css" rel="stylesheet" type="text/css">
<title></title>
<style type="text/css">
body{font:12px Arial,sans-serif;background:#FFF;color:#555}
/*h1{font-size: 20px;color: #375FAD;border-bottom: 3px solid #888888;margin: 0}*/
h3{font-size:1.4em;color: #3980F4;text-align: center}
h4{font-size: 16px;text-align: center}
div#container{width:640px;margin:2em auto}
p#intro{font-size: 110%;text-align:left}

/* Style bttoni */
div.button-cont{text-align:center;margin: 2em 0}
a.button{display: block;width:10em;margin:0 auto;
    height: 35px;padding-left: 1em;
    font: bold 150%/35px "Trebuchet MS",Arial,sans-serif;
    background: url(bottone.png) no-repeat top left;
    text-decoration: none;color: #286C98}
a.button span{display: block;cursor: pointer;padding-right: 1em;
    background: url(bottone.png) no-repeat top right}
a.button:hover{background-position: bottom left;color: #6B9828}
a.button:hover span{background-position: bottom right}

/* Style barra */
dl.stat{float:left;width:260px}
dl.stat p{font-size: 110%;text-align:right}
dl.stat dt{float:left;width:150px;
    height:18px;line-height:18px;
    margin: 2px 0;padding:0;text-align:right}
dl.stat dd{float:right;
    width:100px !important; width /**/:104px;
    height:16px;line-height:16px;
    padding:1px;border:1px solid #CCC;margin:1px 0;
    text-align:center}
dl.stat dd span{display:block;width:100px;
    background:#ECECEC url(progressBk.png) no-repeat 0 0;color:#002F7E}
dl.stat dd span#gre{display:block;width:100px;
    background:#ECECEC url(progressBk.png) no-repeat 0 0;color:#002F7E}
dl.stat dd span#red{display:block;width:100px;
		background:#ECECEC url(progressBkred.png) no-repeat 0 0;color:#002F7E}
dl.stat dd span#ara{display:block;width:100px;
		background:#ECECEC url(progressBkara.png) no-repeat 0 0;color:#002F7E}
dl.stat dd span#gia{display:block;width:100px;
		background:#ECECEC url(progressBkgia.png) no-repeat 0 0;color:#002F7E}
dl.stat dd span#cel{display:block;width:100px;
		background:#ECECEC url(progressBkcel.png) no-repeat 0 0;color:#002F7E}

/* Tabelle di Navigazione */
ul#nav{float: left;width: 100%;list-style: none;
     margin: 0;padding: 0;border-bottom: 1px solid #D7D7D7}
ul#nav li{float: left;margin: 0 0.2em 0;padding: 0}
ul#nav a{float: left;padding: 0 0 0 0.4em;
    background: url(../tab.png) no-repeat top left;
    text-decoration: none;color: #222}
ul#nav span{float: left;padding: 0.4em 0.4em 0.4em 0;
    background: url(../tab.png) no-repeat top right;cursor: pointer}
ul#nav li#active a,ul#nav a:hover{
    background: url(../tab2.png) no-repeat top left}
ul#nav li#active span,ul#nav a:hover span{
    background: url(../tab2.png) no-repeat top right;color: #184D8A}
</style>

</head>

	<body>
		<?
		$funzione = $_SESSION['funzione'];
		$nome_alias = $_SESSION['nome_alias'];
		$group = $_SESSION['group'];
		?>
		
		<div id="container">
		<p id="intro">
		
		Benvenuto <b><? echo $nome_alias;?></b><br><br>
		Da questa pagina potrai eseguire le attivit&agrave relative al tuo profilo che &egrave <b><? echo $group;?></b>.<br><br>
		<br><br><br>
		
		<b>Altre info:</b><br><br>
		<img src='img/lb8.gif'>
		<iframe src="UtilityReports/HD_status/disk_space_01.php" frameborder="0" scrolling="no" width="300" height="36"></iframe><br>
		&nbsp;&nbsp;<br>
		
		</p>
		</div>
	</body>
</html>
