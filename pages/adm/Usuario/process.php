<?php

// Configuração do banco de dados
$servername = "50.116.86.120";
$username = "motionfi_sistemaRH";
$password = "@Motion123"; // **ALTERE IMEDIATAMENTE** por segurança
$dbname = "motionfi_bdmotion";

// Criando a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Checando a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Função para limpar e validar dados
function sanitize_input($data) {
    return htmlspecialchars(trim($data));
}

// Manipulação das ações com base no parâmetro "action"
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = isset($_POST['action']) ? sanitize_input($_POST['action']) : '';

    switch ($action) {
        case 'create':
            // Criar Novo Usuário
            $nome = sanitize_input($_POST['nome']);
            $email = sanitize_input($_POST['email']);
            $senha = sanitize_input($_POST['senha']);
            $tipoUsuario = sanitize_input($_POST['tipoUsuario']);

            $sqlCreate = "INSERT INTO tdusuario (nome, email, senha, tipoUsuario, data_criacao) VALUES (?, ?, ?, ?, NOW())";
            $stmt = $conn->prepare($sqlCreate);

            if ($stmt === false) {
                die("Erro ao preparar a consulta: " . $conn->error);
            }

            $stmt->bind_param("ssss", $nome, $email, $senha, $tipoUsuario);

            if ($stmt->execute()) {
                echo "Usuário criado com sucesso.";
            } else {
                echo "Erro ao criar usuário: " . $stmt->error;
            }
            $stmt->close();
            break;

        case 'update':
            // Atualizar Usuário
            $idUsuario = isset($_POST['idUsuario']) ? (int)$_POST['idUsuario'] : 0;
            $nome = sanitize_input($_POST['nome']);
            $email = sanitize_input($_POST['email']);
            $senha = sanitize_input($_POST['senha']);
            $tipoUsuario = sanitize_input($_POST['tipoUsuario']);

            if ($idUsuario <= 0) {
                echo "ID do usuário inválido. ID recebido: $idUsuario";
                exit;
            }

            $sqlUpdate = "UPDATE tdusuario SET nome = ?, email = ?, senha = ?, tipoUsuario = ? WHERE idUsuario = ?";
            $stmt = $conn->prepare($sqlUpdate);

            if ($stmt === false) {
                die("Erro ao preparar a consulta: " . $conn->error);
            }

            $stmt->bind_param("ssssi", $nome, $email, $senha, $tipoUsuario, $idUsuario);

            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    echo "Usuário atualizado com sucesso.";
                } else {
                    echo "Nenhuma alteração detectada ou usuário não encontrado.";
                }
            } else {
                echo "Erro ao atualizar usuário: " . $stmt->error;
            }
            $stmt->close();
            break;

        case 'delete':
            // Deletar Usuário
            $idUsuario = isset($_POST['idUsuario']) ? (int)$_POST['idUsuario'] : 0;

            if ($idUsuario <= 0) {
                echo "ID do usuário inválido. ID recebido: $idUsuario";
                exit;
            }

            $sqlDelete = "DELETE FROM tdusuario WHERE idUsuario = ?";
            $stmt = $conn->prepare($sqlDelete);

            if ($stmt === false) {
                die("Erro ao preparar a consulta: " . $conn->error);
            }

            $stmt->bind_param("i", $idUsuario);

            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    echo "Usuário deletado com sucesso.";
                } else {
                    echo "Usuário não encontrado.";
                }
            } else {
                echo "Erro ao deletar usuário: " . $stmt->error;
            }
            $stmt->close();
            break;

        case 'restore':
            // Restaurar Usuário
            $idUsuario = isset($_POST['idUsuario']) ? (int)$_POST['idUsuario'] : 0;

            if ($idUsuario <= 0) {
                echo "ID do usuário inválido. ID recebido: $idUsuario";
                exit;
            }

            // Supondo que você tenha uma coluna `deleted` ou similar para marcar o usuário como excluído
            $sqlRestore = "UPDATE tdusuario SET deleted = 0 WHERE idUsuario = ?";
            $stmt = $conn->prepare($sqlRestore);

            if ($stmt === false) {
                die("Erro ao preparar a consulta: " . $conn->error);
            }

            $stmt->bind_param("i", $idUsuario);

            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    echo "Usuário restaurado com sucesso.";
                } else {
                    echo "Usuário não encontrado ou já restaurado.";
                }
            } else {
                echo "Erro ao restaurar usuário: " . $stmt->error;
            }
            $stmt->close();
            break;

        default:
            echo "Ação não reconhecida.";
            break;
    }
}

// Fechando a conexão
$conn->close();
?>
