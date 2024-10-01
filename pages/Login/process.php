<?php
// Habilita a exibição de erros (remova em produção)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inicia a sessão
session_start();

// Credenciais do banco de dados
$servername = "50.116.86.123";
$username = "motionfi_contato";
$password = "68141096@Total";
$dbname = "motionfi_bdmotion";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Verifica se os dados foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtém os dados
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Prepara a consulta
    $stmt = $conn->prepare("SELECT * FROM tbusuario WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result === false) {
        die("Erro na consulta SQL: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verifica a senha (assumindo que as senhas estão hashadas)
        if (password_verify($senha, $user['senha'])) {
            $_SESSION['user_id'] = $user['idUsuario'];
            $_SESSION['user_name'] = $user['nomeUsuario'];
            $_SESSION['user_role'] = $user['tipoUsuario'];
            $_SESSION['usuario_logado'] = true;

            header("Location: ../Home/?role=" . urlencode($user['tipoUsuario']));
            exit();
        } else {
            $_SESSION['usuario_logado'] = false;
            $_SESSION['login_error'] = "Email ou senha incorretos";
            header("Location: ../Login/");
            exit();
        }
    } else {
        $_SESSION['usuario_logado'] = false;
        $_SESSION['login_error'] = "Email ou senha incorretos";
        header("Location: ../Login/");
        exit();
    }
    $stmt->close();
} else {
    // Se o acesso não for via POST, redireciona para o formulário
    header("Location: ../Login/");
    exit();
}

$conn->close();
?>
