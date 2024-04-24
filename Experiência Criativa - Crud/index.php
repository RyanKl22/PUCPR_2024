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
  
    <div class="container mt-4">

        <?php include('message.php'); ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Detalhes Usuário PF
                            <a href="user-create.php" class="btn btn-primary float-end me-2">Adicionar Usuário PF</a>
                            <a href="index2.php" class="btn btn-primary float-end me-2">Analisar Usuário PJ</a>
                            <a href="index3.php" class="btn btn-primary float-end me-2">Analisar Usuários (Geral)</a>
                        </h4>
                    </div>
                    <div class="card-body">

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Primeiro Nome</th>
                                    <th>Segundo Nome</th>
                                    <th>Email</th>
                                    <th>Data de Nascimento</th>
                                    <th>CPF</th>
                                    <th>Gênero</th>
                                    <th>Telefone</th>  
                                    <th>Funções</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $query = "SELECT * FROM usuarios_pf";
                                    $query_run = mysqli_query($con, $query);

                                    if(mysqli_num_rows($query_run) > 0)
                                    {
                                        foreach($query_run as $usuarios_pf)
                                        {
                                            ?>
                                            <tr>
                                                <td><?= $usuarios_pf['ID']; ?></td>
                                                <td><?= $usuarios_pf['PrimeiroNome']; ?></td>
                                                <td><?= $usuarios_pf['SegundoNome']; ?></td>
                                                <td><?= $usuarios_pf['Email']; ?></td>
                                                <td><?= $usuarios_pf['DataNascimento']; ?></td>
                                                <td><?= $usuarios_pf['CPF']; ?></td>
                                                <td><?= $usuarios_pf['Genero']; ?></td>
                                                <td><?= $usuarios_pf['Telefone']; ?></td>
                                                <td>
                                                    <a href="user-view.php?id=<?= $usuarios_pf['ID']; ?>" class="btn btn-info btn-sm">Vizualizar</a>
                                                    <a href="user-edit.php?id=<?= $usuarios_pf['ID']; ?>" class="btn btn-success btn-sm">Editar</a>
                                                    <form action="code.php" method="POST" class="d-inline">
                                                        <button type="submit" name="delete_userpf" value="<?=$usuarios_pf['ID'];?>" class="btn btn-danger btn-sm">Deletar</button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        echo "<h5> Não foram encontrados dados </h5>";
                                    }
                                ?>
                                
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>