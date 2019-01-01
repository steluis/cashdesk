<?
/*******************************************************************************
* CASH DESK - CANCEL RECEIPT AND PRINT A MESSAGE                               *
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
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Cancel receipt</title>

<script type="text/javascript">
    function disableTimeout()
    {
    setTimeout(disableTheButton, 1);
    }
    function disableTheButton(){
    	var txt1="Id: " + document.getElementById("once").id
			txt1=txt1 + ", type: " + document.getElementById("once").type
			txt1=txt1 + ", value: " + document.getElementById("once").value
			document.getElementById("once").disabled=true
		
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
	var http3 = createRequestObject();

    

   /*  The following three variables are for setting the properties of your table containing the tool tip */ 
    var tborder="0"
    var cspace="0"
    var cpad="0"
    var msgtop=200     // Set the top position of the div
    var msgleft=200     // Set the left position of the div
    var boxt=""

</script> 
<link href="cssnn.css" rel="stylesheet" type="text/css">


</head>
<body>

<div id="testbox"></div>
  
<?  /*Modulo connessione data base */
include("../../Accounts_MySql/datilogin.txt");
?>
<br>
<?
	
if (isset($_POST['ola_request'])) 
{
	$scontrino = $_POST['scontrino'];
	$coda = "SELECT * FROM scontrini WHERE scontrino LIKE '".$scontrino."'";
	$dati = mysqli_query ($link, $coda);
	$n = mysqli_num_rows($dati);
	if ($n == 0){
	?><script type="text/javascript">
        window.alert('ATTENZIONE !!\nLo scontrino numero <?echo $scontrino;?> non e\' a sistema');
 	</script>
	<?
	}else
	{
        $dbase_update =	"UPDATE scontrini SET nullo = '1' WHERE scontrino LIKE '$scontrino'";
	mysqli_query ($link, $dbase_update);
	?><script type="text/javascript">
        window.alert('AGGIORNAMENTO SISTEMA\nScontrino numero <?echo $scontrino;?> annullato\nInviata comunicazione alla stampante in cucina');
 	</script>
	<?
	$messaggio1 = "ANNULLARE LO SCONTRINO NUMERO";
	$messaggio2 = $scontrino;
	$coda = "SELECT * FROM parametri WHERE descrizione LIKE 'stampante_cucina'";
	$dati = mysqli_query ($link, $coda);
	$row=mysqli_fetch_assoc($dati);
	$stampante_cucina = $row['valore'];

	$coda = "SELECT * FROM parametri WHERE descrizione LIKE 'logo'";
	$dati = mysqli_query ($link, $coda);
	$row=mysqli_fetch_assoc($dati);
	$logo = $row['valore'];

	$coda = "SELECT * FROM parametri WHERE descrizione LIKE 'intestazione'";
	$dati = mysqli_query ($link, $coda);
	$row=mysqli_fetch_assoc($dati);
	$intestazione = $row['valore'];


	require('./fpdf/fpdf.php');
	define('EURO', chr(128));
	$query = "SELECT * FROM stampanti WHERE cucina LIKE '1'";
	$result = mysqli_query($link, $query);
	$row=mysqli_fetch_assoc($result);
	$nome_stampante_cucina = $row['nome'];
	$formato_carta_cucina = $row['formatocarta'];

	$nome_ricevuta = "annulla_scontrino_".$scontrino;

	if ($formato_carta_cucina == "A5")
	$pdf=new FPDF('P','mm','A5');

	if ($formato_carta_cucina == "A5")
	{
	 $testo_messaggio = "ANNULLARE LA COMANDA N. ";
	 $dim_font_messaggio = 20;
	 $x_testo = 20;
	 $y_testo = 50;
	 $x_testo2 = 5;
	 $pdf->AddPage();
	 $pdf->SetFont('Arial','BI',$dim_font_messaggio);
	 $pdf->Text($x_testo,$y_testo,"MESSAGGIO PER LA CUCINA");
	 $pdf->SetFont('Arial','B',$dim_font_messaggio);
	 $pdf->Text($x_testo2,$y_testo2,$testo_messaggio.$scontrino);
	 $pdf->Output("./scontrini/".$nome_ricevuta.".pdf","F");
	 }
	else
	{
	 // Stampa in cucina
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
         if ($tipo_stampante_cucina != 'ESC-POS')
         {
	  $comando = "lp -d ".$tipo_stampante_cucina." ./scontrini/".$nome_ricevuta.".pdf";
	  exec($comando);
         }
         else
	 {
	  $comando = "sudo ./stampa.bash \"".$scontrino."\" \"xxxxx\" \"x\" \"xxx\" \"stampaannullascontrino.py\" \"xxx\" ".$prodid_stampante_cucina_dec." ".$vendid_stampante_cucina_dec." \"".$id_stampante_cucina."\" > /dev/null 2>&1 &";
          exec($comando);
         }
	}
       }
}

?>
	
<caption><strong> Inserire il numero dello scontrino da annullare</strong></caption><p>
<form name="formola" method="post" action="<?echo $PATH_INFO;?>"> 
  <div id="content">
  <div id="tabella">
<br>
<table id="tabella1">
<tbody>
<?
	   echo "<tr>";
	   echo "<th>";
 	   echo "<input type='text' style=\"font-size:150%;text-align:center\" size='30' name='scontrino' value=''>";
	   echo "</th>";
	   echo "<th>";
?>
</tbody>
 </table>
<br>
<div>	
<br>
<br>
<?
if($disable_button)
{
?>
 &nbsp;&nbsp;<input id="once" type="submit" value="ANNULLA SCONTRINO" name="ola_request" disabled="true">	
<?
}else{
?>		
&nbsp;&nbsp;<input id="once" type="submit" value="ANNULLA SCONTRINO" name="ola_request" onclick="disableTimeout()">	
<?
}
?>	
</div>
</div>
</div>
</form>
