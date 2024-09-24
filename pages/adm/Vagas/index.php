<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "bdmotion");
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
