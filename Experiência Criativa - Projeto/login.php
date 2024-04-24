<?php
session_start(); // Inicia a sessão, que é usada para salvar informações do usuário logado

include 'db.php'; // Inclui o script de conexão com o banco de dados

// Verifica se o email e a senha foram enviados
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $senha = $_POST['password'];

    // Prepara a consulta SQL para verificar se o usuário existe com o email fornecido
    $stmt = $conn->prepare("SELECT * FROM usuario WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();

        // Verifica a senha
        if (password_verify($senha, $usuario['Senha'])) {
            // Senha está correta, salva as informações necessárias na sessão
            $_SESSION['usuario_email'] = $usuario['Email'];
            
            // Redireciona para a página desejada após o login
            header("Location: main.html");
            exit();
        } else {
            // Senha incorreta
            header("Location: index.html?erro=senha_incorreta"); // Use o nome do seu arquivo de entrada se não for index.html
            exit();
        }
    } else {
        // Usuário não encontrado
        header("Location: index.html?erro=usuario_nao_encontrado"); // Use o nome do seu arquivo de entrada se não for index.html
        exit();
    }

    $stmt->close();
} else {
    // Não foi postado um email ou senha
    header("Location: index.html?erro=dados_incompletos"); // Use o nome do seu arquivo de entrada se não for index.html
    exit();
}

$conn->close();
?>

