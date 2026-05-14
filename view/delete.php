<?php 
require_once '../model/database.php';
require_once '../model/presupuesto.php';
$presupuesto = new Presupuesto();

echo "<br><br><br><div style='width:100%' align='center' >";
if(isset($_REQUEST['id'])){
  echo $presupuesto->Eliminar($_REQUEST['id']);
}

echo "<script>window.parent.opener.location.reload();</script>"

?>

