<?php
session_start();

$servername = "50.116.86.120";
$username = "motionfi_sistemaRH";
$password = "@Motion123"; // **ALTERE IMEDIATAMENTE** por segurança
$dbname = "motionfi_bdmotion";

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

    // Verificar se o tipo de usuário é "adm"
    if ($user['tipoUsuario'] == 'adm') {
        // Iniciar a sessão e armazenar informações do usuário
        $_SESSION['user_id'] = $user['id']; // Ajuste se necessário
        $_SESSION['user_name'] = $user['nomeUsuario']; // Ajuste se necessário
        $_SESSION['user_role'] = $user['tipoUsuario']; // Ajuste se necessário
        $_SESSION['usuario_logado'] = true;

        // Redirecionar para a página inicial de acordo com o tipo de usuário
        header("Location: ../Home/?role=" . urlencode($user['tipoUsuario']));
        exit();
    } else {
        // Se o tipo de usuário não for "ADM", recusar login
        $_SESSION['usuario_logado'] = false;
        $_SESSION['login_error'] = "Você não tem permissão para acessar o sistema.";
        header("Location: ../Login/");
        exit();
    }

} else {
    // Se a consulta não encontrar nenhum usuário com as credenciais fornecidas
    $_SESSION['usuario_logado'] = false;
    $_SESSION['login_error'] = "Email ou senha incorretos.";
    header("Location: ../Login/");
    exit();
}

$conn->close();
?>
