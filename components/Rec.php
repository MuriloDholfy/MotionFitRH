<?php
session_start();

// Conectar ao banco de dados
$servername = "50.116.86.123/pages/Login/";
$username = "motionfi_contato
";
$password = "68141096@Total";

$dbname = "motionfi_bdmotion";
// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Checar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Inicializar a variável tipoUsuario
$tipoUsuario = '';

// Verifica se o usuário está logado
if (isset($_SESSION['user_id'])) {
    // Recuperar o tipo de usuário do banco de dados
    $user_id = intval($_SESSION['user_id']);
    $sql = "SELECT tipoUsuario FROM tbUsuario WHERE idUsuario = $user_id";
    $result = $conn->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $tipoUsuario = $row['tipoUsuario'];
            $_SESSION['tipoUsuario'] = $tipoUsuario;
        } else {
            $_SESSION['access_denied'] = true;
        }
    } else {
        die("Erro na consulta: " . $conn->error);
    }
} else {
    $_SESSION['access_denied'] = true;
}

// Obter as vagas com status 'Aprovado' para o select
$sql_vagas = "SELECT idVaga, nomeVaga FROM tbVaga WHERE statusVaga = 'Aprovado'";
$result_vagas = $conn->query($sql_vagas);

// Fechar a conexão
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
    <link rel="stylesheet" href="../../css/workStyle.css">
</head>
<body>
    <!-- Conteúdo da página -->
    <div class="row">
        <div class="col-md-11">
            <div class="row">
                <div class="col-md-4">
                    <div class="card-projeto nav-link" type="button">
                        <a href="../Projetos/vagas.php" class="nav-link" title="Vagas" data-bs-toggle="tooltip">
                            <div class="card-body">
                                <p class="card-text"> Consultar Vagas</p>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-projeto nav-link">
                        <a href="../CriarChamado/index.php" class="nav-link" data-toggle="modal" data-target="#exampleModal">
                            <div class="card-body">
                                <p class="card-text">Criar Candidato</p>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-projeto nav-link" type="button">
                        <a href="../consultarCandidato/consultarCandidatos.php" class="nav-link" title="consultarCandidatos" data-bs-toggle="tooltip">
                            <div class="card-body">
                                <p class="card-text">Consultar Candidatos</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Cadastrar Candidato -->
    <div class="modal fade p-4" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-text m-2" id="exampleModalLabel">Cadastrar Candidato</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size: 1.5rem;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="../criarCandidato/process.php" method="POST">
                        <div class="row">
                            <div class="form-group col-6">
                                <label for="nome">Nome Completo</label>
                                <input type="text" class="form-control" id="nome" name="nome" required>
                            </div>
                            <div class="form-group col-6">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-6">
                                <label for="telefone">Telefone</label>
                                <input type="text" class="form-control" id="telefone" name="telefone" required>
                            </div>
                            <div class="form-group col-6">
                                <label for="unidade">Unidade:</label>
                                <select class="form-control" id="unidade" name="unidade" required>
                                    <option value="itanhaem">Itanhaém</option>
                                    <option value="savoy">Savoy</option>
                                    <option value="suzano">Suzano</option>
                                    <option value="belasArtes">Belas Artes</option>
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
                        <div class="row">
                            <div class="form-group col-6">
                                <label for="triagem">Triagem</label>
                                <select class="form-control" id="triagem" name="triagem">
                                    <option value="Sim">Sim</option>
                                    <option value="Não">Não</option>
                                </select>
                            </div>
                            <div class="form-group col-6">
                                <label for="entrevista">Entrevista</label>
                                <input type="date" class="form-control" id="entrevista" name="entrevista" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12">
                                <label for="vaga">Vaga:</label>
                                <select class="form-control" id="vaga" name="vaga" required>
                                    <option value="">Selecione a Vaga</option>
                                    <?php
                                    if ($result_vagas->num_rows > 0) {
                                        while ($row_vaga = $result_vagas->fetch_assoc()) {
                                            echo "<option value='{$row_vaga['idVaga']}'>{$row_vaga['idVaga']}</option>";
                                        }
                                    } else {
                                        echo "<option value='' disabled>Nenhuma vaga disponível</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-green btn btn ml-3">Enviar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Acesso Negado -->
    <div class="modal fade p-4" id="accessDeniedModal" tabindex="-1" aria-labelledby="accessDeniedModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <img src="../../img/icons/navbar/Criar.png" width="50" height="50">
                    <h2 class="modal-text m-2" id="accessDeniedModalLabel">Acesso Negado</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size: 1.5rem;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Você não tem permissão para acessar esta página.</p>
                </div>
                <div class="modal-footer">
                    <a href="./index.php" class="btn btn-primary">Confirmar</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Verifica o tipo de usuário e abre o modal se necessário
        document.addEventListener("DOMContentLoaded", function() {
            <?php if ($tipoUsuario !== 'recrutamento'): ?>
                $('#accessDeniedModal').modal('show');
                setTimeout(function() {
                    window.location.href = './'; // Redireciona para a página após exibir o modal
                }, 1000); // Atraso de 1 segundo para garantir que o usuário veja o modal
            <?php endif; ?>
        });
    </script>
</body>
</html>
