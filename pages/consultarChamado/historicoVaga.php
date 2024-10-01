
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RH - Vagas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/projetoStyle.css">
    <link rel="stylesheet" href="../../css/consultarChamadoStyle.css">
    <style>
       /* Estilo para a paginação fixa */
       .pagination-container {
            position: fixed;
            width: 100%;
            background-color: transparent;
            padding: 10px 0;
        }

        .pagination-container .pagination {
            justify-content: center;
        }

        .table {
            width: 100%;
            background: transparent;
            border-radius: 10px;
            overflow: hidden;
            color: #fff;
            border: 1px solid #dee2e6; /* Borda externa da tabela */
            border-collapse: collapse; /* Unir as bordas das células com a borda da tabela */
        }

        .table th,
        .table td {
            padding: .75rem;
            vertical-align: top;
            border: 1px solid #dee2e6; /* Borda das linhas e células */
            font-size: 13px
        }

        thead {
            background-color: #93cc4c;
        }

        tbody {
            background-color: transparent;
        }
    </style>
</head>

<body>

    <div class="container">
        <?php include '../../components/navBar.php'; ?>
        <div class="row p-3">
            <?php include '../../components/sideBar.php'; ?>
            <div class="col-md-11">
                <div class="row col-md-11 justify-content-between">
                    <form method="GET" class="form-inline">
                        <!-- Filtro de Unidade -->
                        <div class="form-group mr-3">
                            <select class="form-control" id="unidade" name="unidade">
                                <option value="">Todas as Unidades</option>
                                <!-- Adicione as opções de unidades conforme necessário -->
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
                                <!-- Adicione mais opções conforme necessário -->
                            </select>
                        </div>

                        <!-- Botão de Submissão -->
                        <button type="submit" class="btn-green btn  ml-3">Filtrar</button>
                    </form>
                </div>

                <div class="row p-2">
                    <?php
                    // Habilitar exibição de erros para diagnóstico
                    error_reporting(E_ALL);
                    ini_set('display_errors', 1);

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

                    // Definindo o número de registros por página
                    $registros_por_pagina = 5;

                    // Descobrir o número da página atual
                    $pagina_atual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

                    // Calcular o offset
                    $offset = ($pagina_atual - 1) * $registros_por_pagina;

                    // Filtros
                    $unidade = isset($_GET['unidade']) ? $conn->real_escape_string($_GET['unidade']) : '';
                    $dataAberturaVaga = isset($_GET['dataAberturaVaga']) ? $conn->real_escape_string($_GET['dataAberturaVaga']) : '';

                    // Montando a query com filtros
                    $sql = "SELECT v.*, u.nomeUnidade
                            FROM tbVaga v
                            JOIN tbUnidade u ON v.idUnidade = u.idUnidade
                            WHERE v.statusVaga IS NOT NULL AND v.statusVaga != ''";

                    if ($unidade != '') {
                        $sql .= " AND u.nomeUnidade = '$unidade'";
                    }

                    if ($dataAberturaVaga != '') {
                        $sql .= " AND DATE(v.dataAberturaVaga) = '$dataAberturaVaga'";
                    }

                    $sql .= " AND v.statusVaga != 'inativo'";

                    $sql .= " LIMIT $registros_por_pagina OFFSET $offset";

                    // Recuperar o total de registros para calcular o número total de páginas
                    $sql_total = str_replace("SELECT v.*, u.nomeUnidade", "SELECT COUNT(*) as total", $sql);
                    $result_total = $conn->query($sql_total);
                    if ($result_total) {
                        $row_total = $result_total->fetch_assoc();
                        $total_registros = $row_total['total'];
                    } else {
                        $total_registros = 0;
                    }
                    
                    
                    // Calcular o número total de páginas
                    $total_paginas = ceil($total_registros / $registros_por_pagina);

                    // Recuperar os dados para a página atual
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        echo '<table class="table table-bordered">';
                        echo '<thead>';
                        echo '  <tr>';
                        echo '    <th>Cargo</th>';
                        echo '    <th>Unidade</th>';
                        echo '    <th>Tipo de Vaga</th>';
                        echo '    <th>Especialidade</th>';
                        echo '    <th>Horário</th>';
                        echo '    <th>Dia da Semana</th>';
                        echo '    <th>Grau de Emergência</th>';
                        echo '    <th>Tipo de Contrato</th>';
                        echo '    <th>Status</th>';  // Nova coluna adicionada
                        echo '    <th>Responsavel</th>';  // Nova coluna adicionada
                        echo '    <th> </th>';  // Nova coluna adicionada
                        echo '  </tr>';
                        echo '</thead>';
                        echo '<tbody>';

                        while ($row = $result->fetch_assoc()) {
                            // Verificação segura dos valores
                            $cargoVaga = isset($row['cargoVaga']) ? htmlspecialchars($row['cargoVaga']) : 'N/A';
                            $nomeUnidade = isset($row['nomeUnidade']) ? htmlspecialchars($row['nomeUnidade']) : 'N/A';
                            $tipoVaga = isset($row['tipoVaga']) ? htmlspecialchars($row['tipoVaga']) : 'N/A';
                            $especialidadeVaga = isset($row['especialidadeVaga']) ? htmlspecialchars($row['especialidadeVaga']) : 'N/A';
                            $horarioVaga = isset($row['horarioVaga']) ? htmlspecialchars($row['horarioVaga']) : 'N/A';
                            $diaSemana = isset($row['diaSemana']) ? htmlspecialchars($row['diaSemana']) : 'N/A';
                            $grauEmergencia = isset($row['grauEmergencia']) ? htmlspecialchars($row['grauEmergencia']) : 'N/A';
                            $tipoContrato = isset($row['tipoContrato']) ? htmlspecialchars($row['tipoContrato']) : 'N/A';
                            $statusVaga = isset($row['statusVaga']) ? htmlspecialchars($row['statusVaga']) : 'N/A';
                            $idUsuario = isset($row['idUsuario']) ? htmlspecialchars($row['idUsuario']) : 'N/A';

                            // Determina a classe CSS com base no status
                            $statusClass = '';
                            if ($statusVaga == 'aprovado') {
                                $statusClass = 'status-aprovado';
                            } elseif ($statusVaga == 'rejeitado') {
                                $statusClass = 'status-rejeitado';
                            } elseif ($statusVaga == 'pendente') {
                                $statusClass = 'status-pendente';
                            }
                        ?>
                        <tr>
                            <td><?php echo $cargoVaga; ?></td>
                            <td><?php echo $nomeUnidade; ?></td>
                            <td><?php echo $tipoVaga; ?></td>
                            <td><?php echo $especialidadeVaga; ?></td>
                            <td><?php echo $horarioVaga; ?></td>
                            <td><?php echo $diaSemana; ?></td>
                            <td><?php echo $grauEmergencia; ?></td>
                            <td><?php echo $tipoContrato; ?></td>
                            <td class="<?php echo $statusClass; ?>"><?php echo $statusVaga; ?></td>  <!-- Aplicar classe CSS -->
                            <td>
                                <?php
                                // Array de mapeamento dos IDs para os nomes
                                $usuarios = [
                                    1 => 'Isaias Dos Santos',
                                    2 => 'Raquel Souza',
                                    3 => 'Marcos Vinicios',
                                    4 => 'Erika Justino'
                                ];
                                // Verificar e exibir o nome correspondente ao idUsuario
                                echo isset($usuarios[$idUsuario]) ? $usuarios[$idUsuario] : 'Desconhecido';
                                ?>
                            </td>                       
                            <td>
                                <a href="detalhesHistoricoVaga.php?id=<?php echo htmlspecialchars($row['idVaga']); ?>" class="btn-green btn btn-sm">Consultar <i class="fas fa-search"></i></a>
                            </td>
                            </tr>
                        <?php } ?>

                        </tbody>
                        </table>
                    <?php   } else {
                        echo '<div class="alert alert-warning center-message" role="alert">Nenhum candidato encontrado.</div>';
                    }

                    // Fechar a conexão
                    $conn->close();
                    ?>

                </div>
            </div>
        </div>
    </div>
                    
    <div class="pagination-container">
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <?php if ($pagina_atual > 1): ?>
                    <li class="page-item"><a class="page-link btn-white btn btn-sm" href="?pagina=<?php echo $pagina_atual - 1; ?>">Anterior</a></li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                    <li class="page-item <?php if ($i == $pagina_atual) echo 'active'; ?>"><a class="page-link btn-white btn btn-sm" href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                <?php endfor; ?>

                <?php if ($pagina_atual < $total_paginas): ?>
                    <li class="page-item"><a class="page-link btn-white btn btn-sm" href="?pagina=<?php echo $pagina_atual + 1; ?>">Próximo</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

</body>

</html>
