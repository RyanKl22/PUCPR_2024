<?php

include 'auth.php';

// Verifica se as variáveis de sessão estão definidas
if (!isset($_SESSION['usuario_nome'])) {
    $_SESSION['usuario_nome'] = 'Valor Padrão do Nome';
}

if (!isset($_SESSION['usuario_adm'])) {
    $_SESSION['usuario_adm'] = false; // Valor padrão para o tipo de usuário
}

if (!isset($_SESSION['usuario_pj_pf'])) {
    $_SESSION['usuario_pj_pf'] = 0; // Valor padrão para o tipo de usuário
}

// Recupera o ID do usuário da sessão
$usuario_id = $_SESSION['usuario_id_geral'];

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

// Recupera os dados do usuário com base no tipo de usuário
if ($_SESSION['usuario_pj_pf'] == 0) {
    $sql = "SELECT PrimeiroNome, SegundoNome, Email, DataNascimento, CPF, Genero, Telefone FROM usuarios_PF WHERE Id = ? ";
} else {
    $sql = "SELECT NomeFantasia, RazaoSocial, CNPJ, Email, DataAbertura, InscricaoEstadual, Telefone FROM usuarios_PJ WHERE Id = ? ";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

// Verifica se o usuário foi encontrado
if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();
} else {
    die("Usuário não encontrado.");
}

$stmt->close();
$conn->close();

// Função para formatar data no padrão DD/MM/YYYY
function formatarData($data) {
    $date = new DateTime($data);
    return $date->format('d/m/Y');
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="profile.css">
    <title>JourneyBuddy - Perfil</title>
    <style>
        .profile-field {
            margin-bottom: 15px;
        }
        .profile-field label {
            display: block;
            font-weight: bold;
        }
        .profile-field input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #f9f9f9;
        }
        .profile-field input.editable {
            background-color: #e0f7fa; /* Cor diferente para o campo editável */
            border-color: #00acc1; /* Borda diferente para o campo editável */
        }
        .primary-button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .primary-button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <!-- Barra Lateral -->
    <div class="sidebar">
        <a href="#" class="logo"></a>
        <ul class="side-menu">
            <li><a href="main.php"><i class='bx bxs-home-smile'></i>Home</a></li>
            <li><a href="ajuda.php"><i class='bx bx-help-circle'></i>Ajuda</a></li>
        </ul>
        <ul class="side-menu">
            <li>
                <a href="logout.php" class="logout">
                    <i class='bx bx-log-out-circle'></i>
                    Sair
                </a>
            </li>
        </ul>
    </div>
    <!-- Fim da Barra Lateral -->

    <!-- Conteúdo Principal -->
    <div class="content">
        <!-- Barra de Navegação -->
        <nav>
            <form action="#">
                <div class="form-input">
                    <input type="search" placeholder="Procurar...">
                    <button class="search-btn" type="submit"><i class='bx bx-search'></i></button>
                </div>
            </form>
            <input type="checkbox" id="theme-toggle" hidden>
            <a href="main.php" class="profile">
                <img src="images/Logo.png">
            </a>
            <div class="user-info">
                <span class="user-name"><?php echo $_SESSION['usuario_nome']; ?></span>
                <span class="user-role"><?php echo ($_SESSION['usuario_adm'] ? 'Administrador' : 'Viajante'); ?></span>
            </div>
        </nav>

        <!-- Área de Perfil -->
        <main>
            <h2>Perfil do Usuário</h2>
            <div class="profile-info">
                <?php if ($_SESSION['usuario_pj_pf'] == 0): ?>
                    <!-- Campos para Pessoa Física -->
                    <div class="profile-field">
                        <label>Nome Completo:</label>
                        <input type="text" value="<?php echo htmlspecialchars($usuario['PrimeiroNome'] . ' ' . $usuario['SegundoNome']); ?>" readonly>
                    </div>
                    <div class="profile-field">
                        <label>Email:</label>
                        <input type="text" value="<?php echo htmlspecialchars($usuario['Email']); ?>" readonly>
                    </div>
                    <div class="profile-field">
                        <label>Data de Nascimento:</label>
                        <input type="text" value="<?php echo htmlspecialchars(formatarData($usuario['DataNascimento'])); ?>" readonly>
                    </div>
                    <div class="profile-field">
                        <label>CPF:</label>
                        <input type="text" value="<?php echo htmlspecialchars($usuario['CPF']); ?>" readonly>
                    </div>
                    <div class="profile-field">
                        <label>Gênero:</label>
                        <input type="text" value="<?php echo htmlspecialchars($usuario['Genero']); ?>" readonly>
                    </div>
                    <div class="profile-field" id="telefone-field">
                        <label>Telefone:</label>
                        <input type="text" value="<?php echo htmlspecialchars($usuario['Telefone']); ?>" readonly>
                    </div>
                <?php else: ?>
                    <!-- Campos para Pessoa Jurídica -->
                    <div class="profile-field">
                        <label>Nome Fantasia:</label>
                        <input type="text" value="<?php echo htmlspecialchars($usuario['NomeFantasia']); ?>" readonly>
                    </div>
                    <div class="profile-field">
                        <label>Razão Social:</label>
                        <input type="text" value="<?php echo htmlspecialchars($usuario['RazaoSocial']); ?>" readonly>
                    </div>
                    <div class="profile-field">
                        <label>CNPJ:</label>
                        <input type="text" value="<?php echo htmlspecialchars($usuario['CNPJ']); ?>" readonly>
                    </div>
                    <div class="profile-field">
                        <label>Email:</label>
                        <input type="text" value="<?php echo htmlspecialchars($usuario['Email']); ?>" readonly>
                    </div>
                    <div class="profile-field">
                        <label>Data de Abertura:</label>
                        <input type="text" value="<?php echo htmlspecialchars(formatarData($usuario['DataAbertura'])); ?>" readonly>
                    </div>
                    <div class="profile-field">
                        <label>Inscrição Estadual:</label>
                        <input type="text" value="<?php echo htmlspecialchars($usuario['InscricaoEstadual']); ?>" readonly>
                    </div>
                    <div class="profile-field" id="telefone-field">
                        <label>Telefone:</label>
                        <input type="text" value="<?php echo htmlspecialchars($usuario['Telefone']); ?>" readonly>
                    </div>
                <?php endif; ?>
                <button type="button" class="primary-button" onclick="editarTelefone()">Editar Informações</button>
                <button type="button" class="primary-button hidden" id="salvar-button" onclick="salvarAlteracoes()">Salvar Alterações</button>
            </div>
        </main>
        
    </div>
    <script>
        function editarTelefone() {
            var telefoneField = document.querySelector('.profile-field#telefone-field'); // Seleciona a div com id telefone-field
            var telefoneInput = telefoneField.querySelector('input'); // Seleciona o input dentro dessa div
            telefoneInput.removeAttribute('readonly'); // Remove o atributo readonly do input
            telefoneInput.classList.add('editable'); // Adiciona a classe editable ao input
            document.getElementById('salvar-button').classList.remove('hidden'); // Exibe o botão Salvar Alterações
        }

        function salvarAlteracoes() {
            var telefoneField = document.querySelector('.profile-field#telefone-field');
            var telefoneInput = telefoneField.querySelector('input');
            var novoTelefone = telefoneInput.value;

            // Envia uma requisição AJAX para salvar o novo telefone no banco de dados
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "salvar_alteracoes.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Se a resposta for positiva, faz o campo voltar a ser somente leitura
                    telefoneInput.setAttribute('readonly', 'readonly');
                    telefoneInput.classList.remove('editable');
                    document.getElementById('salvar-button').classList.add('hidden');
                }
            };

            // Envia o ID do usuário e o novo telefone
            xhr.send("usuario_id=" + <?php echo $usuario_id; ?> + "&telefone=" + encodeURIComponent(novoTelefone));
        }

    </script>
</body>
</html>
