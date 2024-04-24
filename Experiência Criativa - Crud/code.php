<?php
session_start();
require 'dbcon.php';

if(isset($_POST['delete_userpf']))
{
    $user_id = mysqli_real_escape_string($con, $_POST['delete_userpf']);

    $query = "DELETE FROM usuarios_pf WHERE id='$user_id' ";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "Usuário excluido com sucesso";
        header("Location: index.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Erro ao exluir Usuário";
        header("Location: index.php");
        exit(0);
    }
}

if(isset($_POST['delete_userpj']))
{
    $user_id = mysqli_real_escape_string($con, $_POST['delete_userpj']);

    $query = "DELETE FROM usuarios_pj WHERE id='$user_id' ";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "Usuário excluido com sucesso";
        header("Location: index2.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Erro ao exluir Usuário";
        header("Location: index2.php");
        exit(0);
    }
}

if(isset($_POST['update_userpf']))
{
    $user_id = mysqli_real_escape_string($con, $_POST['usuarios_pf_id']);
    $primeiro_nome = mysqli_real_escape_string($con, $_POST['PrimeiroNome']);
    $segundo_nome = mysqli_real_escape_string($con, $_POST['SegundoNome']);
    $email = mysqli_real_escape_string($con, $_POST['Email']);
    $data_nascimento = mysqli_real_escape_string($con, $_POST['DataNascimento']);
    $cpf = mysqli_real_escape_string($con, $_POST['CPF']);
    $genero = mysqli_real_escape_string($con, $_POST['Genero']);
    $telefone = mysqli_real_escape_string($con, $_POST['Telefone']);

    $query = "UPDATE usuarios_pf SET PrimeiroNome='$primeiro_nome', SegundoNome='$segundo_nome', Email='$email', DataNascimento='$data_nascimento', CPF='$cpf', Genero='$genero', Telefone='$telefone' WHERE ID='$user_id'";

    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "Usuário atualizado com sucesso";
        header("Location: index.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Falha ao editar usuário";
        header("Location: index.php");
        exit(0);
    }
}


if(isset($_POST['update_userpj']))
{
    $user_id = mysqli_real_escape_string($con, $_POST['usuarios_pf_id']);
    $nome_fantasia = mysqli_real_escape_string($con, $_POST['NomeFantasia']);
    $razao_social = mysqli_real_escape_string($con, $_POST['RazaoSocial']);
    $cnpj = mysqli_real_escape_string($con, $_POST['CNPJ']);
    $email = mysqli_real_escape_string($con, $_POST['Email']);
    $data_abertura = mysqli_real_escape_string($con, $_POST['DataAbertura']);
    $inscricao_estadual = mysqli_real_escape_string($con, $_POST['InscricaoEstadual']);
    $telefone = mysqli_real_escape_string($con, $_POST['Telefone']);

    $query = "UPDATE usuarios_pj 
              SET NomeFantasia='$nome_fantasia', 
                  RazaoSocial='$razao_social', 
                  CNPJ='$cnpj', 
                  Email='$email', 
                  DataAbertura='$data_abertura', 
                  InscricaoEstadual='$inscricao_estadual', 
                  Telefone='$telefone' 
              WHERE ID='$user_id'";

    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "Usuário atualizado com sucesso";
        header("Location: index2.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Falha ao editar usuário";
        header("Location: index2.php");
        exit(0);
    }
}

if(isset($_POST['save_userpf']))
{
    $primeiro_nome = mysqli_real_escape_string($con, $_POST['PrimeiroNome']);
    $segundo_nome = mysqli_real_escape_string($con, $_POST['SegundoNome']);
    $email = mysqli_real_escape_string($con, $_POST['Email']);
    $data_nascimento = mysqli_real_escape_string($con, $_POST['DataNascimento']);
    $cpf = mysqli_real_escape_string($con, $_POST['CPF']);
    $genero = mysqli_real_escape_string($con, $_POST['Genero']);
    $telefone = mysqli_real_escape_string($con, $_POST['Telefone']);
    $senha = mysqli_real_escape_string($con, $_POST['Senha']);

    $query = "INSERT INTO usuarios_pf (PrimeiroNome, SegundoNome, Email, DataNascimento, CPF, Genero, Telefone) 
              VALUES ('$primeiro_nome', '$segundo_nome', '$email', '$data_nascimento', '$cpf', '$genero', '$telefone')";

    $query_run = mysqli_query($con, $query);

    // Criptografar a senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $query = "INSERT INTO usuario (Email, Senha) VALUES ('$email', '$senha_hash')";

    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "Usuário criado com sucesso";
        header("Location: index.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Erro ao criar usuário";
        header("Location: index.php");
        exit(0);
    }
}

if(isset($_POST['save_userpj']))
{
    $email = mysqli_real_escape_string($con, $_POST['Email']);
    $nome_fantasia = mysqli_real_escape_string($con, $_POST['NomeFantasia']);
    $razao_social = mysqli_real_escape_string($con, $_POST['RazaoSocial']);
    $cnpj = mysqli_real_escape_string($con, $_POST['CNPJ']);
    $data_abertura = mysqli_real_escape_string($con, $_POST['DataAbertura']);
    $inscricao_estadual = mysqli_real_escape_string($con, $_POST['InscricaoEstadual']);
    $telefone = mysqli_real_escape_string($con, $_POST['Telefone']);
    $senha = mysqli_real_escape_string($con, $_POST['Senha']);

    $query = "INSERT INTO usuarios_pj (Email, NomeFantasia, RazaoSocial, CNPJ, DataAbertura, InscricaoEstadual, Telefone) 
              VALUES ('$email', '$nome_fantasia', '$razao_social', '$cnpj', '$data_abertura', '$inscricao_estadual', '$telefone')";

    $query_run = mysqli_query($con, $query);

    // Criptografar a senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $query = "INSERT INTO usuario (Email, Senha) VALUES ('$email', '$senha_hash')";

    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "Usuário criado com sucesso";
        header("Location: index2.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Erro ao criar usuário";
        header("Location: index2.php");
        exit(0);
    }
}

?>