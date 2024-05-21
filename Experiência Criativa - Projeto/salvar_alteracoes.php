<?php

include 'auth.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id_geral'])) {
    die("Acesso negado.");
}

$usuario_id = $_SESSION['usuario_id_geral'];
$novo_telefone = $_POST['telefone'];

// Conecta ao banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "JBB";

$conn = new mysqli($servername, $username, $password, $dbname);

// Checa a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Atualiza o telefone no banco de dados
if ($_SESSION['usuario_pj_pf'] == 0) {
    $sql = "UPDATE usuarios_PF SET Telefone = ? WHERE Id = ?";
} else {
    $sql = "UPDATE usuarios_PJ SET Telefone = ? WHERE Id = ?";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param('si', $novo_telefone, $usuario_id);

if ($stmt->execute()) {
    echo "Telefone atualizado com sucesso.";
} else {
    echo "Erro ao atualizar o telefone.";
}

$stmt->close();
$conn->close();
?>
