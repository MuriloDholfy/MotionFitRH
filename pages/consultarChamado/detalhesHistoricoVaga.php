
<?php
session_start();

// Conectar ao banco de dados
$servername = "<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Usuário</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../css/admStyle.css">
    <style>
        /* Adiciona estilo aos ícones de ação */
        .crud-icons {
            font-size: 16px;
            margin-right: 8px;
            cursor: pointer;
            color: #333;
        }
        .create {
            font-size: 36px;
            color: green;
        }
        .update {
            color: orange;
        }
        .restore {
            color: blue;
        }
        .delete {
            color: red;
        }
        .crud-icons:hover {
            opacity: 0.7;
        }
        .table td, .table th{
            padding:5px
        }
    </style>
</head>
<body>
    <!-- Menu lateral com seta para voltar ao login -->
    <?php include '../../../components/admSidebar.php'; ?>

    <div class="content container">
        <h1>Lista de Usuários</h1>
        <!-- Botão de Criar Novo Usuário -->
        <div class="mb-3">
            <span class='crud-icons create' title='Criar Novo' data-toggle="modal" data-target="#modalCreate">&#43;</span>
        </div>
        <!-- Tabela de Usuários -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Senha</th>
                        <th>Tipo</th>
                        <th>Data Criação</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Conectando ao banco de dados MySQL
                    $servername = "50.116.86.123";
                    $username = "motionfi_contato
";
                    $password = "68141096@Total";

                    $dbname = "motionfi_bdmotion";
                    // Criando a conexão
                    $conn = new mysqli($servername, $username, $password, $dbname);

                    // Checando a conexão
                    if ($conn->connect_error) {
                        die("Conexão falhou: " . $conn->connect_error);
                    }

                    // Definindo o número de itens por página
                    $itensPorPagina = 10;

                    // Pegando o número da página atual (se não for definido, é a página 1)
                    $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

                    // Calculando o offset (a partir de qual registro a consulta vai buscar)
                    $offset = ($pagina - 1) * $itensPorPagina;

                    // Consulta SQL para contar o número total de usuários
                    $sqlTotal = "SELECT COUNT(*) AS total FROM tbUsuario";
                    $resultTotal = $conn->query($sqlTotal);
                    if (!$resultTotal) {
                        die("Erro na consulta SQL: " . $conn->error);
                    }
                    $rowTotal = $resultTotal->fetch_assoc();
                    $totalUsuarios = $rowTotal['total'];

                    // Calculando o número total de páginas
                    $totalPaginas = ceil($totalUsuarios / $itensPorPagina);

                    // Consulta SQL para buscar os usuários com limite e offset
                    $sql = "SELECT idUsuario, nome, email, senha, tipoUsuario, data_criacao FROM tbUsuario LIMIT $itensPorPagina OFFSET $offset";
                    $result = $conn->query($sql);

                    // Verificando se a consulta falhou
                    if (!$result) {
                        die("Erro na consulta SQL: " . $conn->error);
                    }

                    // Verificando se há resultados
                    if ($result->num_rows > 0) {
                        // Saída dos dados de cada linha
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["idUsuario"] . "</td>";
                            echo "<td>" . htmlspecialchars($row["nome"], ENT_QUOTES, 'UTF-8') . "</td>";
                            echo "<td>" . htmlspecialchars($row["email"], ENT_QUOTES, 'UTF-8') . "</td>";
                            echo "<td>" . htmlspecialchars($row["senha"], ENT_QUOTES, 'UTF-8') . "</td>";
                            echo "<td>" . htmlspecialchars($row["tipoUsuario"], ENT_QUOTES, 'UTF-8') . "</td>";
                            echo "<td>" . htmlspecialchars($row["data_criacao"], ENT_QUOTES, 'UTF-8') . "</td>";
                            echo "<td>
                                    <span class='crud-icons update' title='Atualizar' data-toggle='modal' data-target='#modalUpdate' data-id='" . $row["idUsuario"] . "'>&#9998;</span>
                                    <span class='crud-icons restore' title='Restaurar' data-toggle='modal' data-target='#modalRestore' data-id='" . $row["idUsuario"] . "'>&#8634;</span>
                                    <span class='crud-icons delete' title='Deletar' data-toggle='modal' data-target='#modalDelete' data-id='" . $row["idUsuario"] . "'>&#128465;</span>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center'>Nenhum usuário encontrado.</td></tr>";
                    }

                    // Fechando a conexão
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Paginação -->
        <div class="pagination">
            <?php
            if ($pagina > 1) {
                echo "<a href='?pagina=" . ($pagina - 1) . "'>&laquo; Anterior</a>";
            }

            for ($i = 1; $i <= $totalPaginas; $i++) {
                if ($i == $pagina) {
                    echo "<strong>$i</strong>";
                } else {
                    echo "<a href='?pagina=$i'>$i</a>";
                }
            }

            if ($pagina < $totalPaginas) {
                echo "<a href='?pagina=" . ($pagina + 1) . "'>Próximo &raquo;</a>";
            }
            ?>
        </div>
    </div>

    <!-- Modal Criar -->
    <div class="modal fade" id="modalCreate" tabindex="-1" aria-labelledby="modalCreateLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formCreate" method="POST" action="process.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCreateLabel">Criar Novo Usuário</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="create">
                        <div class="form-group">
                            <label for="nomeCreate">Nome</label>
                            <input type="text" class="form-control" id="nomeCreate" name="nome" required>
                        </div>
                        <div class="form-group">
                            <label for="emailCreate">Email</label>
                            <input type="email" class="form-control" id="emailCreate" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="senhaCreate">Senha</label>
                            <input type="password" class="form-control" id="senhaCreate" name="senha" required>
                        </div>
                        <div class="form-group">
                            <label for="tipoUsuarioCreate">Tipo</label>
                            <select class="form-control" id="tipoUsuarioCreate" name="tipoUsuario" required>
                                <option value="gerenteRegional">Gerente Regional</option>
                                <option value="recrutamento">Recrutamento</option>
                                <option value="selecao">Seleção</option>
                                <option value="dpPessoal">DP Pessoal</option>
                                <option value="adm">Admin</option>
                                <!-- Adicione outros tipos se necessário -->
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Criar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Atualizar -->
    <div class="modal fade" id="modalUpdate" tabindex="-1" aria-labelledby="modalUpdateLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formUpdate" method="POST" action="process.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalUpdateLabel">Atualizar Usuário</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" id="idUpdate" name="idUsuario">
                        <div class="form-group">
                            <label for="nomeUpdate">Nome</label>
                            <input type="text" class="form-control" id="nomeUpdate" name="nome" required>
                        </div>
                        <div class="form-group">
                            <label for="emailUpdate">Email</label>
                            <input type="email" class="form-control" id="emailUpdate" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="senhaUpdate">Senha</label>
                            <input type="password" class="form-control" id="senhaUpdate" name="senha" required>
                        </div>
                        <div class="form-group">
                            <label for="tipoUsuarioUpdate">Tipo</label>
                            <select class="form-control" id="tipoUsuarioUpdate" name="tipoUsuario" required>
                                <option value="gerenteRegional">Gerente Regional</option>
                                <option value="recrutamento">Recrutamento</option>
                                <option value="selecao">Seleção</option>
                                <option value="dpPessoal">DP Pessoal</option>
                                <option value="adm">Admin</option>
                                <!-- Adicione outros tipos se necessário -->
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Atualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Restaurar -->
    <div class="modal fade" id="modalRestore" tabindex="-1" aria-labelledby="modalRestoreLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formRestore" method="POST" action="process.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalRestoreLabel">Restaurar Usuário</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="restore">
                        <input type="hidden" id="idRestore" name="idUsuario">
                        <p>Tem certeza de que deseja restaurar este usuário?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Restaurar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Deletar -->
    <div class="modal fade" id="modalDelete" tabindex="-1" aria-labelledby="modalDeleteLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formDelete" method="POST" action="process.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDeleteLabel">Deletar Usuário</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" id="idDelete" name="idUsuario">
                        <p>Tem certeza de que deseja deletar este usuário?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Deletar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
  <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Atualiza os campos do modal de atualizar com base no id do usuário
        $('#modalUpdate').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var modal = $(this);

            $.ajax({
                url: 'get_user_data.php',
                method: 'POST',
                data: { idUsuario: id },
                dataType: 'json',
                success: function (data) {
                    modal.find('#idUpdate').val(data.idUsuario);
                    modal.find('#nomeUpdate').val(data.nome);
                    modal.find('#emailUpdate').val(data.email);
                    modal.find('#senhaUpdate').val(data.senha);
                    modal.find('#tipoUsuarioUpdate').val(data.tipoUsuario);
                },
                error: function () {
                    alert('Erro ao carregar dados do usuário.');
                }
            });
        });

        // Define o id do usuário para os modais de restaurar e deletar
        $('#modalRestore').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var modal = $(this);
            modal.find('#idRestore').val(id);
        });

        $('#modalDelete').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var modal = $(this);
            modal.find('#idDelete').val(id);
        });
    </script>
</body>
</html>
";
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
                        echo '    <a href="processHistorico.php?id=' . htmlspecialchars($row['idVaga']) . '&acao=aprovar" class="btn btn-success">Aprovar</a>';
                        echo '    <a href="processHistorico.php?id=' . htmlspecialchars($row['idVaga']) . '&acao=rejeitar" class="btn btn-danger">Rejeitar</a>';
                        echo '    <a href="processHistorico.php?id=' . htmlspecialchars($row['idVaga']) . '&acao=cancelar" class="btn btn-danger">Cancelar</a>';
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
                    <a href="./index.php" class="btn btn-primary">OK</a>
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
                <?php unset($_SESSION['mensagem']); // Limpa a mensagem da sessão após exibir ?>
            <?php endif; ?>
        });
    </script>
</body>
</html>
