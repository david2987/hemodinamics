<?php
  header('Content-Type: application/json charset=UTF-8');
require_once __DIR__ . '/../model/database.php';
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME) or die("Connection failed: " . mysqli_connect_error());

 

  $titulo = $_GET['phrase'];
  $sql = "SELECT HospDesc,HospCod FROM hospitales where HospDesc like '%".$titulo."%' ";
 $queryRecords = mysqli_query($conn, $sql) or die('error to fetch  data');

 $json = '['; 
foreach($queryRecords as $res):
    $json .= '{"name":"'.trim($res['HospDesc']).'","cod_producto":"'.trim($res['HospCod']).'"},' ;
endforeach;
$json = substr($json, 0, -1);
$json .= ']'; 

/*limpia caracteres*/ 
$json = trim($json);
$json = preg_replace("/[\r\n|\n|\r]+/", "\\n", $json);
/******************/

echo utf8_encode($json);
?>