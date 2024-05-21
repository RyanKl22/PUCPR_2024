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
            <a href="profile.php"class="profile">
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
                <form action="#" method="post">
                    <div class="form-group">
                        <label for="titulo">Título:</label>
                        <input type="text" id="titulo" name="titulo" required>
                    </div>
                    <div class="form-group">
                        <label for="descricao">Descrição:</label>
                        <textarea id="descricao" name="descricao" rows="4" class="fixed-textarea" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="localizacao">Localização e Valor Gasto:</label>
                        <div id="localizacao-valor-container">
                            <div class="localizacao-valor-item">
                                <input type="text" name="localizacao[]" id="localizacao" placeholder="Localização" required>
                                <input type="text" id="valorInput" placeholder="Valor" required>
                                <!-- Campos ocultos para latitude e longitude -->
                                <input type="hidden" name="latitude[]" class="latitude">
                                <input type="hidden" name="longitude[]" class="longitude">
                            </div>
                        </div>
                        <select id="moedaSelect">
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
                    </div>
                    <button type="submit">Criar Roteiro</button>
                </form>
            </div>
            <!-- Fim do formulário de criação de roteiro -->

            <div class="bottom-data">
                
            </div>

        </main>

    </div>

    <script src="main.js"></script>
    <script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAI0_JvMUWuxEczMwkclXtvlqXJ4wQMx7s&libraries=places&callback=initAutocomplete"></script>

    <script>
        // Função para adicionar campos de localização e valor dinamicamente
        function adicionarLocalizacaoValor() {
            const localizacaoValorContainer = document.getElementById('localizacao-valor-container');
            const novoItem = document.createElement('div');
            novoItem.classList.add('localizacao-valor-item');
            novoItem.innerHTML = `
                <input type="text" name="localizacao[]" placeholder="Localização" required>
                <input type="text" name="valor[]" placeholder="Valor" required>
                <!-- Campos ocultos para latitude e longitude -->
                <input type="hidden" name="latitude[]" class="latitude">
                <input type="hidden" name="longitude[]" class="longitude">
            `;
            localizacaoValorContainer.appendChild(novoItem);
            initAutocomplete(); // Inicializa o autocomplete para o novo campo
        }

        // Função para formatar o valor monetário
        function formatarValor(input) {
            let valor = input.value;
            // Verifica se o valor inserido contém uma vírgula
            if (valor.includes(',')) {
                // Remove todos os caracteres não numéricos, exceto a vírgula
                valor = valor.replace(/[^\d,]/g, '');
                // Substitui a vírgula por ponto
                valor = valor.replace(',', '.');
                // Atualiza o valor do campo de entrada
                input.value = valor;
            }
        }

        // Seleciona o campo de entrada e o seletor de moeda
        const valorInput = document.getElementById('valorInput');
        const moedaSelect = document.getElementById('moedaSelect');

        // Adiciona um evento de focus ao campo de entrada para chamar a função de formatação
        valorInput.addEventListener('focusout', function() {
            formatarValor(this);
        });

        // Adiciona um evento de mudança ao seletor de moeda para chamar a função de formatação
        moedaSelect.addEventListener('change', function() {
            formatarValor(valorInput);
        });

        // Inicializa o Google Places Autocomplete
        function initAutocomplete() {
            const localizacaoInputs = document.querySelectorAll('input[name="localizacao[]"]');
            localizacaoInputs.forEach(input => {
                const autocomplete = new google.maps.places.Autocomplete(input, { types: ['geocode'] });

                // Evento de lugar alterado
                autocomplete.addListener('place_changed', function() {
                    const place = autocomplete.getPlace();
                    const latitude = place.geometry.location.lat();
                    const longitude = place.geometry.location.lng();

                    // Encontra os campos ocultos correspondentes e atualiza-os
                    const parentDiv = input.closest('.localizacao-valor-item');
                    parentDiv.querySelector('.latitude').value = latitude;
                    parentDiv.querySelector('.longitude').value = longitude;
                });
            });
        }

        // Inicializa o autocomplete para o campo de localização existente ao carregar a página
        window.onload = function() {
            initAutocomplete();
        };
    </script>
</body>

</html>
