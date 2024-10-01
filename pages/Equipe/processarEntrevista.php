<?php
session_start();

// Conectar ao banco de dados
$servername = "50.116.86.123";
$username = "motionfi_contato
";
$password = "68141096@Total";

$dbname = "motionfi_bdmotion";
// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Checar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verifica se o usuário está logado e autorizado
if (!isset($_SESSION['user_id']) || $_SESSION['tipoUsuario'] !== 'gerenteRegional') {
    header('Location: acessoNegado.php'); // Redireciona para uma página de acesso negado
    exit();
}

// Verifica se o ID do candidato e os dados do formulário foram enviados
if (isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $idCandidato = intval($_GET['id']);
    $dataEntrevista = $_POST['dataEntrevista'];
    $dataAprovacaoEntrevista = $_POST['dataAprovacaoEntrevista'];

    // Atualizar a data da entrevista e data de aprovação da entrevista
    $sql = "UPDATE tbCandidato SET 
            dataEntrevista = ?, 
            dataAprovacaoEntrevista = ? 
            WHERE idCandidato = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $dataEntrevista, $dataAprovacaoEntrevista, $idCandidato);

    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "Entrevista criada com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Erro ao criar a entrevista.";
    }

    $stmt->close();
}

$conn->close();

header('Location: detalhesCandidato.php?id=' . $idCandidato);
exit();
