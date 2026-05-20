<?php
  header('Content-Type: application/json charset=UTF-8');
require_once __DIR__ . '/../model/database.php';
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME) or die("Connection failed: " . mysqli_connect_error());

 

  $titulo = $_GET['phrase'];
  $sql = "SELECT producto_titulo, cod_producto , detalle FROM productos WHERE producto_titulo LIKE '%".$titulo."%' ";
  $queryRecords = mysqli_query($conn, $sql) or die('error to fetch data');

  $result = [];
  foreach($queryRecords as $res) {
      $result[] = [
          'name' => trim($res['producto_titulo']),
          'cod_producto' => trim($res['cod_producto']),
          'detalle' => trim($res['detalle'])
      ];
  }

  echo json_encode($result);
?>