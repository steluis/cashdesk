<?//Controllo accesso
ini_set ('session.gc_maxlifetime','28800');
if ((substr($_SERVER['SERVER_ADDR'],0,7) != "192.168")&&(substr($_SERVER['SERVER_ADDR'],0,7) != "127.0.0")&&($_SERVER['SERVER_ADDR'] != "::1"))
ini_set("session.save_path","/web/htdocs/www.lambdaprogetti.it/home/tmp/");

session_start();
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past

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
/*
if (! role_check(ROLE_ADMIN))
{
	header('location:access_denied.php');
	exit;
}

*/
include "langsettings.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="-1">
<link href="cssnn.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<SCRIPT LANGUAGE = "JavaScript">
var dato = new Array();
function createRequestObject() {
	var ro;
	var browser = navigator.appName;
	if(browser == "Microsoft Internet Explorer"){
		ro = new ActiveXObject("Microsoft.XMLHTTP");
	}else{
		ro = new XMLHttpRequest();
	}
	return ro;
}

var http = createRequestObject();

/**************************************************************/
/*** QUESTA FUNZIONE RICHIEDE I DATI ALLO SCRIPT PHP        ***/
/**************************************************************/
function sndReq(action) {
    var fatturascelta= document.getElementById('fattura').value;
	http.open('get', 'cercainfofatture.php?parametro1='+fatturascelta);
	http.onreadystatechange = handleResponse;
	http.send(null);
}

/**************************************************************/
/***    GESTIONE DELLA RISPOSTA DALLO SCRIPT PHP            ***/
/**************************************************************/
function handleResponse() {
if(http.readyState == 4){
 var response = http.responseText;
 var update = new Array();

// La risposta data dal core è composta da due parti: prima del carattere | c'è
// un identificativo che andremo a mettere in update[0], dopo il carattere | 
// c'è il contenuto che metteremo in update[1].
		if(response.indexOf('|' != -1)) {
			update = response.split('|');
			for (i=1; i < update.length; i+=1)
			{
			 dato[i] = update[i];
			}
		}

	if (dato[1] != 0) {
	 window.alert("STAI ANNULLANDO L'ULTIMA FATTURA/RICEVUTA EMESSA\nSELEZIONA SE MANTENERE LA NUMERAZIONE PRECEDENTE O PROSEGUIRE CON LA NUOVA NUMERAZIONE");
	 document.getElementById('numerazione').disabled=false;
	 document.getElementById('numerazione').checked=true;
	 document.getElementById('scritta').style.color='#000000';
	}
	else
	{
	 document.getElementById('numerazione').disabled=true;
	 document.getElementById('numerazione').checked=false;
	 document.getElementById('scritta').style.color='#808080';
	}
	}
}
//-->
</SCRIPT>

<script type="text/javascript" src="calendar.js"></script>
<script type="text/javascript" src="calendar-it.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="skins/aqua/theme.css" title="Aqua" />


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

/* Stile tabella */
table.stat th, table.stat td {
  font-size : 77%;
  font-family : "Myriad Web",Verdana,Helvetica,Arial,sans-serif;
  background : #efe none; color : #630; }

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
    
#quadrato_sopra {position:absolute; left:101; top:1; width:90; height:90;}
#quadrato_sotto {position:absolute; left:101; top:1; width:90; height:90;}
#quadrato_centro1 {position:absolute; left:101; top:1; width:90; height:90;}
#quadrato_centro2 {position:absolute; left:101; top:1; width:90; height:90;}
#quadrato_centro3 {position:absolute; left:101; top:1; width:90; height:90;}
#quadrato_centro4 {position:absolute; left:101; top:1; width:90; height:90;}
#quadrato_centro5 {position:absolute; left:101; top:1; width:90; height:90;}
#quadrato_centro6 {position:absolute; left:101; top:1; width:90; height:90;}
#quadrato_centro7 {position:absolute; left:101; top:1; width:90; height:90;}
#quadrato_centro8 {position:absolute; left:101; top:1; width:90; height:90;}
#quadrato_centro9 {position:absolute; left:101; top:1; width:90; height:90;}
#quadrato_centro10 {position:absolute; left:101; top:1; width:90; height:90;}

#field {
 position: absolute;
 margin: 10px;
 padding: 1em;
 border: 1px solid #666;
 width: 440px;
 height: 50px;
 top: 80px;
 left: 0px;
}

#field h2 {
 display: inline;
 position: absolute;
 top: -13px;
 background: #fbfbf2;
 color: #008;
 margin: 0 3px;
 padding:2px;
 font:  bold 120% Georgia, serif;
}

#field2 {
 position: absolute;
 margin: 10px;
 padding: 1em;
 border: 1px solid #666;
 width: 440px;
 height: 110px;
 top: 110px;
 left: 0px;
}

#field2 h2 {
 display: inline;
 position: absolute;
 top: -13px;
 background: #fbfbf2;
 color: #008;
 margin: 0 3px;
 padding:2px;
 font:  bold 120% Georgia, serif;
}

#field3 {
 position: absolute;
 margin: 10px;
 padding: 1em;
 width: 800px;
 height: 10px;
 top: 380px;
 left: 0px;
}

#field3 h3 {
 display: inline;
 position: absolute;
 top: 20px;
 left: 0px;
 background: #fbfbf2;
 color: #008;
 margin: 0 3px;
 padding:2px;
 font:  bold 120% Georgia, serif;
}

</style>

</head>

<!--<body onLoad="tabella()" text="#333366">-->

<body>
<div ID="Titolo" style="font-size:32px;font-weight:bold">Cancella ricevuta o fattura</div>



<form name="premifiltra" method=post action="cancella_fattura.php"> 
<div style="position:absolute;left:20px;top:50px;font-weight:bold">MA</div>
<div style="position:absolute;left:20px;top:65px;">
<input type='checkbox' name='ma' id='ma'>
</div>
<div style="position:absolute;left:60px;top:50px;font-weight:bold">ME</div>
<div style="position:absolute;left:60px;top:65px;">
<input type='checkbox' name='me' id='me'>
</div>
<div style="position:absolute;left:100px;top:50px;font-weight:bold">GA</div>
<div style="position:absolute;left:100px;top:65px;">
<input type='checkbox' name='ga' id='ga'>
</div>
<div style="position:absolute;left:140px;top:50px;font-weight:bold">MI</div>
<div style="position:absolute;left:140px;top:65px;">
<input type='checkbox' name='mi' id='mi'>
</div>

<div style="position:absolute;left:180px;top:50px;font-weight:bold">Anno</div>
<div style="position:absolute;left:180px;top:65px;font-weight:bold">
<select style='font-size:80%;width:50px' name='anno' id='anno'>
<option value='2015' size=10 style='width:100px'>2015</option>
<option value='2014' size=10 style='width:100px'>2014</option>
<option value='2013' size=10 style='width:100px'>2013</option>
<option value='2012' size=10 style='width:100px'>2012</option>
</select>
</div>

<div style="position:absolute;left:260px;top:50px;font-weight:bold">Ric/Fatt</div>
<div style="position:absolute;left:260px;top:65px;font-weight:bold">
<select style='font-size:80%;width:80px' name='ricfat' id='ricfat'>
<option value='Ricevuta' size=10 style='width:100px'>Ricevuta</option>
<option value='Fattura' size=10 style='width:100px'>Fattura</option>
</select>
</div>

<div style="position:absolute;left:380px;top:50px;font-weight:bold">Acc./Saldi</div>
<div style="position:absolute;left:400px;top:65px;">
<input type='checkbox' name='acc' id='acc'>
</div>

<div style="position:absolute;left:40px;top:90px;font-weight:bold">
<input type="submit" value="Filtra" name="filtra" id="filtra">
</div>
</form>

<?
if (isset($_POST['filtra'])) {
$anno_sel = $_POST['anno'];
$ricfat = $_POST['ricfat'];
if ($ricfat == "Fattura") $rf="F";
if ($ricfat == "Ricevuta") $rf="R";

if (!isset($_POST['acc']))
{
 $query_1 = "SELECT * FROM giornale_magazzino WHERE ";
 $query_2 .= "";
 if (isset($_POST['ga'])) {
 $query_2 .= "(substring(fattura,2,2) LIKE 'GA') AND ";
 }
 if (isset($_POST['ma'])) {
 $query_2 .= "(substring(fattura,2,2) LIKE 'MA') AND ";
 }
 if (isset($_POST['me'])) {
 $query_2 .= "(substring(fattura,2,2) LIKE 'ME') AND ";
 }
 if (isset($_POST['mi'])) {
 $query_2 .= "(substring(fattura,2,2) LIKE 'MI') AND ";
 }
 $query_3 .= "(substring(fattura,-4,4) LIKE '".$anno_sel."') AND (substring(fattura,1,1) LIKE '".$rf."')";
 $query_finale = $query_1.$query_2.$query_3;
 $query = $query_finale." GROUP BY fattura ORDER BY id DESC";
}
else
{
 $query_1 = "SELECT * FROM comesse WHERE ";
 $query_2 .= "";
 if (isset($_POST['ga'])) {
 $query_2 .= "((substring(numero_fattura_saldo,2,2) LIKE 'GA') OR (substring(numero_fattura_acc1,2,2) LIKE 'GA') OR (substring(numero_fattura_acc2,2,2) LIKE 'GA') OR (substring(numero_fattura_acc3,2,2) LIKE 'GA')) AND ";
 }
 if (isset($_POST['ma'])) {
 $query_2 .= "((substring(numero_fattura_saldo,2,2) LIKE 'MA') OR (substring(numero_fattura_acc1,2,2) LIKE 'MA') OR (substring(numero_fattura_acc3,2,2) LIKE 'MA') OR (substring(numero_fattura_acc3,2,2) LIKE 'MA')) AND ";
 }
 if (isset($_POST['me'])) {
 $query_2 .= "((substring(numero_fattura_saldo,2,2) LIKE 'ME') OR (substring(numero_fattura_acc1,2,2) LIKE 'ME') OR (substring(numero_fattura_acc2,2,2) LIKE 'ME') OR (substring(numero_fattura_acc3,2,2) LIKE 'ME')) AND ";
 }
 if (isset($_POST['mi'])) {
 $query_2 .= "((substring(numero_fattura_saldo,2,2) LIKE 'MI') OR (substring(numero_fattura_acc1,2,2) LIKE 'MI') OR (substring(numero_fattura_acc2,2,2) LIKE 'MI') OR (substring(numero_fattura_acc3,2,2) LIKE 'MI')) AND ";
 }
 $query_3 .= "((substring(numero_fattura_saldo,-4,4) LIKE '".$anno_sel."') OR (substring(numero_fattura_acc1,-4,4) LIKE '".$anno_sel."') OR (substring(numero_fattura_acc2,-4,4) LIKE '".$anno_sel."') OR (substring(numero_fattura_acc3,-4,4) LIKE '".$anno_sel."')) AND ((substring(numero_fattura_saldo,1,1) LIKE '".$rf."') OR (substring(numero_fattura_acc1,1,1) LIKE '".$rf."') OR (substring(numero_fattura_acc2,1,1) LIKE '".$rf."') OR (substring(numero_fattura_acc3,1,1) LIKE '".$rf."'))";
 $query_finale = $query_1.$query_2.$query_3;
 $query = $query_finale." GROUP BY numero_fattura_saldo,numero_fattura_acc1,numero_fattura_acc2,numero_fattura_acc3 ORDER BY id DESC";
}
}
$query_finale = $query_1.$query_2;
 echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br>Query finale = ".$query_finale."<br>";
?>

<form name="cerca" method=post action="cancella_fattura.php"> 
<div ID="produttore_text" style="position:absolute;left:20px;top:130px;font-weight:bold">Fattura/Ricevuta</div>
<div ID="produttore_id" style="position:absolute;left:20px;top:145px;">
<?
 include("../../dati_Login/dati.txt");
 $link=mysql_connect("$serverdb","$userdb","$passworddb")
 or die("Non riesco a connettermi a <b>$serverdb"); 
 mysql_select_db ($basedb) or die ("Non riesco a selezionare il db $basedb<br>"); 
 echo "<select style='font-size:80%;width:150px' name='fattura' id='fattura' onchange=sndReq('ciao')>";
 echo "<option value='' size=10 style='width:100px'></option>";
// $query = "SELECT * FROM giornale_magazzino GROUP BY fattura ORDER BY substring(fattura,-4,4) DESC,substring(fattura,1,7)";
//$query = $query_finale." ORDER BY id DESC";

 $result = mysql_query($query, $link);
 while( $row=mysql_fetch_array($result) ){
 if ($row['fattura']<>'')
  echo "<option value='".$row['fattura']."' size=10 style='width:100px'>".$row['fattura']."</option>";
 if ($row['numero_fattura_saldo']<>'')
  echo "<option value='".$row['numero_fattura_saldo']."' size=10 style='width:100px'>".$row['numero_fattura_saldo']."</option>";
 if ($row['numero_fattura_acc1']<>'')
  echo "<option value='".$row['numero_fattura_acc1']."' size=10 style='width:100px'>".$row['numero_fattura_acc1']."</option>";
 if ($row['numero_fattura_acc2']<>'')
  echo "<option value='".$row['numero_fattura_acc2']."' size=10 style='width:100px'>".$row['numero_fattura_acc2']."</option>";
 if ($row['numero_fattura_acc3']<>'')
  echo "<option value='".$row['numero_fattura_acc3']."' size=10 style='width:100px'>".$row['numero_fattura_acc3']."</option>";
 }
?>
</select>
</div>
<div id="scritta" style="position:absolute;left:220px;top:145px;font-weight:bold;color:#808080">Mantieni numerazione</div>
<div style="position:absolute;left:190px;top:145px;">
<input type='checkbox' name='numerazione' id='numerazione' disabled='enabled'>
</div>

<div style="position:absolute;left:40px;top:200px;font-weight:bold">
<input type="submit" value="Elimina" name="elimina" id="elimina">
</div>
</form>
<?
/*** INIZIO PROCEDURA DI ELIMINAZIONE FATTURA/RICEVUTE ***/
if (isset($_POST['elimina'])) {
$fattura = $_POST['fattura'];
if (strlen($fattura) >= 4)
{ // Se una fattura è stata selezionata
if (isset($_POST['numerazione'])) $numerazione = 'on';
else $numerazione = 'off';

$sede_fattura = substr($fattura,1,2);
$tipo = substr($fattura,0,1);

/*** AGGIORNAMENTO INDICI DI FATTURA E RICEVUTA ***/
if ($numerazione == 'on')
{
//echo "Utilizzo la vecchia numerazione";
if ($tipo == 'R')
{
 if ($sede_fattura == 'ME')
 {
  $query = "UPDATE indici SET valore=valore-1 WHERE nome LIKE 'incrementale_ricevute_mestre'";
//echo "Query1 update indici = ".$query."<br>";  
  $result = mysql_query($query, $link);
 }
 if ($sede_fattura == 'MA')
 {
  $query = "UPDATE indici SET valore=valore-1 WHERE nome LIKE 'incrementale_ricevute_martellago'";
//echo "Query2 update indici = ".$query."<br>";  
  $result = mysql_query($query, $link);
 }
 if ($sede_fattura == 'GA')
 {
  $query = "UPDATE indici SET valore=valore-1 WHERE nome LIKE 'incrementale_ricevute_gardigiano'";
//echo "Query2 update indici = ".$query."<br>";  
  $result = mysql_query($query, $link);
 }
 if ($sede_fattura == 'MI')
 {
  $query = "UPDATE indici SET valore=valore-1 WHERE nome LIKE 'incrementale_ricevute_mirano'";
//echo "Query2 update indici = ".$query."<br>";  
  $result = mysql_query($query, $link);
 }
}

if ($tipo == 'F')
{
 if ($sede_fattura == 'ME')
 {
  $query = "UPDATE indici SET valore=valore-1 WHERE nome LIKE 'incrementale_fatture_mestre'";
//echo "Query3 update indici = ".$query."<br>";  
  $result = mysql_query($query, $link);
 }
 if ($sede_fattura == 'MA')
 {
  $query = "UPDATE indici SET valore=valore-1 WHERE nome LIKE 'incrementale_fatture_martellago'";
//echo "Query4 update indici = ".$query."<br>";  
  $result = mysql_query($query, $link);
 }
 if ($sede_fattura == 'GA')
 {
  $query = "UPDATE indici SET valore=valore-1 WHERE nome LIKE 'incrementale_fatture_gardigiano'";
//echo "Query4 update indici = ".$query."<br>";  
  $result = mysql_query($query, $link);
 }
 if ($sede_fattura == 'MI')
 {
  $query = "UPDATE indici SET valore=valore-1 WHERE nome LIKE 'incrementale_fatture_mirano'";
//echo "Query4 update indici = ".$query."<br>";  
  $result = mysql_query($query, $link);
 }
}
}
/*** fine aggiornamento indici di fattura e ricevuta ***/

/*** AGGIORNAMENTO DELLE QUANTITA' E RIMOZIONE DAL GIORNALE MAGAZZINO ***/
$query = "SELECT * FROM giornale_magazzino WHERE fattura LIKE '".$fattura."'";
$result = mysql_query($query, $link);
//echo "Query giornale: ".$query."<br>";
while( $row=mysql_fetch_array($result) ){
$prodotto = $row['codice'];
$qta = $row['qta'];
//$consegnata = $row['qta_consegnata'];
$commissione = $row['rif_copia_commissione'];
$delta_mart = $row['vecchio_mart']-$row['nuovo_mart'];
$delta_ma = $row['vecchio_ma']-$row['nuovo_ma'];
$delta_me = $row['vecchio_me']-$row['nuovo_me'];
$delta_out = $row['vecchio_out']-$row['nuovo_out'];

if ($delta_mart <> 0) $sede_iniziale="MA";
else
if ($delta_me <> 0) $sede_iniziale="ME";
else
if ($delta_out <> 0) $sede_iniziale="OUT";
else
$sede_iniziale="GA";

$query6 = "SELECT * FROM comesse WHERE codice_commessa LIKE '".$commissione."'";
$result6 = mysql_query($query6, $link);
$row6=mysql_fetch_array($result6);
$consegnata = $row6['qta_consegnata'];

$query2 = "SELECT * FROM magazzino WHERE codice LIKE '".$prodotto."'";
//echo "Query2 ".$query2."<br>";
$result2 = mysql_query($query2, $link);
$row2=mysql_fetch_array($result2);
//$sede_iniziale = substr($fattura,1,2);
if ($sede_iniziale == 'MA')
$mag = $row2['qta_mart'];
else
if ($sede_iniziale == 'ME')
$mag = $row2['qta_me'];
else
if ($sede_iniziale == 'OUT')
$mag = $row2['qta_out'];
else
$mag = $row2['qta_ma'];

$prenotata2 = $row2['qta_prenotata'];
if ($commissione <> 'banco')
$nuova_prenotata = $prenotata2 + $qta;
else 
$nuova_prenotata = $prenotata2;

$nuovo_mag = $mag + $qta;
$nuova_consegnata = $consegnata-$qta;
//echo "Nuova consegnata = ".$nuova_consegnata."<br>";


if ($nuova_consegnata < 0) $nuova_consegnata = 0;
if ($sede_iniziale == 'MA')
$query3 = "UPDATE magazzino SET qta_prenotata='".$nuova_prenotata."',qta_mart='".$nuovo_mag."' WHERE codice LIKE '".$prodotto."'";
else
if ($sede_iniziale == 'ME')
$query3 = "UPDATE magazzino SET qta_prenotata='".$nuova_prenotata."',qta_me='".$nuovo_mag."' WHERE codice LIKE '".$prodotto."'";
else
if ($sede_iniziale == 'OUT')
$query3 = "UPDATE magazzino SET qta_prenotata='".$nuova_prenotata."',qta_out='".$nuovo_mag."' WHERE codice LIKE '".$prodotto."'";
else
$query3 = "UPDATE magazzino SET qta_prenotata='".$nuova_prenotata."',qta_ma='".$nuovo_mag."' WHERE codice LIKE '".$prodotto."'";

$result3 = mysql_query($query3, $link);
$query3 = "UPDATE comesse SET qta_consegnata='".$nuova_consegnata."',tutto_consegnato=0,numero_fattura='',data_consegna_effettiva='0000-00-00',caparra_tolta_in_fattura=0 WHERE codice_prodotto LIKE '".$prodotto."' AND codice_commessa LIKE '".$commissione."'";
$result3 = mysql_query($query3, $link);
}
/*** fine aggiornamento quantità ***/

$query4 = "DELETE FROM giornale_magazzino WHERE fattura LIKE '".$fattura."'";
$result4 = mysql_query($query4, $link);
$query4 = "DELETE FROM venditabanco WHERE fattura LIKE '".$fattura."'";
$result4 = mysql_query($query4, $link);
$data_di_oggi = date("Y-m-d");

if ($numerazione == 'off')
{
 $query5 = "INSERT INTO fatture_annullate (numero_fattura,data) VALUES ('".$fattura."','".$data_di_oggi."')";
 $dati5 = mysql_query($query5, $link); 
}

?>
<script type="text/javascript">
window.alert("Fattura rimossa");
</script>
<?
} // FINE Se una fattura è stata selezionata
else
{
 ?>
 <script type="text/javascript">
 window.alert("Selezionare una ricevuta o fattura");
 </script>
 <?
}
}
/*** FINE PROCEDURA DI ELIMINAZIONE FATTURA/RICEVUTE ***/

if ($fattura <> '')
/*** RIMOZIONE DELLA FATTURA DA QUELLE A SALDO ***/
{
 $query = "UPDATE comesse SET saldo='0.00',numero_fattura_saldo='',data_saldo='0000-00-00',imp_saldo_iva1='0.00',saldo_iva1='0.00',imp_saldo_iva2='0.00',saldo_iva2='0.00' WHERE numero_fattura_saldo='".$fattura."'";
 $result = mysql_query($query, $link);
// echo "Query = ".$query."<br>";
/*** ***/

/*** RIMOZIONE DELLA FATTURA DA QUELLE IN ACCONTO1 ***/
$query = "UPDATE comesse SET acconto='0.00',iva_acconto='0.00',numero_fattura_acc1='',data_acconto='0000-00-00' WHERE numero_fattura_acc1='".$fattura."'";
$result = mysql_query($query, $link);
/*** ***/

/*** RIMOZIONE DELLA FATTURA DA QUELLE IN ACCONTO2 ***/
$query = "UPDATE comesse SET acconto2='0.00',iva_acconto2='0.00',numero_fattura_acc2='',data_acconto2='0000-00-00' WHERE numero_fattura_acc2='".$fattura."'";
$result = mysql_query($query, $link);
/*** ***/

/*** RIMOZIONE DELLA FATTURA DA QUELLE IN ACCONTO3 ***/
$query = "UPDATE comesse SET acconto3='0.00',iva_acconto3='0.00',numero_fattura_acc3='',data_acconto3='0000-00-00' WHERE numero_fattura_acc3='".$fattura."'";
$result = mysql_query($query, $link);
/*** ***/
}
?>
</body>
</html>
