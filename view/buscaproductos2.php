<?php

require_once __DIR__ . '/../model/database.php';
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME) or die("Connection failed: " . mysqli_connect_error());

 

if(!empty($_GET['phrase'])){
  $titulo = $_GET['phrase'];
}else
{
  $titulo = '';
}

  $sql = "SELECT producto_titulo,cod_producto FROM productos where producto_titulo like '%".$titulo."%' ";
 $queryRecords = mysqli_query($conn, $sql) or die('error to fetch  data');

 $json = '';
// $json = '['; 
foreach($queryRecords as $res):
  //  $json .= '{"name":"'.trim($res['producto_titulo']).'","cod_producto":"'.trim($res['cod_producto']).'"},' ;
  $json .= trim($res['producto_titulo']).'|';
endforeach;
echo $json ;
//$json = substr($json, 0, -1);
//$json .= ']'; 

/*limpia caracteres*/ 
//$json = trim($json);
//$json = preg_replace("/[\r\n|\n|\r]+/", "\\n", $json);
/******************/

//echo utf8_encode($json);
?>