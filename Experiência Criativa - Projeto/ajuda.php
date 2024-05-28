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
    <link rel="stylesheet" href="ajuda.css">
    <title>JourneyBuddy</title>
</head>

<body>

    <!-- Barra Lateral -->
    <div class="sidebar">
        <a href="#" class="logo">
            
        </a>
        <ul class="side-menu">
            <li><a href="main.php"><i class='bx bxs-home-smile'></i></i>Home</a></li>
            <li><a href="grupo.php"><i class='bx bxs-group'></i></i>Grupos</a></li>
            <li><a href="roteiro.php"><i class='bx bx-images'></i></i>Criar Roteiro</a></li>
            <li><a href="My_roteiro.php"><i class='bx bx-map-alt'></i>Meus Roteiros</a></li> 
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
            <a href="profile.php"class="profile">
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
                    <h1>Ajuda</h1>
                    <ul class="breadcrumb">
                        <!-- Aqui você pode adicionar breadcrumbs se necessário -->
                    </ul>
                </div>
                <div class="right">
                    <h2>Informações de Contato</h2>
                    <p><strong>Email:</strong> JourneyBuddy@gmail.com</p>
                    <p><strong>Telefone:</strong> (41) 99249-5680</p>
                    <!-- Adicione mais informações de contato, se necessário -->
                </div>
            </div>

            <div class="bottom-data">
                <h2>Formulário de Contato</h2>
                <form action="#" method="post">
                    <div class="form-group">
                        <label for="nome">Nome:</label>
                        <input type="text" id="nome" name="nome" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="comentarios">Comentários:</label>
                        <textarea id="comentarios" name="comentarios" rows="4" required></textarea>
                    </div>
                    <button type="submit">Enviar</button>
                </form>
            </div>
        </main>
    </div>

    <script src="main.js"></script>
</body>

</html>
