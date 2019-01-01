<?
/*******************************************************************************
* CASH DESK - CHANGE PASSWORD                                                  *
*                                                                              *
* Version: 1.0                                                                 *
* Date:    21.11.2018                                                          *
* Author:  Unknown                                                             *
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
?>

<html>
<head>
<title>Modifica Password process</title>
<link href="cssnn.css" rel="stylesheet" type="text/css">
</head>
<body>
<h1>Modifica Password </h1>
<h2></h2>
		
<style>
/* di seguito gli stili implementati ai Form*/
div#box					  {
						border: 1px solid #f8e8a0;
					  }

em					  {
						float:left;
					  }
																		 
form#new_passwd				  {
						width: 450px;
						height: 200px;
						font: 96% Arial, sans-serif;
						padding:5px 0;
						margin:20px;
						border-style: solid; 
						border-color: #006699; 
						border-width: 1px;
						background: #f0f0f0;
					  }

form p					  {
						margin:1em 10px;
					  }
						
input#go				  {
						width: 80px;
						height:25px;
						margin-left: 20px;
						cursor:pointer;
					  }									

fieldset				  {
						border: none;
						border-top: 1px solid #99A0FA;
						height:150px;
						width:400px;
					  }
									
fieldset div
					  {
						float:left;
						width:100%;
						padding: 10px 0 10px;
    						background-color: #f0f0f0;
					  }									

legend					  {
						font-weight:bold;
						color: #333;
						background-color: #f0f0f0;
					  }					
				
label					  {
						float:left;
						width: 120px;
						display: inline;
						margin-left: 10px;
    						line-height: 23px;
					  }

option    				  {
						font-family: verdana, tahoma, arial; 
						color: red; 
						font-size: 6px;
					  }
.msg	
					  {
						background: ActiveBorder;	
						padding: 6px;
						border: outset thin;
						font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
						font-size: x-small;
					  }
              
.msgButton
					  {
						background: ButtonFace;
						color: ButtonText;
						padding: 4px;
						text-decoration: none;
						border: groove thin;
					  }
              
.msgTitle
					  {
						background: ActiveCaption;
						color: HighlightText;		
					  }
              
.msglinks
					  {
						text-decoration: none;
						color: ButtonText;
					  }
              
.msgIcon
					  {
						font-family: Wingdings;
						font-weight: bolder;
						font-size: xx-large;
					  }									
</style>	
	
<?  /*Modulo connessione data base */

/*Variabili dell'identificazione dell'utente loggato*/

$userid = $_SESSION['userid'];
$group = $_SESSION['group'];
$funzione = $_SESSION['funzione'];
$nome_alias = $_SESSION['nome_alias'];

?>
Userid: <? echo $userid;?><p>

<!-- Form per la modifica della password  -->
<div id="box">
<em>
<form id="new_passwd" method=post action=ch_passwd.php>  <?//?action=upload_password>?> 
	<fieldset>
  	<legend> Modifica password </legend>
			
				<div>
				<label for="old_pwd">Vecchia password:</label>
				<input id="old_pwd" name="old_pwd" size="20" type="password">
				<input type=HIDDEN name="userid" value="<? echo $userid ?>">
				</div>
				
				<div>
				<label for="new_pwd">Nuova password:</label>
				<input id="new_pwd" name="new_pwd" size="20" type="password">
				</div>
				
				<div>
				<input id="go" type="submit" value="Conferma" name="avvia_query">
				</div>

	</fieldset>
	
</form>
</div>
</em>
<p>

<?

/* effettua Upload in users della nuova password*/
/*---------------------Aggiornamento users------------------------------------*/
/*                                                                            */
/*   Procedura di UPDATE dei campi password nella tabella users               */
/*   partendo dai dati confermati e rintracciabili tramite id                 */
/*   La query utilizzata e' del tipo:                                         */
/*   "UPDATE users SET password='$new_pwd' WHERE id='$userid'";"             */
/*                       																											*/
/*----------------------------------------------------------------------------*/

include("msgbox.inc.php");
$a=new msgBox("Vecchia password errata.","Fault","Attenzione !" ); 
$b=new msgBox("La nuova password non &egrave valida.","Fault","Attenzione !" ); 
$c=new msgBox("Premere OK per continuare","OKOnly","Modifica password eseguita" ); 

$links=array("ch_passwd.php"); 
$a->makeLinks($links);

$links=array("ch_passwd.php"); 
$b->makeLinks($links);

$links=array("start.php"); // make the links
$c->makeLinks($links);

if (isset($_POST['new_pwd']))
{
include("../../Accounts_MySql/datilogin.txt");// Connessione al Data Base

	$old_pwd = md5($_POST['old_pwd']); //Vecchia Password cryptata
	$new_pwd = md5($_POST['new_pwd']); //Nuova Password cryptata
	$userid = $_POST['userid'];
	
	$query_login = "SELECT * FROM users WHERE id = '$userid' AND password ='$old_pwd'";
	$result_db = mysqli_query($link, $query_login);
	$row_up=mysqli_fetch_array($result_db);
		
	if (($row_up[0]<>$userid)or($row_up[1]<>$old_pwd) or $row_up[1]==FALSE)
	{
		$a->showMsg();	

	} else if ($_POST['new_pwd']==""){
		$b->showMsg();

	} else if (($row_up[0]===$userid)and($row_up[1]===$old_pwd)){
		$dbase_update =	"UPDATE users SET password='$new_pwd' WHERE id='$userid'";
  	mysqli_query ($link, $dbase_update);
		$c->showMsg();
	}
	mysqli_free_result ($result_db);
mysqli_close ($link);	
}

?>

</body>
</html>
