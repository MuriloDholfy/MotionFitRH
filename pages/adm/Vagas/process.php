<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "bdmotion");
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$action = $_GET['action'];

if ($action === 'create') {
    $cargo = $_POST['cargo'];
    $especialidade = $_POST['especialidade'];
    $tipo = $_POST['tipo'];
    $contrato = $_POST['contrato'];
    $grauEmergencia = $_POST['grauEmergencia'];
    $horario = $_POST['horario'];
    $diaSemana = $_POST['diaSemana'];
    $dataAbertura = $_POST['dataAbertura'];
    $idUnidade = $_POST['unidade'];
    $status = $_POST['status'];
    $processo = $_POST['processo'];

    $sql = "INSERT INTO tbVaga (cargoVaga, especialidadeVaga, tipoVaga, tipoContrato, grauEmergencia, horarioVaga, diaSemana, dataAberturaVaga, idUnidade, statusVaga, ProcessoVaga) 
            VALUES ('$cargo', '$especialidade', '$tipo', '$contrato', '$grauEmergencia', '$horario', '$diaSemana', '$dataAbertura', '$idUnidade', '$status', '$processo')";

    if ($conn->query($sql) === TRUE) {
        echo "Nova vaga criada com sucesso!";
    } else {
        echo "Erro ao criar vaga: " . $conn->error;
    }

} elseif ($action === 'update') {
    $id = $_POST['id'];
    // Atualize com os dados do POST
    $sql = "UPDATE tbVaga SET cargoVaga = '$_POST[cargo]', especialidadeVaga = '$_POST[especialidade]', tipoVaga = '$_POST[tipo]', tipoContrato = '$_POST[contrato]', grauEmergencia = '$_POST[grauEmergencia]', horarioVaga = '$_POST[horario]', diaSemana = '$_POST[diaSemana]', dataAberturaVaga = '$_POST[dataAbertura]', idUnidade = '$_POST[unidade]', statusVaga = '$_POST[status]', ProcessoVaga = '$_POST[processo]' WHERE idVaga = '$id'";

    if ($conn->query($sql) === TRUE) {
        echo "Vaga atualizada com sucesso!";
    } else {
        echo "Erro ao atualizar vaga: " . $conn->error;
    }
} elseif ($action === 'delete') {
    $id = $_POST['id'];
    $sql = "DELETE FROM tbVaga WHERE idVaga = '$id'";

    if ($conn->query($sql) === TRUE) {
        echo "Vaga deletada com sucesso!";
    } else {
        echo "Erro ao deletar vaga: " . $conn->error;
    }
}


$conn->close();
header('Location: ./index.php'); // Redireciona para a página de gestão de usuários
?>
