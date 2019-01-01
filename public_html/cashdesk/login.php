<?
/*******************************************************************************
* CASH DESK - LOGIN SCRIPT                                                     *
*                                                                              *
* Version: 1.0                                                                 *
* Date:    21.11.2018                                                          *
* Author:  Stefano LUISE                                                       *
*******************************************************************************/
session_start();
?>
<!DOCTYPE html>
<html>
<head>


<!-- Disabilita la possibilità di tornare indietro dopo il login -->
<script>
history.forward();
</script>

</head>

<body leftmargin="0" topmargin="0">
<style>
/* di seguito gli stili implementati ai Form*/

div#box					{
					  border: 1px solid #fff;
					}

em					{
					  float:left;
					}
																		 
form#login				{
					  width: 240px;
					  font: 96% Arial, sans-serif;
					  padding:5px 0;
					  margin:20px;
					  border-style: solid; 
					  border-color: #006699; 
					  border-width: 1px;
					  background: #f0f0f0;
					}

					form p	{margin:1em 10px;
						}
						
input#go				{
					  width: 80px;
					  height:25px;
					  margin-left: 75px;
					  cursor:pointer;
					}									

fieldset				{
   					  border: none;
					  border-top: 1px solid #99A0FA;
					  height:200px;
					  width:200px;
					}
									
fieldset div				{
					  float:left;
					  width:100%;
					  padding: 10px 0 10px;
					  background-color: #f0f0f0;
					}									

legend					{
					  font-weight:bold;
					  color: #333;
					  background-color: #f0f0f0;
					}					
				
label					{
					  float:left;
					  width: 60px;
					  display: inline;
					  margin-left: 10px;
					  line-height: 24px;
					  font-size: 12px;
					}

input#in				{
					  width: 80px;
					  display: inline;
					  margin-left: 5px;
    					}

option    				{
					  font-family: verdana, tahoma, arial; 
					  color: red; 
					  font-size: 6px;
					}
</style>

<iframe src="bg_icon.php" frameborder="0" scrolling="no" width="1260" height="120"></iframe>

<?
include("../../Accounts_MySql/datilogin.txt");
?>

<div id="box">

<form id="login" method="post" action=<? echo $PATH_INFO ?>>
  <fieldset>
	 <legend align=bottom> Login utente  </legend> 

		<div>
			<label id="userid" for="userid">UserID:</label>
			<input id="in" name="userid" size="20" type="text">
		</div>
		
		<div>
			<label id="passwd" for="passwd">Password:</label>
			<input id="in" name="passwd" size="20" type="password">
		</div>
		
		<div>
		<label id="stampante" for="stampante">Stampante</label>
		<select style='font-size:11px;width:120px;font-color:#000000' name='stampante' id='stampante'>
<?
		$sql = "SELECT * FROM stampanti";
		$result = mysqli_query($link, $sql);
		while($row = mysqli_fetch_assoc($result)) {
		if ($row['cucina'] == 0)
		 {
		  echo "<option value='".$row['nome']."' size=18 style='width:80px;font-size:11px'>".$row['nome']."</option>";
		 }
		}

?>
		</select>
		</div>



		<input name="controllo" value="1234" type="hidden">
  
    <div>
    	<input id="go" value="Login" name="avvia_query" type="submit">
     </div>

  </fieldset>
</form>
</div>

<hr noshade size="1" width="96%" align="center">
<FONT FACE="Arial" SIZE="-1">
<CENTER>
<BR>Cash Desk - Gestione Sagre e Stand Gastronomici
</CENTER>
<P align="center">&copy; 2019 Stefano Luise</P>
</FONT>

<?

if (!get_magic_quotes_gpc())
{
   $user = addslashes($_POST['userid']);
   $passwd = addslashes($_POST['passwd']);
   $stampante = addslashes($_POST['stampante']);
}else{
   $user = $_POST['userid'];
   $passwd = $_POST['passwd'];
   $stampante = $_POST['stampante'];
}
$crypt_pass = md5($passwd);
$control = $_POST['controllo'];

if (isset($control))
{ 
/*############################################################################*/
/* Interrogazione del file delle variabili di accesso esclusivo per il Login  */
/* al Dbase MySql, al suo interno il file riporta le variabili del profilo    */
/* dell'utente 'inmysql' con i privilegi solo per effettuare le SELECT alla   */
/* tabella 'users' in 'orderdb'                                               */
/*############################################################################*/

$query = "SELECT * FROM users WHERE id LIKE '$user'"; 

$dati = mysqli_query($link,$query); 
$row = mysqli_fetch_array($dati);


 if (($row[0]<>$user) or ($row[1]<>$crypt_pass) or $row==FALSE )
	{ 
	?>
 	 <script type="text/javascript">
    	  window.alert('Attenzione!!\nUser ID o password errati.');
	 </script>
	<?
	 exit;
	} 
 else if (($row[0]==$user)and($row[1]==$crypt_pass)){
    $_SESSION['userid']=$user;
    $_SESSION['group']=$row[2];
    $_SESSION['funzione']=$row[3];
    $_SESSION['roles']=$row[4];
    $_SESSION['nome_alias']=$row[5];
    $_SESSION['e_mail']=$row[7];
    $_SESSION['enable']=TRUE;
    $_SESSION['stampante']=$stampante;
    $stato = "on";
    $sec = time();
    
   /* upload della tabella user per cambiare lo stato login  utente */
    $log_status = "UPDATE users SET logon ='$stato', SEC ='$sec' WHERE id ='$user'";
    mysqli_query ($link,$log_status);

    ?>
     <script type="text/javascript">
      if (window.confirm('Accesso garantito.\n\nPremere OK per continuare,\naltrimenti ANNULLA')){
	window.open('index.php','_top','');
      }
      else
      {
       window.self.close('','','');
       window.open('login.php','','');//ritorno del prompt della pagina login
      }
     </script>
    <? 		
 }

mysqli_free_result ($dati);
mysqli_close ($link);
}
?>
</body>
</html>
