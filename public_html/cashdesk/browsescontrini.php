<?
/*******************************************************************************
* CASH DESK - REPRINT RECEIPTS                                                 *
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

?>
<!DOCTYPE html>
<head>

<title>Ristampa Scontrini</title>

</head>
<body>
<?

/*Modulo connessione data base */
 include("../../Accounts_MySql/datilogin.txt");
mysqli_set_charset($link, 'utf8');

echo "<b>SELEZIONA LO SCONTRINO DA RISTAMPARE</b><br><br>";

$path = "./scontrini/";
// Open the folder     
$dir_handle = @opendir($path) or die("Unable to open $path");      

?>
<form name="premi" method=post action="browsescontrini.php"> 
<div style="position:absolute;left:220px;top:50px;font-weight:bold">Cucina</div>
<div style="position:absolute;left:220px;top:65px;">
<input type='checkbox' name='cucina' id='cucina' checked>
</div>
<div style="position:absolute;left:360px;top:50px;font-weight:bold">Cassa</div>
<div style="position:absolute;left:360px;top:65px;">
<input type='checkbox' name='cassa' id='cassa' checked>
</div>

<div style="position:absolute;left:40px;top:50px;font-weight:bold">N° Scontrino</div>
<div style="position:absolute;left:40px;top:70px;font-weight:bold">
<select style='font-size:80%;width:150px' name='nscontrino' id='nscontrino'>
<?
// Loop through the files     
while ($file = readdir($dir_handle)) {
if(($file == ".") || ($file == "..") || ($file == "index.php") || (strpos($file, "nulla") != false))          
 continue;
$f[] = $file;         
}
sort($f);
	
foreach($f as $file) 
echo "<option value='".$file."' size=10 style='width:100px'>".$file."</option>";      
  
// Close     
closedir($dir_handle);  
?>
</select>
</div>

<div style="position:absolute;left:40px;top:130px;font-weight:bold">
<input type="submit" value="STAMPA" name="filtra" id="filtra">
</div>
</form>
<?
if (isset($_POST['filtra'])) {
 $cucina = $_POST['cucina'];
 $cassa = $_POST['cassa'];
 $nscontrino = $_POST['nscontrino'];

 $coda = "SELECT * FROM parametri WHERE descrizione LIKE 'logo'";
 $dati = mysqli_query ($link, $coda);
 $row=mysqli_fetch_assoc($dati);
 $logo = $row['valore'];
 $coda = "SELECT * FROM parametri WHERE descrizione LIKE 'intestazione'";
 $dati = mysqli_query ($link, $coda);
 $row=mysqli_fetch_assoc($dati);
 $intestazione = $row['valore'];

 $query = "SELECT * FROM stampanti WHERE cucina LIKE '1'";
 $result = mysqli_query($link, $query);
 $row=mysqli_fetch_assoc($result);
 $nome_stampante_cucina = $row['nome'];
 $formato_carta_cucina = $row['formatocarta'];
 $tipo_stampante_cucina = $row['tipo'];
 $id_stampante_cucina = $row['id'];
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
 $prodid_stampante_cassa_dec = hexdec($row['prodID']);
 $vendid_stampante_cassa_dec = hexdec($row['vendID']);
 $connessione_stampante_cassa = $row['connessione'];
 $ip_stampante_cassa = $row['ip'];

 $coda = "SELECT * FROM parametri WHERE descrizione LIKE 'abilita_stampa_cucina'";
 $dati = mysqli_query ($link,$coda);
 $row=mysqli_fetch_assoc($dati);
 $abilita_stampa_cucina = $row['valore'];

 $file = fopen("./scontrini/".$nscontrino,"r");
 $n_sco = fgets($file);
 $rec_scontrino = trim($n_sco);
 $rec_asporto = trim(fgets($file));
 $rec_coperti = trim(fgets($file));
 $rec_resto = "";
 while (!feof($file)) {
  $rec_resto .= fgets($file);
 }
 fclose($file);
    

 $stampa_eseguita = 0;

 if ($cassa=='on')
 {
  $stampa_eseguita = 1;

 // Stampa in cassa	 
  if ($tipo_stampante_cassa != 'ESC-POS')
  {
   $comando = "lp -d ".$_SESSION['stampante']." ./scontrini/".$nscontrino;
   exec($comando);
  }
  else
  {
   $comando = "sudo ./stampa.bash \"".$rec_scontrino."\" \"".$rec_asporto."\" \"".$rec_coperti."\" \"".$rec_resto."\" \"stampascontrino.py\" \"".$intestazione."\" ".$prodid_stampante_cassa_dec." ".$vendid_stampante_cassa_dec." \"".$id_stampante_cassa."\" > /dev/null 2>&1 &";
   exec($comando);
  }
 }

 if ($cucina=='on')
 {
  $stampa_eseguita = 1;
 // Stampa in cucina
 if ($abilita_stampa_cucina == 1)
 {


  if ($tipo_stampante_cucina != 'ESC-POS')
  {
   $comando = "lp -d ".$nome_stampante_cucina." ./scontrini/".$nscontrino;
   exec($comando);
  }
  else
  {
   $comando = "sudo ./stampa.bash \"".$rec_scontrino."\" \"".$rec_asporto."\" \"".$rec_coperti."\" \"".$rec_resto."\" \"stampascontrino.py\" \"".$intestazione."\" ".$prodid_stampante_cucina_dec." ".$vendid_stampante_cucina_dec." \"".$id_stampante_cucina."\" > /dev/null 2>&1 &";
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

if ($stampa_eseguita == 1)
 {
  ?><script type="text/javascript">
  window.alert('Ristampato scontrino numero\n <?echo $nscontrino; ?>');
  </script>
  <?
 }
}

?>
