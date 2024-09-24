<?php
session_start();

// Conectar ao banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bdmotion";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Checar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Obter o ID e a ação da URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$acao = isset($_GET['acao']) ? $_GET['acao'] : '';

// Definir o novo status com base na ação
switch ($acao) {
    case 'aprovar':
        $novoStatus = 'Aprovado';
        $ProcessoVaga = 'Pedido Aprovado';
        $_SESSION['mensagem'] = 'Vaga aprovada com sucesso.';
        break;
    case 'rejeitar':
        $novoStatus = 'Rejeitado';
        $_SESSION['mensagem'] = 'Vaga rejeitada com sucesso.';
        break;
    case 'cancelar': // Adicionado o caso para Cancelar
        $novoStatus = 'Cancelado';
        $ProcessoVaga = 'Pedido Cancelado'; // Pode ajustar o texto conforme necessário
        $_SESSION['mensagem'] = 'Vaga cancelada com sucesso.';
        break;
    default:
        die("Ação inválida.");
}

// Atualizar o status da vaga
$sql = "UPDATE tbVaga SET statusVaga = '$novoStatus', ProcessoVaga = '$ProcessoVaga' WHERE idVaga = $id";
if ($conn->query($sql) !== TRUE) {
    $_SESSION['mensagem'] = "Erro ao atualizar vaga: " . $conn->error;
}

// Fechar a conexão
$conn->close();

// Redirecionar de volta para a página de detalhes da vaga
header("Location: detalhesHistoricoVaga.php?id=$id");
exit();
?>
