<?php
// Ativar exibição de erros para depuração
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Iniciar a sessão com configurações seguras
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => 'motionfitapi.com.br',
    'secure' => true, // Defina como true em produção com HTTPS
    'httponly' => true,
    'samesite' => 'Strict'
]);
session_start();

// Credenciais do banco de dados
$servername = "50.116.86.120";
$username = "motionfi_sistemaRH";
$password = "@Motion123"; // **ALTERE IMEDIATAMENTE** por segurança
$dbname = "motionfi_bdmotion";

// Conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    error_log("Falha na conexão: " . $conn->connect_error);
    $_SESSION['login_error'] = "Erro de conexão com o banco de dados.";
    header("Location: ../Login/?error=database_connection");
    exit();
}

// Verifica se os dados foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $senha = $_POST['senha']; // Senha fornecida pelo usuário

    if (!$email) {
        error_log("Email inválido fornecido: " . $_POST['email']);
        $_SESSION['login_error'] = "Email inválido.";
        header("Location: ../Login/?error=invalid_email");
        exit();
    }

    // Preparar consulta para buscar o usuário pelo email
    $stmt = $conn->prepare("SELECT * FROM tbusuario WHERE email = ?");
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if ($senha === $user['senha']) { // Confere a senha
                // Definir variáveis de sessão
                $_SESSION['user_id'] = $user['idUsuario'];
                $_SESSION['user_name'] = $user['nome'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['tipoUsuario'];

                // Atualizar o status de login e data/hora de login no banco
                $update_stmt = $conn->prepare("UPDATE tbusuario SET usuario_logado = 1, data_login = NOW() WHERE idUsuario = ?");
                $update_stmt->bind_param("i", $user['idUsuario']);
                $update_stmt->execute();

                // Redirecionar para a página inicial
                header("Location: ../Home/?role=" . urlencode($user['tipoUsuario']));
                exit();
            } else {
                $_SESSION['login_error'] = "Senha incorreta.";
            }
        } else {
            $_SESSION['login_error'] = "Usuário não encontrado.";
        }
        $stmt->close();
    } else {
        error_log("Erro ao preparar consulta: " . $conn->error);
        $_SESSION['login_error'] = "Erro na autenticação.";
    }
    header("Location: ../Login/?error=authentication");
    exit();
}
header("Location: ../Login/");
exit();
?>
