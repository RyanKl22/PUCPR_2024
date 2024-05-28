<?php
session_start();
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

// Verifica se a solicitação POST contém a senha antiga, a nova senha e o ID do usuário
if (isset($_POST['senha_antiga'], $_POST['senha_nova'], $_SESSION['usuario_id'])) {
    // Obtendo a senha antiga, a nova senha e o ID do usuário da sessão
    $senhaAntiga = $_POST['senha_antiga'];
    $novaSenha = $_POST['senha_nova'];
    $usuarioId = $_SESSION['usuario_id'];

    // Consulta SQL para obter a senha armazenada no banco de dados para o usuário com o ID fornecido
    $query = "SELECT Senha FROM usuario WHERE ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $usuarioId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificando se a consulta retornou alguma linha (ou seja, se o usuário existe)
    if ($result->num_rows == 1) {
        $usuario = $result->fetch_assoc();
        $senhaArmazenada = $usuario['Senha'];

        // Verificando se a senha antiga corresponde à senha armazenada usando password_verify
        if (password_verify($senhaAntiga, $senhaArmazenada)) {
            // Senha antiga válida, então podemos prosseguir com a troca de senha

            // Hashing da nova senha
            $novaSenhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);

            // Consulta SQL para atualizar a senha no banco de dados para o usuário com o ID fornecido
            $queryTrocarSenha = "UPDATE usuario SET Senha = ? WHERE ID = ?";
            $stmtTrocarSenha = $conn->prepare($queryTrocarSenha);
            $stmtTrocarSenha->bind_param("si", $novaSenhaHash, $usuarioId);

            // Executando a consulta para trocar a senha
            if ($stmtTrocarSenha->execute()) {
                // Senha alterada com sucesso, retornando uma resposta JSON com status 'success'
                $response = array('status' => 'success', 'message' => 'Senha alterada com sucesso.');
            } else {
                // Erro ao trocar a senha, retornando uma resposta JSON com status 'error' e uma mensagem de erro
                $response = array('status' => 'error', 'message' => 'Erro ao trocar a senha: ' . $stmtTrocarSenha->error);
            }
        } else {
            // Senha antiga inválida, retornando uma resposta JSON com status 'error' e uma mensagem de erro
            $response = array('status' => 'error', 'message' => 'A senha antiga inserida é inválida.');
        }
    } else {
        // Usuário não encontrado, retornando uma resposta JSON com status 'error'
        $response = array('status' => 'error', 'message' => 'Usuário não encontrado.');
    }
} else {
    // Se a senha antiga, a nova senha ou o ID do usuário não estiverem presentes na solicitação POST, retornar uma resposta JSON com status 'error'
    $response = array('status' => 'error', 'message' => 'A senha antiga, a nova senha ou o ID do usuário não foram fornecidos.');
}

// Retornando a resposta como JSON
echo json_encode($response);

// Fechando a conexão com o banco de dados
$conn->close();
?>
