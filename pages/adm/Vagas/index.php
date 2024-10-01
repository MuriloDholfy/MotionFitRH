<?php
// Conexão com o banco de dados
$conn = new mysqli("<!DOCTYPE html>
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
", "motionfi_contato
", "", "bdmotion");
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Paginação
$itensPorPagina = 10;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina - 1) * $itensPorPagina;

// Contando total de vagas
$resultTotal = $conn->query("SELECT COUNT(*) AS total FROM tbVaga");
$totalVagas = $resultTotal->fetch_assoc()['total'];
$totalPaginas = ceil($totalVagas / $itensPorPagina);

// Buscando vagas
$result = $conn->query("SELECT * FROM tbVaga LIMIT $itensPorPagina OFFSET $offset");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Vagas</title>
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
            padding:2px
        }

    </style>
</head>
<body>

    <?php include '../../../components/admSidebar.php'; ?>

    <div class="content">
        <h1>Lista de Vagas</h1>
        <div style="overflow-x: auto;">
            <span class='crud-icons create' title='Criar Novo' onclick='openModal("create")'>&#43;</span>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cargo</th>
                        <th>Especialidade</th>
                        <th>Tipo</th>
                        <th>Contrato</th>
                        <th>Grau de Emergência</th>
                        <th>Horário</th>
                        <th>Dia da Semana</th>
                        <th>Abertura</th>
                        <th>ID Unidade</th>
                        <th>Status</th>
                        <th>Processo</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>{$row['idVaga']}</td>";
                            echo "<td>{$row['cargoVaga']}</td>";
                            echo "<td>{$row['especialidadeVaga']}</td>";
                            echo "<td>{$row['tipoVaga']}</td>";
                            echo "<td>{$row['tipoContrato']}</td>";
                            echo "<td>{$row['grauEmergencia']}</td>";
                            echo "<td>{$row['horarioVaga']}</td>";
                            echo "<td>{$row['diaSemana']}</td>";
                            echo "<td>{$row['dataAberturaVaga']}</td>";
                            echo "<td>{$row['idUnidade']}</td>";
                            echo "<td>{$row['statusVaga']}</td>";
                            echo "<td>{$row['ProcessoVaga']}</td>";
                            echo "<td>
                                    <span class='crud-icons update' title='Atualizar' onclick='openModal(\"update\", {$row['idVaga']}, \"{$row['cargoVaga']}\", \"{$row['especialidadeVaga']}\", \"{$row['tipoVaga']}\", \"{$row['tipoContrato']}\", \"{$row['grauEmergencia']}\", \"{$row['horarioVaga']}\", \"{$row['diaSemana']}\", \"{$row['dataAberturaVaga']}\", {$row['idUnidade']}, \"{$row['statusVaga']}\", \"{$row['ProcessoVaga']}\")'>&#9998;</span>
                                    <span class='crud-icons restore' title='Restaurar' onclick='openModal(\"restore\", {$row['idVaga']})'>&#8634;</span>
                                    <span class='crud-icons delete' title='Deletar' onclick='openModal(\"delete\", {$row['idVaga']})'>&#128465;</span>
                                </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='13'>Nenhuma vaga encontrada.</td></tr>";
                    }

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
                echo $i == $pagina ? "<strong>$i</strong>" : "<a href='?pagina=$i'>$i</a>";
            }

            if ($pagina < $totalPaginas) {
                echo "<a href='?pagina=" . ($pagina + 1) . "'>Próximo &raquo;</a>";
            }
            ?>
        </div>
    </div>

    <!-- Modal Criar Vaga -->
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Criar Nova Vaga</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="createForm" method="POST" action="process.php?action=create">
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="createCargo">Cargo</label>
                                <input type="text" class="form-control" id="createCargo" name="cargo" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="createEspecialidade">Especialidade</label>
                                <input type="text" class="form-control" id="createEspecialidade" name="especialidade" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="createTipo">Tipo</label>
                                <input type="text" class="form-control" id="createTipo" name="tipo" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="createContrato">Tipo de Contrato</label>
                                <input type="text" class="form-control" id="createContrato" name="contrato" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="createGrauEmergencia">Grau de Emergência</label>
                                <input type="text" class="form-control" id="createGrauEmergencia" name="grauEmergencia" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="createHorario">Horário</label>
                                <input type="text" class="form-control" id="createHorario" name="horario" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="createDiaSemana">Dia da Semana</label>
                                <input type="text" class="form-control" id="createDiaSemana" name="diaSemana" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="createDataAbertura">Data de Abertura</label>
                                <input type="date" class="form-control" id="createDataAbertura" name="dataAbertura" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="createUnidade">ID Unidade</label>
                                <input type="number" class="form-control" id="createUnidade" name="unidade" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="createStatus">Status</label>
                                <select class="form-control" id="createStatus" name="status" required>
                                    <option value="aberto">Aberto</option>
                                    <option value="fechado">Fechado</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="createProcesso">Processo</label>
                                <input type="text" class="form-control" id="createProcesso" name="processo" required>
                            </div>
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

    <!-- Modal Atualizar Vaga -->
    <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Atualizar Vaga</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="updateForm" method="POST" action="process.php?action=update">
                    <div class="modal-body">
                        <input type="hidden" id="updateId" name="id">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="updateCargo">Cargo</label>
                                <input type="text" class="form-control" id="updateCargo" name="cargo" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="updateEspecialidade">Especialidade</label>
                                <input type="text" class="form-control" id="updateEspecialidade" name="especialidade" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="updateTipo">Tipo</label>
                                <input type="text" class="form-control" id="updateTipo" name="tipo" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="updateContrato">Tipo de Contrato</label>
                                <input type="text" class="form-control" id="updateContrato" name="contrato" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="updateGrauEmergencia">Grau de Emergência</label>
                                <input type="text" class="form-control" id="updateGrauEmergencia" name="grauEmergencia" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="updateHorario">Horário</label>
                                <input type="text" class="form-control" id="updateHorario" name="horario" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="updateDiaSemana">Dia da Semana</label>
                                <input type="text" class="form-control" id="updateDiaSemana" name="diaSemana" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="updateDataAbertura">Data de Abertura</label>
                                <input type="date" class="form-control" id="updateDataAbertura" name="dataAbertura" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="updateUnidade">ID Unidade</label>
                                <input type="number" class="form-control" id="updateUnidade" name="unidade" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="updateStatus">Status</label>
                                <select class="form-control" id="updateStatus" name="status" required>
                                    <option value="aberto">Aberto</option>
                                    <option value="fechado">Fechado</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="updateProcesso">Processo</label>
                                <input type="text" class="form-control" id="updateProcesso" name="processo" required>
                            </div>
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
    <!-- Modal Restaurar Vaga -->
    <div class="modal fade" id="restoreModal" tabindex="-1" role="dialog" aria-labelledby="restoreModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="restoreModalLabel">Restaurar Vaga</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="restoreForm" method="POST" action="process.php?action=restore">
                    <div class="modal-body">
                        <p>Tem certeza que deseja restaurar esta vaga?</p>
                        <input type="hidden" id="restoreId" name="id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning">Restaurar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal Deletar Vaga -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Deletar Vaga</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="deleteForm" method="POST" action="process.php?action=delete">
                        <div class="modal-body">
                            <p>Tem certeza que deseja deletar esta vaga?</p>
                            <input type="hidden" id="deleteId" name="id">
                        </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Deletar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function openModal(action, id = null, cargo = '', especialidade = '', tipo = '', contrato = '', grauEmergencia = '', horario = '', diaSemana = '', dataAbertura = '', unidade = '', status = '', processo = '') {
        if (action === 'create') {
            $('#createModal').modal('show');
        } else if (action === 'update') {
            $('#updateId').val(id);
            $('#updateCargo').val(cargo);
            $('#updateEspecialidade').val(especialidade);
            $('#updateTipo').val(tipo);
            $('#updateContrato').val(contrato);
            $('#updateGrauEmergencia').val(grauEmergencia);
            $('#updateHorario').val(horario);
            $('#updateDiaSemana').val(diaSemana);
            $('#updateDataAbertura').val(dataAbertura);
            $('#updateUnidade').val(unidade);
            $('#updateStatus').val(status);
            $('#updateProcesso').val(processo);
            $('#updateModal').modal('show');
        } else if (action === 'restore') {
            $('#restoreId').val(id);
            $('#restoreModal').modal('show');
        } else if (action === 'delete') {
            $('#deleteId').val(id);
            $('#deleteModal').modal('show');
        }
    }
</script>


</body>
</html>
