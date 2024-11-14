<?php
session_start();

// Verificar se o usuário está autenticado
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] === false) {
    header("Location: ../Login/?error=not_authenticated");
    exit();
}

// Obter o ID do usuário
$user_id = $_SESSION['user_id'];

// Incluir o arquivo de validação do usuário
include '../Validacao/valida_usuario.php';

// Credenciais do banco de dados
$servername = "50.116.86.120";
$username = "motionfi_sistemaRH";
$password = "@Motion123";
$dbname = "motionfi_bdmotion";

// Criar a conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    error_log("Falha na conexão: " . $conn->connect_error);
    $_SESSION['login_error'] = "Erro na conexão com o banco de dados.";
    header("Location: ../Login/?error=database_connection");
    exit();
}

// Coletar dados do formulário
$cargo = $_POST['cargo'];
$unidade = $_POST['unidade'];
$tipoVaga = $_POST['tipoVaga'];
$especialidade = $_POST['especialidade'];
$diaSemana = $_POST['diaSemana'];
$horarioInicioVaga = $_POST['horarioInicioVaga'];
$horarioFinalVaga = $_POST['horarioFinalVaga'];
$grauEmergencia = $_POST['grauEmergencia'];
$tipoContrato = $_POST['tipoContrato'];
$dataAberturaVaga = $_POST['dataAberturaVaga'];

// Verificar se a unidade é um nome ou um ID
if (is_numeric($unidade)) {
    $sql_check = "SELECT idUnidade FROM tbunidade WHERE idUnidade = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("i", $unidade);
} else {
    $sql_check = "SELECT idUnidade FROM tbunidade WHERE nomeUnidade = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $unidade);
}

if (!$stmt_check) {
    die("Erro na preparação da consulta: " . $conn->error);
}

$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    $stmt_check->bind_result($idUnidade);
    $stmt_check->fetch();
    $stmt_check->close();

    $sql = "INSERT INTO tbvaga (cargoVaga, tipoVaga, especialidadeVaga, horarioInicioVaga, horarioFinalVaga, grauEmergencia, tipoContrato, idUnidade, diaSemana, dataAberturaVaga, idUsuario)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Erro na preparação da consulta: " . $conn->error);
    }
    $stmt->bind_param("ssssssssssi", $cargo, $tipoVaga, $especialidade, $horarioInicioVaga, $horarioFinalVaga, $grauEmergencia, $tipoContrato, $idUnidade, $diaSemana, $dataAberturaVaga, $user_id);

    if ($stmt->execute()) {
        // Recuperar o tipo de usuário
        $sql_user = "SELECT tipoUsuario FROM tbusuario WHERE idUsuario = ?";
        $stmt_user = $conn->prepare($sql_user);
        if (!$stmt_user) {
            die("Erro na preparação da consulta: " . $conn->error);
        }
        $stmt_user->bind_param("i", $user_id);
        $stmt_user->execute();
        $stmt_user->bind_result($tipoUsuario);
        $stmt_user->fetch();
        $stmt_user->close();

        if ($tipoUsuario === 'gerente') {
            header('Location: ../Projetos/vagas.php');
        } else {
            header('Location: ../consultarChamado/index.php');
        }
        exit();
    } else {
        echo "Erro ao enviar pedido: " . $stmt->error;
    }
} else {
    $sql_insert_unidade = "INSERT INTO tbunidade (nomeUnidade) VALUES (?)";
    $stmt_insert_unidade = $conn->prepare($sql_insert_unidade);
    if (!$stmt_insert_unidade) {
        die("Erro na preparação da consulta: " . $conn->error);
    }
    $stmt_insert_unidade->bind_param("s", $unidade);

    if ($stmt_insert_unidade->execute()) {
        $idUnidade = $stmt_insert_unidade->insert_id;
        $stmt_insert_unidade->close();

        $sql = "INSERT INTO tbvaga (cargoVaga, tipoVaga, especialidadeVaga, horarioInicioVaga, horarioFinalVaga, grauEmergencia, tipoContrato, idUnidade, diaSemana, dataAberturaVaga, idUsuario)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Erro na preparação da consulta: " . $conn->error);
        }
        $stmt->bind_param("ssssssssssi", $cargo, $tipoVaga, $especialidade, $horarioInicioVaga, $horarioFinalVaga, $grauEmergencia, $tipoContrato, $idUnidade, $diaSemana, $dataAberturaVaga, $user_id);

        if ($stmt->execute()) {
            $sql_user = "SELECT tipoUsuario FROM tbusuario WHERE idUsuario = ?";
            $stmt_user = $conn->prepare($sql_user);
            if (!$stmt_user) {
                die("Erro na preparação da consulta: " . $conn->error);
            }
            $stmt_user->bind_param("i", $user_id);
            $stmt_user->execute();
            $stmt_user->bind_result($tipoUsuario);
            $stmt_user->fetch();
            $stmt_user->close();

            if ($tipoUsuario === 'gerente') {
                header('Location: ../Projetos/vagas.php');
            } else {
                header('Location: ../consultarChamado/index.php');
            }
            exit();
        } else {
            echo "Erro ao enviar pedido: " . $stmt->error;
        }
    } else {
        echo "Erro ao inserir unidade: " . $stmt_insert_unidade->error;
    }
}

$stmt_check->close();
$conn->close();
?>
