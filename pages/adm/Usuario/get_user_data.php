<?php
$servername = "50.116.86.123";
$username = "motionfi_contato
";
$password = "68141096@Total";

$dbname = "motionfi_bdmotion";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$idUsuario = $_POST['idUsuario'];
$sql = "SELECT idUsuario, nome, email, senha, tipoUsuario FROM tbUsuario WHERE idUsuario=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$result = $stmt->get_result();

$data = $result->fetch_assoc();

echo json_encode($data);

$stmt->close();
$conn->close();
?>
