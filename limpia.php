<?php
require_once __DIR__ . '/model/database.php';
$pdoHeader = Database::StartUp();
$stm = $pdoHeader->query("SELECT ParCod, ParVarChr From sispar where Parcod >= 48");
$rutas = $stm->fetchAll(PDO::FETCH_OBJ);
foreach ($rutas as $e) {
    if ($e->ParCod == 48) {
        $Rutaapp = $e->ParVarChr;
    }
    if ($e->ParCod == 49) {
        $RutaappTomcat = $e->ParVarChr;
    }
}

$_POST = array();

header('location: ' . $Rutaapp.'/index.php');
