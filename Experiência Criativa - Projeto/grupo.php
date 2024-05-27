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
                <div id="criar-grupo-content" class="hidden">
                    <h2>Criar Novo Grupo</h2>
                    <form id="criar-grupo-form">
                        <label for="novo-roteiro">Roteiro:</label>
                        <input type="text" id="novo-roteiro" name="roteiro" required>
                        <label for="novo-titulo-grupo">Título do Grupo:</label>
                        <input type="text" id="novo-titulo-grupo" name="titulo_grupo" required>
                        <label for="novo-descricao">Descrição:</label>
                        <input type="text" id="novo-descricao" name="descricao" required>
                        <button type="submit">Criar Grupo</button>
                    </form>
                </div>

        </main>

    </div>

    <!-- Popup -->
    <div id="popup" class="popup">
        <div class="popup-content">
            <h2>Escolha uma opção</h2>
            <button class="group-btn" id="meus-grupos-btn">Meus Grupos</button>
            <button class="group-btn" id="grupos-participantes-btn">Criar grupo</button>
            <button class="close-btn" id="close-popup-btn">Fechar</button>
        </div>
    </div>

    <script>
        document.getElementById('meus-grupos-btn').addEventListener('click', function() {
            fetch('fetch_grupos.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok ' + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        document.getElementById('roteiro').value = data.roteiro;
                        document.getElementById('titulo-grupo').value = data.titulo_grupo;
                        document.getElementById('descricao').value = data.descricao;
                        document.getElementById('membros').value = data.membros;
                        document.getElementById('meus-grupos-content').classList.remove('hidden');
                        document.getElementById('popup').style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                    alert('Erro ao buscar dados: ' + error.message);
                });
        });

        document.getElementById('close-popup-btn').addEventListener('click', function() {
            window.location.href = 'main.php';
        });

        window.onload = function() {
            document.getElementById('popup').style.display = 'flex';
        };


        //teste
        document.getElementById('criar-grupo-form').addEventListener('submit', function(event) {
        event.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('create_grupo.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                alert(data.error);
            } else {
                alert(data.success);
                document.getElementById('criar-grupo-content').classList.add('hidden');
                document.getElementById('meus-grupos-content').classList.remove('hidden');
                // Atualizar a lista de grupos se necessário
            }
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
            alert('Erro ao criar grupo: ' + error.message);
        });
    });

    document.getElementById('grupos-participantes-btn').addEventListener('click', function() {
        document.getElementById('popup').style.display = 'none';
        document.getElementById('criar-grupo-content').classList.remove('hidden');
    });
    </script>

</body>

</html>
