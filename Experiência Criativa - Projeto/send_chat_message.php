<?php
include 'auth.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "JBB";

// Criando a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Checando a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$data = json_decode(file_get_contents('php://input'), true);
$id_grupo = $data['id_grupo'];
$id_usuario = $data['id_usuario'];
$comentario = $data['comentario'];
$data_envio = date('Y-m-d H:i:s');

$response = [];

$sql = "INSERT INTO comunicacoesviagem (id_grupo, id_usuario, comentario, data) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiss", $id_grupo, $id_usuario, $comentario, $data_envio);

if ($stmt->execute()) {
    $response['success'] = true;
} else {
    $response['success'] = false;
    $response['error'] = $stmt->error;
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
