<?php
include 'auth.php';

// Verifica se as variáveis de sessão estão definidas
if (!isset($_SESSION['usuario_nome'])) {
    $_SESSION['usuario_nome'] = 'Valor Padrão do Nome';
}

if (!isset($_SESSION['usuario_adm'])) {
    $_SESSION['usuario_adm'] = false; // Valor padrão para o tipo de usuário
}

$logged_in_user_id = $_SESSION['usuario_id']; // Certifique-se de que você tem o ID do usuário armazenado na sessão

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

// Verifica se a coluna Segundo_nome existe na tabela funcao_user
$column_exists = $conn->query("SHOW COLUMNS FROM funcao_user LIKE 'Segundo_nome'")->num_rows;

// Se a coluna existe, usa a concatenação, senão usa apenas Primeiro_nome
$name_field = $column_exists ? "CONCAT(Primeiro_nome, ' ', COALESCE(Segundo_nome, '')) AS nome_completo" : "Primeiro_nome AS nome_completo";

// Query para obter os dados dos grupos onde o usuário logado é participante (membro) mas não administrador
$sql = "SELECT gv.id, gv.id_roteiro, gv.nome, gv.descricao, rv.titulo 
        FROM gruposviagem gv 
        LEFT JOIN roteirosViagem rv ON gv.id_roteiro = rv.id 
        LEFT JOIN membrosgrupo mg ON gv.id = mg.id_grupo 
        WHERE mg.id_usuario = $logged_in_user_id AND gv.id_admin != $logged_in_user_id";

$result = $conn->query($sql);

// Query para obter a lista de usuários, exceto o usuário logado
$users_sql = "SELECT id, $name_field FROM funcao_user WHERE id != $logged_in_user_id";
$users_result = $conn->query($users_sql);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="main.css">
    <style>
        .group {
            position: relative;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .group .infoGroup {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .group .infoGroup strong {
            font-size: 16px;
        }
        .group form {
            margin-top: 10px;
        }
        .group form input[type='text'],
        .group form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .group form textarea {
            resize: none; /* Impede que o textarea seja redimensionável */
            height: 100px; /* Define uma altura fixa para o textarea */
        }
        .group form .members-list input[type='text'] {
            margin-bottom: 5px;
        }
        .member-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .member-item .funcao {
            padding: 10px;
            border-radius: 5px;
        }
        .member-item .funcao.admin {
            background-color: yellow; /* Yellow for Admin */
        }
        .member-item .funcao.member {
            background-color: #add8e6; /* Light Blue for Member */
        }
        /* Estilo para o modal (popup) */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
            align-items: center;
            justify-content: center;
        }
        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%; /* Could be more or less, depending on screen size */
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            border-radius: 10px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .chat-modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 2; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
            align-items: center;
            justify-content: center;
        }
        .chat-modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%; /* Could be more or less, depending on screen size */
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            border-radius: 10px;
        }
        .chat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .chat-messages {
            max-height: 400px;
            overflow-y: auto;
            margin: 20px 0;
        }
        .chat-input {
            display: flex;
        }
        .chat-input input[type="text"] {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-right: 10px;
        }
        .chat-input button {
            padding: 10px;
            background-color: #f39c12;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .chat-input button:hover {
            background-color: #e67e22;
        }
        .action-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px; /* Para posicionar os botões na parte de baixo */
        }
        .action-buttons button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .action-buttons button.group-chat {
            background-color: #f39c12;
            color: white;
        }
        .action-buttons button.group-chat:hover {
            background-color: #e67e22;
        }
    </style>
    <title>JourneyBuddy</title>
</head>

<body>

    <!-- Barra Lateral -->
    <div class="sidebar">
        <a href="#" class="logo"></a>
        <ul class="side-menu">
            <li><a href="main.php"><i class='bx bxs-home-smile'></i>Home</a></li>
            <li><a href="roteiro.php"><i class='bx bx-images'></i>Criar Roteiro</a></li>
            <li><a href="My_roteiro.php"><i class='bx bx-map-alt'></i>Meus Roteiros</a></li>
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
            <a href="profile.php" class="profile">
                <img src="Imagens/Logo.png">
            </a>
            <div class="user-info">
                <span class="user-name"><?php echo $_SESSION['usuario_nome']; ?></span>
                <span class="user-role"><?php echo ($_SESSION['usuario_adm'] ? 'Administrador' : 'Viajante'); ?></span>
            </div>
        </nav>
        <!-- Fim da Barra de Navegação -->

        <main>
            <div class="header">
                <div class="left">
                    <h1>Grupos Participantes</h1>
                </div>
            </div>

            <div class="bottom-data">
                <!-- Informações do grupo -->
                <div class="groupInfo">
                    <?php 
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            // Query para obter os membros do grupo
                            $group_id = $row['id'];
                            $members_sql = "SELECT mg.id_usuario, $name_field, 
                                            IF(mg.id_usuario = gv.id_admin, 'Admin', 'Membro') AS funcao 
                                            FROM membrosgrupo mg 
                                            LEFT JOIN funcao_user fu ON mg.id_usuario = fu.ID 
                                            LEFT JOIN gruposviagem gv ON mg.id_grupo = gv.id 
                                            WHERE mg.id_grupo = $group_id
                                            ORDER BY funcao DESC";
                            $members_result = $conn->query($members_sql);

                            echo "<div class='group' id='group-{$row['id']}'>";
                            echo "<div class='infoGroup'>";
                            echo "<strong>Roteiro do Grupo: " . $row['titulo'] . "</strong>";
                            echo "</div>";
                            echo "<form action='' class='formGroup' id='formGroup-{$row['id']}'>";
                            echo "<h3>Título do Grupo</h3>";
                            echo "<input type='text' name='groupTitle' value='" . $row['nome'] . "' placeholder='Título do Grupo' readonly>";
                            echo "<h3>Descrição do Grupo</h3>";
                            echo "<textarea name='groupDescription' placeholder='Descrição do Grupo' readonly>" . $row['descricao'] . "</textarea>";
                            echo "<h3>Membros do Grupo</h3>";
                            echo "<div class='members-list'>";
                            if ($members_result->num_rows > 0) {
                                while($member_row = $members_result->fetch_assoc()) {
                                    $funcaoClass = $member_row['funcao'] == 'Admin' ? 'admin' : 'member';
                                    echo "<div class='member-item'>";
                                    echo "<input type='text' value='" . $member_row['nome_completo'] . "' readonly>";
                                    echo "<input type='text' value='" . $member_row['funcao'] . "' class='funcao $funcaoClass' readonly>";
                                    echo "</div>";
                                }
                            } else {
                                echo "<div class='member-item'>";
                                echo "<input type='text' value='Nenhum membro encontrado.' readonly>";
                                echo "<input type='text' value='' readonly>";
                                echo "</div>";
                            }
                            echo "</div>";
                            echo "<div class='action-buttons'>";
                            echo "<button class='group-chat' type='button' data-group-id='{$row['id']}'>Chat do Grupo</button>";
                            echo "</div>";
                            echo "</form>";
                            echo "</div>";
                        }
                    } else {
                        echo "<p>Nenhum grupo encontrado.</p>";
                    }
                    $conn->close();
                    ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Chat Modal -->
    <div id="chatModal" class="chat-modal">
        <div class="chat-modal-content">
            <div class="chat-header">
                <h2>Chat do Grupo</h2>
                <span class="close-chat">&times;</span>
            </div>
            <div class="chat-messages" id="chatMessages">
                <!-- Messages will be dynamically added here -->
            </div>
            <div class="chat-input">
                <input type="text" id="chatMessageInput" placeholder="Digite sua mensagem...">
                <button id="sendChatMessage">Enviar</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const chatModal = document.getElementById("chatModal");
            const chatBtns = document.querySelectorAll(".group-chat");
            const chatSpan = document.getElementsByClassName("close-chat")[0];

            window.onclick = function(event) {
                if (event.target == chatModal) {
                    chatModal.style.display = "none";
                }
            }

            // Função para abrir o chat do grupo
            chatBtns.forEach(btn => {
                btn.onclick = function() {
                    const groupId = this.getAttribute('data-group-id');
                    chatModal.setAttribute('data-group-id', groupId);
                    chatModal.style.display = "flex";
                    loadChatMessages(groupId); // Carregar mensagens do chat
                }
            });

            chatSpan.onclick = function() {
                chatModal.style.display = "none";
            }

            // Função para carregar mensagens do chat
            function loadChatMessages(groupId) {
                fetch('load_chat_messages.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id_grupo: groupId }),
                })
                .then(response => response.json())
                .then(data => {
                    const chatMessages = document.getElementById('chatMessages');
                    chatMessages.innerHTML = '';
                    data.messages.forEach(message => {
                        const messageDiv = document.createElement('div');
                        messageDiv.textContent = `${message.usuario_nome}: ${message.comentario} (${message.data})`;
                        chatMessages.appendChild(messageDiv);
                    });
                })
                .catch((error) => {
                    console.error('Erro:', error);
                });
            }

            // Função para enviar mensagem no chat
            const sendChatMessageBtn = document.getElementById('sendChatMessage');
            sendChatMessageBtn.onclick = function() {
                const groupId = chatModal.getAttribute('data-group-id');
                const messageInput = document.getElementById('chatMessageInput');
                const message = messageInput.value;

                if (message.trim() === '') {
                    alert('Digite uma mensagem.');
                    return;
                }

                fetch('send_chat_message.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id_grupo: groupId, id_usuario: <?php echo $logged_in_user_id; ?>, comentario: message }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadChatMessages(groupId); // Recarregar mensagens do chat
                        messageInput.value = '';
                    } else {
                        alert('Erro ao enviar mensagem.');
                    }
                })
                .catch((error) => {
                    console.error('Erro:', error);
                });
            }
        });
    </script>

</body>
</html>
