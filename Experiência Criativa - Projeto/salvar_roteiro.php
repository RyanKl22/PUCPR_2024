<?php

include 'auth.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "JBB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$response = ['success' => false];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $privacidade = isset($_POST['privacidade']) ? 1 : 0;
    $localizacoes = $_POST['localizacao'];
    $valores = $_POST['valor'];
    $moeda = $_POST['moeda'];
    $imagens = $_FILES['imagens'];

    $id_usuario = $_SESSION['usuario_id'];

    // Inserindo dados na tabela roteirosviagem
    $sql_roteiro = "INSERT INTO roteirosviagem (id_usuario, titulo, descricao, publico_privado) 
                    VALUES ('$id_usuario', '$titulo', '$descricao', '$privacidade')";
    mysqli_query($conn, $sql_roteiro);

    $id_roteiro = mysqli_insert_id($conn);

    // Inserindo dados na tabela localizacoesvalores
    for ($i = 0; $i < count($localizacoes); $i++) {
        $localizacao = $localizacoes[$i];
        $valor = $valores[$i];
        $latitude = $_POST['latitude'][$i];
        $longitude = $_POST['longitude'][$i];

        $sql_localizacao = "INSERT INTO localizacoesvalores (id_roteiro, nome_local, latitude, longitude, valor_gasto, moeda) 
                            VALUES ('$id_roteiro', '$localizacao', '$latitude', '$longitude', '$valor', '$moeda')";
        mysqli_query($conn, $sql_localizacao);
    }

    // Inserindo dados na tabela imagens
    if (!empty($imagens['name'][0])) {
        $target_dir = "uploads/";

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        for ($i = 0; $i < count($imagens['name']); $i++) {
            $target_file = $target_dir . basename($imagens['name'][$i]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            $check = getimagesize($imagens['tmp_name'][$i]);
            if ($check !== false) {
                if (move_uploaded_file($imagens['tmp_name'][$i], $target_file)) {
                    $sql_imagem = "INSERT INTO imagens (id_roteiro, imagem) VALUES ('$id_roteiro', '$target_file')";
                    mysqli_query($conn, $sql_imagem);
                }
            }
        }
    }

    // Inserindo dados na tabela gruposviagem
    $sql_grupo = "INSERT INTO gruposviagem (id_roteiro, id_admin, nome, descricao) 
                  VALUES ('$id_roteiro', '$id_usuario', '$titulo', '')";
    mysqli_query($conn, $sql_grupo);

    $id_grupo = mysqli_insert_id($conn);

    // Inserindo dados na tabela membrosgrupo
    $sql_membro = "INSERT INTO membrosgrupo (id_grupo, id_usuario) 
                   VALUES ('$id_grupo', '$id_usuario')";
    mysqli_query($conn, $sql_membro);

    $response['success'] = true;
}

echo json_encode($response);
?>
