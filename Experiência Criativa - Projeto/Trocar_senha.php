<?php
   include 'auth.php';
   
   // Verifica se as variáveis de sessão estão definidas
   if (!isset($_SESSION['usuario_nome'])) {
       $_SESSION['usuario_nome'] = 'Valor Padrão do Nome';
   }
   
   if (!isset($_SESSION['usuario_adm'])) {
       $_SESSION['usuario_adm'] = false; // Valor padrão para o tipo de usuário
   }

    // Recupera o ID do usuário da sessão
    $usuario_id = $_SESSION['usuario_id'];

?>
<!DOCTYPE html>
<html lang="pt-BR">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
      <link rel="stylesheet" href="Trocar_senha.css">
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
            <li><a href="roteiro.php"><i class='bx bx-images'></i></i>Roteiros</a></li>
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
            <div class="bottom-data">
               <h2>Trocar Senha</h2>
               <form action="#" method="post">
                  <div class="form-group">
                     <label for="senha_antiga">Senha Anterior:</label>
                     <input type="password" id="senha_antiga" name="senha_antiga" required>
                  </div>
                  <div class="form-group">
                     <label for="senha_nova">Nova Senha:</label>
                     <input type="password" id="senha_nova" name="senha_nova" required>
                  </div>
                  <div class="form-group">
                     <label for="senha_conf">Confirmar Senha Nova:</label>
                     <input type="password" id="senha_conf" name="senha_conf" required>
                  </div>
                  <!-- Div para exibir mensagens de erro -->
                  <div id="mensagemErro" style="color: red;"></div>
                  <button type="button" class="primary-button" onclick="trocarSenha()">Confirmar</button>
               </form>
            </div>
         </main>
      </div>
      <script>

function trocarSenha() {
            console.log("Alterando Senha...");

            // Obter os valores dos campos de senha antiga, nova e confirmação de senha
            var senhaAntiga = document.getElementById('senha_antiga').value;
            var senhaNova = document.getElementById('senha_nova').value;
            var senhaConf = document.getElementById('senha_conf').value;

            // Obtendo o ID do usuário
            var userId = <?php echo json_encode($usuario_id); ?>;
            console.log("ID = " + userId);

            // Verificar se a nova senha é válida
            function validarSenha() {
                // Verifica se as senhas correspondem
                if (senhaNova !== senhaConf) {
                    document.getElementById('mensagemErro').innerText = "As senhas não correspondem.";
                    return false;
                } else {
                    document.getElementById('mensagemErro').innerText = "";
                }

                // Verifica se a senha é forte o suficiente
                if (senhaNova.length < 8) {
                    document.getElementById('mensagemErro').innerText = "A senha deve ter pelo menos 8 caracteres.";
                    return false;
                }

                // Verifica se a senha contém pelo menos uma letra maiúscula
                if (!/[A-Z]/.test(senhaNova)) {
                    document.getElementById('mensagemErro').innerText = "A senha deve conter pelo menos uma letra maiúscula.";
                    return false;
                }

                // Senha válida
                return true;
            }

            if (!validarSenha()) {
                return;
            }

            // Enviar uma solicitação AJAX para validar a senha antiga e trocar a senha
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'ValidaSenhaNova.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (xhr.status == 200) {
                    var resposta = JSON.parse(xhr.responseText);
                    if (resposta.status == 'success') {
                        alert(resposta.message);
                        window.location.href = 'profile.php'; // Redirecionar para profile.php após a alteração bem-sucedida
                    } else {
                        document.getElementById('mensagemErro').innerText = resposta.message;
                    }
                } else {
                    alert('Erro ao trocar a senha. Por favor, tente novamente.');
                }
            };
            xhr.onerror = function () {
                alert('Erro ao trocar a senha. Por favor, tente novamente.');
            };
            xhr.send('senha_antiga=' + encodeURIComponent(senhaAntiga) + '&senha_nova=' + encodeURIComponent(senhaNova) + '&user_id=' + encodeURIComponent(userId));
        }

      </script>
   </body>
</html>
