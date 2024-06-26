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

// Query para obter os roteiros de viagem ordenados por ID em ordem decrescente
$sql = "SELECT rv.id, rv.titulo, rv.descricao, img.imagem, rv.id_usuario, GROUP_CONCAT(lv.nome_local SEPARATOR ';') AS nome_local, 
        GROUP_CONCAT(lv.valor_gasto ORDER BY lv.id SEPARATOR ';') AS valor_gasto, lv.moeda, fu.Primeiro_nome 
        FROM roteirosViagem rv 
        LEFT JOIN Imagens img ON rv.id = img.id_roteiro
        LEFT JOIN localizacoesvalores lv ON rv.id = lv.id_roteiro
        LEFT JOIN funcao_user fu ON rv.id_usuario = fu.ID
        GROUP BY rv.id
        ORDER BY rv.id DESC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="main.css">
    <style>
        .post {
            position: relative;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .post .infoUser {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .post .infoUser .imgUser img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .post .infoUser strong {
            font-size: 16px;
        }
        .post form {
            margin-top: 10px;
        }
        .post form input[type='text'],
        .post form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .post form textarea {
            resize: none; /* Impede que o textarea seja redimensionável */
            height: 100px; /* Define uma altura fixa para o textarea */
        }
        .post form .image-upload {
            margin-bottom: 10px;
        }
        .post form .image-upload img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }
        .post form .location-value input[type='text'] {
            width: calc(50% - 5px);
            margin-right: 5px;
            margin-bottom: 10px;
        }
        .dropdown {
            position: absolute;
            top: 10px;
            right: 10px;
            display: inline-block;
        }
        .dropdown .dropbtn {
            background-color: #3498db;
            color: white;
            padding: 10px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            border-radius: 5px;
            right: 0; /* Alinha o dropdown para a esquerda */
            transform: translateX(-50%);
        }
        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            border-radius: 5px;
        }
        .dropdown-content a:hover {background-color: #f1f1f1}
        .dropdown:hover .dropdown-content {
            display: block;
        }
        .dropdown:hover .dropbtn {
            background-color: #2980b9;
        }
        .like-button, .dislike-button, .comment-button {
            margin-right: 10px;
        }
        body {
            transition: background-color 0.5s, color 0.5s;
        }

        body.light-theme {
            background-color: #ffffff;
            color: #000000;
        }

        body.dark-theme {
            background-color: #000000;
            color: #ffffff;
        }
        .post-actions {
            display: flex;
            justify-content: flex-start;
            margin-top: 10px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            background-color: transparent;
            cursor: pointer;
            padding: 10px;
            margin-right: 10px;
            transition: color 0.3s;
        }

        .btn-icone {
            margin-right: 5px;
        }

        .btn-curtir .btn--icone-curtir,
        .btn-curtir .btn--icone-curtido,
        .btn-descurtir .btn--icone-descurtir,
        .btn-descurtir .btn--icone-descurtido,
        .btn-comentar .btn--icone-comentar {
            color: #666;
        }

        .btn-curtir:hover .btn--icone-curtir,
        .btn-curtir:hover .btn--icone-curtido,
        .btn-descurtir:hover .btn--icone-descurtir,
        .btn-descurtir:hover .btn--icone-descurtido,
        .btn-comentar:hover .btn--icone-comentar {
            color: #333;
        }

        .btn-curtir .btn-conteudo--curtir,
        .btn-curtir .btn-conteudo--curtido,
        .btn-descurtir .btn-conteudo--descurtir,
        .btn-descurtir .btn-conteudo--descurtido,
        .btn-comentar .btn-conteudo--comentar {
            display: inline;
        }

        .btn-curtir .btn--icone-curtido,
        .btn-curtir .btn-conteudo--curtido,
        .btn-descurtir .btn--icone-descurtido,
        .btn-descurtir .btn-conteudo--descurtido {
            display: none;
        }

        .btn-curtir.active .btn--icone-curtir,
        .btn-curtir.active .btn-conteudo--curtir,
        .btn-descurtir.active .btn--icone-descurtir,
        .btn-descurtir.active .btn-conteudo--descurtir {
            display: none;
        }

        .btn-curtir.active .btn--icone-curtido,
        .btn-curtir.active .btn-conteudo--curtido,
        .btn-descurtir.active .btn--icone-descurtido,
        .btn-descurtir.active .btn-conteudo--descurtido {
            display: inline;
        }
    </style>
    <title>JourneyBuddy</title>
</head>

<body>

    <!-- Barra Lateral -->
    <div class="sidebar">
        <a href="#" class="logo"></a>
        <ul class="side-menu">
            <li><a href="grupo.php"><i class='bx bxs-group'></i>Grupos</a></li>
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
                    <h1>JourneyBuddy</h1>
                </div>
            </div>

            <div class="bottom-data">
                <!-- Formulário de postagem -->
                <div class="newPost">
                    <?php 
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<div class='post' id='post-{$row['id']}'>";
                            echo "<div class='dropdown'>";
                            echo "<button class='dropbtn'>Opções</button>";
                            echo "<div class='dropdown-content'>";
                            if ($row['id_usuario'] != $logged_in_user_id) {
                                echo "<a href='#' class='import-post' data-id='{$row['id']}'>Importar</a>";
                            }
                            echo "<a href='#'>Compartilhar</a>";
                            if ($row['id_usuario'] == $logged_in_user_id) {
                                echo "<a href='#' class='delete-post' data-id='{$row['id']}'>Excluir</a>";
                            }
                            echo "</div>";
                            echo "</div>";
                            echo "<div class='infoUser'>";
                            echo "<div class='imgUser'>";
                            echo "<img src='Imagens/Logo.png' alt='Avatar do Usuário'>";
                            echo "</div>";
                            echo "<strong>" . $row['Primeiro_nome'] . "</strong>";
                            echo "</div>";
                            echo "<form action='' class='formPost' id='formPost'>";
                            echo "<h3>Título Roteiro</h3>";
                            echo "<input type='text' name='title' value='" . $row['titulo'] . "' placeholder='Título' readonly>";
                            echo "<h3>Descrição</h3>";
                            echo "<textarea name='textarea' placeholder='Descrição' readonly>" . $row['descricao'] . "</textarea>";
                            if (!empty($row['imagem'])) {
                                echo "<h3>Imagem</h3>";
                                echo "<div class='image-upload'>";
                                echo "<img src='/Projeto/" . $row['imagem'] . "' alt='Imagem do Roteiro'>";
                                echo "</div>";
                            }
                            echo "<h3>Valores Gastos</h3>";
                            echo "<div class='location-value'>";
                            // Separando as localizações e valores em um array
                            $locais = explode(';', $row['nome_local']);
                            $valores = explode(';', $row['valor_gasto']);
                            
                            // Verificando se o número de localizações e valores corresponde
                            if (count($locais) == count($valores)) {
                                // Iterando sobre os locais e valores para exibir em pares
                                for ($i = 0; $i < count($locais); $i++) {  // Adicionei a declaração correta da variável $i
                                    echo "<input type='text' name='location' value='" . trim($locais[$i]) . "' placeholder='Descrição' readonly>";
                                    echo "<input type='text' name='value' value='" . trim($valores[$i]) . " " . $row['moeda'] . "' placeholder='Valor' readonly>";
                                }
                            } else {
                                echo "<p style='color: red;'>Erro: número de localizações e valores não corresponde.</p>";
                            }
                            echo "</div>";
                            echo "</form>";
                            echo "<div class='post-actions'>";
                            echo "<button class='btn btn-curtir'>
                                    <span class='btn-icone btn--icone-curtir'>
                                        <span class='fa fa-heart'></span>
                                    </span>
                                    <span class='btn-icone btn--icone-curtido'>
                                        <span class='fa fa-heart'></span>
                                    </span>
                                    <span class='btn-conteudo btn-conteudo--curtido'>Curtido</span>
                                    <span class='btn-conteudo btn-conteudo--curtir'>Curtir</span>
                                </button>";
                            echo "<button class='btn btn-descurtir'>
                                    <span class='btn-icone btn--icone-descurtir'>
                                        <span class='fa fa-thumbs-down'></span>
                                    </span>
                                    <span class='btn-icone btn--icone-descurtido'>
                                        <span class='fa fa-thumbs-down'></span>
                                    </span>
                                    <span class='btn-conteudo btn-conteudo--descurtido'>Descurtido</span>
                                    <span class='btn-conteudo btn-conteudo--descurtir'>Descurtir</span>
                                </button>";
                            echo "<button class='btn btn-comentar'>
                                    <span class='btn-icone btn--icone-comentar'>
                                        <span class='fa fa-comment'></span>
                                    </span>
                                    <span class='btn-conteudo btn-conteudo--comentar'>Comentar</span>
                                </button>";
                            echo "</div>";
                            echo "</div>";
                        }
                    } else {
                        echo "<p>Nenhuma postagem encontrada.</p>";
                    }
                    $conn->close();
                    ?>
                </div>
                <ul class="posts" id="posts"></ul>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            document.querySelectorAll('.delete-post').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('Delete button clicked');  // Adiciona o console.log aqui
                    const postId = this.getAttribute('data-id');
                    if (confirm('Tem certeza que deseja excluir este roteiro?')) {
                        fetch('delete_post.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ id: postId }),
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                document.getElementById('post-' + postId).remove();
                                alert('Roteiro excluído com sucesso!');
                            } else {
                                alert('Erro ao excluir o roteiro.');
                            }
                        })
                        .catch((error) => {
                            console.error('Erro:', error);
                        });
                    }
                });
            });

            document.querySelectorAll('.import-post').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('Import button clicked');  // Adiciona o console.log aqui
                    const postId = this.getAttribute('data-id');
                    if (confirm('Tem certeza que deseja importar este roteiro?')) {
                        fetch('import_post.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ id: postId }),
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Roteiro importado com sucesso!');
                            } else {
                                alert('Erro ao importar o roteiro: ' + data.message);
                            }
                        })
                        .catch((error) => {
                            console.error('Erro:', error);
                        });
                    }
                });
            });
        });
    </script>

</body>
</html>
