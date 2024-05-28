<?php

include "db."
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

session_start();
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(["error" => "Usuário não está logado."]);
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "JBB";

// Criando a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Checando a conexão
if ($conn->connect_error) {
    echo json_encode(["error" => "Conexão falhou: " . $conn->connect_error]);
    exit();
}


$stmt->close();
$conn->close();

echo json_encode($data);
exit();
?>
