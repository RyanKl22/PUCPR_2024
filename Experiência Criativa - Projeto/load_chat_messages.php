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

$response = [];

$sql = "SELECT c.comentario, c.data, fu.Primeiro_nome AS usuario_nome
        FROM comunicacoesviagem c
        LEFT JOIN funcao_user fu ON c.id_usuario = fu.ID
        WHERE c.id_grupo = ?
        ORDER BY c.data ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_grupo);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

$response['messages'] = $messages;

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
