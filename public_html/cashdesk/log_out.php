<?
/*******************************************************************************
* CASH DESK - SESSION LOGOUT                                                   *
*                                                                              *
* Version: 1.0                                                                 *
* Date:    21.11.2018                                                          *
* Author:  Stefano LUISE                                                       *
*******************************************************************************/
# Destroy current session:
include("session_exists.php");

if( session_exists() )
{
/*############################################################################*/
/*# Interrogazione del file delle variabili di accesso esclusivo per il Login#*/
/*# al Dbase MySql, al suo interno il file riporta le variabili del profilo  #*/
/*# dell'utente 'inmysql' con i privilegi solo per effettuare le SELECT alla #*/
/*# tabella 'users' in 'orderdb'                                             #*/
/*############################################################################*/
    include("../../Accounts_MySql/datilogin.txt");

/*############################################################################*/
/*# Prima della chiusura e distruzione della sessione utente, viene riporta- #*/
/*# ta a 'off' la condizione di stato logon utente.                          #*/
/*# La tabella users in orderdb riporta in 'logon' lo stato utente.          #*/
/*#                                                                          #*/
/*############################################################################*/

    $user = $_SESSION['userid'];
    $stato = "off";
    $log_status =	"UPDATE users SET logon ='$stato' WHERE id='$user'";
    mysqli_query ($link,$log_status);

    session_destroy();
    $_SESSION = /*. (array[string]mixed) .*/ array();
    setcookie(session_name(), '', 0, '/');
}
?>
<!DOCTYPE html>
<head>
<title>Logout utente</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="cssnn.css" rel="stylesheet" type="text/css">

<!-- Disabilita la possibilità di tornare indietro dopo il login -->
<script>
history.forward();
</script>

</head>
<body>
<h1></h1>
      		
      <script type="text/javascript">
				if (window.confirm('Log Out.\n\nPremere OK per confermare il Logout e chiudere la finestra,\nANNULLA per aprire una nuova sessione')){
      	window.self.close('','','');
        }
        else
        {
				window.open('login.php','_top','');//ritorno del prompt della pagina login
		}
      </script>
</body>
</html>

