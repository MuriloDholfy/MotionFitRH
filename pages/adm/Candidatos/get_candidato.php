<?php
include 'db_connection.php'; // Arquivo para conexão com o banco de dados

$idCandidato = $_GET['idCandidato'];
$stmt = $conn->prepare("SELECT * FROM tbCandidato WHERE idCandidato = ?");
$stmt->bind_param("i", $idCandidato);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode(['success' => true, 'idCandidato' => $row['idCandidato'], 'nomeCandidato' => $row['nomeCandidato']]);
} else {
    echo json_encode(['success' => false, 'message' => 'Candidato não encontrado!']);
}

$stmt->close();
$conn->close();
