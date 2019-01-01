<?php
/*******************************************************************************
* CASH DESK - FRAME ON THE LEFT WITH NAVI MENU                                 *
*                                                                              *
* Version: 1.0                                                                 *
* Date:    21.11.2018                                                          *
* Author:  Stefano LUISE                                                       *
*******************************************************************************/
include("session_exists.php");
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

include("role_check.php"); 
define ("ROLE_ADMIN", 0);
define ("ROLE_POWERUSER", 1);
define ("ROLE_A_MANAGER", 2);
define ("ROLE_BASIC", 3);
?>

<!DOCTYPE html>
<html>
	<head>
		<meta name="author">
		<link href="cssnn.css" rel="stylesheet" type="text/css">
		<script language="JavaScript" type="text/javascript" src="xampp.js"></script>
		<script>history.forward();</script>
		<title></title>
	</head>

	<body class="n">
		<table border="0" cellpadding="0" cellspacing="0">
			<tr valign="top">
				<td align="left" class="navi">
					<img src="img/blank.gif" alt="" width="145" height="15"><br>
					<span class="nh">&nbsp;<?php echo "FUNZIONI";?></span>
					<br>
				</td>
			</tr>
			<tr>
				<td height="1" bgcolor="#fb7922" colspan="1" style="background-image:url(img/strichel.gif)" class="white"></td>
			</tr>
			<tr valign="top">
				<td align="left" class="navi">

				<a class="n" target="content" onclick="h(this);" href="scontrini.php">Emetti scontrini</a><br>
				<a class="n" target="content" onclick="h(this);" href="annulla_scontrino.php">Annulla scontrino</a><br>
				<a class="n" target="content" onclick="h(this);" href="browsescontrini.php">Ristampa scontrino</a><br>
				<a class="n" target="content" onclick="h(this);" href="report.php">Report Finale</a><br>
				<a class="n" target="content" onclick="h(this);" href="log_out.php">Logout</a><br>
<?
/* Menu dedicato al profilo Administrator*/
// Start ciclo IF per role_check()
	if (role_check(ROLE_ADMIN) or role_check(ROLE_POWERUSER))
	{
?>
				<br>&nbsp;<br>
				<span class="nh"><?php echo SETUP; ?></span><br>
				</td>
			</tr>
			<tr>
				<td height="1" bgcolor="#fb7922" colspan="1" style="background-image:url(img/strichel.gif)" class="white"></td>
			</tr>
			<tr valign="top">
				<td align="left" class="navi">		
				<a class="n" target="content" onclick="h(this);" href="impostazioni.php">Impostazioni</a><br>
				<a class="n" target="content" onclick="h(this);" href="gestione_stampanti.php">Stampanti</a><br>
				<a class="n" target="content" onclick="h(this);" href="bk_ld_db.php">Carica/Salva archivio</a><br>
				<span class="navi">[PHP: <?php echo phpversion(); ?>]</span>					
				<a class="n" target="content" onclick="h(this);" href="phpinfo.php">phpinfo</a><br>
				</td>
			</tr>
<?
	}//End ciclo IF per role_check()
?>
			<tr>
				<td bgcolor="#fb7922" colspan="1" class="white"></td>
			</tr>
			<tr valign="top">
				<td align="left" class="navi">
					<br>
					<p class="navi">&copy;2019 <br>
				</td>
			</tr>
		</table>
	</body>
</html>
