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

// Verifica se o ID do candidato e os dados do formulário foram enviados
if (isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $idCandidato = intval($_GET['id']);
    $caju = $_POST['caju'] ?? '';
    $registro = $_POST['registro'] ?? '';
    $dataRegistro = $_POST['dataRegistro'] ?? null;
    $ponto = $_POST['ponto'] ?? '';
    $contratoAssinado = $_POST['contratoAssinado'] ?? '';

    // Atualizar dados do candidato
    $sql = "UPDATE tbCandidato SET 
            caju = ?, 
            registro = ?, 
            dataRegistro = ?, 
            ponto = ?, 
            contratoAssinado = ? 
            WHERE idCandidato = ?";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Erro na preparação da consulta: " . $conn->error);
    }

    $stmt->bind_param(
        "sssssi", 
        $caju, 
        $registro, 
        $dataRegistro, 
        $ponto, 
        $contratoAssinado, 
        $idCandidato
    );

    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "Dados atualizados com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Erro ao atualizar os dados.";
    }

    $stmt->close();
}

$conn->close();

header('Location: detalhes.php?id=' . $idCandidato);
exit();
?>
