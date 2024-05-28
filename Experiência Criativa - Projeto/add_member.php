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
$id_usuario = $data['id_usuario'];
$id_grupo = $data['id_grupo'];

$response = [];

if (empty($id_usuario) || empty($id_grupo)) {
    $response['success'] = false;
    $response['error'] = 'ID de usuário ou grupo está vazio.';
} else {
    $sql = "INSERT INTO membrosgrupo (id_usuario, id_grupo) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_usuario, $id_grupo);

    if ($stmt->execute()) {
        $response['success'] = true;
    } else {
        $response['success'] = false;
        $response['error'] = $stmt->error;
    }

    $stmt->close();
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
