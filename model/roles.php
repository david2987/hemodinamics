<?php
// Roles de usuario (catalogo sisgru): 1=Administrador, 2=Operador, 3=Coordinador, 4=Operador/Coord, 5=Deposito, 6=Consumos, 7=Facturacion
define('GRUCOD_ADMINISTRADOR', 1);
define('GRUCOD_OPERADOR', 2);
define('GRUCOD_COORDINADOR', 3);
define('GRUCOD_OPERADOR_COORD', 4);
define('GRUCOD_DEPOSITO', 5);
define('GRUCOD_CONSUMOS', 6);
define('GRUCOD_FACTURACION', 7);

function GruCodActual() {
    return isset($_SESSION['user']['GruCod']) ? (int)$_SESSION['user']['GruCod'] : null;
}

function EsAdmin() {
    return GruCodActual() === GRUCOD_ADMINISTRADOR;
}

function EsCoordinador() {
    return in_array(GruCodActual(), [GRUCOD_COORDINADOR, GRUCOD_OPERADOR_COORD], true);
}
