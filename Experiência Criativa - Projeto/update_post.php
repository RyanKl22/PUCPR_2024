<?php
include 'auth.php';

if (!isset($_SESSION['usuario_nome'])) {
    $_SESSION['usuario_nome'] = 'Valor Padrão do Nome';
}

if (!isset($_SESSION['usuario_adm'])) {
    $_SESSION['usuario_adm'] = false;
}

$logged_in_user_id = $_SESSION['usuario_id'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "JBB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$data = json_decode(file_get_contents("php://input"), true);
$post_id = $data['id'];
$updated_data = $data['data'];

$titulo = $updated_data['title'];
$descricao = $updated_data['textarea'];
$sql = "UPDATE roteirosviagem SET titulo = ?, descricao = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $titulo, $descricao, $post_id);
$stmt->execute();
$stmt->close();

if (isset($updated_data['image'])) {
    $imagem = $updated_data['image'];
    if ($imagem == 'REMOVER') {
        $sql = "DELETE FROM Imagens WHERE id_roteiro = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $post_id);
        $stmt->execute();
        $stmt->close();
    } else {
        $sql = "INSERT INTO Imagens (id_roteiro, imagem) VALUES (?, ?) ON DUPLICATE KEY UPDATE imagem = VALUES(imagem)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $post_id, $imagem);
        $stmt->execute();
        $stmt->close();
    }
}

$local_ids = $updated_data['location_ids'];
$localizacoes = $updated_data['location'];
$valores = $updated_data['value'];
$latitudes = $updated_data['latitude'];
$longitudes = $updated_data['longitude'];

for ($i = 0; $i < count($local_ids); $i++) {
    $local_id = $local_ids[$i];
    $local = $localizacoes[$i];
    $valor = $valores[$i];
    $latitude = $latitudes[$i];
    $longitude = $longitudes[$i];

    $sql = "UPDATE localizacoesvalores SET nome_local = ?, valor_gasto = ?, latitude = ?, longitude = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssdi", $local, $valor, $latitude, $longitude, $local_id);
    $stmt->execute();
    $stmt->close();
}

$conn->close();

echo json_encode(['success' => true]);
?>
