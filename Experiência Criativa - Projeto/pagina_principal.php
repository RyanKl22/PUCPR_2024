<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JourneyBuddy</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    <nav>
        <div class="container main-nav flex">
            <a href="#" class="company-logo">
                <img class="Logoo" src="./images/Logo - Copia.png" alt="company logo">
            </a>
            <div class="nav-links" id="nav-links">
                <ul class="flex">
                    
                </ul>
            </div>
            <a href="#" class="nav-toggle hover-link" id="nav-toggle">
                <i class="fa-solid fa-bars"></i>
            </a>
        </div>
    </nav>

    <header>
        <div class="container header-section flex">
            <div class="header-left">
                <div class="login-container">
                    <!-- Início do formulário de login -->
                    <form action="login.php" method="post">
                        <h2>Entrou / Teste </h2>
                    </form>
                    <!-- Fim do formulário de login -->
                </div>
            </div>
        </div>
    </header>

    <br>
    
    <!-- Popup de erro -->
    <div id="errorPopup" class="error-popup" style="display: none;">
        <p id="errorMessage"></p>
        <button onclick="closePopup()">Fechar</button>
    </div>

    <footer>
        <div class="subfooter">
            <div class="container flex subfooter-container">
                <a class="hover-link2" href="#">© 2024 JourneyBuddy. Todos os direitos reservados</a>
            </div>
        </div>
    </footer>

    <script src="https://kit.fontawesome.com/c1fc3d2826.js" crossorigin="anonymous"></script>

    <script>
        const toggleButton = document.getElementById('nav-toggle');
        const navLinks = document.getElementById('nav-links');

        toggleButton.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });

    </script>

</body>
</html>
