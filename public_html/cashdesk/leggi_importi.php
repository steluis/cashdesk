<?
/*******************************************************************************
* CASH DESK - READ PRICE LIST AND PUT VALUES IN A STRING                       *
*                                                                              *
* Version: 1.0                                                                 *
* Date:    21.11.2018                                                          *
* Author:  Stefano Luise                                                       *
*******************************************************************************/

/*Modulo connessione data base */

	include("../../Accounts_MySql/datilogin.txt");

	$query = "SELECT * FROM listino ORDER BY id";
	$result = mysqli_query($link, $query);
	while($row=mysqli_fetch_array($result)){
	$stringa .= "|".$row['importo'];	
	}
    echo trim($stringa,"|");
?>
