<?php

include 'db.php'; // Inclui o script de conexão

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $registroBemSucedido = false; // Flag para rastrear sucesso ou falha

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

        // Prepara e executa a inserção na tabela Usuarios_pf
        $stmtPf = $conn->prepare("INSERT INTO Usuarios_pf (PrimeiroNome, SegundoNome, Email, DataNascimento, CPF, Genero, Telefone) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmtPf->bind_param("sssssss", $primeiroNome, $segundoNome, $email, $dataNascimento, $cpf, $genero, $telefone);
        
        $registroBemSucedido = $stmtPf->execute();
        
        // Obtém o ID gerado na inserção
        $usuario_id = $stmtPf->insert_id;

        $stmtPf->close();

        // Concatena primeiroNome e segundoNome
        $nomeCompleto = $primeiroNome . ' ' . $segundoNome;

        // Preenche a nova tabela com os dados inseridos
        $stmtPreencherTabela = $conn->prepare("INSERT INTO funcao_user (email, Primeiro_nome, ADM, Anunciante, PJ_PF, id_geral) VALUES (?, ?, ?, ?, ?, ?)");
        $PJ_PF = false; // Define PJ_PF como false para Pessoa Física
        $ADM = 0;
        $Anunciante = 0;
        $stmtPreencherTabela->bind_param("ssiiii", $email, $nomeCompleto, $ADM, $Anunciante, $PJ_PF, $usuario_id);
        $stmtPreencherTabela->execute();
        $stmtPreencherTabela->close();
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

        // Prepara e executa a inserção na tabela usuarios_pj
        $stmtPj = $conn->prepare("INSERT INTO usuarios_pj (NomeFantasia, RazaoSocial, CNPJ, Email, DataAbertura, InscricaoEstadual, Telefone) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmtPj->bind_param("sssssss", $nomeFantasia, $razaoSocial, $cnpj, $email, $dataAbertura, $inscricaoEstadual, $telefone);
        
        $registroBemSucedido = $stmtPj->execute();
        
        // Obtém o ID gerado na inserção
        $usuario_id = $stmtPj->insert_id;

        $stmtPj->close();

        // Preenche a nova tabela com os dados inseridos
        $stmtPreencherTabela = $conn->prepare("INSERT INTO funcao_user (email, Primeiro_nome, ADM, Anunciante, PJ_PF, id_geral) VALUES (?, ?, ?, ?, ?, ?)");
        $PJ_PF = true; // Define PJ_PF como true para Pessoa Jurídica
        $ADM = 0;
        $Anunciante = 0;
        $stmtPreencherTabela->bind_param("ssiiii", $email, $nomeFantasia, $ADM, $Anunciante, $PJ_PF, $usuario_id);
        $stmtPreencherTabela->execute();
        $stmtPreencherTabela->close();
    }

    // Processa o registro na tabela usuario comum para ambas as contas
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $senhaCriptografada = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmtUsuario = $conn->prepare("INSERT INTO usuario (Email, Senha) VALUES (?, ?)");
        $stmtUsuario->bind_param("ss", $email, $senhaCriptografada);
        
        $registroBemSucedido &= $stmtUsuario->execute(); // Atualiza o estado de sucesso com base nesta inserção também
        $stmtUsuario->close();
    }

    // Se o registro foi bem-sucedido, retorna uma mensagem de sucesso
    if ($registroBemSucedido) {
        // Define a mensagem de sucesso na URL como parâmetro de consulta
        header("Location: index.html?success=1");
        exit();
    } else {
        // Se ocorreu algum erro durante o registro, retorna uma mensagem de erro
        echo json_encode(["success" => false, "message" => "Ocorreu um erro durante o registro. Por favor, tente novamente."]);
        exit();
    }
}

$conn->close(); // Fecha a conexão com o banco
?>
