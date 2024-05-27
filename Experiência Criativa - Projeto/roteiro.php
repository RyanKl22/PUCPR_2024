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
      <link rel="stylesheet" href="roteiro.css">
      <title>JourneyBuddy</title>
   </head>
   <body>
      <!-- Barra Lateral -->
      <div class="sidebar">
         <a href="#" class="logo">
         </a>
         <ul class="side-menu">
            <li><a href="main.php"><i class='bx bxs-home-smile'></i>Home</a></li>
            <li><a href="grupo.php"><i class='bx bxs-group'></i>Grupos</a></li>
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
            <img src="images/Logo.png">
            </a>
            <div class="user-info">
               <span class="user-name"><?php echo $_SESSION['usuario_nome']; ?></span>
               <span class="user-role"><?php echo ($_SESSION['usuario_adm'] ? 'Administrador' : 'Viajante'); ?></span>
            </div>
         </nav>
         <!-- Fim da Barra de Navegação -->
         <main>
            <!-- Adição de um formulário para criar roteiro -->
            <div class="form-container">
               <h2>Criar Roteiro</h2>
               <form id="roteiro-form" action="salvar_roteiro.php" method="post" enctype="multipart/form-data">
                  <div class="form-group">
                     <label for="titulo">Título:</label>
                     <input type="text" id="titulo" name="titulo" required>
                  </div>
                  <div class="form-group">
                     <label for="descricao">Descrição:</label>
                     <textarea id="descricao" name="descricao" rows="3" class="fixed-textarea" required></textarea>
                  </div>
                  <div class="form-group">
                     <label for="localizacao">Localização e Valor Gasto:</label>
                     <div id="localizacao-valor-container">
                        <div class="localizacao-valor-item">
                           <input type="text" name="localizacao[]" id="localizacao" placeholder="Localização" required>
                           <input type="number" step="0.10" id="valorInput" name="valor[]" placeholder="Valor" required>
                           <!-- Campos ocultos para latitude e longitude -->
                           <input type="hidden" name="latitude[]" class="latitude">
                           <input type="hidden" name="longitude[]" class="longitude">
                        </div>
                     </div>
                     <select id="moedaSelect" name="moeda">
                        <option value="BRL">R$ (BRL)</option>
                        <option value="USD">$ (USD)</option>
                        <option value="EUR">€ (EUR)</option>
                        <option value="GBP">£ (GBP)</option>
                        <!-- Adicione mais opções conforme necessário -->
                     </select>
                     <button type="button" onclick="adicionarLocalizacaoValor()">Adicionar</button>
                  </div>
                  <div class="form-group">
                     <label for="imagens">Inserir Imagens:</label>
                     <label for="file-upload" class="custom-file-upload">Escolher Arquivo</label>
                     <input type="file" id="file-upload" name="imagens[]" multiple>
                     <div id="image-preview"></div>
                     <!-- Container para pré-visualização das imagens -->
                  </div>
                  <button type="submit">Criar Roteiro</button>
               </form>
            </div>
            <div class="bottom-data">
               <!-- Caso a gente queira Outros conteúdos -->
            </div>
         </main>
      </div>

      <script src="main.js"></script>
      <script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAI0_JvMUWuxEczMwkclXtvlqXJ4wQMx7s&libraries=places&callback=initAutocomplete"></script>
      <script>
document.getElementById('file-upload').addEventListener('change', function (event) {
    const files = event.target.files;
    const formData = new FormData();

    for (let i = 0; i < files.length; i++) {
        formData.append('imagens[]', files[i]);
    }

    fetch('upload_imagem.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const imagePreview = document.getElementById('image-preview');
            imagePreview.innerHTML = ''; // Limpa as pré-visualizações anteriores
            data.filepaths.forEach(filepath => {
                const img = document.createElement('img');
                img.src = filepath;
                img.style.width = '100px';
                img.style.height = '100px';
                imagePreview.appendChild(img);
            });
        } else {
            alert('Falha no upload da imagem');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
    });
});

document.getElementById('roteiro-form').addEventListener('submit', function (event) {
    event.preventDefault();
    const formData = new FormData(this);

    fetch('salvar_roteiro.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Substitua este alert pelo seu popup JavaScript
            alert('Roteiro criado com sucesso!');
            window.location.href = 'main.php'; // Redireciona para a página principal
        } else {
            alert('Erro ao criar roteiro.');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
    });
});

function adicionarLocalizacaoValor() {
    const localizacaoValorContainer = document.getElementById('localizacao-valor-container');
    const novoItem = document.createElement('div');
    novoItem.classList.add('localizacao-valor-item');
    novoItem.innerHTML = `
        <input type="text" name="localizacao[]" placeholder="Localização" required>
        <input type="text" name="valor[]" class="valorInput" placeholder="Valor" required>
        <!-- Campos ocultos para latitude e longitude -->
        <input type="hidden" name="latitude[]" class="latitude">
        <input type="hidden" name="longitude[]" class="longitude">
    `;
    localizacaoValorContainer.appendChild(novoItem);
    initAutocomplete(); // Inicializa o autocomplete para o novo campo
}

function initAutocomplete() {
    const localizacaoInputs = document.querySelectorAll('input[name="localizacao[]"]');
    const brasilBounds = new google.maps.LatLngBounds(
        new google.maps.LatLng(-33.8688, -74.2149), // sudoeste do Brasil
        new google.maps.LatLng(5.2718, -32.3987)    // nordeste do Brasil
    );

    const europeBounds = new google.maps.LatLngBounds(
        new google.maps.LatLng(35.0, -10.0), // sudoeste da Europa
        new google.maps.LatLng(71.0, 40.0)   // nordeste da Europa
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

            const parentDiv = input.closest('.localizacao-valor-item');
            parentDiv.querySelector('.latitude').value = latitude;
            parentDiv.querySelector('.longitude').value = longitude;
        });
    });
}
      </script>
   </body>
</html>
