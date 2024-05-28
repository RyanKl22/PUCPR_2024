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

// Query para obter os roteiros de viagem do usuário logado, ordenados por ID em ordem decrescente
$sql = "SELECT rv.id, rv.titulo, rv.descricao, img.imagem, rv.id_usuario, 
        GROUP_CONCAT(lv.id ORDER BY lv.id SEPARATOR ';') AS local_ids,
        GROUP_CONCAT(lv.nome_local ORDER BY lv.id SEPARATOR ';') AS nome_local, 
        GROUP_CONCAT(lv.valor_gasto ORDER BY lv.id SEPARATOR ';') AS valor_gasto, 
        GROUP_CONCAT(lv.latitude ORDER BY lv.id SEPARATOR ';') AS latitude, 
        GROUP_CONCAT(lv.longitude ORDER BY lv.id SEPARATOR ';') AS longitude,
        lv.moeda, fu.Primeiro_nome 
        FROM roteirosViagem rv 
        LEFT JOIN Imagens img ON rv.id = img.id_roteiro
        LEFT JOIN localizacoesvalores lv ON rv.id = lv.id_roteiro
        LEFT JOIN funcao_user fu ON rv.id_usuario = fu.ID
        WHERE rv.id_usuario = $logged_in_user_id
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
        .post form input[type='text'].editing,
        .post form textarea.editing {
            background-color: #e6f7ff;
            border-color: #80bdff;
        }
        .post form textarea {
            resize: none;
            height: 100px;
        }
        .post form .image-upload {
            margin-bottom: 10px;
            position: relative;
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
            right: 0;
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
        .edit-btn,
        .confirm-btn {
            position: absolute;
            top: 10px;
            right: 100px;
            background-color: #f39c12;
            color: white;
            padding: 10px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            margin-right: 10px;
        }
        .confirm-btn {
            background-color: #4CAF50;
            display: none;
        }
        .remove-image-btn {
            background-color: #ff6b6b;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 5px;
            display: none;
        }
        .upload-image {
            display: none;
        }
    </style>
    <title>JourneyBuddy</title>
</head>

<body>

    <div class="sidebar">
        <a href="#" class="logo"></a>
        <ul class="side-menu">
            <li><a href="main.php"><i class='bx bxs-home-smile'></i></i>Home</a></li>
            <li><a href="grupo.php"><i class='bx bxs-group'></i>Grupos</a></li>
            <li><a href="roteiro.php"><i class='bx bx-images'></i>Roteiros</a></li>
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

    <div class="content">
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

        <main>
            <div class="header">
                <div class="left">
                    <h1>Meus Roteiros</h1>
                </div>
            </div>

            <div class="bottom-data">
                <div class="newPost">
                    <?php 
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<div class='post' id='post-{$row['id']}'>";
                            echo "<button class='edit-btn' data-id='{$row['id']}'>Alterar</button>";
                            echo "<button class='confirm-btn' id='confirm-btn-{$row['id']}'>Confirmar Alterações</button>";
                            echo "<div class='dropdown'>";
                            echo "<button class='dropbtn'>Opções</button>";
                            echo "<div class='dropdown-content'>";
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
                            echo "<textarea name='textarea' placeholder='Vamos mudar o mundo?' id='textarea' readonly>" . $row['descricao'] . "</textarea>";
                            if (!empty($row['imagem'])) {
                                echo "<h3>Imagem</h3>";
                                echo "<div class='image-upload'>";
                                echo "<img src='/Projeto/" . $row['imagem'] . "' alt='Imagem do Roteiro'>";
                                echo "<button type='button' class='remove-image-btn'>Remover Imagem</button>";
                                echo "<input type='file' name='image' class='upload-image' />";
                                echo "</div>";
                            } else {
                                echo "<div class='image-upload'>";
                                echo "<button type='button' class='remove-image-btn' style='display:none;'>Remover Imagem</button>";
                                echo "<input type='file' name='image' class='upload-image' style='display:none;' />";
                                echo "</div>";
                            }
                            echo "<h3>Valores Gastos</h3>";
                            echo "<div class='location-value'>";
                            $locais = explode(';', $row['nome_local']);
                            $valores = explode(';', $row['valor_gasto']);
                            
                            if (count($locais) == count($valores)) {
                                for ($i = 0; $i < count($locais); $i++) {
                                    echo "<input type='text' name='location[]' value='" . trim($locais[$i]) . "' placeholder='Localização' readonly>";
                                    echo "<input type='text' name='value[]' value='" . trim($valores[$i]) . "' placeholder='Valor' readonly>";
                                    echo "<input type='hidden' name='latitude[]' value=''>";
                                    echo "<input type='hidden' name='longitude[]' value=''>";
                                }
                            } else {
                                echo "<p style='color: red;'>Erro: número de localizações e valores não corresponde.</p>";
                            }
                            echo "</div>";
                            echo "</form>";
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
                    console.log('Botão de Deleter Clicado');
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

            document.querySelectorAll('.edit-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('Edit button clicked');
                    const postId = this.getAttribute('data-id');
                    const postElement = document.getElementById('post-' + postId);
                    const inputs = postElement.querySelectorAll('input, textarea');
                    const confirmButton = document.getElementById('confirm-btn-' + postId);
                    const removeImageButton = postElement.querySelector('.remove-image-btn');
                    const uploadImageInput = postElement.querySelector('.upload-image');

                    inputs.forEach(input => {
                        input.removeAttribute('readonly');
                        input.classList.add('editing');
                    });

                    confirmButton.style.display = 'inline-block';
                    if (removeImageButton) removeImageButton.style.display = 'inline-block';

                    removeImageButton.addEventListener('click', function() {
                        const imageElement = postElement.querySelector('.image-upload img');
                        imageElement.style.display = 'none';
                        uploadImageInput.style.display = 'inline-block';
                        removeImageButton.style.display = 'none';
                    });

                    uploadImageInput.addEventListener('change', function(event) {
                        const file = event.target.files[0];
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const imageElement = postElement.querySelector('.image-upload img');
                            imageElement.src = e.target.result;
                            imageElement.style.display = 'block';
                            removeImageButton.style.display = 'inline-block';
                            uploadImageInput.style.display = 'none';
                        }
                        reader.readAsDataURL(file);
                    });

                    confirmButton.addEventListener('click', function() {
                        const updatedData = {
                            location_ids: [],
                            location: [],
                            value: [],
                            latitude: [],
                            longitude: []
                        };
                        inputs.forEach(input => {
                            if (input.name === 'location[]') {
                                updatedData.location.push(input.value);
                                updatedData.location_ids.push(input.dataset.id);
                            } else if (input.name === 'value[]') {
                                updatedData.value.push(input.value);
                            } else if (input.name === 'latitude[]') {
                                updatedData.latitude.push(input.value);
                            } else if (input.name === 'longitude[]') {
                                updatedData.longitude.push(input.value);
                            } else {
                                updatedData[input.name] = input.value;
                            }
                            input.setAttribute('readonly', true);
                            input.classList.remove('editing');
                        });

                        fetch('update_post.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ id: postId, data: updatedData }),
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Roteiro atualizado com sucesso!');
                            } else {
                                alert('Erro ao atualizar o roteiro.');
                            }
                            confirmButton.style.display = 'none';
                            if (removeImageButton) removeImageButton.style.display = 'none';
                            if (uploadImageInput) uploadImageInput.style.display = 'none';
                        })
                        .catch((error) => {
                            console.error('Erro:', error);
                            confirmButton.style.display = 'none';
                            if (removeImageButton) removeImageButton.style.display = 'none';
                            if (uploadImageInput) uploadImageInput.style.display = 'none';
                        });
                    });

                    initAutocomplete();
                });
            });

            function initAutocomplete() {
                const localizacaoInputs = document.querySelectorAll('input[name="location[]"]');
                const brasilBounds = new google.maps.LatLngBounds(
                    new google.maps.LatLng(-33.8688, -74.2149),
                    new google.maps.LatLng(5.2718, -32.3987)
                );

                const europeBounds = new google.maps.LatLngBounds(
                    new google.maps.LatLng(35.0, -10.0),
                    new google.maps.LatLng(71.0, 40.0)
                );

                localizacaoInputs.forEach(input => {
                    const autocomplete = new google.maps.places.Autocomplete(input, {
                        bounds: brasilBounds.union(europeBounds),
                        componentRestrictions: { country: ['BR', 'DE', 'FR', 'ES', 'IT', 'GB', 'PT', 'NL', 'BE', 'CH', 'AT'] },
                        types: ['geocode']
                    });

                    autocomplete.addListener('place_changed', function() {
                        const place = autocomplete.getPlace();
                        const latitude = place.geometry.location.lat();
                        const longitude = place.geometry.location.lng();

                        const parentDiv = input.closest('.location-value');
                        parentDiv.querySelector('.latitude').value = latitude;
                        parentDiv.querySelector('.longitude').value = longitude;
                    });
                });
            }

            initAutocomplete();
        });
    </script>
    <script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAI0_JvMUWuxEczMwkclXtvlqXJ4wQMx7s&libraries=places&callback=initAutocomplete"></script>
</body>
</html>

