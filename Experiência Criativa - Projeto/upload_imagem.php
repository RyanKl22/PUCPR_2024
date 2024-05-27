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

$response = ['success' => false, 'filepaths' => []];


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['imagens'])) {

    $target_dir = "uploads/";

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    for ($i = 0; $i < count($_FILES['imagens']['name']); $i++) {
        $target_file = $target_dir . basename($_FILES['imagens']['name'][$i]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES['imagens']['tmp_name'][$i]);
        if ($check !== false) {
            if (move_uploaded_file($_FILES['imagens']['tmp_name'][$i], $target_file)) {
                $response['filepaths'][] = $target_file;
            }
        }
    }

    $response['success'] = true;
}

echo json_encode($response);
?>
