<?
/*******************************************************************************
* CASH DESK - FUNCTION session_exists()                                        *
*                                                                              *
* Version: 1.0                                                                 *
* Date:    21.11.2018                                                          *
* Author:  Anonymous                                                           *
*******************************************************************************/
/* Funzione per salvaguardare l'entropia del generatore di sessioni       */
/* Viene evitato lo start di sessione ogni volta che si accede ad         */
/* una pagina dopo che l'utente ha effettuato il login e quindi ha        */
/* avviato la session_start().  Questa funzione sostituisce il ripetersi  */
/* dell'apertura di una nuova sessione.                                   */
/* E' un controllo per innalzare le difese contro attacchi di accesso     */
/* illegale.                                                              */

/* .boolean. */ 
function session_exists()
/*
    Risulta TRUE se una sessione client e' attiva.

    Una sessione e'valida se ad un determinato client risulta un cookie valido 
    e corresponde ad una sessione nel file. In questo caso, richiama il cookie
    e ripristina la $_SESSION[].
    Se la session non esiste, risulta FALSE.
		
    Si puo' considerare questa funzione un'alternativa alla session_start(),
    ma con la sostanziale differenza che il cookie rilasciato dal client non
    e' una sessione valida o diversamente il client non rilascia un cookie a
    tutti cosi' non vien aperta una nuova sessione per preservare l'entropia. 
*/
{
    if( $GLOBALS['_session_started'] )
        return TRUE;
    $sn = session_name();
    if( ! isset( $_COOKIE[$sn] ) )
        # Nessun cookie dal client.
        return FALSE;
    $sv = (string) $_COOKIE[$sn];
    if( preg_match('/^[-,a-zA-Z0-9]+$/', $sv) !== 1 )
        # Sisntassi non valida del cookie.
        return FALSE;
    $sf = session_save_path() ."/sess_". $sv;
    if( ! file_exists($sf) )
        # Questo cookie non e' una sessione o la sessione e' scaduta.
        return FALSE;
    session_start();  # ripristina la sessione.
    if( session_id() === $sv ){
        # La sessione ripristinata e' quella appena analizzata.
        $GLOBALS['_session_started'] = TRUE;
        return TRUE;
    }
    # Lo stato della routine ha rilevato: che la vecchia sessione $sv nel
    # frattempo e' scaduta ed una nuova e' stata creata dalla funzione
    # session_start(), quindi $_SESSION[] e' vuota. 
    # Riposizionamento allo stato iniziale e risultato FALSE.
    session_destroy();
    return FALSE;
}
?>
