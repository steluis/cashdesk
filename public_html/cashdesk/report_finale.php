<?
/*******************************************************************************
* CASH DESK - PRINT FINAL REPORT                                               *
*                                                                              *
* Version: 1.0                                                                 *
* Date:    21.11.2018                                                          *
* Author:  Stefano Luise                                                       *
*******************************************************************************/

//Controllo accesso
include("session_exists.php");
// viene sostituita la funzione di session_start();
$da = $_POST['date3'];
$a = $_POST['date2'];

$data_da = date('Y-m-d',strtotime($da));
$data_a = date('Y-m-d',strtotime($a));

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

  /*Modulo connessione data base */
include("../../Accounts_MySql/datilogin.txt");

$coda = "SELECT * FROM parametri WHERE descrizione LIKE 'logo'";
$dati = mysqli_query ($link, $coda);
$row=mysqli_fetch_assoc($dati);
$logo = $row['valore'];
require('./fpdf/fpdf.php');
$pdf=new FPDF();
$pdf->AddPage();
//$pdf->Image("./logos/".$logo,4,4,30,30);
$pdf->SetFont('Arial','BI',20);
$pdf->Text(50,25,"Report finale");
$pdf->SetFont('Arial','',12);
$pdf->Text(50,32,"Dal ".$da." al ".$a);
$data_s = date("d-m-Y");
$pdf->Text(100,25,$data_s);

$xq = 10;
$yq = 55;
$delta_q = 0;
$xi = 110;
$yi = 55;
$delta_i = 0;

/* Stampa dati cucina */
 $pdf->SetFont('Arial','',10);
 $totale_piatti_cucina = 0;
 $query2 = "SELECT descrizione,SUM(qta) FROM scontrini WHERE ((nullo !=1)&&(tipo_piatto NOT LIKE 'bar')&&(data >= '".$data_da."')&&(data <= '".$data_a."')) GROUP BY descrizione";
 $result2 = mysqli_query($link, $query2);
 while($row2=mysqli_fetch_assoc($result2))
 {
  $somma = $row2['SUM(qta)'];
  $pdf->Text($xq,$yq+$delta_q,utf8_decode($row2['descrizione']));
  $pdf->Text($xq+70,$yq+$delta_q,$somma);
  $delta_q += 4;
  $totale_piatti_cucina += $somma;
 }

 $totale_importo_cucina = 0;
 $query2 = "SELECT descrizione,SUM(importo) FROM scontrini WHERE ((nullo !=1)&&(tipo_piatto NOT LIKE 'bar')&&(data >= '".$data_da."')&&(data <= '".$data_a."')) GROUP BY descrizione";
 $result2 = mysqli_query($link, $query2);
 while($row2=mysqli_fetch_assoc($result2))
 {
  $somma = bcadd($row2['SUM(importo)'], 0, 2);
  $somma_fmt = number_format($somma, 2,',','.');
  $pdf->Text($xi,$yi+$delta_i,EURO);
  $sposta = 2.0*(7-strlen($somma_fmt));
  $pdf->Text($xi+10+$sposta,$yi+$delta_i,$somma_fmt);
  $delta_i += 4;
  $totale_importo_cucina += $somma;
 }

 $pdf->SetFont('Arial','B',11);
 $pdf->Text($xq,$yq+$delta_q,"TOTALE CUCINA");
 $pdf->Text($xq+70,$yq+$delta_q,$totale_piatti_cucina);
 $pdf->Text($xi,$yi+$delta_i,EURO);
 $totale_importo_cucina = bcadd($totale_importo_cucina, 0, 2);
 $totale_importo_cucina_fmt = number_format($totale_importo_cucina, 2,',','.');
 $sposta = 1.7*(7-strlen($totale_importo_cucina_fmt));
 $pdf->Text($xi+10+$sposta,$yi+$delta_i,$totale_importo_cucina_fmt);

/* FINE Stampa dati cucina */


/* Stampa dati bar */

 $pdf->SetFont('Arial','',10);
 $totale_piatti_bar = 0;
 $delta_q += 8;
 $query2 = "SELECT descrizione,SUM(qta) FROM scontrini WHERE ((nullo !=1)&&(tipo_piatto LIKE 'bar')&&(data >= '".$data_da."')&&(data <= '".$data_a."')) GROUP BY descrizione";
 $result2 = mysqli_query($link, $query2);
 while($row2=mysqli_fetch_assoc($result2))
 {
  $somma = $row2['SUM(qta)'];
  $pdf->Text($xq,$yq+$delta_q,utf8_decode($row2['descrizione']));
  $pdf->Text($xq+70,$yq+$delta_q,$somma);
  $delta_q += 4;
  $totale_piatti_bar += $somma;
 }


 $pdf->SetFont('Arial','',10);
 $totale_importo_bar = 0;
 $delta_i += 8;
 $query2 = "SELECT descrizione,SUM(importo) FROM scontrini WHERE ((nullo !=1)&&(tipo_piatto LIKE 'bar')&&(data >= '".$data_da."')&&(data <= '".$data_a."')) GROUP BY descrizione";
 $result2 = mysqli_query($link, $query2);
 while($row2=mysqli_fetch_assoc($result2))
 {
  $somma = bcadd($row2['SUM(importo)'], 0, 2);
  $somma_fmt = number_format($somma, 2,',','.');
  $pdf->Text($xi,$yi+$delta_i,EURO);
  $sposta = 2.0*(7-strlen($somma_fmt));
  $pdf->Text($xi+10+$sposta,$yi+$delta_i,$somma_fmt);
  $delta_i += 4;
  $totale_importo_bar += $somma;
 }

 $pdf->SetFont('Arial','B',11);
 $pdf->Text($xq,$yq+$delta_q,"TOTALE BAR");
 $pdf->Text($xq+70,$yq+$delta_q,$totale_piatti_bar);
 $pdf->Text($xi,$yi+$delta_i,EURO);
 $totale_importo_bar = bcadd($totale_importo_bar, 0, 2);
 $totale_importo_bar_fmt = number_format($totale_importo_bar, 2,',','.');
 $sposta = 1.7*(7-strlen($totale_importo_bar_fmt));
 $pdf->Text($xi+10+$sposta,$yi+$delta_i,$totale_importo_bar_fmt);

/* FINE Stampa dati bar */

  $pdf->Line($xq,$yi+$delta_i+2,$xq+140,$yi+$delta_i+2);
  $pdf->Text($xq,$yi+$delta_i+7,"TOTALE GENERALE");
  $pdf->Text($xi,$yi+$delta_i+7,EURO);
  $totale_generale = bcadd($totale_importo_bar+$totale_importo_cucina, 0, 2);
  $totale_piatti = $totale_piatti_bar+$totale_piatti_cucina;
  $totale_generale_fmt = number_format($totale_generale, 2,',','.');
  $sposta = 1.7*(7-strlen($totale_generale_fmt));
  $pdf->Text($xq+70,$yi+$delta_i+7,$totale_piatti);
  $pdf->Text($xi+10+$sposta,$yi+$delta_i+7,$totale_generale_fmt);


 $totale_coperti = 0;
 $query2 = "SELECT * FROM scontrini WHERE ((nullo !=1)&&(data >= '".$data_da."')&&(data <= '".$data_a."')) GROUP BY scontrino";
 $result2 = mysqli_query($link, $query2);
 $pdf->SetFont('Arial','B',12);
 while($row2=mysqli_fetch_assoc($result2))
 {
  $totale_coperti += $row2['coperti'];
 }
 $pdf->Text(10,40,"Totale coperti : ".$totale_coperti);

 $query = "SELECT * FROM parametri WHERE descrizione LIKE 'numero_scontrino'";
 $result = mysqli_query($link, $query);
 $row=mysqli_fetch_assoc($result);
 $numero_scontrino = $row['valore'];
 $pdf->SetFont('Arial','B',12);
 $pdf->Text(92,40,"Scontrini emessi : ".$numero_scontrino);

 $pdf->Output();


