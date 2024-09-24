

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RH</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/homeStyle.css">
</head>

<body>
<?php
// Iniciar a sessão
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
    $sql = "SELECT tipoUsuario FROM tbusuario WHERE idUsuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($tipoUsuario);
    
    if ($stmt->fetch()) {
        $_SESSION['tipoUsuario'] = $tipoUsuario;
    } else {
        $_SESSION['tipoUsuario'] = 'gerenteRegional'; // Definir um valor padrão ou lidar com erro
    }
    $stmt->close();
} else {
    $_SESSION['tipoUsuario'] = 'gerenteRegional'; // Definir um valor padrão ou lidar com erro
}

// Verifica se o usuário é um "gerente regional"
if (!isset($_SESSION['tipoUsuario']) || $_SESSION['tipoUsuario'] !== 'gerenteRegional') {
    $_SESSION['gerenteRegional'] = false;
} else {
    $_SESSION['gerenteRegional'] = true;
}

// Obter o ID da URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Recuperar dados para o ID específico com JOIN
$sql = "SELECT v.*, u.nomeUnidade
        FROM tbVaga v
        JOIN tbUnidade u ON v.idUnidade = u.idUnidade
        WHERE v.idVaga = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Erro na consulta: " . $conn->error);
}

// Recuperar os últimos 5 pedidos
$sql = "SELECT * FROM tbVaga ORDER BY idVaga DESC LIMIT 4";
$result = $conn->query($sql);

// Contar total de pedidos
$sqlTotalPedidos = "SELECT COUNT(*) AS totalPedidos FROM tbVaga  WHERE statusVaga != 'NULL'";
$resultTotalPedidos = $conn->query($sqlTotalPedidos);
$totalPedidos = $resultTotalPedidos->fetch_assoc()['totalPedidos'];



// Contar pedidos pendentes
$sqlPendente = "SELECT COUNT(*) AS totalPendente FROM tbVaga WHERE statusVaga = 'Pendente'";
$resultPendente = $conn->query($sqlPendente);
$totalPendente = $resultPendente->fetch_assoc()['totalPendente'];

// Contar pedidos aprovados
$sqlAprovado = "SELECT COUNT(*) AS totalAprovado FROM tbVaga WHERE statusVaga = 'Aprovado'";
$resultAprovado = $conn->query($sqlAprovado);
$totalAprovado = $resultAprovado->fetch_assoc()['totalAprovado'];

// Contar pedidos rejeitados
$sqlRejeitado = "SELECT COUNT(*) AS totalRejeitado FROM tbVaga WHERE statusVaga = 'Rejeitado'";
$resultRejeitado = $conn->query($sqlRejeitado);
$totalRejeitado = $resultRejeitado->fetch_assoc()['totalRejeitado'];

// Fechar a conexão
$conn->close();
?>

    <div class="container">
        <?php include '../../components/navBar.php'; ?>

        <div class="row p-3">
            <?php include '../../components/sideBar.php'; ?>

            <div class="col-md-11">
                <div class="row">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card-projeto nav-link">
                                    <a href="../CriarChamado/index.php" class="nav-link" data-toggle="modal" data-target="#exampleModal">
                                        <div class="card-body">
                                            <p class="card-text">Criar <br>Vaga</p>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <?php if ($_SESSION['tipoUsuario'] === 'gerenteRegional') : ?>
                                    <div class="card-projeto nav-link">
                                    <a href="../Projetos/vagas.php" class="nav-link">
                                        <div class="card-body">
                                            <p class="card-text">Consultar Vagas</p>
                                        </div>
                                    </a>
                                </div>
                                    <?php else : ?>
                                        <div class="card-projeto nav-link">
                                            <a href="../consultarChamado/index.php" class="nav-link">
                                                <div class="card-body">
                                                    <p class="card-text">Consultar Vagas</p>
                                                </div>
                                            </a>
                                        </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="card-feed">
                                    <div class="card-body">
                                        <p class="card-text">Vagas Abertas</p>
                                    </div>
                                    <div class="card-footer">
                                        <small class="text"><?php echo $totalPedidos ?? '0'; ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <p class="card-text">Vagas Aprovadas</p>
                                        <div class="card-footer">
                                            <small class="text"><?php echo $totalAprovado ?? '0'; ?></small>
                                        </div>                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <p class="card-text">Vagas Rejeitadas</p>
                                        <div class="card-footer">
                                            <small class="text"><?php echo $totalRejeitado ?? '0'; ?></small>
                                        </div>                                       
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card-grande">
                            <div class="card-body">
                                <p class="card-text">Histórico de Vagas</p>
                            </div>
                            <?php
                            // Reabrir a conexão
                            $conn = new mysqli($servername, $username, $password, $dbname);

                            // Recuperar os últimos 5 pedidos
                            $sql = "SELECT * FROM tbVaga ORDER BY idVaga DESC LIMIT 4";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<div class="card-footer">';
                                    echo '  <small class="card-text">' . $row['cargoVaga'] . '</small>';
                                    echo '</div>';
                                }
                            } else {
                                echo '<p>Nenhum pedido encontrado.</p>';
                            }

                            // Fechar a conexão
                            $conn->close();
                            ?>
                        </div>
                        <div class="card mt-3">
                            <div class="card-body">
                                <p class="card-text">Vagas Pendentes</p>
                            </div>
                            <div class="card-footer">
                                <small class="text"><?php echo $totalPendente; ?></small>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade p-4" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-text m-2" id="exampleModalLabel">Pedido de Contratação</h2>
                    <button type="button" class="close " data-dismiss="modal" aria-label="Close" style="font-size: 1.5rem;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="process.php" method="POST">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="cargo">Cargo:</label>
                                <input type="text" class="form-control" id="cargo" name="cargo" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="unidade">Unidade:</label>
                                <select class="form-control" id="unidade" name="unidade" required>
                                    <option value="itanhaem">Itanhaém</option>
                                    <option value="savoy">Savoy</option>
                                    <option value="suzano">Suzano</option>
                                    <option value="belasArtes">Belas Artes </option>
                                    <option value="saoMiguel">São Miguel</option>
                                    <option value="jacui">Jacuí</option>
                                    <option value="jardimHelena">Jardim Helena</option>
                                    <option value="piresDoRio">Pires Do Rio</option>
                                    <option value="tatuape">Tatuapé</option>
                                    <option value="peruibe">Peruíbe</option>
                                    <option value="limoeiro">Limoeiro</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            
                            <div class="form-group col-md-6">
                                <label for="especialidade">Especialidade:</label>
                                <input type="text" class="form-control" id="especialidade" name="especialidade" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="tipoVaga">Tipo de Vaga:</label>
                                <select class="form-control" id="tipoVaga" name="tipoVaga" required>
                                    <option value="Reposicao">Reposição</option>
                                    <option value="NovaVaga">Nova Vaga</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                        <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                            <div class="form-group col-md-3">
                                <label for="horario">Horário:</label>
                                <input type="time" class="form-control" id="horario" name="horario" required>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="diaSemana">Dia da Semana:</label>
                                <select class="form-control" id="diaSemana" name="diaSemana" required>
                                    <option value="Segunda-feira">Segunda-feira</option>
                                    <option value="Terça-feira">Terça-feira</option>
                                    <option value="Quarta-feira">Quarta-feira</option>
                                    <option value="Quinta-feira">Quinta-feira</option>
                                    <option value="Sexta-feira">Sexta-feira</option>
                                    <option value="Sábado">Sábado</option>
                                    <option value="Domingo">Domingo</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="dataAberturaVaga">Abertura de Vaga:</label>
                                <input type="date" class="form-control" id="dataAberturaVaga" name="dataAberturaVaga" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="grauEmergencia">Grau de Emergência:</label>
                                <select class="form-control" id="grauEmergencia" name="grauEmergencia" required>
                                    <option value="baixa">Baixa</option>
                                    <option value="media">Média</option>
                                    <option value="alta">Alta</option>
                                    <option value="critica">Crítica</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="tipoContrato">Tipo de Contrato:</label>
                                <select class="form-control" id="tipoContrato" name="tipoContrato" required>
                                    <option value="Tercerizado">Tercerizado</option>
                                    <option value="Horista">Hórista</option>
                                    <option value="Estagio">Estágio</option>
                                    <option value="Mensal">Mensal</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row justify-content-end">
                            <button type="submit" class="btn btn-form">Enviar Pedido</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="acessoNegadoModal" tabindex="-1" role="dialog" aria-labelledby="acessoNegadoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="acessoNegadoModalLabel">Acesso Negado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Você não tem permissão para acessar esta área.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

</body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</html>
