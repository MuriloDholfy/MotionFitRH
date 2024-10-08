<?php


// Iniciar a sessão
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    die("Usuário não está logado.");
}

// Obter o ID do usuário
$user_id = $_SESSION['user_id'];

// Credenciais do banco de dados
$servername = "50.116.86.123";
$username = "motionfi_contato";
$password = "68141096@Total"; // **ALTERE IMEDIATAMENTE** por segurança
$dbname = "motionfi_bdmotion";

// Cria a conexão com tratamento de erros
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    // Log do erro
    error_log("Falha na conexão: " . $conn->connect_error);
    
    // Redireciona para a página de login com uma mensagem genérica
    $_SESSION['usuario_logado'] = false;
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
$horario = $_POST['horario'];
$grauEmergencia = $_POST['grauEmergencia'];
$tipoContrato = $_POST['tipoContrato'];
$dataAberturaVaga = $_POST['dataAberturaVaga']; // Corrigido para corresponder ao nome do campo no HTML

// Verificar se o idUnidade é um nome e precisa ser inserido
if (is_numeric($unidade)) {
    // Se $unidade é um ID, verificar se existe
    $sql_check = "SELECT idUnidade FROM tbUnidade WHERE idUnidade = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("i", $unidade);
} else {
    // Se $unidade é um nome, verificar se existe
    $sql_check = "SELECT idUnidade FROM tbUnidade WHERE nomeUnidade = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $unidade);
}

if (!$stmt_check) {
    die("Erro na preparação da consulta: " . $conn->error);
}

$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    // Se o idUnidade existe, prosseguir com a inserção em tbVaga
    $stmt_check->bind_result($idUnidade);
    $stmt_check->fetch();
    $stmt_check->close();

    $sql = "INSERT INTO tbVaga (cargoVaga, tipoVaga, especialidadeVaga, horarioVaga, grauEmergencia, tipoContrato, idUnidade, diaSemana, dataAberturaVaga, idUsuario)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Erro na preparação da consulta: " . $conn->error);
    }
    // Corrigido para corresponder ao número de variáveis (10)
    $stmt->bind_param("ssssssssss", $cargo, $tipoVaga, $especialidade, $horario, $grauEmergencia, $tipoContrato, $idUnidade, $diaSemana, $dataAberturaVaga, $user_id);

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

        // Redirecionar para a página apropriada com base no tipo de usuário
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
    // Se o idUnidade não existir, insira uma nova unidade e use o ID gerado
    $sql_insert_unidade = "INSERT INTO tbUnidade (nomeUnidade) VALUES (?)";
    $stmt_insert_unidade = $conn->prepare($sql_insert_unidade);
    if (!$stmt_insert_unidade) {
        die("Erro na preparação da consulta: " . $conn->error);
    }
    $stmt_insert_unidade->bind_param("s", $unidade);

    if ($stmt_insert_unidade->execute()) {
        $idUnidade = $stmt_insert_unidade->insert_id; // Obter o ID gerado para a nova unidade
        $stmt_insert_unidade->close();

        // Agora insira os dados na tabela tbVaga usando o $idUnidade
        $sql = "INSERT INTO tbVaga (cargoVaga, tipoVaga, especialidadeVaga, horarioVaga, grauEmergencia, tipoContrato, idUnidade, diaSemana, dataAberturaVaga, idUsuario)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Erro na preparação da consulta: " . $conn->error);
        }
        // Corrigido para corresponder ao número de variáveis (10)
        $stmt->bind_param("ssssssssss", $cargo, $tipoVaga, $especialidade, $horario, $grauEmergencia, $tipoContrato, $idUnidade, $diaSemana, $dataAberturaVaga, $user_id);

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

            // Redirecionar para a página apropriada com base no tipo de usuário
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
