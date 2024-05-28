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
$group_id = $data['id'];
$nome = $data['nome'];
$descricao = $data['descricao'];

$sql = "UPDATE gruposviagem SET nome = ?, descricao = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $nome, $descricao, $group_id);

$response = [];
if ($stmt->execute()) {
    $response['success'] = true;
} else {
    $response['success'] = false;
    $response['error'] = $stmt->error;
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>
