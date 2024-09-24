<?php
$conn = new mysqli('localhost', 'root', '', 'bdmotion');

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

if (isset($_POST['acao'])) {
    switch ($_POST['acao']) {
        case 'criar':
            $nomeCandidato = $_POST['nomeCandidato'];
            $emailCandidato = $_POST['emailCandidato'];
            $telefoneCandidato = $_POST['telefoneCandidato'];

            $sql = "INSERT INTO tbCandidato (nomeCandidato, emailCandidato, telefoneCandidato) VALUES ('$nomeCandidato', '$emailCandidato', '$telefoneCandidato')";
            if ($conn->query($sql) === TRUE) {
                echo "Candidato criado com sucesso.";
            } else {
                echo "Erro: " . $conn->error;
            }
            break;

        case 'atualizar':
            $idCandidato = $_POST['idCandidato'];
            $nomeCandidato = $_POST['nomeCandidato'];
            $emailCandidato = $_POST['emailCandidato'];
            $telefoneCandidato = $_POST['telefoneCandidato'];

            $sql = "UPDATE tbCandidato SET nomeCandidato='$nomeCandidato', emailCandidato='$emailCandidato', telefoneCandidato='$telefoneCandidato' WHERE idCandidato='$idCandidato'";
            if ($conn->query($sql) === TRUE) {
                echo "Candidato atualizado com sucesso.";
            } else {
                echo "Erro: " . $conn->error;
            }
            break;

        case 'deletar':
            $idCandidato = $_POST['idCandidato'];
            $sql = "DELETE FROM tbCandidato WHERE idCandidato='$idCandidato'";
            if ($conn->query($sql) === TRUE) {
                echo "Candidato deletado com sucesso.";
            } else {
                echo "Erro: " . $conn->error;
            }
            break;

        case 'buscar':
            $idCandidato = $_POST['idCandidato'];
            $sql = "SELECT * FROM tbCandidato WHERE idCandidato='$idCandidato'";
            $result = $conn->query($sql);
            $candidato = $result->fetch_assoc();
            echo json_encode($candidato);
            break;
    }
}

$conn->close();
