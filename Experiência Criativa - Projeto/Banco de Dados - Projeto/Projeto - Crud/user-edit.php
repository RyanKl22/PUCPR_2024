<?php
session_start();
require 'dbcon.php';
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
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
                        <h4>Editar Usuário 
                            <a href="index.php" class="btn btn-danger float-end">Voltar</a>
                        </h4>
                    </div>
                    <div class="card-body">

                        <?php
                        if(isset($_GET['id']))
                        {
                            $user_id = mysqli_real_escape_string($con, $_GET['id']);
                            $query = "SELECT * FROM usuarios_pf WHERE id='$user_id' ";
                            $query_run = mysqli_query($con, $query);

                            if(mysqli_num_rows($query_run) > 0)
                            {
                                $usuarios_pf = mysqli_fetch_array($query_run);
                                ?>
                                <form action="code.php" method="POST">
                                    <input type="hidden" name="usuarios_pf_id" value="<?= $usuarios_pf['ID']; ?>">

                                    <div class="mb-3">
                                        <label>Primeiro Nome</label>
                                        <input type="text" name="PrimeiroNome" value="<?=$usuarios_pf['PrimeiroNome'];?>" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label>Segundo Nome</label>
                                        <input type="text" name="SegundoNome" value="<?=$usuarios_pf['SegundoNome'];?>" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label>Email</label>
                                        <input type="email" name="Email" value="<?=$usuarios_pf['Email'];?>" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label>Data de Nascimento</label>
                                        <input type="date" name="DataNascimento" value="<?=$usuarios_pf['DataNascimento'];?>" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label>CPF</label>
                                        <input type="text" name="CPF" value="<?=$usuarios_pf['CPF'];?>" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label>Gênero</label>
                                        <input type="text" name="Genero" value="<?=$usuarios_pf['Genero'];?>" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label>Telefone</label>
                                        <input type="text" name="Telefone" value="<?=$usuarios_pf['Telefone'];?>" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" name="update_userpf" class="btn btn-primary">
                                            Atualizar Usuário
                                        </button>
                                    </div>

                                </form>
                                <?php
                            }
                            else
                            {
                                echo "<h4>Usuário não encontrado</h4>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>