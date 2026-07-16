<?php

$host = "l127.0.0.1";
$db = "sisbecas_web";
$user = "root";
$password = "!Y)m_yxsccxz0B3l";

try
{
    $pdo = new PDO(
        "mysql:host=localhost;dbname=sisbecas_web;charset=utf8mb4",
        $user,
        $password
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}
catch(PDOException $e)
{
    die("Error de conexión: " . $e->getMessage());
}