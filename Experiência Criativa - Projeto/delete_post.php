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

// Obtendo o ID do roteiro a ser excluído
$data = json_decode(file_get_contents("php://input"), true);
$post_id = $data['id'];

if (!$post_id) {
    echo json_encode(['success' => false, 'message' => 'ID do roteiro não fornecido']);
    exit;
}

// Iniciando a transação
$conn->begin_transaction();

try {
    // Excluir o roteiro
    $conn->query("DELETE FROM roteirosViagem WHERE id = $post_id");

    // Excluir as localizações e valores
    $conn->query("DELETE FROM localizacoesvalores WHERE id_roteiro = $post_id");

    // Excluir as imagens
    $conn->query("DELETE FROM Imagens WHERE id_roteiro = $post_id");

    // Excluir grupos de viagem
    $grupos_result = $conn->query("SELECT id FROM gruposviagem WHERE id_roteiro = $post_id");
    while ($grupo = $grupos_result->fetch_assoc()) {
        $grupo_id = $grupo['id'];
        
        // Excluir membros do grupo
        $conn->query("DELETE FROM membrosgrupo WHERE id_grupo = $grupo_id");

        // Excluir comunicações de viagem
        $conn->query("DELETE FROM comunicacoesviagem WHERE id_grupo = $grupo_id");
    }
    
    // Excluir o grupo de viagem
    $conn->query("DELETE FROM gruposviagem WHERE id_roteiro = $post_id");

    // Commit da transação
    $conn->commit();
    
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    // Rollback em caso de erro
    $conn->rollback();
    
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();
?>
