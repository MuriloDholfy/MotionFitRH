<?php
header('Content-Type: application/json');

// Conectar ao banco de dados
$servername = "50.116.86.120";
$username = "motionfi_sistemaRH";
$password = "@Motion123"; // **ALTERE IMEDIATAMENTE** por segurança
$dbname = "motionfi_bdmotion";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Filtrar por unidade se fornecido
$unidade = isset($_GET['unidade']) ? $conn->real_escape_string($_GET['unidade']) : '';

// Contar total de chamados e candidatos
$sqlChamados = "SELECT COUNT(*) AS totalChamados FROM tbvaga WHERE statusVaga != 'inativo'";
$sqlCandidatos = "SELECT COUNT(*) AS totalCandidatos FROM tbcandidato ";

// Adicionar filtro de unidade, se necessário
if ($unidade != '') {
    $sqlChamados .= " AND idUnidade = (SELECT idUnidade FROM tbunidade WHERE nomeUnidade = '$unidade')";
    $sqlCandidatos .= " AND idUnidade = (SELECT idUnidade FROM tbunidade WHERE nomeUnidade = '$unidade')";
}

$resultChamados = $conn->query($sqlChamados);
$resultCandidatos = $conn->query($sqlCandidatos);

$totalChamados = $resultChamados->fetch_assoc()['totalChamados'];
$totalCandidatos = $resultCandidatos->fetch_assoc()['totalCandidatos'];

// Contar pedidos por status
$sqlStatus = "SELECT statusVaga, COUNT(*) AS countStatus FROM tbvaga WHERE statusVaga != 'inativo'";
if ($unidade != '') {
    $sqlStatus .= " AND idUnidade = (SELECT idUnidade FROM tbunidade WHERE nomeUnidade = '$unidade')";
}
$sqlStatus .= " GROUP BY statusVaga";

$resultStatus = $conn->query($sqlStatus);

$statusData = [
    'Pendente' => 0,
    'Aprovado' => 0,
    'Rejeitado' => 0
];

while ($row = $resultStatus->fetch_assoc()) {
    $statusData[$row['statusVaga']] = (int) $row['countStatus'];
}

// Obter o número de chamados por unidade
$sqlChamadosPorUnidade = "SELECT u.nomeUnidade, COUNT(*) AS totalChamadosPorUnidade 
                           FROM tbvaga v
                           JOIN tbunidade u ON v.idUnidade = u.idUnidade
                           WHERE v.statusVaga != 'inativo'";

if ($unidade != '') {
    $sqlChamadosPorUnidade .= " AND u.nomeUnidade = '$unidade'";
}

$sqlChamadosPorUnidade .= " GROUP BY u.nomeUnidade";

$resultChamadosPorUnidade = $conn->query($sqlChamadosPorUnidade);

$unidades = [];
$chamadosPorUnidade = [];

while ($row = $resultChamadosPorUnidade->fetch_assoc()) {
    $unidades[] = $row['nomeUnidade'];
    $chamadosPorUnidade[] = (int) $row['totalChamadosPorUnidade'];
}

// Obter o número de candidatos por unidade
$sqlCandidatosPorUnidade = "SELECT u.nomeUnidade, COUNT(*) AS totalCandidatosPorUnidade 
                             FROM tbcandidato c
                             JOIN tbunidade u ON c.idUnidade = u.idUnidade";

if ($unidade != '') {
    $sqlCandidatosPorUnidade .= " WHERE u.nomeUnidade = '$unidade'";
}

$sqlCandidatosPorUnidade .= " GROUP BY u.nomeUnidade";

$resultCandidatosPorUnidade = $conn->query($sqlCandidatosPorUnidade);

$candidatosPorUnidade = [];

while ($row = $resultCandidatosPorUnidade->fetch_assoc()) {
    $candidatosPorUnidade[] = (int) $row['totalCandidatosPorUnidade'];
}

// Ajustar o número de candidatos por unidade para alinhar com as unidades, se necessário
while (count($candidatosPorUnidade) < count($unidades)) {
    $candidatosPorUnidade[] = 0; // Adicionar zeros para unidades sem candidatos
}

$response = [
    'totalChamados' => $totalChamados,
    'totalCandidatos' => $totalCandidatos,
    'totalPendente' => $statusData['Pendente'],
    'totalAprovado' => $statusData['Aprovado'],
    'totalRejeitado' => $statusData['Rejeitado'],
    'unidades' => $unidades,
    'chamadosPorUnidade' => $chamadosPorUnidade,
    'candidatosPorUnidade' => $candidatosPorUnidade
];

echo json_encode($response);

$conn->close();
?>
