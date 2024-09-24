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

// Verifica se o usuário está logado e autorizado
if (!isset($_SESSION['user_id']) || $_SESSION['tipoUsuario'] !== 'gerenteRegional') {
    header('Location: acessoNegado.php'); // Redireciona para uma página de acesso negado
    exit();
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
    $caju = $_POST['caju'];
    $registro = $_POST['registro'];
    $dataRegistro = $_POST['dataRegistro'];
    $ponto = $_POST['ponto'];
    $contratoAssinado = $_POST['contratoAssinado'];
    $uniforme = $_POST['uniforme'];

// Atualize sua consulta SQL de UPDATE para incluir esses campos.

    // Atualizar dados do candidato
    $sql = "UPDATE tbCandidato SET 
            nomeCandidato = ?, 
            emailCandidato = ?, 
            telefoneCandidato = ?, 
            triagemCandidato = ?, 
            dataEntrevista = ?, 
            dataAprovacaoEntrevista = ? 
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

header('Location: detalhes.php?id=' . $idCandidato);
exit();
