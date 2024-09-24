
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
    $sql = "SELECT tipoUsuario FROM tbUsuario WHERE idUsuario = $user_id";
    $result = $conn->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $_SESSION['tipoUsuario'] = $row['tipoUsuario'];
        } else {
            $_SESSION['access_denied'] = true;
        }
    } else {
        die("Erro na consulta: " . $conn->error);
    }
} else {
    $_SESSION['access_denied'] = true;
}

// Verifica se o usuário é um "gerente regional"
if (!isset($_SESSION['tipoUsuario']) || $_SESSION['tipoUsuario'] !== 'gerenteRegional') {
    $_SESSION['access_denied'] = true;
}

// Obter o ID da URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Recuperar dados para o ID específico com JOIN
$sql = "SELECT v.*, u.nomeUnidade
        FROM tbVaga v
        JOIN tbUnidade u ON v.idUnidade = u.idUnidade
        WHERE v.idVaga = $id";
$result = $conn->query($sql);

// Verifica se a consulta foi bem-sucedida
if (!$result) {
    die("Erro na consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes da Vaga</title>
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
                <?php
                if ($result->num_rows > 0) {
                    // Exibir dados em um card
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="card-grande justify-content-center" style="text-align: left;">';
                        echo '<h1 class="card-text mb-5" style="color:#fff">Detalhes da Vaga</h1>';
                        echo '  <div class="card-body col-md-12">';
                        echo '    <div class="row">';
                        echo '      <div class="col-md-6">';
                        echo '        <p class="card-text"><strong>Cargo:</strong> ' . htmlspecialchars($row['cargoVaga']) . '</p>';
                        echo '        <p class="card-text"><strong>Unidade:</strong> ' . htmlspecialchars($row['nomeUnidade']) . '</p>';
                        echo '      </div>';
                        echo '      <div class="col-md-6">';
                        echo '        <p class="card-text"><strong>Tipo de Vaga:</strong> ' . htmlspecialchars($row['tipoVaga']) . '</p>';
                        echo '        <p class="card-text"><strong>Especialidade:</strong> ' . htmlspecialchars($row['especialidadeVaga']) . '</p>';
                        echo '      </div>';
                        echo '    </div>';
                        echo '    <div class="row">';
                        echo '      <div class="col-md-6">';
                        echo '        <p class="card-text"><strong>Horário:</strong> ' . htmlspecialchars($row['horarioVaga']) . '</p>';
                        echo '        <p class="card-text"><strong>Grau de Emergência:</strong> ' . htmlspecialchars($row['grauEmergencia']) . '</p>';
                        echo '      </div>';
                        echo '      <div class="col-md-6">';
                        echo '        <p class="card-text"><strong>Tipo de Contrato:</strong> ' . htmlspecialchars($row['tipoContrato']) . '</p>';
                        if (!empty($row['aprovadorVaga'])) {
                            echo '        <p class="card-text"><strong>Aprovador:</strong> ' . htmlspecialchars($row['aprovadorVaga']) . '</p>';
                        }
                        echo '      </div>';
                        echo '    </div>';
                        if (!empty($row['revisorVaga'])) {
                            echo '    <p class="card-text"><strong>Revisor:</strong> ' . htmlspecialchars($row['revisorVaga']) . '</p>';
                        }
                        echo '  </div>';
                        echo '  <div style="margin-left: 20px;">';
                        echo '    <a href="process.php?id=' . htmlspecialchars($row['idVaga']) . '&acao=aprovar" class="btn btn-green">Aprovar</a>';
                        echo '    <a href="process.php?id=' . htmlspecialchars($row['idVaga']) . '&acao=rejeitar" class="btn btn-red">Rejeitar</a>';
                        echo '  </div>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="alert alert-warning center-message" role="alert">Nenhum candidato encontrado.</div>';
                }

                // Fechar a conexão
                $conn->close();
                ?>
            </div>
        </div>
    </div>

    <!-- Modal de Acesso Negado -->
    <div class="modal fade" id="acessoNegadoModal" tabindex="-1" role="dialog" aria-labelledby="acessoNegadoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="color: red;">
                    <h5 class="modal-title" id="acessoNegadoModalLabel">Acesso Negado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Você não tem permissão para acessar esta página.</p>
                </div>
                <div class="modal-footer">
                    <a href="./index.php" class="btn-red btn ">Confirmar</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de Ação de Vaga -->
    <div class="modal fade" id="vagaAcaoModal" tabindex="-1" role="dialog" aria-labelledby="vagaAcaoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="vagaAcaoModalLabel">Ação de Vaga</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><?php echo isset($_SESSION['mensagem']) ? $_SESSION['mensagem'] : ''; ?></p>
                </div>
                <div class="modal-footer">
                    <a href="./index.php" class="btn btn-green ">OK</a>
                </div>
            </div>
        </div>
    </div>



    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            <?php if (isset($_SESSION['mensagem'])): ?>
                $('#vagaAcaoModal').modal('show');
                <?php unset($_SESSION['mensagem']); ?>
            <?php endif; ?>
        });
    </script>
</body>
</html>
