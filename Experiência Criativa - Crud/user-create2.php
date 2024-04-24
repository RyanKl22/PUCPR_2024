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
                        <h4>Criar Usuário PJ
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
                                <label>Nome Fantasia</label>
                                <input type="text" name="NomeFantasia" class="form-control" placeholder="Digite o Nome Fantasia" required>
                            </div>
                            <div class="mb-3">
                                <label>Razão Social</label>
                                <input type="text" name="RazaoSocial" class="form-control" placeholder="Digite a Razão Social" required>
                            </div>
                            <div class="mb-3">
                                <label>CNPJ</label>
                                <input type="text" name="CNPJ" class="form-control" placeholder="Digite o CNPJ" required>
                            </div>
                            <div class="mb-3">
                                <label>Data da Abertura da Empresa</label>
                                <input type="date" name="DataAbertura" class="form-control" placeholder="Digite a Data de Abertura" required>
                            </div>
                            <div class="mb-3">
                                <label>Inscrição Estadual</label>
                                <input type="text" name="InscricaoEstadual" class="form-control" placeholder="Digite a Inscrição Estadual" required>
                            </div>
                            <div class="mb-3">
                                <label>Telefone para contato</label>
                                <input type="tel" name="Telefone" class="form-control" placeholder="Digite o Telefone para Contato" required>
                            </div>
                            <div class="mb-3">
                                <label>Senha</label>
                                <input type="password" name="Senha" class="form-control" placeholder="Digite seu telefone" required>
                            </div>
                            </div>
                            <div class="mb-3">
                                <button type="submit" name="save_userpj" class="btn btn-primary">Salvar Usuário</button>
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