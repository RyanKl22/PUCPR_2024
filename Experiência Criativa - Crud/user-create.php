<?php
session_start();
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>CRUD Usuário</title>
</head>
<body>
  
    <div class="container mt-5">

        <?php include('message.php'); ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Criar Usuário PF
                            <a href="index.php" class="btn btn-danger float-end">Voltar</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="code.php" method="POST">

                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="Email" class="form-control" placeholder="Digite seu email" required>
                            </div>
                            <div class="mb-3">
                                <label>Primeiro Nome</label>
                                <input type="text" name="PrimeiroNome" class="form-control" placeholder="Digite o primeiro nome" required>
                            </div>
                            <div class="mb-3">
                                <label>Segundo Nome</label>
                                <input type="text" name="SegundoNome" class="form-control" placeholder="Digite o segundo nome" required>
                            </div>
                            <div class="mb-3">
                                <label>Data Nascimento</label>
                                <input type="date" name="DataNascimento" class="form-control" placeholder="Digite a data de nascimento" required>
                            </div>
                            <div class="mb-3">
                                <label>CPF</label>
                                <input type="text" name="CPF" class="form-control" placeholder="Digite o CPF" required>
                            </div>
                            <div class="mb-3">
                                <label>Gênero</label>
                                <input type="text" name="Genero" class="form-control" placeholder="Digite seu Gênero" required>
                            </div>
                            <div class="mb-3">
                                <label>Telefone</label>
                                <input type="tel" name="Telefone" class="form-control" placeholder="Digite seu telefone" required>
                            </div>
                            <div class="mb-3">
                                <label>Senha</label>
                                <input type="password" name="Senha" class="form-control" placeholder="Digite seu telefone" required>
                            </div>
                            </div>
                            <div class="mb-3">
                                <button type="submit" name="save_userpf" class="btn btn-primary">Salvar Usuário</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>