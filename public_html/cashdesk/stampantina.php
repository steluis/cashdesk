<?
$prodid = $_POST['prodid'];
$vendid = $_POST['vendid'];
$stringa_scontrino = $_POST['stringa_scontrino'];
$stringa_asporto = $_POST['stringa_asporto'];
$coperti = $_POST['coperti'];
$stringa_prt = $_POST['stringa_prt'];
$logo = $_POST['logo'];
$intestazione = $_POST['intestazione'];
$codec = $_POST['codec'];

file_put_contents("logfile.log","Scontrino=".$stringa_scontrino."   Stampante:".$prodid." ".$vendid."\n",FILE_APPEND);

exec("sudo ./stampa.bash \"".$stringa_scontrino."\" \"".$stringa_asporto."\" \"".$coperti."\" \"".$stringa_prt."\" \"".$logo."\" \"".$intestazione."\" \"".$prodid."\" \"".$vendid."\" \"".$codec."\"");
?>
