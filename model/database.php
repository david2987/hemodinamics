<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'hemodinamics');
define('DB_USER', 'root');
define('DB_PASS', '');

class Database
{
    public static function StartUp()
    {
        $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8', DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
        return $pdo;
    }
}