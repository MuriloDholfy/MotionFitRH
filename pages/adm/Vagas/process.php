<?php
$conn = new mysqli('50.116.86.120', 'motionfi_sistemaRH', '@Motion123', 'motionfi_bdmotion');

if ($conn->connect_error) {
    die("Conex茫o falhou: " . $conn->connect_error);
}

$action = isset($_GET['action']) ? $_GET['action'] : null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    switch ($action) {
        case 'create':
            // Criar nova vaga
            $cargo = $_POST['cargo'];
            $especialidade = $_POST['especialidade'];
            $tipo = $_POST['tipo'];
            $contrato = $_POST['contrato'];
            $status = $_POST['status'];

            $stmt = $conn->prepare("INSERT INTO tbvaga (cargoVaga, especialidadeVaga, tipoVaga, tipoContrato, statusVaga) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $cargo, $especialidade, $tipo, $contrato, $status);
            if ($stmt->execute()) {
                header("Location: index.php?message=Vaga criada com sucesso");
            } else {
                header("Location: index.php?error=Erro ao criar a vaga");
            }
            exit;

        case 'update':
            // Atualizar vaga
            $idVaga = $_POST['idVaga'];
            $nome = $_POST['nome'];
            $cargo = $_POST['cargo'];
            $especialidade = $_POST['especialidade'];
            $dataAbertura = $_POST['dataAbertura'];
            $diaSemana = $_POST['diaSemana'];
            $grauEmergencia = $_POST['grauEmergencia'];
            $horario = $_POST['horario'];
            $tipoContrato = $_POST['tipoContrato'];
            $tipoVaga = $_POST['tipoVaga'];
            $status = $_POST['status'];
            $processo = $_POST['processo'];
            $aprovador = $_POST['aprovador'];

            $stmt = $conn->prepare("UPDATE tbvaga SET nomeVaga = ?, cargoVaga = ?, especialidadeVaga = ?, dataAberturaVaga = ?, diaSemana = ?, grauEmergencia = ?, horarioVaga = ?, tipoContrato = ?, tipoVaga = ?, statusVaga = ?, processoVaga = ?, aprovadorVaga = ? WHERE idVaga = ?");
            $stmt->bind_param("ssssssssssssi", $nome, $cargo, $especialidade, $dataAbertura, $diaSemana, $grauEmergencia, $horario, $tipoContrato, $tipoVaga, $status, $processo, $aprovador, $idVaga);
            $stmt->execute();
            header("Location: index.php?message=Vaga atualizada com sucesso");
            exit;

        case 'restore':
            $id = $_POST['id'];

            // L贸gica para restaurar a vaga
            $stmt = $conn->prepare("UPDATE tbvaga SET statusVaga='aberto' WHERE idVaga=?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                header("Location: index.php?message=Vaga restaurada com sucesso");
            } else {
                header("Location: index.php?error=Erro ao restaurar a vaga");
            }
            exit;

        case 'delete':
            $idVaga = $_POST['idVaga'];
            $stmt = $conn->prepare("DELETE FROM tbvaga WHERE idVaga = ?");
            $stmt->bind_param("i", $idVaga);
            if ($stmt->execute()) {
                header("Location: index.php?message=Vaga deletada com sucesso");
            } else {
                header("Location: index.php?error=Erro ao deletar a vaga");
            }
            exit;

        default:
            header("Location: index.php?error=Acao invalida");
            exit;
    }
}

$conn->close();
?>
