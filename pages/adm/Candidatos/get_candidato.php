<?php
$servername = "50.116.86.120";
$username = "motionfi_sistemaRH";
$password = "@Motion123"; // **ALTERE IMEDIATAMENTE** por seguran���a
$dbname = "motionfi_bdmotion";

// Criar conex���o
$conn = new mysqli($servername, $username, $password, $dbname);

// Checar conex���o
if ($conn->connect_error) {
    die("Conex���o falhou: " . $conn->connect_error);
}
$idCandidato = $_GET['idCandidato'];
$stmt = $conn->prepare("SELECT * FROM tbcandidato WHERE idCandidato = ?");
$stmt->bind_param("i", $idCandidato);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode(['success' => true, 'idCandidato' => $row['idCandidato'], 'nomeCandidato' => $row['nomeCandidato']]);
} else {
    echo json_encode(['success' => false, 'message' => 'Candidato n���o encontrado!']);
}

$stmt->close();
$conn->close();
