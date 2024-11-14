<?php
session_start();

// Verifica se o usuário está logado
if (isset($_SESSION['usuario_logado'])) {
    // Conexão com o banco de dados
    $servername = "50.116.86.120";
    $username = "motionfi_sistemaRH";
    $password = "@Motion123"; // **ALTERE IMEDIATAMENTE** por segurança
    $dbname = "motionfi_bdmotion";

    // Cria a conexão
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verifica a conexão
    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }

    // Obtém o ID do usuário logado
    $usuario_logado = $_SESSION['usuario_logado'];

    // Atualiza o campo usuario_logado para 0 no banco de dados
    $sql = "UPDATE tbusuario SET usuario_logado = 0 WHERE idUsuario = ?";

    // Prepara a consulta SQL para evitar SQL Injection
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Erro na preparação da consulta: " . $conn->error);
    }

    // Vincula o parâmetro e executa a consulta
    $stmt->bind_param("i", $usuario_logado);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
    } else {
    }

    // Fecha a consulta e a conexão
    $stmt->close();
    $conn->close();
}

// Limpa todas as variáveis de sessão
session_unset();

// Define o valor de usuario_logado como 0
$_SESSION['usuario_logado'] = 0;

// Destroi a sessão
session_destroy();

// Redireciona para a página de login
header("Location: ../pages/Login/index.php");

exit();
?>
