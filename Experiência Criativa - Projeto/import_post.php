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

// Obtendo o ID do roteiro a ser importado e o ID do usuário logado
$data = json_decode(file_get_contents("php://input"), true);
$post_id = $data['id'];
$logged_in_user_id = $_SESSION['usuario_id'];

if (!$post_id || !$logged_in_user_id) {
    echo json_encode(['success' => false, 'message' => 'ID do roteiro ou usuário não fornecido']);
    exit;
}

// Iniciando a transação
$conn->begin_transaction();

try {
    // Copiar o roteiro
    $result = $conn->query("SELECT * FROM roteirosviagem WHERE id = $post_id");
    $roteiro = $result->fetch_assoc();
    $conn->query("INSERT INTO roteirosviagem (id_usuario, titulo, descricao, publico_privado) VALUES ($logged_in_user_id, '{$roteiro['titulo']}', '{$roteiro['descricao']}', '{$roteiro['publico_privado']}')");
    $new_roteiro_id = $conn->insert_id;

    // Copiar localizacoesvalores
    $result = $conn->query("SELECT * FROM localizacoesvalores WHERE id_roteiro = $post_id");
    while ($localizacao = $result->fetch_assoc()) {
        $conn->query("INSERT INTO localizacoesvalores (id_roteiro, nome_local, latitude, longitude, valor_gasto, moeda) VALUES ($new_roteiro_id, '{$localizacao['nome_local']}', '{$localizacao['latitude']}', '{$localizacao['longitude']}', '{$localizacao['valor_gasto']}', '{$localizacao['moeda']}')");
    }

    // Copiar imagens
    $result = $conn->query("SELECT * FROM imagens WHERE id_roteiro = $post_id");
    while ($imagem = $result->fetch_assoc()) {
        $conn->query("INSERT INTO imagens (id_roteiro, imagem) VALUES ($new_roteiro_id, '{$imagem['imagem']}')");
    }

    // Copiar gruposviagem e membrosgrupo
    $result = $conn->query("SELECT * FROM gruposviagem WHERE id_roteiro = $post_id");
    while ($grupo = $result->fetch_assoc()) {
        $conn->query("INSERT INTO gruposviagem (id_roteiro, id_admin, nome, descricao) VALUES ($new_roteiro_id, $logged_in_user_id, '{$grupo['nome']}', '{$grupo['descricao']}')");
        $new_grupo_id = $conn->insert_id;
        $conn->query("INSERT INTO membrosgrupo (id_grupo, id_usuario) VALUES ($new_grupo_id, $logged_in_user_id)");
    }

    // Commit da transação
    $conn->commit();
    
    echo json_encode(['success' => true, 'new_roteiro_id' => $new_roteiro_id]);
} catch (Exception $e) {
    // Rollback em caso de erro
    $conn->rollback();
    
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();
?>
