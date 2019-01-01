<?
/*******************************************************************************
* CASH DESK - TOP FRAME                                                        *
*                                                                              *
* Version: 1.0                                                                 *
* Date:    21.11.2018                                                          *
* Author:  Stefano LUISE                                                       *
*******************************************************************************/

//Controllo accesso

session_start();
  
/*Ulteriore controllo sulla sessione attivata dall'utente*/

if(!isset($_SESSION['userid']))
{
	header('location:login.php');
	exit;
}
$gruppo = $_SESSION['group'];

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
		<meta name="author">
		<title></title>
		<link href="focus_slide_1.css" rel="stylesheet" type="text/css" /></link>
		<link href="cssnn.css" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="focus_slide.js"></script>
	
	</head>
<body leftmargin="0" topmargin="0" bgcolor="#ffffff" background="img/icon_bg_grad2.gif">
	
<style>
/* di seguito gli stili implementati ai Form*/
				p {
					float:left;
					margin:1em 20px;
					line-height: 10px;
					font-family: verdana, tahoma, arial;
			            	font-size: 9px;
				  }
									
</style>


		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td><img src="img/cash_desk.png" alt="" width="87" height="68"></td>
				<td><img src="img/blank.gif" alt="" width="10" height="1"></td>
				<td><img src="img/blank.gif" alt="" width="10" height="1"></td>
				<td>
				</td>			
			
				<td>
				</td>			
				
				<td>
					<P align="right">&nbsp;
  					<?$nome_alias = $_SESSION['nome_alias'];
					print ("<img src='img/lb21.gif'>&nbsp;&nbsp;Utente connesso:&nbsp;<b>$nome_alias</b>");
  					?>
					</p>						
				</td>
				<td>
					<P align="right">&nbsp;
  					<?$stampante_user = $_SESSION['stampante'];
					print ("<img src='img/printer.png'>&nbsp;&nbsp;Stampante:&nbsp;<b>$stampante_user</b>");
  					?>
					</p>						
				</td>
			</tr>

		</table>
		

      <ul id="navheader">
      	
      	<li id="Change_passwd">
      		<a href="ch_passwd.php" title="Cambia Password" target="content">Cambia Password</a>
      	</li>
      
      	<li id="LOGOUT">
      		<a href="log_out.php" title="Log Out" target="_parent">Sconnetti</a>
      	</li>

      	<li id="HOME">
      		<a href="start.php" title="Home page" target="content">Home</a>
      	</li>
      </ul>		
</body>
</html>
