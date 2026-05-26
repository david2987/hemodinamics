<?php
header('Content-Type: application/json charset=UTF-8');
require_once __DIR__ . '/../model/database.php';
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME) or die("Connection failed: " . mysqli_connect_error());



$titulo = $_GET['phrase'];
$sql = "SELECT producto_titulo, cod_producto , detalle FROM productos WHERE producto_titulo LIKE '%" . $titulo . "%' ";
$queryRecords = mysqli_query($conn, $sql) or die('error to fetch data');

$result = [];
foreach ($queryRecords as $res) {
  $result[] = [
    'name' => trim($res['producto_titulo']),
    'cod_producto' => trim($res['cod_producto']),
    'detalle' => trim($res['detalle'])
  ];
}


// 1. Limpiamos el array asegurando que todo sea UTF-8 válido
array_walk_recursive($result, function (&$item) {
  if (is_string($item)) {
    // Detecta y repara caracteres mal codificados
    $item = mb_convert_encoding($item, 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252');
  }
});

// 2. Ahora el json_encode funcionará perfectamente
$json = json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

echo $json;
