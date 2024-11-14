<?php

$servername = "50.116.86.120";
$username = "motionfi_sistemaRH";
$password = "@Motion123"; // **ALTERE IMEDIATAMENTE** por segurança
$dbname = "motionfi_bdmotion";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Checar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verifica se o ID do candidato e os dados do formulário foram enviados
if (isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] === 'POST') {  
    $idCandidato = intval($_GET['id']);  
    $nome = $_POST['nome'];  
    $email = $_POST['email'];  
    $telefone = $_POST['telefone'];  
    $triagem = $_POST['triagem'];  
    $dataEntrevista = $_POST['dataEntrevista'];  
    $dataAprovacaoEntrevista = $_POST['dataAprovacaoEntrevista'];  
    $uniforme = isset($_POST['uniforme']) ? $_POST['uniforme'] : null;  // Verifica se o uniforme foi enviado

    // Atualize sua consulta SQL de UPDATE para incluir esses campos.

    // Atualizar dados do candidato
    $sql = "UPDATE tbcandidato SET 
            nomeCandidato = ?, 
            emailCandidato = ?, 
            telefoneCandidato = ?, 
            triagemCandidato = ?, 
            dataEntrevista = ?, 
            dataAprovacaoEntrevista = ?, 
            uniforme = ? 
            WHERE idCandidato = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $nome, $email, $telefone, $triagem, $dataEntrevista, $dataAprovacaoEntrevista, $uniforme, $idCandidato);

    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "Dados atualizados com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Erro ao atualizar os dados.";
    }

    $stmt->close();
}

$conn->close();

header('Location: detalhesCandidato.php?id=' . $idCandidato);
exit();
