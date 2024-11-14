<?php
// Inicia o buffer de saída
ob_start();

// Inicia a sessão (necessário se você estiver usando $_SESSION)
session_start();

// Configurações do banco de dados
$servername = "50.116.86.120";
$username = "motionfi_sistemaRH";
$password = "@Motion123"; // **ALTERE IMEDIATAMENTE** por segurança
$dbname = "motionfi_bdmotion";

// Criar conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Checar se a conexão foi bem-sucedida
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
    case 'Cancelar':
        $novoStatus = 'Cancelar';
        $_SESSION['mensagem'] = 'Vaga Cancelada com sucesso.';
        break;
    default:
        die("Ação inválida.");
}

// Atualizar o status da vaga
$sql = "UPDATE tbvaga SET statusVaga = '$novoStatus' WHERE idVaga = $id";
if ($conn->query($sql) !== TRUE) {
    $_SESSION['mensagem'] = "Erro ao atualizar vaga: " . $conn->error;
}

// Atualizar o processo da vaga
$sql = "UPDATE tbvaga SET ProcessoVaga = '$ProcessoVaga' WHERE idVaga = $id";
if ($conn->query($sql) !== TRUE) {
    $_SESSION['mensagem'] = "Erro ao atualizar vaga: " . $conn->error;
}

// Fechar a conexão com o banco de dados
$conn->close();

// Redirecionar de volta para a página de detalhes da vaga
header("Location: detalhesVaga.php?id=$id");
exit();

// Finaliza o buffer de saída (opcional, mas ajuda a limpar qualquer saída antes do redirecionamento)
ob_end_flush();
?>
