<?php

include 'auth.php';

// Verifica se as variáveis de sessão estão definidas
if (!isset($_SESSION['usuario_nome'])) {
    $_SESSION['usuario_nome'] = 'Valor Padrão do Nome';
}

if (!isset($_SESSION['usuario_adm'])) {
    $_SESSION['usuario_adm'] = false; // Valor padrão para o tipo de usuário
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="grupo.css">
    <title>JourneyBuddy - Grupo</title>
</head>

<body>

    <!-- Barra Lateral -->
    <div class="sidebar">
        <a href="#" class="logo">
        </a>
        <ul class="side-menu">
            <li><a href="main.php"><i class='bx bxs-home-smile'></i>Home</a></li>
            <li><a href="roteiro.php"><i class='bx bx-images'></i>Criar Roteiro</a></li>
            <li><a href="My_roteiro.php"><i class='bx bx-map-alt'></i>Meus Roteiros</a></li>
            <li><a href="ajuda.php"><i class='bx bx-help-circle'></i>Ajuda</a></li>
        </ul>
        <ul class="side-menu">
            <li>
                <a href="index.html" class="logout">
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
                    <h1>Grupos</h1>
                    <ul class="breadcrumb">     
                </div>
            </div>

            <div class="bottom-data">
                <!-- Campos de Meus Grupos -->
                <div id="meus-grupos-content" class="hidden">
                    <h2>Meus Grupos</h2>
                    <label for="roteiro">Roteiro:</label>
                    <input type="text" id="roteiro" readonly>
                    <label for="titulo-grupo">Título do Grupo:</label>
                    <input type="text" id="titulo-grupo" readonly>
                    <label for="descricao">Descrição:</label>
                    <input type="text" id="descricao" readonly>
                    <label for="membros">Membros / Função:</label>
                    <input type="text" id="membros" readonly>
                    <button id="convidar-membro-btn">Convidar Membro</button>
                </div>
            </div>

        </main>

    </div>

    <!-- Popup -->
    <div id="popup" class="popup">
        <div class="popup-content">
            <h2>Escolha uma opção</h2>
            <button class="group-btn" id="meus-grupos-btn" onclick="window.location.href='my_grupos.php';">Meus Grupos</button>
            <button class="group-btn" id="grupos-participantes-btn" onclick="window.location.href='grupos_participantes.php';">Grupos Participantes</button>
            <button class="close-btn" id="close-popup-btn" onclick="closePopup();">Fechar</button>
        </div>
    </div>

    <script>
        function closePopup() {
            window.location.href = 'main.php';
        }

        window.onload = function() {
            document.getElementById('popup').style.display = 'flex';
        };
    </script>

</body>

</html>
