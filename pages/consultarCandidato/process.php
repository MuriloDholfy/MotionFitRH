<?php
// Iniciar a sessão
session_start();

// Verificar se o usuário está autenticado
if (!isset($_SESSION['AutenticaoUser']) || $_SESSION['AutenticaoUser'] != 'SIM') {
    header('Location: ../login.php?login=erro2');
    exit();
}

// Conectar ao banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bdmotion";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Obter dados da URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$acao = isset($_GET['acao']) ? $_GET['acao'] : '';

// Validar a ação
if (!in_array($acao, ['aprovar', 'rejeitar'])) {
    die("Ação inválida.");
}

// Atualizar o status do candidato
$status = $acao === 'aprovar' ? 'Aprovado' : 'Rejeitado';
$sql = "UPDATE tbCandidato SET triagemCandidato = ? WHERE idCandidato = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $status, $id);

if ($stmt->execute()) {
    echo "Candidato " . $status . " com sucesso!";
} else {
    echo "Erro ao atualizar candidato: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
