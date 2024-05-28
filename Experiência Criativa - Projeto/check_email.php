<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "JBB";

// Criando a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Checando a conexão
if ($conn->connect_error) {
  die("Conexão falhou: " . $conn->connect_error);
}


// Verifica se o email já está cadastrado
function checkEmailExists($conn, $email) {
    // Verifica na tabela Usuarios_pf
    $stmt = $conn->prepare("SELECT Email FROM Usuarios_pf WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->close();
        return true;
    }
    $stmt->close();

    // Verifica na tabela Usuarios_pj
    $stmt = $conn->prepare("SELECT Email FROM Usuarios_pj WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->close();
        return true;
    }
    $stmt->close();

    return false;
}

// Verifica se o email foi enviado via método POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"])) {
    $email = $_POST["email"];

    // Verifica se o email já está cadastrado
    if (checkEmailExists($conn, $email)) {
        echo json_encode(["exists" => true]);
    } else {
        echo json_encode(["exists" => false]);
    }
}

$conn->close(); // Fecha a conexão com o banco de dados
?>
