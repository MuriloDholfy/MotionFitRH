<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bdmotion";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$action = $_POST['action'];
$idUsuario = $_POST['idUsuario'];
$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';
$tipoUsuario = $_POST['tipoUsuario'] ?? '';

switch ($action) {
    case 'create':
        $sql = "INSERT INTO tbUsuario (nome, email, senha, tipoUsuario) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $nome, $email, $senha, $tipoUsuario);
        $stmt->execute();
        $stmt->close();
        break;

    case 'update':
        $sql = "UPDATE tbUsuario SET nome=?, email=?, senha=?, tipoUsuario=? WHERE idUsuario=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $nome, $email, $senha, $tipoUsuario, $idUsuario);
        $stmt->execute();
        $stmt->close();
        break;

    case 'restore':
        // Aqui você deve adicionar a lógica para restaurar o usuário se estiver utilizando uma tabela de usuários inativos
        break;

    case 'delete':
        $sql = "DELETE FROM tbUsuario WHERE idUsuario=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $stmt->close();
        break;

    default:
        echo "Ação inválida.";
        break;
}

$conn->close();
header('Location: ./index.php'); // Redireciona para a página de gestão de usuários
?>
