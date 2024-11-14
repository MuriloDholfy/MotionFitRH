<?php
session_start();

// Verifica se o usuário está autenticado
if (!isset($_SESSION['user_id'])) {
    die("Erro: Usuário não está autenticado.");
}

// Obtém o ID do usuário logado
$user_id = $_SESSION['user_id'];

// Credenciais do banco de dados
$servername = "50.116.86.120";
$username = "motionfi_sistemaRH";
$password = "@Motion123"; // **ALTERE IMEDIATAMENTE** por segurança
$dbname = "motionfi_bdmotion";

// Conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Prepara a consulta SQL para buscar os dados do usuário pelo ID
$sql = "SELECT * FROM tbusuario WHERE idUsuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Verifica se o usuário foi encontrado
if ($result && $result->num_rows > 0) {
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
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Usuário</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/equipeStyle.css">
    <link rel="icon" href="../img/icons/navbar/logo.png" type="image/x-icon">
</head>
<body>
    <div class="container">
        <?php include '../../components/navbar.php'; ?>
        <div class="row p-3">
            <?php include '../../components/sideBar.php'; ?>
            <div class="col-md-11">
                <div class="card-grande">
                    <div class="card-body" style="text-align: left;">
                        <h1 class="card-text mb-5">Perfil</h1>
                        <p class="card-text">Nome: <?php echo htmlspecialchars($user['nome']); ?></p>
                        <p class="card-text">Cargo: <?php echo htmlspecialchars($user['tipoUsuario']); ?></p>
                        <p class="card-text">Email: <?php echo htmlspecialchars($user['email']); ?></p>
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
