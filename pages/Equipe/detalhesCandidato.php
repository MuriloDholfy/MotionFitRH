
<?php
session_start();

// Conectar ao banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bdmotion";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Checar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verifica se o usuário está logado
if (isset($_SESSION['user_id'])) {
    // Recuperar o tipo de usuário do banco de dados
    $user_id = intval($_SESSION['user_id']);
    $sql = "SELECT tipoUsuario FROM tbUsuario WHERE idUsuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['tipoUsuario'] = $row['tipoUsuario'];
    } else {
        $_SESSION['access_denied'] = true;
    }
} else {
    $_SESSION['access_denied'] = true;
}

// Verifica se o usuário é um "gerente regional"
if (!isset($_SESSION['tipoUsuario']) || $_SESSION['tipoUsuario'] !== 'gerenteRegional') {
    $_SESSION['access_denied'] = true;
}

// Verifica se o acesso foi negado e exibe o modal
if (isset($_SESSION['access_denied']) && $_SESSION['access_denied'] === true) {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#acessoNegadoModal').modal('show');
        });
    </script>";
    unset($_SESSION['access_denied']);
}

// Obter o ID do candidato da URL
$idCandidato = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Verificar se o ID do candidato foi fornecido
if ($idCandidato > 0) {
    // Consulta SQL para recuperar os detalhes do candidato
    $sql = "SELECT c.idCandidato, c.nomeCandidato, c.emailCandidato, c.telefoneCandidato, 
            c.triagemCandidato, u.idUnidade, u.nomeUnidade, c.dataEntrevista, c.dataAprovacaoEntrevista,
            c.uniforme
            FROM tbCandidato c 
            JOIN tbUnidade u ON c.idUnidade = u.idUnidade
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
        
        <?php include '../../components/navBar.php'; ?>
        
        <div class="row p-3">
            <?php include '../../components/sideBar.php'; ?>
            
            <div class="col-md-11">
                <?php if (isset($candidato)): ?>
                    <div class="card-grande justify-content-center" style="text-align: left;">
                    <h1 class="card-text mb-5" style="color:#fff">Detalhes do Candidato</h1>
                    <div class="card-body col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="card-text"><strong>Nome:</strong> <?= htmlspecialchars($candidato['nomeCandidato']) ?></p>
                                <p class="card-text"><strong>E-mail:</strong> <?= htmlspecialchars($candidato['emailCandidato']) ?></p>
                            </div>
                            <div class="col-md-6">
                                <p class="card-text"><strong>Unidade:</strong> <?= htmlspecialchars($candidato['nomeUnidade']) ?></p>
                                <p class="card-text"><strong>Triagem:</strong> <?= htmlspecialchars($candidato['triagemCandidato']) ?></p>
                            </div>
                            <div class="col-md-6">
                                <p class="card-text"><strong>Telefone:</strong> <?= htmlspecialchars($candidato['telefoneCandidato']) ?></p>
                                <p class="card-text"><strong>Data Entrevista:</strong> <?= isset($candidato['dataEntrevista']) ? htmlspecialchars($candidato['dataEntrevista']) : 'Não informada' ?></p>
                            </div>
                            <!-- Atualizar o bloco existente onde são exibidos os detalhes do candidato -->
                            <div class="col-md-6">
                                <p class="card-text"><strong>Uniforme:</strong> <br><?= isset($candidato['uniforme']) ? htmlspecialchars($candidato['uniforme']) : 'Não informada' ?></p>
                                <p class="card-text"><strong>Data Aprovação Entrevista:</strong> <br><?= isset($candidato['dataAprovacaoEntrevista']) ? htmlspecialchars($candidato['dataAprovacaoEntrevista']) : 'Não informada' ?></p>
                            </div>

                        </div>
                        <div class="row mt-2">
                            <button type="button" class="btn-green btn btn col-3 mx-3" data-toggle="modal" data-target="#editModal">Editar</button>
                            <button type="button" class="btn-green btn btn col-3 " data-toggle="modal" data-target="#createInterviewModal">Entrevista</button>
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
                            <label for="nome">Nome</label>
                            <input type="text" id="nome" name="nome" class="form-control" value="<?= htmlspecialchars($candidato['nomeCandidato']) ?>" required>
                        </div>
                        <div class="form-group col-6">
                            <label for="email">E-mail</label>
                            <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($candidato['emailCandidato']) ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="telefone">Telefone</label>
                            <input type="text" id="telefone" name="telefone" class="form-control" value="<?= htmlspecialchars($candidato['telefoneCandidato']) ?>" required>
                        </div>
                        <div class="form-group col-6">
                            <label for="triagem">Triagem</label>
                            <input type="text" id="triagem" name="triagem" class="form-control" value="<?= htmlspecialchars($candidato['triagemCandidato']) ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="unidade">Unidade</label>
                            <input type="text" id="unidade" name="unidade" class="form-control" value="<?= htmlspecialchars($candidato['nomeUnidade']) ?>" required >
                        </div>
                        <div class="form-group col-6">
                            <label for="uniforme">Uniforme</label>
                            <input type="text" id="uniforme" name="uniforme" class="form-control" value="<?= htmlspecialchars($candidato['uniforme']) ?>" required >
                        </div> 
                    </div>
                    <div class="row">
                       <div class="form-group col-6">
                            <label for="dataEntrevista">Data da Entrevista</label>
                            <input type="date" id="dataEntrevista" name="dataEntrevista" class="form-control" value="<?= isset($candidato['dataEntrevista']) ? htmlspecialchars($candidato['dataEntrevista']) : '' ?>" required>
                        </div>
                        <div class="form-group col-6">
                            <label for="dataAprovacaoEntrevista">Data Aprovação Entrevista</label>
                            <input type="date" id="dataAprovacaoEntrevista" name="dataAprovacaoEntrevista" class="form-control" value="<?= isset($candidato['dataAprovacaoEntrevista']) ? htmlspecialchars($candidato['dataAprovacaoEntrevista']) : '' ?>" required>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn-green btn btn ml-3">Criar</button>
                    </div>                
                </form>
            </div>
        </div>
    </div>
</div>



<!-- Modal de Criação de Entrevista -->
<div class="modal fade" id="createInterviewModal" tabindex="-1" role="dialog" aria-labelledby="createInterviewModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createInterviewModalLabel">Criar Entrevista</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="processarEntrevista.php?id=<?= $idCandidato ?>" method="POST">
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="dataEntrevista">Data da Entrevista</label>
                            <input type="date" id="dataEntrevista" name="dataEntrevista" class="form-control" required>
                        </div>
                        <div class="form-group col-6">
                            <label for="dataAprovacaoEntrevista">Data Aprovação Entrevista</label>
                            <input type="date" id="dataAprovacaoEntrevista" name="dataAprovacaoEntrevista" class="form-control">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn-green btn btn ml-3">Criar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

  

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
