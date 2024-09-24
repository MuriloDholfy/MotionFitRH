<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bdmotion";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$email = $_POST['email'];
$senha = $_POST['senha'];

$email = $conn->real_escape_string($email);
$senha = $conn->real_escape_string($senha);

$sql = "SELECT * FROM tbusuario WHERE email = '$email' AND senha = '$senha'";
$result = $conn->query($sql);

if ($result === false) {
    die("Erro na consulta SQL: " . $conn->error);
}

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $_SESSION['user_id'] = $user['idUsuario']; // Ajuste se necessário
    $_SESSION['user_name'] = $user['nomeUsuario']; // Ajuste se necessário
    $_SESSION['user_role'] = $user['tipoUsuario']; // Ajuste se necessário
    $_SESSION['usuario_logado'] = true;

    header("Location: ../Home/?role=" . urlencode($user['tipoUsuario']));
    exit();
} else {
    $_SESSION['usuario_logado'] = false;
    $_SESSION['login_error'] = "Email ou senha incorretos";
    header("Location: ../Login/");
    exit();
}

$conn->close();
?>
