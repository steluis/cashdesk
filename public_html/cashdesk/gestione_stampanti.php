<?
/*******************************************************************************
* CASH DESK - PRINTERS SETUP AND PAPER SIZE                                    *
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
<title>Printers setup</title>

<script type="text/javascript" charset="utf-8">
function select_tipo(valore)
{
// window.alert('SELECT TIPO = '+valore);
 if (valore != 'ESC-POS')
 {
  document.getElementById('selectconn').getElementsByTagName('option')[0].selected = 'selected';
  document.getElementById("indirizzoip").value = '';
  document.getElementById("pid").value = '';
  document.getElementById("vid").value = '';
  document.getElementById("selectconn").disabled = true;
  document.getElementById("indirizzoip").disabled = true;
  document.getElementById("pid").disabled = true;
  document.getElementById("vid").disabled = true;
  document.getElementById("selectcarta").options.length=0;
  document.getElementById("selectcarta").options[0]=new Option("A5", "A5", true, false);
 }
 else
 {
  document.getElementById("selectconn").disabled = false;
  document.getElementById("indirizzoip").disabled = false;
  document.getElementById("pid").disabled = false;
  document.getElementById("vid").disabled = false;
  document.getElementById("selectcarta").options.length=0;
  document.getElementById("selectcarta").options[0]=new Option("80mm", "POS80", true, false);
  document.getElementById("selectcarta").options[1]=new Option("56mm", "POS56", false, false);
 }
}

function select_connessione(valore)
{
 document.getElementById('selecttipo').getElementsByTagName('option')[1].selected = 'selected';
 if (valore == 'USB')
 {
  document.getElementById("pid").disabled = false;
  document.getElementById("vid").disabled = false;
  document.getElementById("indirizzoip").disabled = true;
 }
 if (valore == 'Rete')
 {
  document.getElementById("pid").disabled = true;
  document.getElementById("vid").disabled = true;
  document.getElementById("indirizzoip").disabled = false;
 }
 
}

</script>
<link href="cssnn.css" rel="stylesheet" type="text/css">

<style type="text/css">
table.fixed {table-layout:fixed; width:90px;}/*Setting the table width is important!*/
table.fixed td {overflow:hidden;}/*Hide text outside the cell.*/
table.fixed td:nth-of-type(1) {width:100px;}/*Setting the width of column 1.*/
table.fixed td:nth-of-type(2) {width:100px;}/*Setting the width of column 2.*/
table.fixed td:nth-of-type(3) {width:100px;}/*Setting the width of column 3.*/
table.fixed td:nth-of-type(4) {width:100px;}/*Setting the width of column 4.*/
table.fixed td:nth-of-type(5) {width:100px;}/*Setting the width of column 5.*/
table.fixed td:nth-of-type(6) {width:100px;}/*Setting the width of column 6.*/
table.fixed td:nth-of-type(7) {width:100px;}/*Setting the width of column 7.*/
table.fixed td:nth-of-type(8) {width:100px;}/*Setting the width of column 8.*/

table.seleziona {table-layout:fixed; width:90px;}/*Setting the table width is important!*/
table.seleziona td {overflow:hidden;}/*Hide text outside the cell.*/
table.seleziona td:nth-of-type(1) {width:200px;}/*Setting the width of column 1.*/
table.seleziona td:nth-of-type(2) {width:50px;}/*Setting the width of column 2.*/
table.seleziona td:nth-of-type(3) {width:50px;}/*Setting the width of column 3.*/
table.seleziona td:nth-of-type(4) {width:100px;}/*Setting the width of column 3.*/
table.seleziona td:nth-of-type(5) {width:50px;}/*Setting the width of column 3.*/
table.seleziona td:nth-of-type(6) {width:50px;}/*Setting the width of column 3.*/
table.seleziona td:nth-of-type(7) {width:100px;}/*Setting the width of column 3.*/
table.seleziona td:nth-of-type(8) {width:100px;}/*Setting the width of column 3.*/
table.seleziona td:nth-of-type(9) {width:100px;}/*Setting the width of column 3.*/
table.seleziona td:nth-of-type(10) {width:100px;}/*Setting the width of column 3.*/

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
#it{
background-image:url("freccia.jpg");

}
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

<form action="gestione_stampanti.php" method="post">

<fieldset style="width:300px">
<legend>Stampanti disponibili</legend>
<?php
/***********************************/
/*   Pressione pulsante IMPOSTA    */
/*   In Selezione Stampante Cucina */
/***********************************/
if (isset($_POST['press_cu']))
{
  $croce = $_POST['croce'];
  if ($croce != '')
  {
   $query = "DELETE from stampanti WHERE id LIKE '".$croce."'";
   $coda = mysqli_query($link, $query);
  }
  $sql = "SELECT * FROM stampanti";
  $result = mysqli_query($link, $sql);
  while($row = mysqli_fetch_assoc($result)) {
   $indice = $row['id'];
   $buffer = "UPDATE stampanti SET cucina='0' WHERE id LIKE '".$row['id']."'";  
   $coda = mysqli_query($link, $buffer);
  }
  $id_st = $_POST['printer']; 
  $buffer = "UPDATE stampanti SET cucina=\"1\" WHERE id LIKE '".$id_st."'";
  $coda = mysqli_query($link, $buffer);
}
/* FINE pressione pulsante IMPOSTA in Selezione Stampante Cucina */

/***********************************/
/*   Pressione pulsante AGGIUNGI   */
/*       per nuova stampante       */
/***********************************/
if (isset($_POST['press_new']))
{
 $sql = "SELECT * FROM stampanti";
 $result = mysqli_query($link, $sql);
 $tipo = $_POST['tipo'];
 $connessione = $_POST['connessione'];
 $ip = $_POST['ip'];
 $productid = $_POST['productid'];
 $vendorid = $_POST['vendorid'];
 $nomestampante = $_POST['nomestampante'];
 $carta = $_POST['carta'];
 $codifica = $_POST['codifica'];
 $cucina=0;
 $buffer="INSERT INTO stampanti (tipo, cucina, formatocarta, connessione, ip, prodID, vendID, nome, codec) VALUES(\"".$tipo."\",".$cucina.",\"".$carta."\",\"".$connessione."\",\"".$ip."\",\"".$productid."\",\"".$vendorid."\",\"".$nomestampante."\",\"".$codifica."\")";
 $coda = mysqli_query($link, $buffer);
}
/* FINE pressione pulsante IMPOSTA in Formato Carta */

$sql = "SELECT * FROM stampanti";
$result = mysqli_query($link, $sql);
echo "<table class='seleziona'>";
echo "<tr>";
echo "<td>";
echo "<b>Stampante</b>";
echo "</td>";
echo "<td align='center'>";
echo "<b>Carta</b>";
echo "</td>";
echo "<td align='center'>";
echo "<b>Connessione</b>";
echo "</td>";
echo "<td align='center'>";
echo "<b>IP</b>";
echo "</td>";
echo "<td align='center'>";
echo "<b>ProdID</b>";
echo "</td>";
echo "<td align='center'>";
echo "<b>VendID</b>";
echo "</td>";
echo "<td align='center'>";
echo "<b>Font</b>";
echo "</td>";
echo "<td align='center'>";
echo "<b>Nome</b>";
echo "</td>";
echo "<td align='center'>";
echo "<b>Cucina</b>";
echo "</td>";
echo "<td align='center'>";
echo "<b>Rimuovi</b>";
echo "</td>";
echo "</tr>";


if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
     echo "<tr>";
    echo "<td>";
     echo $row["tipo"];
    echo "</td>";

    echo "<td align='center'>";
    echo $row["formatocarta"];
    echo "</td>";
    echo "<td align='center'>";
    echo $row["connessione"];
    echo "</td>";
    echo "<td align='center'>";
    echo $row["ip"];
    echo "</td>";
    echo "<td align='center'>";
    echo $row["prodID"];
    echo "</td>";
    echo "<td align='center'>";
    echo $row["vendID"];
    echo "</td>";
    echo "<td align='center'>";
    echo $row["codec"];
    echo "</td>";


     if ($row["cucina"] == 1)
     {
      echo "<td align='center'>";
      echo $row["nome"];
      echo "</td>";
      echo "<td align='center'>";
      echo "<input type=\"radio\" name=\"printer\" value=\"".$row["id"]."\" checked><br>";
      echo "</td>";
     }
     else
     {
      echo "<td align='center'>";
      echo $row["nome"];
      echo "</td>";
      echo "<td align='center'>";
      echo "<input type=\"radio\" name=\"printer\" value=\"".$row["id"]."\"><br>";
      echo "</td>";
     }
    echo "<td align='center'>";
    echo "<button type=\"submit\" name=\"croce\" value=\"".$row['id']."\"><img src='img/croce.png' width='15px' height='15px'></button>";
    echo "</td>";
    echo "</tr>";
    }
}
echo "</table>";

echo "<br>";
echo "<input type=\"HIDDEN\" name=\"press_cu\" value=\"1\">";
?>
<input type="submit" value="Imposta">
</fieldset>
</form> 
 
<form action="gestione_stampanti.php" method="post">
<fieldset style="width:400px">
<legend>Aggiungi Stampante</legend>
<?php
$sql = "SELECT * FROM stampanti";
$result = mysqli_query($link, $sql);
echo "<table class='fixed'>";
echo "<tr>";
echo "<td align='center'>";
echo "Tipo o stampante";
echo "</td>";
echo "<td align='center'>";
echo "Connessione";
echo "</td>";
echo "<td align='center'>";
echo "Indirizzo IP";
echo "</td>";
echo "<td align='center'>";
echo "ProductID";
echo "</td>";
echo "<td align='center'>";
echo "VendorID";
echo "</td>";
echo "<td align='center'>";
echo "Nome";
echo "</td>";
echo "<td align='center'>";
echo "Dimensione carta";
echo "</td>";
echo "<td align='center'>";
echo "Codifica";
echo "</td>";
echo "</tr>";
echo "<tr>";
echo "<td align='center'>";
$o = shell_exec("lpstat -d -p");
$res = explode("\n", $o);
$i = 0;
echo "<select id=\"selecttipo\" name=\"tipo\" onchange=\"select_tipo(this.value)\" style=\"font-size:80%;width:100px;\">";
echo "<option value=\"\"></option>";
echo "<option value=\"ESC-POS\">ESC-POS</option>";
foreach ($res as $r) {
    echo "r = ".$r."<br>";
    $active = 0;
    if (strpos($r, "printer") !== FALSE) {
        $r = str_replace("printer ", "", $r);
        if (strpos($r, "is idle") !== FALSE)
            $active = 1;
        $r = explode(" ", $r);
        echo "<option value=\"".$r[0]."\">".$r[0]."</option>";
    }
}

echo "</select>";
echo "</td>";

echo "<td align='center'>";
echo "<select id=\"selectconn\" name=\"connessione\" onchange=\"select_connessione(this.value)\" style=\"font-size:80%;width:100px;\">";
echo "<option value=\"\"></option>";
echo "<option value=\"USB\">USB</option>";
echo "<option value=\"Rete\">Rete</option>";
echo "</select>";
echo "</td>";

echo "<td align='center'>";
echo "<input id=\"indirizzoip\" type='text' style=\"font-size:80%;text-align:left\" size='12' name=\"ip\">";
echo "</td>";

echo "<td align='center'>";
echo "<input id=\"pid\" type='text' style=\"font-size:80%;text-align:left\" size='12' name=\"productid\">";
echo "</td>";

echo "<td align='center'>";
echo "<input id=\"vid\" type='text' style=\"font-size:80%;text-align:left\" size='12' name=\"vendorid\">";
echo "</td>";

echo "<td align='center'>";
echo "<input type='text' style=\"font-size:80%;text-align:left\" size='12' name=\"nomestampante\">";
echo "</td>";

echo "<td align='center'>";
echo "<select id=\"selectcarta\" name=\"carta\" style=\"font-size:80%;width:100px;\">";
echo "<option value=\"POS80\">80mm</option>";
echo "<option value=\"POS56\">56mm</option>";
echo "<option value=\"A5\">A5</option>";
echo "</select>";
echo "</td>";

echo "<td>";
$query = "SELECT * FROM caratteri";
$result = mysqli_query($link, $query);
echo "<select id=\"codcar\" name=\"codifica\" style=\"font-size:80%;width:100px;\">";
while($row = mysqli_fetch_assoc($result))
{
  $codec = $row['charset'];
  echo "<option value=\"".$codec."\">".$codec."</option>";
}
echo "</select>";
echo "</td>";
echo "</tr>";
echo "</table>";


echo "<input type=\"HIDDEN\" name=\"press_new\" value=\"1\">";
echo "<br>";
?>
<input type="submit" value="Aggiungi">
</fieldset>
</form> 

