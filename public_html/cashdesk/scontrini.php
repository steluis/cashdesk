<?
/*******************************************************************************
* CASH DESK - GENERATE RECEIPT AND PRINT IT                                    *
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
$larghezza=18; //28 per quella grande

if (!function_exists(bcmul)) {
 function bcmul ($_ro, $_lo, $_scale=0) {
  return round($_ro*$_lo, $_scale);
 }
}

if (!function_exists(bcadd)) {
 function bcadd ($_ro, $_lo, $_scale=0) {
  return round($_ro+$_lo, $_scale);
 }
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>

<title>Prepare receipt</title>

    <script type="text/javascript" charset="utf-8">
    function disableTimeout()
    {
    setTimeout(disableTheButton, 1);
    }
    function disableTheButton(){
    	var txt1="Id: " + document.getElementById("once").id
			txt1=txt1 + ", type: " + document.getElementById("once").type
			txt1=txt1 + ", value: " + document.getElementById("once").value
			document.getElementById("once").disabled=true
		
    //document.getElementById('once').disabled = true;
    }
    
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
	var http1 = createRequestObject();
	var http2 = createRequestObject();
	var http3 = createRequestObject();
	var costo_asporto = 0;
    
    function sndReq(action) {  // Funzione che chiama uno script PHP ed attende la risposta
	http.open('get', 'leggi_importi.php?action='+action);
	http.onreadystatechange = handleResponse;
	http.send(null);
	}

    function sndReq1(action) {  // Funzione che chiama uno script PHP ed attende la risposta
	http1.open('get', 'leggi_asporto.php?action='+action);
	http1.onreadystatechange = handleResponse1;
	http1.send(null);
	}


    function handleResponse() {
	if(http.readyState == 4){
		var response = http.responseText;
		var update = new Array();
		var valore = 0.0;
		var cavolo = 0.0;
		var totale = 0.0;
		var importo = 0.0
		var importo_ic = 0.0
		var importo_mat = 0.0
		var totale_mat = 0.0
		var totale_ic = 0.0
		totale = 0;
		cavolo = 0;
// La risposta data dal server e' composta da una serie di valori pari agli importi degli item, divisi dal carattere | 
		if(response.indexOf('|' != -1)) {
			update = response.split('|');
			for (i=0; i < update.length; i++)
			{
			importo = document.formola.elements[i*5+2].value*update[i];
			importo = parseFloat(importo);
			document.formola.elements[i*5+4].value = importo.toFixed(2);
 			valore = parseFloat(update[i]);
			document.formola.elements[i*5+3].value = valore.toFixed(2);
			totale = totale + importo;
			}
		}
	}
totale=totale+costo_asporto;
totale = Math.round(totale*100)/100;
totale = totale.toFixed(2);
document.formola.totale.value = totale;
}


    function handleResponse1() {
	if(http1.readyState == 4){
		var response = http1.responseText;
		var update = new Array();
		var valore = 0.0;
		var totale = 0.0;
		var importo = 0.0
		totale = 0;
		totale_prec = 0;
		cavolo = 0;
// La risposta data dal server e' composta da una serie di valori pari agli importi degli item, divisi dal carattere | 
		if(response.indexOf('|' != -1)) {
			update = response.split('|');
			for (i=0; i < update.length; i++)
			{
 			 valore = parseFloat(update[i]);
			}
		}
		totale_prec = document.formola.totale.value;
		totale_prec = Math.round(totale_prec*100)/100; // Added
		if (document.formola.asporto.checked == 1)
		{
		 totale = totale_prec + valore;
		 costo_asporto = valore;
		}
		else
		{
		 totale = totale_prec - valore;
		 costo_asporto = 0;
		}
		
		totale = Math.round(totale*100)/100; // Added
		totale = totale.toFixed(2);
		document.formola.totale.value = totale;
	}
}


function sndReq2(codec,prodid,vendid,stringa_scontrino,stringa_asporto,coperti,stringa_prt,logo,intestazione) {  
// Funzione che chiama uno script PHP ed attende la risposta
	var vars = "codec="+codec+"&prodid="+prodid+"&vendid="+vendid+"&stringa_scontrino="+stringa_scontrino+"&stringa_asporto="+stringa_asporto+"&coperti="+coperti+"&stringa_prt="+stringa_prt+"&logo="+logo+"&intestazione="+intestazione;
	http2.open('post', 'stampantina.php');
//	window.alert('Chiamata per = '+prodid+":"+vendid);
	http2.setRequestHeader ("Content-type", "application/x-www-form-urlencoded");
	http2.onreadystatechange = handleResponse2;
	http2.send(vars);
}


function handleResponse2() {
//	window.alert('RITORNO');
	}



   /*  The following three variables are for setting the properties of your table containing the tool tip */ 
    var tborder="0"
    var cspace="0"
    var cpad="0"
    var msgtop=200     // Set the top position of the div
    var msgleft=200     // Set the left position of the div
    var boxt=""



    </script> 
		
		
<link href="cssnn.css" rel="stylesheet" type="text/css">

<style type="text/css">
body{font:12px verdena,arial,sans-serif;background-color: #ffffff;}
div#container{width: 500px;padding: 10px;margin: 0px auto;
background-color: #FFF;text-align: left}
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
<!-- Impostare qui sotto la posizione della messagebox -->
   <div id="formbox" style="position:absolute;visibility:hidden;height:200;width:400"></div>

<?  /*Modulo connessione data base */
include("../../Accounts_MySql/datilogin.txt");
mysqli_set_charset($link,'utf8');

if (isset($_POST['ola_request'])) 
{
	$coperti = $_POST['coperti'];
	$asporto = $_POST['asporto'];
	$importo_ricevuto = $_POST['importo_ricevuto'];
	$numero_scontrino = null;

	$query = "SELECT * FROM listino WHERE descrizione LIKE 'ESPORTAZIONE'";
	$result = mysqli_query($link,$query);
	$row=mysqli_fetch_array($result);
	$costo_asporto = $row['importo'];	

// Individua se bar e/o cucina
$cucina=false;
$bar=false;
$query = "SELECT * FROM listino";
$result = mysqli_query($link,$query);
while($row=mysqli_fetch_assoc($result)){
 $qty = $_POST['qty'.$row['id']];
 $note = $_POST['note'.$row['id']];
 if ($qty <> 0){
  $query2 = "SELECT * FROM listino WHERE id LIKE ".$row['id'];
  $result2 = mysqli_query($link,$query2);
  $row2 = mysqli_fetch_assoc($result2);
  $tipo_piatto = $row2['tipo_piatto'];
  if (($tipo_piatto == "primo")||($tipo_piatto == "secondo")||($tipo_piatto == "contorno"))
   $cucina = true;
  if ($tipo_piatto == "bar") 
   $bar=true;
 }
}

$query = "LOCK TABLES parametri WRITE";
$result = mysqli_query($link,$query);
$query = "SELECT * FROM parametri WHERE descrizione LIKE 'numero_scontrino'";
$result = mysqli_query($link,$query);
$row=mysqli_fetch_assoc($result);
$numero_scontrino = $row['valore'];

$query = "SELECT * FROM parametri WHERE descrizione LIKE 'scontrino_bar'";
$result = mysqli_query($link,$query);
$row=mysqli_fetch_assoc($result);
$scontrino_bar = $row['valore'];

if ($cucina) 
{
 $numero_scontrino++;
 $query = "UPDATE parametri SET valore='".$numero_scontrino."' WHERE descrizione LIKE 'numero_scontrino'";
 $result = mysqli_query($link,$query);
 $stringa_scontrino = $numero_scontrino;
}

if (($bar)&&(!$cucina)) 
{
 $scontrino_bar++;
 $query = "UPDATE parametri SET valore='".$scontrino_bar."' WHERE descrizione LIKE 'scontrino_bar'";
 $result = mysqli_query($link,$query);
 $stringa_scontrino = $scontrino_bar."B";
}

$query = "UNLOCK TABLES";
$result = mysqli_query($link,$query);

if (($scontrino_bar == null)||($numero_scontrino == null)) {
	?><script type="text/javascript">
        window.alert('Attenzione!!\nIdentificativo scontrino non valido');
 	</script>
	<?
}

$query = "SELECT * FROM listino";
$result = mysqli_query($link,$query);
while($row=mysqli_fetch_assoc($result)){
 $qty = $_POST['qty'.$row['id']];
 $note = $_POST['note'.$row['id']];
 if ($qty <> 0){
  $query2 = "SELECT * FROM listino WHERE id LIKE ".$row['id'];
  $result2 = mysqli_query($link,$query2);
  $row2 = mysqli_fetch_assoc($result2);
  $descrizione = $row2['descrizione'];
  $importo_totale = bcmul($qty, $row2['importo'], 2);
  $data = date("Y-m-d");
  $utente = $_SESSION['userid'];
  $ora = date("H:i:s");
  $tipo_piatto = $row2['tipo_piatto'];
  $buffer="INSERT INTO scontrini (coperti, scontrino,
				 descrizione,
				 qta,
				 importo,
				 tipo_piatto,
				 cassiere,
				 data,
				 ora,
				 note)VALUES(\"".$coperti."\",\"".$stringa_scontrino."\",
				\"".$descrizione."\",
				\"".$qty."\",
				\"".$importo_totale."\",
				\"".$tipo_piatto."\",
				\"".$utente."\",
				\"".$data."\",
				\"".$ora."\",
				\"".$note."\")";
  $result3 = mysqli_query($link,$buffer);
 }
}

if ($asporto == "on"){
		 $buffer="INSERT INTO scontrini (coperti, scontrino,
						 descrizione,
						 qta,
  						 importo,
  						 tipo_piatto,
  						 cassiere,
  						 data,
  						 ora,
						 note)VALUES(\"".$coperti."\",\"".$stringa_scontrino."\",
  						\"ESPORTAZIONE\",
  						\"1\",
  						\"".$costo_asporto."\",
  						\"secondo\",
  						\"".$utente."\",
  						\"".$data."\",
  						\"".$ora."\",
						\"".$note."\")";
 $result3 = mysqli_query($link,$buffer);
}


// Inizio procedura di stampa
$coda = "SELECT * FROM parametri WHERE descrizione LIKE 'logo'";
$dati = mysqli_query ($link,$coda);
$row=mysqli_fetch_assoc($dati);
$logo = $row['valore'];
$coda = "SELECT * FROM parametri WHERE descrizione LIKE 'intestazione'";
$dati = mysqli_query ($link, $coda);
$row=mysqli_fetch_assoc($dati);
$intestazione = $row['valore'];

$coda = "SELECT * FROM parametri WHERE descrizione LIKE 'intestazione2'";
$dati = mysqli_query ($link, $coda);
$row=mysqli_fetch_assoc($dati);
$intestazione2 = $row['valore'];

$coda = "SELECT * FROM parametri WHERE descrizione LIKE 'abilita_stampa_cucina'";
$dati = mysqli_query ($link,$coda);
$row=mysqli_fetch_assoc($dati);
$abilita_stampa_cucina = $row['valore'];

	
require('./fpdf/fpdf.php');

$query = "SELECT * FROM stampanti WHERE cucina LIKE '1'";
$result = mysqli_query($link, $query);
$row=mysqli_fetch_assoc($result);
$nome_stampante_cucina = $row['nome'];
$formato_carta_cucina = $row['formatocarta'];
$tipo_stampante_cucina = $row['tipo'];
$id_stampante_cucina = $row['id'];
$prodid_stampante_cucina = "0x".$row['prodID'];
$vendid_stampante_cucina = "0x".$row['vendID'];
$codec_cucina = $row['codec'];
$prodid_stampante_cucina_dec = hexdec($row['prodID']);
$vendid_stampante_cucina_dec = hexdec($row['vendID']);
$connessione_stampante_cucina = $row['connessione'];
$ip_stampante_cucina = $row['ip'];

$query = "SELECT * FROM stampanti WHERE nome LIKE '".$_SESSION['stampante']."'";
$result = mysqli_query($link, $query);
$row=mysqli_fetch_assoc($result);
$formato_carta_cassa = $row['formatocarta'];
$tipo_stampante_cassa = $row['tipo'];
$nome_stampante_cassa = $row['nome'];
$id_stampante_cassa = $row['id'];
$prodid_stampante_cassa = "0x".$row['prodID'];
$vendid_stampante_cassa = "0x".$row['vendID'];
$codec_cassa = $row['codec'];
$prodid_stampante_cassa_dec = hexdec($row['prodID']);
$vendid_stampante_cassa_dec = hexdec($row['vendID']);
$connessione_stampante_cassa = $row['connessione'];
$ip_stampante_cassa = $row['ip'];

if ($asporto == "on")
$stringa_asporto = "ASPORTO";

$ritorno = stampa('A5',$stringa_scontrino,$intestazione,$intestazione2,$asporto,$coperti,$data,$ora,$cucina,$bar,$importo_ricevuto);
$resto = $ritorno[0];
$stringa_prt = $ritorno[1];
$str_enc = urlencode ($stringa_prt);

$data_s = date("d-m-Y");
$nome_scontrino = "scontrino".$stringa_scontrino;
$nome_file = "./scontrini/".$stringa_scontrino."_".$data_s;
$testo_da_scaricare = $stringa_scontrino."\n".$stringa_asporto."\n".$coperti."\n".$stringa_prt;
file_put_contents($nome_file,$testo_da_scaricare,LOCK_EX);
chmod ($nome_file,0777);
if ($cucina) 
{

 // Stampa in cassa	 
  if ($tipo_stampante_cassa != 'ESC-POS')
  {
   $comando = "lp -d ".$tipo_stampante_cassa." ./scontrini/".$nome_scontrino."_".$formato_carta_cassa.".pdf";
   exec($comando);
  }
  else
  {
    $comando = "sudo ./stampa.bash \"".$stringa_scontrino."\" \"".$stringa_asporto."\" \"".$coperti."\" \"".$stringa_prt."\" \"stampascontrino.py\" \"".$intestazione."\" ".$prodid_stampante_cassa_dec." ".$vendid_stampante_cassa_dec." \"".$id_stampante_cassa."\" > /dev/null 2>&1 &";
    exec($comando);
  }

 // Stampa in cucina
 if ($abilita_stampa_cucina == 1)
 { 
  if ($tipo_stampante_cucina != 'ESC-POS')
  {
   $comando = "lp -d ".$tipo_stampante_cucina." ./scontrini/".$nome_scontrino."_".$formato_carta_cucina.".pdf";
   exec($comando);
  }
  else
  {
    $comando = "sudo ./stampa.bash \"".$stringa_scontrino."\" \"".$stringa_asporto."\" \"".$coperti."\" \"".$stringa_prt."\" \"stampascontrino.py\" \"".$intestazione."\" ".$prodid_stampante_cucina_dec." ".$vendid_stampante_cucina_dec." \"".$id_stampante_cucina."\" > /dev/null 2>&1 &";
    exec($comando);
  }
 }
 else
 {
  ?><script type="text/javascript">
  window.alert('Stampante cucina disabilitata');
  </script>
  <?
 }

}

if ($bar&&!$cucina) 
{
  if ($tipo_stampante_cassa != 'ESC-POS')
  {
   // Stampa solo in cassa
   $comando = "lp -d ".$tipo_stampante_cassa." ./scontrini/".$nome_scontrino."_".$formato_carta_cassa.".pdf";
   exec($comando);
  }
  else
  {
    $comando = "sudo ./stampa.bash \"".$stringa_scontrino."\" \"".$stringa_asporto."\" \"".$coperti."\" \"".$stringa_prt."\" \"stampascontrino.py\" \"".$intestazione."\" ".$prodid_stampante_cassa_dec." ".$vendid_stampante_cassa_dec." \"".$id_stampante_cassa."\" > /dev/null 2>&1 &";
    exec($comando);
  }
}

if ($resto >=0) {
  ?><script type="text/javascript">
  window.alert('Emesso scontrino numero\n <?echo $stringa_scontrino; ?> \n Resto <?echo "€ ".$resto; ?>');
  </script>
  <?
}
else
{
  ?><script type="text/javascript">
  window.alert('Emesso scontrino numero\n <?echo $stringa_scontrino; ?> \n');
  </script>
  <?
}


}  //Fine ola_request

?>
	
<caption><strong> Inserire i dati dello scontrino</strong></caption>

<form name="formola" method="post" action="<?echo $PATH_INFO;?>"> 

<div id="content">
<INPUT type='hidden' name='nonconto'> <!-- Serve solo per far tornare il numero degli oggetti allo javascript che riempie i campi importo -->

<div id="tabella">
<table valign="top">
<tr valign="top">
<td>
 <table id="tabella1" valign="top">
 <thead> 
 <tr class="odd">
  <th>Descrizione</th> 
  <th>Qta</th> 
  <th>Prezzo</th>
  <th>TOT</th>
  <th>Note</th>
 </tr>
 </thead>

<tbody>
<?
$query = "SELECT * FROM listino ORDER BY id";
$result = mysqli_query($link,$query);
$num_elementi = mysqli_num_rows($result);
$riga=1;
$colore1="#ffffcf";
$colore2="#ffff99";
	
while ($row=mysqli_fetch_assoc($result)) 
 {
  if ($row['disponibile'] == 1){
  if ($row['posizione'] == 'alto'){
   $colore1="#ffffcf";
   $colore2="#ffff99";
  }
  if ($row['posizione'] == 'basso'){
   $colore1 = "#cfffff";
   $colore2 = "#99ffff";
  } 	   
  echo "<tr>";
  echo "<th>";
  echo "<input readonly type='text' style=\"font-size:80%;text-align:center;background-color:$colore1\" size='22' name=\"".$row['descrizione']."\" value=\"".$row['descrizione']."\">";
  echo "</th>";
  echo "<th>";
  echo "<select onchange=\"sndReq(".$row['id'].")\" style='font-size:80%;width:40px;background-color:$colore1' WIDTH='40' name=\"qty".$row['id']."\">";
?> 
   <option value="0" size="10">0</option>
   <option value="1" size="10">1</option>
   <option value="2" size="10">2</option>
   <option value="3" size="10">3</option>
   <option value="4" size="10">4</option>
   <option value="5" size="10">5</option>
   <option value="6" size="10">6</option>
   <option value="7" size="10">7</option>
   <option value="8" size="10">8</option>
   <option value="9" size="10">9</option>
   <option value="10" size="10">10</option>
   <option value="11" size="10">11</option>
   <option value="12" size="10">12</option>
   <option value="13" size="10">13</option>
   <option value="14" size="10">14</option>
   <option value="15" size="10">15</option>
  </select>
<?
  echo "</th>";
  echo "<th>";
  echo "<input readonly type='text' style=\"font-size:80%;text-align:center;background-color:$colore1\" size='4' name=\"importo".$row['id']."\" value=0>";
  echo "</th>";
  echo "<th>";
  echo "<input readonly type='text' style=\"font-size:80%;text-align:center;background-color:$colore2\" size='6' name=\"totale".$row['id']."\" value=0>";
  echo "</th>";
  echo "<th>";
  echo "<input type='text' style=\"font-size:80%;text-align:left\" size='20' name=\"note".$row['id']."\" value=''>";
  echo "</th>";
  echo "</tr>";
 }
 else
 {
  echo "<tr>";
  echo "<th>";
  echo "<input disabled type='text' style=\"font-size:80%;text-align:center;text-decoration:line-through;background-color:$colore1\" size='22' name=\"".$row['descrizione']."\" value=\"".$row['descrizione']."\">";
  echo "</th>";
  echo "<th>";
  echo "<select disabled onchange=\"sndReq(".$row['id'].")\" style='font-size:80%;width:40px;text-decoration:line-through;background-color:$colore1' WIDTH='40' name=\"qty".$row['id']."\">";
?> 
  <option value="0" size="10">0</option>
  <option value="1" size="10">1</option>
  <option value="2" size="10">2</option>
  <option value="3" size="10">3</option>
  <option value="4" size="10">4</option>
 </select>
<?
  echo "</th>";
  echo "<th>";
  echo "<input disabled type='text' style=\"font-size:80%;text-align:center;text-decoration:line-through;background-color:$colore1\" size='4' name=\"importo".$row['id']."\" value=0>";
  echo "</th>";
  echo "<th>";
  echo "<input disabled type='text' style=\"font-size:80%;text-align:center;background-color:$colore2;text-decoration:line-through\" size='6' name=\"totale".$row['id']."\" value=0>";
  echo "</th>";
  echo "<th>";
  echo "<input disabled type='text' style=\"font-size:80%;text-align:center;text-decoration:line-through\" size='20' name=\"note".$row['id']."\" value=''>";
  echo "</th>";
  echo "</tr>";
 }
 $riga++;
  if ($riga>($num_elementi/2)) break;
 }
echo "</tbody>";
echo "</table>";
echo "</td>";
	
echo "<td>";
?>
<!-- Seconda colonna -->
<table id="tabella1">
<thead> 
<tr class="odd">
<th>Descrizione</th> 
<th>Qta</th> 
<th>Prezzo</th>
<th>TOT</th>
<th>Note</th>
</tr>
</thead>
<tbody>
<?
while($row=mysqli_fetch_array($result)) 
 {
  if ($row['posizione'] == 'alto'){
   $colore1="#ffffcf";
   $colore2="#ffff99";
  }
  if ($row['posizione'] == 'basso'){
   $colore1 = "#cfffff";
   $colore2 = "#99ffff";
  } 	   
  if ($row['disponibile'] == 1){
   echo "<tr>";
   echo "<th>";
   echo "<input readonly type='text' style=\"font-size:80%;text-align:center;background-color:$colore1\" size='22' name=\"".$row['descrizione']."\" value=\"".$row['descrizione']."\">";
   echo "</th>";
   echo "<th>";
   echo "<select onchange=\"sndReq(".$row['id'].")\" style='font-size:80%;width:40px;background-color:$colore1' WIDTH='40' name=\"qty".$row['id']."\">";
?> 
	 <option value="0" size="10">0</option>
 	 <option value="1" size="10">1</option>
 	 <option value="2" size="10">2</option>
 	 <option value="3" size="10">3</option>
 	 <option value="4" size="10">4</option>
 	 <option value="5" size="10">5</option>
 	 <option value="6" size="10">6</option>
 	 <option value="7" size="10">7</option>
 	 <option value="8" size="10">8</option>
 	 <option value="9" size="10">9</option>
 	 <option value="10" size="10">10</option>
 	 <option value="11" size="10">11</option>
 	 <option value="12" size="10">12</option>
 	 <option value="13" size="10">13</option>
 	 <option value="14" size="10">14</option>
 	 <option value="15" size="10">15</option>
 	 </select>
<?
  echo "</th>";
  echo "<th>";
  echo "<input readonly type='text' style=\"font-size:80%;text-align:center;background-color:$colore1\" size='4' name=\"importo".$row['id']."\" value=0>";
  echo "</th>";
  echo "<th>";
  echo "<input readonly type='text' style=\"font-size:80%;text-align:center;background-color:$colore2\" size='6' name=\"totale".$row['id']."\" value=0>";
  echo "</th>";

  echo "<th>";
  echo "<input type='text' style=\"font-size:80%;text-align:left\" size='20' name=\"note".$row['id']."\" value=''>";
  echo "</th>";
  echo "</tr>";
 }
 else
 {
  echo "<tr>";
  echo "<th>";
  echo "<input disabled type='text' style=\"font-size:80%;text-align:center;text-decoration:line-through;background-color:$colore1\" size='22' name='".$row['descrizione']."' value='".$row['descrizione']."'>";
  echo "</th>";
  echo "<th>";
  echo "<select disabled onchange=\"sndReq(".$row['id'].")\" style='font-size:80%;width:40px;text-decoration:line-through;background-color:$colore1' WIDTH='40' name=\"qty".$row['id']."\">";
?> 
	<option value="0" size="10">0</option>
 	<option value="1" size="10">1</option>
 	<option value="2" size="10">2</option>
 	<option value="3" size="10">3</option>
 	<option value="4" size="10">4</option>
 	</select>
<?
  echo "</th>";
  echo "<th>";
  echo "<input disabled type='text' style=\"font-size:80%;text-align:center;text-decoration:line-through;background-color:$colore1\" size='4' name=\"importo".$row['id']."\" value=0>";
  echo "</th>";
  echo "<th>";
  echo "<input disabled type='text' style=\"font-size:80%;text-align:center;background-color:#ffff99;text-decoration:line-through;background-color:$colore2\" size='6' name=\"totale".$row['id']."\" value=0>";
  echo "</th>";
	   
  echo "<th>";
  echo "<input disabled type='text' style=\"font-size:80%;text-align:center;text-decoration:line-through\" size='30' name=\"note".$row['id']."\" value=''>";
  echo "</th>";

  echo "</tr>";
  	
 }
 $riga++;
 } 

?>
 <tr class="gruppo">
 <th>TOTALE<br>Importo ricevuto</th>
 <th></th>
 <th></th>
 <th><input readonly type='text' style="font-size:90%;text-align:center;font-weight:bold;background-color:#ffff99" size='6' name="totale" value=0><br><input type='text' style="font-size:90%;text-align:center;font-weight:bold;background-color:#ffff99" size='6' name="importo_ricevuto"</th>
 <th>
 <table border='0'>
 <tr>
 <td>
  Per asporto 
 </td>
 <td>	
 <INPUT type='checkbox' name='asporto' onclick=sndReq1()>
 </td>
 </tr>
 <tr>
 <td>
 Coperti 
 </td>
 <td>
 <select style="font-size:80%;width:40px;background-color:#ffff99" WIDTH='40' name="coperti">
  <option value="0" size="10">0</option>
  <option value="1" size="10" selected="selected">1</option>
  <option value="2" size="10">2</option>
  <option value="3" size="10">3</option>
  <option value="4" size="10">4</option>
  <option value="5" size="10">5</option>
  <option value="6" size="10">6</option>
  <option value="7" size="10">7</option>
  <option value="8" size="10">8</option>
  <option value="9" size="10">9</option>
  <option value="10" size="10">10</option>
  <option value="11" size="10">11</option>
  <option value="12" size="10">12</option>
  <option value="13" size="10">13</option>
  <option value="14" size="10">14</option>
  <option value="15" size="10">15</option>
 </select>
 </td>
 </tr>
 </table>
 </th>
 </tr>
</tbody>
</table>
</td>
</tr>
</table>
</td>
</table>
<br>
<div>	
<?
 if($disable_button)
 {
?>
  &nbsp;&nbsp;<input id="once" type="submit" value="STAMPA" name="ola_request" disabled="true">	
<?
 }else{
?>		
  &nbsp;&nbsp;<input id="once" type="submit" value="STAMPA" name="ola_request" onclick="disableTimeout()">	
<?
 }
?>	
</div>

</div>
</div>

</form>
<br/>
<br/>


<script type="text/javascript">
 sndReq('value');
</script>


<p>
<div id="content">
<div id="tabella">
<caption><strong></strong></caption><p>
</div>
</div>
<p>	 
<?
/****************************************************************/
/*                  FUNZIONE STAMPA SCONTRINI                   */
/****************************************************************/
function stampa($dim_foglio,$stringa_scontrino,$intestazione,$intestazione2,$asporto,$coperti,$data,$ora,$cucina,$bar,$importo_ricevuto)
{
include("../../Accounts_MySql/datilogin.txt");
// Inizio procedura di stampa
	$coda = "SELECT * FROM parametri WHERE descrizione LIKE 'logo'";
	$dati = mysqli_query ($link,$coda);
	$row=mysqli_fetch_assoc($dati);
	$logo = $row['valore'];
	$coda = "SELECT * FROM parametri WHERE descrizione LIKE 'intestazione'";
	$dati = mysqli_query ($link, $coda);
	$row=mysqli_fetch_assoc($dati);
	$intestazione = $row['valore'];

	$coda = "SELECT * FROM parametri WHERE descrizione LIKE 'intestazione2'";
	$dati = mysqli_query ($link, $coda);
	$row=mysqli_fetch_assoc($dati);
	$intestazione2 = $row['valore'];
	
/**** STAMPA ****/
$font = "arial";
$note_all = "";
if ($dim_foglio == "A5")
{
 $font08 = 8;
 $font11 = 11;
 $font12 = 12;
 $font13 = 13;
 $font14 = 12;
 $font15 = 15;
 $font16 = 16;
 $font17 = 17;
 $font18 = 14;
 $font20 = 20;
 $font_tot_gen = 14;
 $font_note = 8;
 $m1 = 38;
 $m2 = 38;
 $m3 = 85;
 $m4 = 8;
 $delta1 = 80;
 $delta2 = 90;
 $delta3 = 95;
 $delta4 = 105;
 $h1 = 0;
 $h2 = 0;
 $larghezza = 28;
 $xc = 8;
 $yc = 50;
 $xb = 8;
 $yb = 142;
 $ximmagine = 50;
 $yimmagine = 2;
 $l = 149;
 $maxwidth = 100;
 $maxheight = 30;
 $distaccobar = 60;
}
if ($dim_foglio == "POS56")
{
 $font08 = 6;
 $font11 = 9;
 $font12 = 10;
 $font13 = 11;
 $font14 = 8;
 $font15 = 9;
 $font16 = 14;
 $font17 = 15;
 $font18 = 10;
 $font20 = 16;
 $font_tot_gen = 10;
 $font_note = 7;
 $m1 = 5;
 $m2 = 7;
 $m3 = 30;
 $m4 = 4;
 $delta1 = 30;
 $delta2 = 35;
 $delta3 = 37;
 $delta4 = 50;
 $h1 = 10;
 $h2 = 4;
 $larghezza = 18;
 $xc = 2;
 $yc = 50;
 $xb = 2;
 $yb = 142;
 $ximmagine = 8;
 $yimmagine = 2;
 $l = 56;
 $maxwidth = 50;
 $maxheight = 20;
 $distaccobar = 50;
}

if ($dim_foglio == "POS80")
{
 $font08 = 8;
 $font11 = 11;
 $font12 = 11;
 $font13 = 13;
 $font14 = 10;
 $font15 = 11;
 $font16 = 16;
 $font17 = 17;
 $font18 = 12;
 $font20 = 18;
 $font_tot_gen = 11;
 $font_note = 9;
 $m1 = 5;
 $m2 = 7;
 $m3 = 30;
 $m4 = 4;
 $delta1 = 40;
 $delta2 = 45;
 $delta3 = 47;
 $delta4 = 58;
 $h1 = 10;
 $h2 = 4;
 $larghezza = 18;
 $xc = 2;
 $yc = 50;
 $xb = 2;
 $yb = 142;
 $ximmagine = 16;
 $yimmagine = 2;
 $l = 80;
 $maxwidth = 75;
 $maxheight = 30;
 $distaccobar = 60;
}
$larghezza_pos = 18; 

define('EURO', chr(128));
$stringa_prt = "";
$stringa_bar = "";
$nome_scontrino = "scontrino".$stringa_scontrino;
if ($dim_foglio == "POS56")
 $pdf1=new FPDF('P','mm','pos56_1');
if ($dim_foglio == "POS80")
 $pdf1=new FPDF('P','mm','pos80_1');
if ($dim_foglio == "A5")
 $pdf1=new FPDF('P','mm','A5');
$pdf1->AddPage();
list($width, $height) = getimagesize("./logos/".$logo);
// Stabiliamo che la larghezza massima stampabile del logo è $maxwidth mentre l'altezza massima è $maxheight
if (($width <= $maxwidth) && ($height <= $maxheight))
{
 $w = $width;
 $h = $height;
}
if (($width >= $maxwidth) && ($height <= $maxheight))
{
 $r = $maxwidth/$width;
 $w = round($width*$r);
 $h = round($height*$r);
}
if (($width <= $maxwidth) && ($height >= $maxheight))
{
 $r = $maxheight/$height;
 $w = round($width*$r);
 $h = round($height*$r);
}
if (($width >= $maxwidth) && ($height >= $maxheight))
{
 $r = $maxheight/$height;
 $w = round($width*$r);
 $h = round($height*$r);
}
$deltah = $h + 5;
$ximmagine = round($l/2-$w/2-$w/8);
$pdf1->Image("./logos/".$logo,$ximmagine,$yimmagine,$w,$h);
$pdf1->SetMargins(0,0,0);
$pdf1->SetFont($font,'BI',$font15);
$pdf1->Text($m1,$deltah,$intestazione);
if ($dim_foglio == "A5") {
 $pdf1->SetFont($font,'BI',$font11);
 $pdf1->Text($m1,$deltah+5,$intestazione2);
}
$pdf1->SetFont($font,'B',$font20);
$pdf1->Text($m2,$deltah+12,"N. ".$stringa_scontrino);
$pdf1->SetFont($font,'',$font18);
$data_s = date("d-m-Y");
$pdf1->Text($m3,$deltah+12,$data_s);
$stringa_prt .= $data_s."\x0d\x0a\x0d\x0a";
$stringa_asporto = "";
if ($asporto == "on"){
$pdf1->SetFont($font,'B',$font14);
$pdf1->Text($m3,$deltah+17,"ASPORTO");
$stringa_asporto = "ASPORTO";
}

$pdf1->SetFont($font,'B',$font14);
$pdf1->Text($m2,$deltah+17,"N.".$coperti." COPERTI");

/* INIZIO STAMPA COMMESSE */
	$delta_c = 0;
	$delta_b = 0;
        $righe_alto = 0;

/* Determina quante righe ci sono in alto per trovare dove partire con il bar $yb*/
    $query = "SELECT * FROM scontrini WHERE scontrino LIKE '".$stringa_scontrino."' AND data LIKE '".$data."' AND nullo LIKE '0' AND ora LIKE '".$ora."'";
    $result = mysqli_query($link, $query);
     while($row = mysqli_fetch_assoc($result))
     {
      $query2 = "SELECT * FROM listino WHERE descrizione LIKE \"".$row['descrizione']."\"";
      $result2 = mysqli_query($link,$query2);
      $row2 = mysqli_fetch_assoc($result2);
      $posizione = $row2['posizione'];
      if ($posizione == "alto"){
       $righe_alto += 1;
      }
     }

   $yb = 4*$righe_alto + $deltah + $distaccobar;
   $yc = $deltah + 30;
    $query = "SELECT * FROM scontrini WHERE scontrino LIKE '".$stringa_scontrino."' AND data LIKE '".$data."' AND nullo LIKE '0' AND ora LIKE '".$ora."'";
	$result = mysqli_query($link, $query);
	$totale_cucina = 0;
	$totale_bar = 0;
	$totale_generale = 0;
	$pdf1->SetFont($font,'',$font12);

	while($row = mysqli_fetch_assoc($result))
	{
    	 $query2 = "SELECT * FROM listino WHERE descrizione LIKE \"".$row['descrizione']."\"";
	 $result2 = mysqli_query($link,$query2);

	$row2 = mysqli_fetch_assoc($result2);
	$posizione = $row2['posizione'];
        $importo_formattato = number_format($row['importo'],2,",",".");
	if ($posizione == "alto"){
	 $totale_cucina += $row['importo'];
	 $caratteri_importo = 5-strlen($importo_formattato);
	switch ($caratteri_importo) {
    	 case 0:
         $sposta = 0;
         break;
    	 case 1:
         $sposta = 2;
         break;
	}
	 $pdf1->SetFont($font,'',$font11);
	 $pdf1->Text($xc,$yc+$delta_c,utf8_decode($row['descrizione']));
	 $desc_cut = substr($row['descrizione'],0,$larghezza);
	 $stringa_prt .= str_pad($desc_cut,$larghezza_pos);
	 $stringa_prt .= "n.";
	 $stringa_prt .= str_pad($row['qta'],3," ",STR_PAD_LEFT);
	 $pdf1->Text($xc+$delta1,$yc+$delta_c,$row['qta']);
	 $pdf1->Text($xc+$delta2,$yc+$delta_c,EURO);
	 $pdf1->Text($xc+$delta3+$sposta,$yc+$delta_c,$importo_formattato);
//	 $stringa_prt .= " \xd5"; //€
	 $stringa_prt .= " €"; //€
	 $stringa_prt .= str_pad($importo_formattato,6," ",STR_PAD_LEFT);
	 if ($row['note'] <> '')
	 {
	  $note_all .= $row['note']. " ";
	  $note_cut = substr($row['note'],0,15);
	  $stringa_prt .= str_pad(" ".$note_cut,11);
	  $stringa_prt .= "\x0d\x0a";
	 }
         else
 	  $stringa_prt .= "\x0d\x0a";

	 $pdf1->SetFont($font,'',$font_note);
	 $pdf1->Text($xc+$delta4,$yc+$delta_c,$row['note']);
	 $delta_c += 4;
	}



	if ($posizione == "basso"){
	 $totale_bar += $row['importo'];
         $importo_formattato = number_format($row['importo'],2,",",".");
	 $caratteri_importo = 5-strlen($importo_formattato);
	switch ($caratteri_importo) {
    	 case 0:
         $sposta = 0;
         break;
    	 case 1:
         $sposta = 2;
         break;
	}
	 $pdf1->SetFont($font,'',$font11);
	 $pdf1->Text($xb,$yb+$delta_b,utf8_decode($row['descrizione']));
	 $pdf1->Text($xb+$delta1,$yb+$delta_b,$row['qta']);
	 $pdf1->Text($xb+$delta2,$yb+$delta_b,EURO);
	 $pdf1->Text($xb+$delta3+$sposta,$yb+$delta_b,$importo_formattato);
	 $pdf1->SetFont($font,'',$font_note);
	 $pdf1->Text($xb+$delta4,$yb+$delta_b,$row['note']);
	 $delta_b += 4;
	 $desc_cut = substr($row['descrizione'],0,$larghezza);
	 $stringa_bar .= str_pad($desc_cut,$larghezza_pos);
	 $stringa_bar .= "n.";
	 $stringa_bar .= str_pad($row['qta'],3," ",STR_PAD_LEFT);
	 $stringa_bar .= " €";
	 $stringa_bar .= str_pad($importo_formattato,6," ",STR_PAD_LEFT);
	 if ($row['note'] <> '')
	 {
	  $note_all .= $row['note']. " ";
	  $note_cut = substr($row['note'],0,10);
	  $stringa_bar .= str_pad(" ".$note_cut,11);
	  $stringa_bar .= "\x0d\x0a";
	 }
         else
          $stringa_bar .= "\x0d\x0a";

	}

	}

	$totale_cucina = bcadd($totale_cucina, 0, 2);
	$totale_bar = bcadd($totale_bar, 0, 2);
	$totale_generale = bcadd($totale_bar, $totale_cucina, 2);
	$importo_ricevuto = bcadd($importo_ricevuto, 0, 2);
        $totale_cucina = number_format($totale_cucina,2,",",".");

	if ($cucina)
	{
	 $pdf1->Line($xc,$yc+$delta_c-2,115,$yc+$delta_c-2);
	 $pdf1->SetFont($font,'B',$font12);
	 $pdf1->Text($xc,$yc+$delta_c+3,"TOTALE CUCINA");
	 $caratteri_importo = 6-strlen($totale_cucina);
	 switch ($caratteri_importo) {
    	  case 0:
          $sposta = -1;
          break;
    	  case 1:
          $sposta = 0;
          break;
    	  case 2:
          $sposta = 2;
          break;
	}
 
	 $pdf1->Text($xc+$delta2,$yc+$delta_c+3,EURO);
	 $pdf1->Text($xc+$delta3+$sposta,$yc+$delta_c+3,$totale_cucina);
	 $note_insieme = "Note : ".$note_all;
	 $pdf1->SetFont($font,'',$font_note);
	 $pdf1->Text($xc,$yc+$delta_c+6,$note_insieme);

	 $stringa_prt .= "                       ________\x0d\x0a";
	 $stringa_prt .= str_pad("TOTALE CUCINA",$larghezza_pos+5);
//	 $stringa_prt .= " \xd5"; //€
	 $stringa_prt .= " €"; //€
	 $stringa_prt .= str_pad($totale_cucina,6," ",STR_PAD_LEFT);
	 $stringa_prt .= "\x0d\x0a\x0d\x0a";
	}
	$totale_bar = number_format($totale_bar,2,",",".");
	if ($bar)
	{
	 $pdf1->Line($xb,$yb+$delta_b-2,115,$yb+$delta_b-2);
	 $pdf1->SetFont($font,'B',$font12);
	 $pdf1->Text($xb,$yb+$delta_b+3,"TOTALE BAR");
	 $caratteri_importo = 6-strlen($totale_bar);
	 switch ($caratteri_importo) {
    	  case 0:
          $sposta = -1;
          break;
    	  case 1:
          $sposta = 0;
          break;
    	  case 2:
          $sposta = 2;
          break;
	}
	 $pdf1->Text($xb+$delta2,$yb+$delta_b+3,EURO);
	 $pdf1->Text($xb+$delta3+$sposta,$yb+$delta_b+3,$totale_bar);

	 $stringa_bar .= "                       ________\x0d\x0a";
	 $stringa_bar .= str_pad("TOTALE BAR",$larghezza_pos+5);
//	 $stringa_bar .= " \xd5"; //€
	 $stringa_bar .= " €"; //€
	 $stringa_bar .= str_pad($totale_bar,6," ",STR_PAD_LEFT);
	 $stringa_bar .= "\x0d\x0a";  //Al posto di \n

	}

	$pdf1->Line($xb,$yb+$delta_b+6,115,$yb+$delta_b+6);
	$pdf1->SetFont($font,'B',$font_tot_gen);
	$pdf1->Text($xb,$yb+$delta_b+11,"TOTALE GENERALE");

	$stringa_bar .= "                       ________\x0d\x0a";
	$stringa_bar .= str_pad("TOTALE GENERALE ",$larghezza_pos+5); 
//	$stringa_bar .= " \xd5"; 
	$stringa_bar .= " €"; 
	$totale_generale_fmt = number_format($totale_generale,2,",",".");
	$stringa_bar .= str_pad($totale_generale_fmt,6," ",STR_PAD_LEFT);
	$stringa_bar .= "\x0d\x0a";

	$stringa_prt .= $stringa_bar;
	if ($note_all != "")
	 $stringa_prt .= $note_insieme;

	$caratteri_importo = 6-strlen($totale_generale_fmt);
	 switch ($caratteri_importo) {
    	  case 0:
          $sposta = -1;
          break;
    	  case 1:
          $sposta = 0;
          break;
    	  case 2:
          $sposta = 2;
          break;
	}
	$pdf1->Text($xb+$delta2,$yb+$delta_b+11,EURO);
	$pdf1->Text($xb+$delta3+$sposta,$yb+$delta_b+11,$totale_generale_fmt);
	$resto = $importo_ricevuto - $totale_generale;
	$resto_fmt = number_format($resto,2,",",".");
	$importo_ricevuto_fmt = number_format($importo_ricevuto,2,",",".");

	if ($resto >=0) {
	$pdf1->SetFont($font,'B',$font08);
	$pdf1->Text($xb,$yb+$delta_b+15,"Importo ricevuto: ".EURO." ".$importo_ricevuto_fmt." - Resto: ".EURO.$resto_fmt);
	$str_enc = urlencode ($stringa_prt);
	}

/* Si opta per salvare il pdf solo del formato A5. Per 80mm e 56mm lo scontrino è stampato via sequenza ESC/POS    */
/* Tuttavia il codice sopra genera il pdf anche per il formato carta termica.                                      */
/* La stampa di un pdf su carta termica è molto lenta.                                                             */
if ($dim_foglio == "A5")
 $pdf1->Output("./scontrini/".$nome_scontrino."_".$dim_foglio.".pdf","F");

return array($resto,$stringa_prt);	
}
	

?>


