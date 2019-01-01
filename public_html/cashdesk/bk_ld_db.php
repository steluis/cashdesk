<?
/*******************************************************************************
* CASH DESK - LOAD AND BACKUP DATABASE                                         *
*                                                                              *
* Version: 1.0                                                                 *
* Date:    21.11.2018                                                          *
* Author:  Stefano Luise                                                       *
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
 
include("role_check.php"); 
define ("ROLE_ADMIN", 0);
define ("ROLE_POWERUSER", 1);
define ("ROLE_A_MANAGER", 2);
define ("ROLE_BASIC", 3);

define('EURO',chr(128)); // definizione del carattere EURO


?>
<!DOCTYPE html>
<head>

<title>Carica/Salva archivi</title>

<link href="cssnn.css" rel="stylesheet" type="text/css">
<link href="UtilityReports/OrderStatus/layout_big.css" rel="stylesheet" type="text/css">

<style type="text/css">
body{font:12px verdena,arial,sans-serif;background-color: #ffffff;}
div#container{width: 500px;padding: 10px;margin: 0px auto;
    background-color: #FFF;text-align: left}
/*h1{font-size: 20px;color: #375FAD;border-bottom: 5px solid #B02F2F;margin: 0}*/
h3{font-size: 18px;text-align: center}
h4{font-size: 16px;text-align: center}
p#intro{font-size: 110%;text-align:left}

fieldset{padding: 8px;border: 1px solid #B02F2F;margin-bottom: 20px}
legend{padding: 0 5px;text-transform: uppercase;color: #B02F2F}
label.req strong, strong.asterisco{font-weight: bold;font-family: verdana,sans-serif;color: red}
input:focus{background-color: #ffc}
br{clear:left}
fieldset.in label{float: left;text-align: left;margin: 1px 20px 1px 0}
fieldset.in input,select{display: block;width: 170px}
fieldset.in input.large{width: 355px}

input#ordernumber,input#delivery_date,input#import,input#cap,input#provincia,input#to_date,input#from_date{width: auto}

select#ordernumber{width: auto}
fieldset#check label{float: left;width: 120px}
fieldset#account p{float: right;width: 190px;color: #185DA1;margin-top: 10px}
fieldset#agree div#cond{width: 355px;height: 150px;overflow: auto;
    border:1px solid #666;margin: 10px 0;background-color: #f7f7f7}
fieldset#agree div#cond p{margin:0 5px 6px}
div#bottone{text-align: center}
input#exit{float:right;width: 40px;height: 20px;margin-right: 135px;margin-bottom: 10px;
		font-size: 9px;cursor: pointer}
input#insert{float:left;width: 40px;height: 20px;margin-right: 20px;margin-bottom: 10px;
		font-size: 9px;cursor: pointer}
input#go{float: left;width: 80px;height: 20px;margin-left: 20px;margin-bottom: 10px;
		font-size: 9px;cursor: pointer}
/*input#go{border:1px solid #666;background: #ACCDF6 url(sfondobottone.jpg) repeat-x}*/
</style>
<STYLE>
   /*  You can modify these styles to anything you want (or is allowed). */
   /* These are used by both browsers.  You can set these to your preferences. */
   .td1style  {font-weight:normal;border:1px solid black;background-color:lightgrey;color:blue;font-size:14px;fontFamily:Verdana;}
   .td2style  {font-weight:normal;border:1px solid black;background-color:lightyellow;font-size:12px;font-family:Verdana;}
</STYLE>
</head>
<body>


<?  /*Modulo connessione data base */
include("../../Accounts_MySql/datilogin.txt");
mysqli_set_charset($link, 'utf8');

echo "<br><br><b>Nome del file archivio</b><br><br>"; 
echo "<form name='savefile' method='post' action='bk_ld_db.php'>";
echo "<input type='text' name='nomefile'>";
echo "<input type='submit' name='bt_salva' value='Salva'>";
echo "</form>";

echo "<br><br><b>Seleziona archivio</b><br><br>"; 
echo "<form name='loadfile' method='post' action='bk_ld_db.php'>";
echo "<select name='nomeloadfile'>";
foreach(glob(dirname(__FILE__) . '/backup/*') as $filename){
$filename = basename($filename);
echo "<option value='" . $filename . "'>".$filename."</option>";
}
echo "<input type='submit' name='bt_carica' value='Carica'>";
echo "</form>";

if (isset($_POST['bt_salva'])) 
{
$nome_file = $_POST['nomefile'];
$comando = "mysqldump -u ".$userdb." -p".$passworddb." sardea > ./backup/".$nome_file.".sql";
system ($comando);
?>
<script type="text/javascript">
window.alert('Backup eseguito');
</script>
<?
}


if (isset($_POST['bt_carica'])) 
{
$nome_file = $_POST['nomeloadfile'];
$comando = "mysql -u ".$userdb." -p".$passworddb." sardea < ./backup/".$nome_file;
system ($comando);
?>
<script type="text/javascript">
window.alert('Caricamento eseguito');
</script>
<?
}


?>
