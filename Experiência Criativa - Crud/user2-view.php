<?php
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

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Visualizar Usuário PJ
                            <a href="index2.php" class="btn btn-danger float-end">Voltar</a>
                        </h4>
                    </div>
                    <div class="card-body">

                        <?php
                        if(isset($_GET['id']))
                        {
                            $user_id = mysqli_real_escape_string($con, $_GET['id']);
                            $query = "SELECT * FROM usuarios_pj WHERE id='$user_id' ";
                            $query_run = mysqli_query($con, $query);

                            if(mysqli_num_rows($query_run) > 0)
                            {
                                $usuarios_pj= mysqli_fetch_array($query_run);
                                ?>
                                
                                    <div class="mb-3">
                                        <label>Nome Fantasia</label>
                                        <p class="form-control">
                                            <?=$usuarios_pj['NomeFantasia'];?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label>Razão Social</label>
                                        <p class="form-control">
                                            <?=$usuarios_pj['RazaoSocial'];?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label>CNPJ</label>
                                        <p class="form-control">
                                            <?=$usuarios_pj['CNPJ'];?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label>Email</label>
                                        <p class="form-control">
                                            <?=$usuarios_pj['Email'];?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label>DataAbertura</label>
                                        <p class="form-control">
                                            <?=$usuarios_pj['DataAbertura'];?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label>InscricaoEstadual</label>
                                        <p class="form-control">
                                            <?= $usuarios_pj['InscricaoEstadual']?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label>Telefone</label>
                                        <p class="form-control">
                                            <?= $usuarios_pj['Telefone']?>
                                        </p>
                                    </div>
                                <?php
                            }
                            else
                            {
                                echo "<h4>ID de Usuário não encontrado</h4>";
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