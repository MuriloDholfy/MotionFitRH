<?php
$servername = "50.116.86.123";
$username = "motionfi_contato
";
$password = "68141096@Total";

$dbname = "motionfi_bdmotion";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$response = [
    'status' => 'error',
    'message' => ''
];

$action = $_POST['action'] ?? '';

if ($action == 'create') {
    $nomeUnidade = $_POST['nomeUnidade'];
    $idRegiao = $_POST['idRegiao'];

    $stmt = $conn->prepare("INSERT INTO tbUnidade (nomeUnidade, idRegiao) VALUES (?, ?)");
    if ($stmt === false) {
        $response['message'] = "Erro na preparação da consulta: " . $conn->error;
    } else {
        $stmt->bind_param("si", $nomeUnidade, $idRegiao);
        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = "Unidade criada com sucesso!";
        } else {
            $response['message'] = "Erro ao executar a consulta: " . $stmt->error;
        }
        $stmt->close();
    }

} elseif ($action == 'update') {
    $idUnidade = $_POST['idUnidade'];
    $nomeUnidade = $_POST['nomeUnidade'];
    $idRegiao = $_POST['idRegiao'];

    $stmt = $conn->prepare("UPDATE tbUnidade SET nomeUnidade = ?, idRegiao = ? WHERE idUnidade = ?");
    if ($stmt === false) {
        $response['message'] = "Erro na preparação da consulta: " . $conn->error;
    } else {
        $stmt->bind_param("sii", $nomeUnidade, $idRegiao, $idUnidade);
        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = "Unidade atualizada com sucesso!";
        } else {
            $response['message'] = "Erro ao executar a consulta: " . $stmt->error;
        }
        $stmt->close();
    }

} elseif ($action == 'restore') {
    $idUnidade = $_POST['idUnidade'];
    $stmt = $conn->prepare("UPDATE tbUnidade SET status = 'ativo' WHERE idUnidade = ?");
    if ($stmt === false) {
        $response['message'] = "Erro na preparação da consulta: " . $conn->error;
    } else {
        $stmt->bind_param("i", $idUnidade);
        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = "Unidade restaurada com sucesso!";
        } else {
            $response['message'] = "Erro ao executar a consulta: " . $stmt->error;
        }
        $stmt->close();
    }

} elseif ($action == 'delete') {
    $idUnidade = $_POST['idUnidade'];
    $stmt = $conn->prepare("DELETE FROM tbUnidade WHERE idUnidade = ?");
    if ($stmt === false) {
        $response['message'] = "Erro na preparação da consulta: " . $conn->error;
    } else {
        $stmt->bind_param("i", $idUnidade);
        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = "Unidade deletada com sucesso!";
        } else {
            $response['message'] = "Erro ao executar a consulta: " . $stmt->error;
        }
        $stmt->close();
    }

} else {
    $response['message'] = "Ação inválida.";
}

$conn->close();
header('Location: ./index.php'); // Redireciona para a página de gestão de usuários
?>
