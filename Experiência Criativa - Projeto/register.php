<?php
include 'db.php'; // Inclui o script de conexão

$registroBemSucedido = false; // Flag para rastrear sucesso ou falha

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se o tipo de conta é Pessoa Física
    if (isset($_POST['accountType']) && $_POST['accountType'] == 'Pessoa Fisica' && isset($_POST['email']) && isset($_POST['password'])) {
        // Inicializa as variáveis com os dados do POST
        $primeiroNome = $_POST['firstName'];
        $segundoNome = $_POST['lastName'];
        $email = $_POST['email'];
        $dataNascimento = $_POST['dataNascimento'];
        $cpf = $_POST['cpf'];
        $genero = $_POST['gender'];
        $telefone = $_POST['mobilePhone'];

        // Verifica se o email já está em uso
        $stmtVerificarEmail = $conn->prepare("SELECT * FROM Usuarios_pf WHERE Email = ?");
        $stmtVerificarEmail->bind_param("s", $email);
        $stmtVerificarEmail->execute();
        $resultado = $stmtVerificarEmail->get_result();
        if ($resultado->num_rows > 0) {
            // O email já está em uso, redireciona de volta ao formulário com um parâmetro indicando o erro
            header('Location: register.html?registro=erro_email');
            exit(); // Encerra o script para evitar que o restante do código seja executado
        }
        $stmtVerificarEmail->close();

        // Prepara e executa a inserção na tabela Usuarios_pf
        $stmtPf = $conn->prepare("INSERT INTO Usuarios_pf (PrimeiroNome, SegundoNome, Email, DataNascimento, CPF, Genero, Telefone) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmtPf->bind_param("sssssss", $primeiroNome, $segundoNome, $email, $dataNascimento, $cpf, $genero, $telefone);
        
        $registroBemSucedido = $stmtPf->execute();
        $stmtPf->close();
    }
    elseif (isset($_POST['accountType']) && $_POST['accountType'] == 'Pessoa Juridica' && isset($_POST['email']) && isset($_POST['password'])) {
        // Inicializa as variáveis com os dados do POST
        $nomeFantasia = $_POST['tradeName'];
        $razaoSocial = $_POST['corporateName'];
        $cnpj = $_POST['cnpj'];
        $email = $_POST['email'];
        $dataAbertura = $_POST['openDate'];
        $inscricaoEstadual = $_POST['stateRegistration'];
        $telefone = $_POST['contactPhone'];

        // Verifica se o email já está em uso
        $stmtVerificarEmail = $conn->prepare("SELECT * FROM Usuarios_pj WHERE Email = ?");
        $stmtVerificarEmail->bind_param("s", $email);
        $stmtVerificarEmail->execute();
        $resultado = $stmtVerificarEmail->get_result();
        if ($resultado->num_rows > 0) {
            // O email já está em uso, redireciona de volta ao formulário com um parâmetro indicando o erro
            header('Location: register.html?registro=erro_email');
            exit(); // Encerra o script para evitar que o restante do código seja executado
        }
        $stmtVerificarEmail->close();

        // Prepara e executa a inserção na tabela usuarios_pj
        $stmtPj = $conn->prepare("INSERT INTO usuarios_pj (NomeFantasia, RazaoSocial, CNPJ, Email, DataAbertura, InscricaoEstadual, Telefone) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmtPj->bind_param("sssssss", $nomeFantasia, $razaoSocial, $cnpj, $email, $dataAbertura, $inscricaoEstadual, $telefone);
        
        $registroBemSucedido = $stmtPj->execute();
        $stmtPj->close();
    }

    // Processa o registro na tabela usuario comum para ambas as contas
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $senhaCriptografada = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmtUsuario = $conn->prepare("INSERT INTO usuario (Email, Senha) VALUES (?, ?)");
        $stmtUsuario->bind_param("ss", $email, $senhaCriptografada);
        
        $registroBemSucedido &= $stmtUsuario->execute(); // Atualiza o estado de sucesso com base nesta inserção também
        $stmtUsuario->close();
    }

    // Redireciona para index.html com um parâmetro indicando o resultado do registro
    if ($registroBemSucedido) {
        header('Location: index.html?registro=sucesso');
    } else {
        header('Location: index.html?registro=erro');
    }
    exit();
}

$conn->close(); // Fecha a conexão com o banco
?>
