<?php
// submit_form.php

// Conectar ao banco de dados (exemplo usando MySQLi)
$servername = "50.116.86.123";
$username = "motionfi_contato
";
$password = "68141096@Total";

$dbname = "motionfi_bdmotion";
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Receber dados do formulário
$cargo = $_POST['cargo'];
$unidade = $_POST['unidade'];
$tipoVaga = $_POST['tipoVaga'];
$especialidade = $_POST['especialidade'];
$diaSemana = $_POST['diaSemana'];
$horario = $_POST['horario'];
$grauEmergencia = $_POST['grauEmergencia'];
$tipoContrato = $_POST['tipoContrato'];

// Inserir dados no banco de dados
$sql = "INSERT INTO chamados (cargo, unidade, tipoVaga, especialidade, diaSemana, horario, grauEmergencia, tipoContrato)
VALUES ('$cargo', '$unidade', '$tipoVaga', '$especialidade', '$diaSemana', '$horario', '$grauEmergencia', '$tipoContrato')";

if ($conn->query($sql) === TRUE) {
    header('Location: ../consultarChamado/index.php');
    exit();
} else {
    echo "Erro: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
