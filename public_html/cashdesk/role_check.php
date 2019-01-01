<?
/*******************************************************************************
* CASH DESK - FUNCTION session_exists()                                        *
* Funzione per la determinazione del ruolo del client assegnato nella fase     *
* di login                                                                     *
*                                                                              *
* Version: 1.0                                                                 *
* Date:    21.11.2018                                                          *
* Author:  Anonymous                                                           *
*******************************************************************************/
/*. bool .*/ 
function role_check(/*. int .*/ $n)
{
    if( ! isset($_SESSION['roles']) ){
        trigger_error("\$_SESSION['roles'] not set");
        return FALSE;
    }
    $r = (string) $_SESSION['roles'];
    if( ($n < 0) or ($n >= strlen($r)) ){
        trigger_error("no role $n in roles $r");
        return FALSE;
    }
    return $r[$n] === "1";
}
?>
