<?php
// Desativar exibição de erros em produção
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

// Inicia a sessão com configurações seguras
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => 'motionfitapi.com.br',
    'secure' => true, // Defina como 'true' em produção com HTTPS
    'httponly' => true,
    'samesite' => 'Strict'
]);
session_start();

// Credenciais do banco de dados
$servername = "50.116.86.123";
$username = "motionfi_contato";
$password = "68141096@Total"; // **ALTERE IMEDIATAMENTE** por segurança
$dbname = "motionfi_bdmotion";

// Cria a conexão com tratamento de erros
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    // Log do erro
    error_log("Falha na conexão: " . $conn->connect_error);
    // Redireciona para a página de login com uma mensagem genérica
    $_SESSION['usuario_logado'] = false;
    $_SESSION['login_error'] = "Erro na conexão com o banco de dados.";
    header("Location: ../Login/?error=database_connection");
    exit();
}

// Verifica se os dados foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Filtra e valida o email
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $senha = $_POST['senha']; // As senhas geralmente não são filtradas para permitir caracteres especiais
    
    if (!$email) {
        error_log("Email inválido fornecido: " . $_POST['email']);
        $_SESSION['login_error'] = "Email inválido.";
        header("Location: ../Login/?error=invalid_email");
        exit();
    }

    // Prepara a consulta
    $stmt = $conn->prepare("SELECT * FROM tbusuario WHERE email = ?");
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result === false) {
            // Log do erro
            error_log("Erro na consulta SQL: " . $conn->error);
            $_SESSION['login_error'] = "Erro na autenticação.";
            header("Location: ../Login/?error=authentication");   
            exit();
        }

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            error_log("Usuário encontrado: " . $user['email']);
            
            // Verifica a senha (assumindo que as senhas estão hashadas)
            if (password_verify($senha, $user['senha'])) {
                error_log("Senha verificada com sucesso para o usuário: " . $user['email']);
                $_SESSION['user_id'] = $user['idUsuario'];
                $_SESSION['user_name'] = $user['nomeUsuario'];
                $_SESSION['user_role'] = $user['tipoUsuario'];
                $_SESSION['usuario_logado'] = true;
                header("Location: ../Home/?role=" . urlencode($user['tipoUsuario']));
                exit();
            } else {
                error_log("Falha na verificação da senha para o usuário: " . $user['email']);
                $_SESSION['usuario_logado'] = false;
                $_SESSION['login_error'] = "Email ou senha incorretos.";
                header("Location: ../Login/?error=invalid_credentials");
                exit();
            }
        } else {
            error_log("Nenhum usuário encontrado com o email: " . $email);
            $_SESSION['usuario_logado'] = false;
            $_SESSION['login_error'] = "Email ou senha incorretos.";
            header("Location: ../Login/?error=invalid_credentials");
            exit();
        }
        $stmt->close();
    } else {
        // Log do erro ao preparar a consulta
        error_log("Erro ao preparar a consulta: " . $conn->error);
        $_SESSION['login_error'] = "Erro na autenticação.";
        header("Location: ../Login/?error=authentication");
        exit();
    }
} else {
    header("Location: ../Login/");
    exit();
}

$conn->close();
?>
