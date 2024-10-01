<?php
session_start();
include '../Validacao/valida_login.php';

// Verifica se o user_id está definido na sessão
if (!isset($_SESSION['user_id'])) {
    die("Erro: Usuário não está autenticado.");
}

$servername = "50.116.86.123";
$username = "motionfi_contato
";
$password = "68141096@Total";

$dbname = "motionfi_bdmotion";
// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Prepara a consulta SQL para evitar SQL Injection
$sql = "SELECT * FROM tbusuario WHERE idUsuario = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Erro na preparação da consulta: " . $conn->error);
}

// Vincula o parâmetro e executa a consulta
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result === false) {
    die("Erro na execução da consulta SQL: " . $conn->error);
}

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "Erro: Usuário não encontrado.";
    $stmt->close();
    $conn->close();
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Usuário</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/equipeStyle.css">
</head>
<body>
    <div class="container">
        <?php include '../../components/navbar.php'; ?>
        <div class="row p-3">
            <?php include '../../components/sideBar.php'; ?>

            <div class="col-md-11">
                <div class="card-grande">
                <div class="card-body " style="text-align: left;">
                        <h1 class="card-text mb-5">Perfil</h1>
                        <p class="card-text">Nome: <?php echo htmlspecialchars($user['nome']); ?></p>
                        <p class="card-text">Cargo: <?php echo htmlspecialchars($user['tipoUsuario']); ?></p>
                        <p class="card-text">Email: <?php echo htmlspecialchars($user['email']); ?></p>
                        <!-- <p class="card-text">Senha: <?php echo htmlspecialchars($user['senha']); ?></p> -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
