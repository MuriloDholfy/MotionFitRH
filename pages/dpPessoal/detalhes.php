
<?php
session_start();

// Conectar ao banco de dados
$servername = "50.116.86.120";
$username = "motionfi_sistemaRH";
$password = "@Motion123"; // **ALTERE IMEDIATAMENTE** por segurança
$dbname = "motionfi_bdmotion";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Checar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Obter o ID do candidato da URL
$idCandidato = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Verificar se o ID do candidato foi fornecido
if ($idCandidato > 0) {
    // Consulta SQL para recuperar os detalhes do candidato
    $sql = "SELECT c.idCandidato, c.nomeCandidato, c.emailCandidato, c.telefoneCandidato, 
        c.triagemCandidato, u.idUnidade, u.nomeUnidade, c.dataEntrevista, c.dataAprovacaoEntrevista,
        c.caju, c.registro, c.dataRegistro, c.ponto, c.contratoAssinado
        FROM tbcandidato c 
        JOIN tbunidade u ON c.idUnidade = u.idUnidade
        WHERE c.idCandidato = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idCandidato);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $candidato = $result->fetch_assoc();
    } else {
        $_SESSION['mensagem'] = "Candidato não encontrado.";
    }
} else {
    $_SESSION['mensagem'] = "ID do candidato fornecido.";
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Candidato</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/projetoStyle.css">
    <link rel="stylesheet" href="../../css/detalhesStyle.css">
    <link rel="stylesheet" href="../../css/consultarChamadoStyle.css">
</head>
<body>
    
    <div class="container">
        
        <?php include '../../components/navbar.php'; ?>
        
        <div class="row p-3">
            <?php include '../../components/sideBar.php'; ?>
            
            <div class="col-md-11">
                <?php if (isset($candidato)): ?>
                    <div class="card-grande justify-content-center" style="text-align: left;">
                    <h1 class="card-text mb-5" style="color:#fff">Detalhes do Candidato </h1>
                    <div class="card-body col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="card-text"><strong>Caju:</strong> <?= $candidato['caju'] == 'Sim' ? 'Sim' : 'Não' ?></p>
                                <p class="card-text"><strong>Registro:</strong> <?= $candidato['registro'] == 'Sim' ? 'Sim' : 'Não' ?></p>
                                <p class="card-text"><strong>Data de Registro:</strong> <?= isset($candidato['dataRegistro']) ? htmlspecialchars($candidato['dataRegistro']) : 'Não informada' ?></p>
                            </div>
                           
                            <div class="col-md-6">
                                <p class="card-text"><strong>Ponto:</strong> <?= $candidato['ponto'] == 'Sim' ? 'Sim' : 'Não' ?></p>
                                <p class="card-text"><strong>Contrato Assinado:</strong> <?= $candidato['contratoAssinado'] == 'Sim' ? 'Sim' : 'Não' ?></p>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <button type="button" class="btn-green btn btn col-3 mx-3" data-toggle="modal" data-target="#editModal">Editar</button>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <p><?= isset($_SESSION['mensagem']) ? $_SESSION['mensagem'] : 'Erro desconhecido.' ?></p>
            <?php endif; ?>
            </div>
        </div>
    </div>

<!-- Modal de Edição -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Editar Candidato</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="process.php?id=<?= $idCandidato ?>" method="POST">
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="caju">Caju</label>
                            <select id="caju" name="caju" class="form-control">
                                <option value="Sim" <?= $candidato['caju'] == 'Sim' ? 'selected' : '' ?>>Sim</option>
                                <option value="Não" <?= $candidato['caju'] == 'Não' ? 'selected' : '' ?>>Não</option>
                            </select>
                        </div>
                        <div class="form-group col-6">
                            <label for="registro">Registro</label>
                            <select id="registro" name="registro" class="form-control">
                                <option value="Sim" <?= $candidato['registro'] == 'Sim' ? 'selected' : '' ?>>Sim</option>
                                <option value="Não" <?= $candidato['registro'] == 'Não' ? 'selected' : '' ?>>Não</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="dataRegistro">Data de Registro</label>
                            <input type="date" id="dataRegistro" name="dataRegistro" class="form-control" value="<?= htmlspecialchars($candidato['dataRegistro']) ?>">
                        </div>
                        <div class="form-group col-6">
                            <label for="ponto">Ponto</label>
                            <select id="ponto" name="ponto" class="form-control">
                                <option value="Sim" <?= $candidato['ponto'] == 'Sim' ? 'selected' : '' ?>>Sim</option>
                                <option value="Não" <?= $candidato['ponto'] == 'Não' ? 'selected' : '' ?>>Não</option>
                            </select>
                        </div>
                        <div class="form-group col-6">
                            <label for="contratoAssinado">Contrato Assinado</label>
                            <select id="contratoAssinado" name="contratoAssinado" class="form-control">
                                <option value="Sim" <?= $candidato['contratoAssinado'] == 'Sim' ? 'selected' : '' ?>>Sim</option>
                                <option value="Não" <?= $candidato['contratoAssinado'] == 'Não' ? 'selected' : '' ?>>Não</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

 <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
