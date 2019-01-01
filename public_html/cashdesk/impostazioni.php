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

?>
<!DOCTYPE html>
<head>
 <title>Impostazioni</title>

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
		
    }
    </script> 

<link href="cssnn.css" rel="stylesheet" type="text/css">

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
#it
   .td1style  {font-weight:normal;border:1px solid black;background-color:lightgrey;color:blue;font-size:14px;fontFamily:Verdana;}
   .td2style  {font-weight:normal;border:1px solid black;background-color:lightyellow;font-size:12px;font-family:Verdana;}
</STYLE>

</head>
<body>

<?  /*Modulo connessione data base */
include("../../Accounts_MySql/datilogin.txt");
mysqli_set_charset($link, 'utf8');
?>
<br>
<?

if (isset($_POST['nascosto'])) 
{
$target_dir = "./logos/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        //File is an image
        $uploadOk = 1;
    } else {
        //File is not an image
        $uploadOk = 0;
    }
}

// Check if file already exists
if (file_exists($target_file)) {
  ?><script type="text/javascript">
  window.alert('File già presente in archivio \n');
  </script>
  <?    
  $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
  ?><script type="text/javascript">
  window.alert('File troppo grande \n');
  </script>
  <?    
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
  ?><script type="text/javascript">
  window.alert('Sono consentite solo immagini. File JPJ, JPEG, PNG e GIF \n');
  </script>
  <?    
  $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  ?><script type="text/javascript">
  window.alert('File non caricato \n');
  </script>
  <?    
// if everything is ok, try to upload file. Converte l'immagine in BW dithering
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
     $conversione = "convert ".$target_file." -colorspace Gray -ordered-dither h4x4a ".$target_file.".bw.jpg";
     exec ($conversione);
     /****************************************************************************************/
     /*                                                                                      */
     /*          CREA FILE ESADECIMALE DELL'IMMAGINE BW GENERATA ALLA RIGA SOPRA             */
     /*                                                                                      */
     /****************************************************************************************/
     
     list($width, $height) = getimagesize($target_file.".bw.jpg");
     
      //$max_width = 605; // Carta 80mm
      $max_width = 365; //Carta 56mm

      $max_height = 190;

      // Get current dimensions
      $old_width  = $width;
      $old_height = $height;

      // Calculate the scaling we need to do to fit the image inside our frame
      $scale = min($max_width/$old_width, $max_height/$old_height);

      // Get the new dimensions
      $new_width  = ceil($scale*$old_width);
      $new_height = ceil($scale*$old_height);

      $source_image = imagecreatefromjpeg($target_file.".bw.jpg");
      $source_imagex = imagesx($source_image);
      $source_imagey = imagesy($source_image);
      $dest_imagex = $new_width;
      $dest_imagey = $new_height;
      $dest_image = imagecreatetruecolor($dest_imagex, $dest_imagey);
      imagecopyresampled($dest_image, $source_image, 0, 0, 0, 0, $dest_imagex, $dest_imagey, $source_imagex, $source_imagey);

      $rgb = imagecolorat($dest_image, 10, 15);
      $r = ($rgb >> 16) & 0xFF;
      $g = ($rgb >> 8) & 0xFF;
      $b = $rgb & 0xFF;
      $conta_pixel = 0;
      $byte = "";
      $larghezza_estesa = 8*(1+intdiv($new_width,8));
      $larghezza_estesa_byte = $larghezza_estesa/8;
      $larghezza_estesa_hex = dechex($larghezza_estesa_byte);
      $blanc = ($max_width - $new_width)/2;
      $blanc_estesa = 8*(1+intdiv($blanc,8));
      $blanc_estesa_byte = $blanc_estesa/8;
      $larghezza_tot = $blanc_estesa_byte + $larghezza_estesa_byte;
      $larghezza_tot_hex = dechex($larghezza_tot);

      $altezza_hex = dechex($new_height);
      $hex = "\\x1d\\x76\\x30\\x00\\x".str_pad($larghezza_tot_hex,2,"0",STR_PAD_LEFT)."\\x00\\x".$altezza_hex."\\x00";
      $tot_byte = 0;
      for ($y=0;$y<$new_height;$y++)
      {
	for ($w=1;$w<=$blanc_estesa_byte;$w++)
	$hex .= "\\x00";
	for ($x=0;$x<$larghezza_estesa;$x++)
	{
	  if ($x < $new_width)
	  {
	  $rgb = imagecolorat($dest_image, $x, $y);
	  $r = ($rgb >> 16) & 0xFF;
	  $g = ($rgb >> 8) & 0xFF;
	  $b = $rgb & 0xFF;
	  $grey = 0.299*$r+0.587*$g+0.114*$b;
	  if ($grey > 127) $pixel = 0;
	    else $pixel = 1;
	  }
	  else
	  $pixel = 0;
	  $conta_pixel ++;
	  $byte .= $pixel;
	  if ($conta_pixel == 8)
	  {
	  $hex_pad = str_pad(dechex(bindec($byte)), 2, "0", STR_PAD_LEFT);
	  $hex .= "\\x".$hex_pad;
	  $conta_pixel = 0;
	  $byte = "";
	  $tot_byte++;
	  }
	}
      }
      file_put_contents($target_file.".hex", $hex, LOCK_EX);
     
     
     /* fine */
     
     
     ?><script type="text/javascript">
     window.alert('File caricato \n');
     </script>
     <?    
     list($width, $height) = getimagesize($target_file);
     echo "Larghezza = ".$width."<br>";
     echo "Altezza = ".$height."<br>";
 // Da ridimensionare in 50x20    
    } else {
     ?><script type="text/javascript">
      window.alert('Errore di caricamento \n');
      </script>
     <?    
    }
} 
 
 
}

	
if (isset($_POST['incrementale'])) 
{
  	$numero_scontrino = $_POST['numero_scontrino'];
	$logo = $_POST['logo'];
	$intestazione = $_POST['intestazione'];
	$intestazione2 = $_POST['intestazione2'];
	$incrementale = $_POST['incrementale'];
	$archivio_scontrini = $_POST['archivio_scontrini'];
	$abilita_stampa_cucina = $_POST['abilita_stampa_cucina'];
	$freccia = $_POST['freccia'];
	$croce = $_POST['croce'];
        $buffer = "SELECT * FROM parametri WHERE descrizione LIKE 'ultimo_indice'";
	$coda = mysqli_query($link, $buffer); 
	$row=mysqli_fetch_assoc($coda);
	$ultimo_indice = $row['valore'];
	if ($archivio_scontrini == "on"){
?>
        <script type="text/javascript">
        if (window.confirm('ATTENZIONE si procederà con la rimozione dall\'archivio di tutti i dati relativi agli scontrini emessi.\n\nPremere OK per continuare, altrimenti ANNULLA')){
        }
        else
        {
      	window.self.close('','','');
	window.open('index.php','','');
	}
        </script>
<?
        $buffer = "TRUNCATE TABLE scontrini";
        $coda = mysqli_query($link, $buffer); 
	}

	if ($numero_scontrino == "on"){
         $buffer = "UPDATE parametri SET valore=0 WHERE descrizione LIKE 'numero_scontrino'";
	 $coda = mysqli_query($link, $buffer); 
         $buffer = "UPDATE parametri SET valore=0 WHERE descrizione LIKE 'scontrino_bar'";
	 $coda = mysqli_query($link, $buffer); 
	}

	if ($abilita_stampa_cucina == "on"){
         $buffer = "UPDATE parametri SET valore=1 WHERE descrizione LIKE 'abilita_stampa_cucina'";
	 $coda = mysqli_query($link, $buffer); 
        }
        else
        {
         $buffer = "UPDATE parametri SET valore=0 WHERE descrizione LIKE 'abilita_stampa_cucina'";
	 $coda = mysqli_query($link, $buffer); 
        }
	
        $buffer = "UPDATE parametri SET valore=\"".$logo."\" WHERE descrizione LIKE 'logo'";
	$coda = mysqli_query($link, $buffer);

        $buffer = "UPDATE parametri SET valore=\"".$intestazione."\" WHERE descrizione LIKE 'intestazione'";
	$coda = mysqli_query($link, $buffer);

        $buffer = "UPDATE parametri SET valore=\"".$intestazione2."\" WHERE descrizione LIKE 'intestazione2'";
	$coda = mysqli_query($link, $buffer);

        for($j=1; $j<=$incrementale; $j++){
	 if ($croce == $j)
	 {
	  $query = "DELETE from listino WHERE id LIKE '".$j."'";
	  $coda = mysqli_query($link, $query);
	  for ($k=$j+1; $k<=$ultimo_indice; $k++)
	  {
	   $nuovo_id = $k-1;
	   $query = "UPDATE listino SET id='".$nuovo_id."' WHERE id LIKE '".$k."'";
	   $coda = mysqli_query($link, $query);
	  }
	  $nuovo_valore = $ultimo_indice-1;
	  $query = "UPDATE parametri SET valore='".$nuovo_valore."' WHERE descrizione LIKE 'ultimo_indice'";
	  $coda = mysqli_query($link, $query);
	 }

	 if ($freccia == $j)
	 {
	  for ($k=$ultimo_indice; $k>=$j+1; $k--)
	  {
	   $nuovo_id = $k+1;
	   $query = "SELECT * FROM listino WHERE id LIKE '".$k."'";
	   $query = "UPDATE listino SET id='".$nuovo_id."' WHERE id LIKE '".$k."'";
	   $coda = mysqli_query($link, $query);
	  }
	  $nuovo_valore = $ultimo_indice+1;
	  $query = "UPDATE parametri SET valore='".$nuovo_valore."' WHERE descrizione LIKE 'ultimo_indice'";
	  $coda = mysqli_query($link, $query);
	  $nuovo_entrato = $j+1;
	  $query = "INSERT INTO listino (id) VALUES ('".$nuovo_entrato."')";
	  $coda = mysqli_query($link, $query);
	 }
        }

        if (($freccia =='')&&($croce == ''))	
	{
	 for($j=1; $j<=$incrementale; $j++){
	 $descrizione = $_POST['descrizione'.$j];
	 $importo = $_POST['importo'.$j];
	 $posizione = $_POST['posizione'.$j];
	 $tipo_piatto = $_POST['tipo_piatto'.$j];
	 $id = $_POST['id'.$j];
	 $disponibile = $_POST['disponibile'.$j];
	 if ($disponibile == "on") $disponibile = 1;
	  else $disponibile = 0;
	 
         $buffer = "UPDATE listino SET descrizione=\"".$descrizione."\",importo=\"".$importo."\",posizione=\"".$posizione."\",tipo_piatto=\"".$tipo_piatto."\",disponibile=".$disponibile." WHERE id LIKE '".$id."'";
	 $coda = mysqli_query($link, $buffer);
	 }
        }
}

?>
	
<caption><strong> Inserire i nuovi dati e premere il pulsante CONFERMA</strong></caption><p>

<form name="formola" method="post" action="<?echo $PATH_INFO;?>"> 
<div id="content">
<div id="tabella">
<form id="form_two" action="/">
<!-- DUMMY FORM TO ALLOW BROWSER TO ACCEPT NESTED FORM -->
</form>
<br>
	<table id="tabella1">

			<thead> 
					<tr class="odd" align='center'>
    					<th align='center'>Descrizione</th> 
    					<th>Importo unitario</th>
    					<th>Posizione</th>
    					<th>Tipo</th>
    					<th>Disponibile</th>
    					<th>Agg.riga</th>
    					<th>Canc.riga</th>
					</tr>
			</thead>
<tbody>
<?
        $query = "SELECT * FROM listino order by id";
	$result = mysqli_query($link, $query);
	$row=mysqli_fetch_assoc($result);
	$incrementale = 0;
	do {
	   if ($row['posizione'] == 'alto'){
           $colore1="#ffffcf";
	   $colore2="#ffff99";
	   }
	   if ($row['posizione'] == 'basso'){
	    $colore1 = "#cfffff";
	    $colore2 = "#99ffff";
	   } 	   

	   $incrementale++;
	   echo "<tr>";
	   echo "<th>";
 	   echo "<input type='text' style=\"font-size:90%;text-align:center;background-color:$colore1\" size='60' name=\"descrizione".$incrementale."\" value=\"".$row['descrizione']."\">";
	   echo "</th>";
	   echo "<th>";
 	   echo "<input type='text' style=\"font-size:90%;text-align:center;background-color:$colore2\" size='20' name=\"importo".$incrementale."\" value='".$row['importo']."'>";
	   echo "</th>";
	   echo "<th>";
 	   echo "<select style='font-size:90%;width:70px' WIDTH='70' name=\"posizione".$incrementale."\">";
	   ?> 
 	   <option value="<?echo $row['posizione']?>" size="10"><?echo $row['posizione']?></option>
 	   <option value="alto" size="10">alto</option>
 	   <option value="basso" size="10">basso</option>
 	   </select>
 	   </td>
	   <?
	   echo "</th>";
	   echo "<th>";
 	   echo "<select style='font-size:90%;width:70px' WIDTH='70' name=\"tipo_piatto".$incrementale."\">";
	   ?>
 	   <option value="<?echo $row['tipo_piatto']?>" size="10"><?echo $row['tipo_piatto']?></option>
	   <option value="primo" size="10">primo</option>
 	   <option value="secondo" size="10">secondo</option>
 	   <option value="contorno" size="10">contorno</option>
 	   <option value="bar" size="10">bar</option>
 	   </select>
 	   </td>
	   <?
	   echo "</th>";
	   echo "<th align='center'>";
	   if ($row['disponibile'] == 1)
 	   echo "<INPUT type='checkbox' style=\"font-size:90%;text-align:center\" name=\"disponibile".$incrementale."\" CHECKED>";
 	   else
 	   echo "<INPUT type='checkbox' style=\"font-size:90%;text-align:center\" name=\"disponibile".$incrementale."\">"; 	   
	   echo "</th>";
	   echo "<th>";
	   echo "<button type=\"submit\" name=\"freccia\" value=\"".$incrementale."\"><img src='img/freccia.png' width='20px' height='20px'></button>";
	   echo "</th>";
	   echo "<th>";
	   echo "<button type=\"submit\" name=\"croce\" value=\"".$incrementale."\"><img src='img/croce.png' width='20px' height='20px'></button>";
	   echo "</th>";
   	   echo "<input type=\"HIDDEN\" name=\"id".$incrementale."\" value=\"".$row['id']."\">";
  	   echo "</tr>";
 	} while( $row=mysqli_fetch_assoc($result) );
	
?>

</tbody>
</table>
<br>
<br>
	<table id="tabella1">
	<tbody>

<?
           $query = "SELECT * FROM parametri WHERE descrizione LIKE 'numero_scontrino'";
	   $result = mysqli_query($link, $query);
	   $row=mysqli_fetch_assoc($result);
	   $numero_scontrino = $row['valore'];

	   echo "<tr>";
	   echo "<th align='left'>";
	   echo "Azzera numero scontrino";	   
	   echo "</th>";	   
	   echo "<th align='left'>";
   	   echo "<INPUT type='checkbox' style=\"font-size:90%;text-align:center\" name=\"numero_scontrino\">"; 	   
	   echo "</th>";
  	   echo "</tr>";

           $query = "SELECT * FROM parametri WHERE descrizione LIKE 'abilita_stampa_cucina'";
	   $result = mysqli_query($link, $query);
	   $row=mysqli_fetch_assoc($result);
	   $abilita_stampa_cucina = $row['valore'];
	   echo "<tr>";
	   echo "<th align='left'>";
	   echo "Abilita stampante cucina";	   
	   echo "</th>";	   
	   echo "<th align='left'>";
	   if ($abilita_stampa_cucina != 0)	
   	    echo "<INPUT type='checkbox' style=\"font-size:90%;text-align:center\" name=\"abilita_stampa_cucina\" checked>"; 	   
           else
   	    echo "<INPUT type='checkbox' style=\"font-size:90%;text-align:center\" name=\"abilita_stampa_cucina\">"; 	   
	   echo "</th>";
  	   echo "</tr>";


           $query = "SELECT * FROM parametri WHERE descrizione LIKE 'intestazione'";
	   $result = mysqli_query($link, $query);
	   $row=mysqli_fetch_assoc($result);
	   $intestazione = $row['valore'];
           $query = "SELECT * FROM parametri WHERE descrizione LIKE 'intestazione2'";
	   $result = mysqli_query($link, $query);
	   $row=mysqli_fetch_assoc($result);
	   $intestazione2 = $row['valore'];
           $query = "SELECT * FROM parametri WHERE descrizione LIKE 'logo'";
	   $result = mysqli_query($link, $query);
	   $row=mysqli_fetch_assoc($result);
	   $logo = $row['valore'];

	   echo "<tr>";
	   echo "<th align='left'>";
	   echo "Intestazione";	   
	   echo "</th>";	   
	   echo "<th align='left'>";
 	   echo "<input type='text' style=\"font-size:90%;text-align:left\" size='40' name=\"intestazione\" value=\"".$intestazione."\">";
	   echo "</th>";
  	   echo "</tr>";

	   echo "<tr>";
	   echo "<th align='left'>";
	   echo "Seconda riga intestazione";	   
	   echo "</th>";	   
	   echo "<th align='left'>";
 	   echo "<input type='text' style=\"font-size:90%;text-align:left\" size='40' name=\"intestazione2\" value=\"".$intestazione2."\">";
	   echo "</th>";
  	   echo "</tr>";

	   echo "<tr>";
	   echo "<th align='left'>";
	   echo "Logo";	   
	   echo "</th>";	   
	   echo "<th align='left'>";
  
 	   echo "<select style='font-size:90%;width:130px' WIDTH='70' name='logo'>";
	   echo "<option value='".$logo."' size='10'>".$logo."</option>";
           if ($handle = opendir('./logos')) {
            while (false !== ($entry = readdir($handle))) {
            if (($entry != ".") && ($entry != "..") && (strpos($entry, '.bw.') == false) && (strpos($entry, '.hex') == false)) {
	     echo "<option value='".$entry."' size='10'>".$entry."</option>";
             }
            }
           closedir($handle);
           }
 	   echo "</select>";
	   echo "</th>";
	   echo "<th align='left'>";
?>
           <form name="modulo" action="<?echo $PATH_INFO;?>" method="post" enctype="multipart/form-data">
	   <input style="font-size:90%;text-align:left" size='40' type="file" name="fileToUpload" id="fileToUpload">
           <input style="font-size:90%;text-align:left" size='40' type="submit" value="Carica" name="submit">
  	   <input type="HIDDEN" name="nascosto" value="">
           </form> 

<?	   

	   echo "</th>";
	   echo "<th align='center'>";
	   echo "<img src=\"./logos/".$logo."\" alt='Logo Scontrino' style=\"border: 0px solid #000; max-width:100px; max-height:100px;\">";
	   echo "</th>";
	   
  	   echo "</tr>";
   	   
   	   echo "<tr>";
	   echo "<th align='left'>";
	   echo "Svuota archivio scontrini";	   
	   echo "</th>";	   
	   echo "<th align='left'>";
   	   echo "<INPUT type='checkbox' style=\"font-size:90%;text-align:center\" name=\"archivio_scontrini\">"; 	   
	   echo "</th>";
	   
  	   echo "</tr>";

	   echo "<input type=\"HIDDEN\" name=\"incrementale\" value=\"".$incrementale."\">";

?>
  	</tbody>
       </table>

<br>
<div>	
<?
if($disable_button)
{
?>
&nbsp;&nbsp;<input id="once" type="submit" value="CONFERMA" name="ola_request" disabled="true">	
<?
}else{
?>		
&nbsp;&nbsp;<input id="once" type="submit" value="CONFERMA" name="ola_request" onclick="disableTimeout()">	
<?
}
?>	
</div>
</div>
</div>
</form>
</td>
<td>
