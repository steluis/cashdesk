<?
/*******************************************************************************
* CASH DESK - GET EXPORT COST                                                  *
*                                                                              *
* Version: 1.0                                                                 *
* Date:    21.11.2018                                                          *
* Author:  Stefano Luise                                                       *
*******************************************************************************/
/*Modulo connessione data base */

include("../../Accounts_MySql/datilogin.txt");

	$query = "SELECT * FROM listino WHERE descrizione LIKE 'ESPORTAZIONE'";
	$result = mysqli_query($link, $query);
	$row=mysqli_fetch_array($result);
	$stringa = "0|".$row['importo'];	
	echo $stringa;
?>


