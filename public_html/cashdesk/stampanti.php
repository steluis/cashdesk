<?php
$servername = "localhost";
$username = "stefano";
$password = "campa69";
$dbname = "sardea";
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection

?>
<form action="stampanti.php" method="post">
  <fieldset style="width:300px">
    <legend>Seleziona stampante cucina</legend>
<?php
/***********************************/
/*   Pressione pulsante IMPOSTA    */
/*   In Selezione Stampante Cucina */
/***********************************/
if (isset($_POST['press_cu']))
{
  $sql = "SELECT * FROM stampanti";
  $result = mysqli_query($conn, $sql);
  while($row = mysqli_fetch_assoc($result)) {
   $indice = $row['id'];
   $buffer = "UPDATE stampanti SET cucina='0' WHERE id LIKE '".$row['id']."'";  
   $coda = mysqli_query($conn, $buffer);
  }
  $id_st = $_POST['printer']; 
  $buffer = "UPDATE stampanti SET cucina=\"1\" WHERE id LIKE '".$id_st."'";
  $coda = mysqli_query($conn, $buffer);
}
/* FINE pressione pulsante IMPOSTA in Selezione Stampante Cucina */

/***********************************/
/*   Pressione pulsante IMPOSTA    */
/*         In Formato Carta        */
/***********************************/
if (isset($_POST['press_size']))
{
$sql = "SELECT * FROM stampanti";
$result = mysqli_query($conn, $sql);
 while($row = mysqli_fetch_assoc($result)) {
  $sel = $_POST[$row ['id']];
  $buffer = "UPDATE stampanti SET formatocarta='".$sel."' WHERE id LIKE '".$row['id']."'";  
  $coda = mysqli_query($conn, $buffer);
//  echo "Opzione_".$row ['id']."=".$sel."<br>";
 }
}
/* FINE pressione pulsante IMPOSTA in Formato Carta */

/***********************************/
/*      Pressione pulsante         */
/*      Ricarica Stampanti         */
/***********************************/
if (isset($_POST['press_ricarica']))
{
echo "Premuto Ricarica<br>";
}
/* FINE pressione pulsante IMPOSTA in Formato Carta */



$sql = "SELECT * FROM stampanti";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
     if ($row["cucina"] == 1)
      echo $row["nome"]."<input type=\"radio\" name=\"printer\" value=\"".$row["id"]."\" checked><br>";
     else
      echo $row["nome"]."<input type=\"radio\" name=\"printer\" value=\"".$row["id"]."\"><br>";
    }
}
echo "<br>";
echo "<input type=\"HIDDEN\" name=\"press_cu\" value=\"1\">";
?>
<input type="submit" value="Imposta">
</fieldset>
</form> 
 
<form action="stampanti.php" method="post">
<fieldset style="width:300px">
<legend>Formato carta</legend>
<?php
$sql = "SELECT * FROM stampanti";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
      echo $row["nome"]."    ";
      
      echo "<select name='".$row['id']."'>";
	  echo "<option value='".$row['formatocarta']."'>".$row['formatocarta']."</option>";
	  echo "<option value='56mm'>56mm</option>";
      echo "<option value='80mm'>80mm</option>";
      echo "<option value='A5'>A5</option>";
      echo "</select><br><br>";
    }
}
echo "<input type=\"HIDDEN\" name=\"press_size\" value=\"1\">";
mysqli_close($conn);
?>
<input type="submit" value="Imposta">
</fieldset>
</form> 

<form action="stampanti.php" method="post">
<?php
 echo "<input type=\"HIDDEN\" name=\"press_ricarica\" value=\"1\">";
?>
 <input type="submit" value="Ricarica Stampanti">
</form> 
 