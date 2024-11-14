<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Definir as variáveis de conexão
$servername = "50.116.86.120";
$username = "motionfi_sistemaRH";
$password = "@Motion123"; // **ALTERE IMEDIATAMENTE** por segurança
$dbname = "motionfi_bdmotion";

// Verificar se o usuário está logado
if (isset($_SESSION['usuario_logado']) && $_SESSION['usuario_logado'] == 1) {
    // O usuário já está logado, então nada precisa ser feito
} else {
    // Conectar ao banco de dados
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar conexão
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Buscar informações do usuário com base no ID da sessão
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT idUsuario, tipoUsuario FROM tbusuario WHERE idUsuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->store_result();

        // Verificar se o usuário existe no banco
        if ($stmt->num_rows > 0) {
            // Usuário encontrado, obtendo o tipo
            $stmt->bind_result($idUsuario, $tipoUsuario);
            $stmt->fetch();

            // Armazenando o tipo de usuário na sessão
            $_SESSION['tipoUsuario'] = $tipoUsuario;
            $_SESSION['usuario_logado'] = true;  // Marca o usuário como logado
        } else {
            // Usuário não encontrado no banco de dados
            $_SESSION['usuario_logado'] = false;
            header("Location: ../Login/index.php?erro=usuario_invalido");  // Redireciona para o login com mensagem de erro
            exit();
        }

        $stmt->close();
    } else {
        // Se não houver 'user_id' na sessão, o usuário não está logado
        $_SESSION['usuario_logado'] = false;
        header("Location: ../Login/index.php?erro=nao_autorizado");  // Redireciona para o login
        exit();
    }
}

// Agora você pode criar uma nova conexão, já com as variáveis definidas
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar a nova conexão
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch vacancy data
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT v.*, u.nomeUnidade FROM tbvaga v JOIN tbunidade u ON v.idUnidade = u.idUnidade WHERE v.idVaga = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Get counts for different statuses in a single query
$sqlStatusCounts = "SELECT 
                        COUNT(*) AS totalPedidos, 
                        SUM(CASE WHEN statusVaga = 'Pendente' THEN 1 ELSE 0 END) AS totalPendente,
                        SUM(CASE WHEN statusVaga = 'Aprovado' THEN 1 ELSE 0 END) AS totalAprovado,
                        SUM(CASE WHEN statusVaga = 'Rejeitado' THEN 1 ELSE 0 END) AS totalRejeitado
                    FROM tbvaga WHERE statusVaga IS NOT NULL";
$statusCounts = $conn->query($sqlStatusCounts)->fetch_assoc();

$totalPedidos = $statusCounts['totalPedidos'] ?? 0;
$totalPendente = $statusCounts['totalPendente'] ?? 0;
$totalAprovado = $statusCounts['totalAprovado'] ?? 0;
$totalRejeitado = $statusCounts['totalRejeitado'] ?? 0;

// echo "<pre>Tipo de Usuário: " . $_SESSION['tipoUsuario'] . "</pre>";
// echo "<pre>Status de Login: " . ($_SESSION['usuario_logado'] == 1 ? "Logado" : "Deslogado") . "</pre>";

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RH</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/homeStyle.css">
    <link rel="icon" href="./img/icons/navbar/logo.png" type="image/x-icon">
</head>

<body>

<div class="container">
    <?php include '../../components/navbar.php'; ?>
    <div class="row p-3">
        <?php include '../../components/sideBar.php'; ?>
        <div class="col-md-11">
            <div class="row">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-6">
                        <?php if ($_SESSION['tipoUsuario'] == 'gerenteRegional'): ?>
                                <div class="card-projeto nav-link">
                                    <a href="../consultarChamado/index.php" class="nav-link">
                                        <div class="card-body">
                                            <p class="card-text">Aprovar Vagas</p>
                                        </div>
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="card-projeto nav-link">
                                    <a href="../CriarChamado/index.php" class="nav-link" data-toggle="modal" data-target="#exampleModal">
                                        <div class="card-body">
                                            <p class="card-text">Criar <br>Vaga</p>
                                        </div>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <div class="card-projeto nav-link">
                                <a href="../Projetos/vagas.php" class="nav-link">
                                    <div class="card-body">
                                        <p class="card-text">Consultar Vagas</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="card-feed">
                                <div class="card-body">
                                    <p class="card-text">Vagas Abertas</p>
                                </div>
                                <div class="card-footer">
                                    <small class="text"><?php echo $totalPedidos; ?></small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <p class="card-text">Vagas Aprovadas</p>
                                </div>
                                <div class="card-footer">
                                    <small class="text"><?php echo $totalAprovado; ?></small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <p class="card-text">Vagas Rejeitadas</p>
                                </div>
                                <div class="card-footer">
                                    <small class="text"><?php echo $totalRejeitado; ?></small>
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
                        $conn = new mysqli($servername, $username, $password, $dbname);
                        $sql = "SELECT * FROM tbvaga ORDER BY idVaga DESC LIMIT 4";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<div class="card-footer"><small class="card-text">' . $row['cargoVaga'] . '</small></div>';
                            }
                        } else {
                            echo '<p>Nenhum pedido encontrado.</p>';
                        }
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
                                    <option value="holding">Holding</option>
                                    <option value="tatuape">Tatuapé</option>
                                    <option value="peruibe">Peruíbe</option>
                                    <option value="limoeiro">Limoeiro</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            
                            <div class="form-group col-md-6">
                                <label for="especialidade">Especialidade:</label>
                                <input type="text" class="form-control" id="especialidade" name="especialidade">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="tipoVaga">Tipo de Vaga:</label>
                                <select class="form-control" id="tipoVaga" name="tipoVaga" required>
                                    <option value="Reposicao">Reposição</option>
                                    <option value="NovaVaga">Nova Vaga</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row ">
                            <div class="form-group col-md-3">
                                <input type="hidden" name="horarioInicioVaga" value="horarioInicioVaga">
                                <label for="horarioInicioVaga">Horário Inicio:</label>
                                <input type="time" class="form-control" id="horarioInicioVaga" name="horarioInicioVaga" required>
                            </div>
                            <div class="form-group col-md-3">
                                <input type="hidden" name="horarioFinalVaga" value="horarioFinalVaga">
                                <label for="horarioInicioVaga">Horário Final:</label>
                                <input type="time" class="form-control" id="horarioFinalVaga" name="horarioFinalVaga" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="diaSemana">Dia da Semana:</label>
                                <select class="form-control" id="diaSemana" name="diaSemana" required>
                                    <option value="Null"> </option>
                                    <option value="Segunda-feira">Segunda-feira</option>
                                    <option value="Terça-feira">Terça-feira</option>
                                    <option value="Quarta-feira">Quarta-feira</option>
                                    <option value="Quinta-feira">Quinta-feira</option>
                                    <option value="Sexta-feira">Sexta-feira</option>
                                    <option value="Sábado">Sábado</option>
                                    <option value="Domingo">Domingo</option>
                                    

                                </select>
                            </div>
                            <div class="form-group col-md-3">
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
