<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Gestão de Unidades</title>
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

    <div class="content">
        <h1>Lista de Unidades</h1>

        <!-- Tabela de Unidades -->
        <div style="overflow-x: auto;">
            <span class='crud-icons create' title='Criar Novo' onclick='openModal("create")'>&#43;</span>

            <table >
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>ID Região</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Conectando ao banco de dados MySQL
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "bdmotion";

                    $conn = new mysqli($servername, $username, $password, $dbname);

                    if ($conn->connect_error) {
                        die("Conexão falhou: " . $conn->connect_error);
                    }

                    $itensPorPagina = 10;
                    $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
                    $offset = ($pagina - 1) * $itensPorPagina;

                    $sqlTotal = "SELECT COUNT(*) AS total FROM tbUnidade";
                    $resultTotal = $conn->query($sqlTotal);
                    if (!$resultTotal) {
                        die("Erro na consulta SQL: " . $conn->error);
                    }
                    $rowTotal = $resultTotal->fetch_assoc();
                    $totalUnidades = $rowTotal['total'];
                    $totalPaginas = ceil($totalUnidades / $itensPorPagina);

                    $sql = "SELECT idUnidade, nomeUnidade, idRegiao FROM tbUnidade LIMIT $itensPorPagina OFFSET $offset";
                    $result = $conn->query($sql);

                    if (!$result) {
                        die("Erro na consulta SQL: " . $conn->error);
                    }

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["idUnidade"] . "</td>";
                            echo "<td>" . $row["nomeUnidade"] . "</td>";
                            echo "<td>" . $row["idRegiao"] . "</td>";
                            echo "<td>
                                    <span class='crud-icons update' title='Atualizar' onclick='openModal(\"update\", " . $row["idUnidade"] . ")'>&#9998;</span>
                                    <span class='crud-icons restore' title='Restaurar' onclick='openModal(\"restore\", " . $row["idUnidade"] . ")'>&#8634;</span>
                                    <span class='crud-icons delete' title='Deletar' onclick='openModal(\"delete\", " . $row["idUnidade"] . ")'>&#128465;</span>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>Nenhuma unidade encontrada.</td></tr>";
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
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Criar Nova Unidade</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createForm" action="process.php" method="post">
                        <input type="hidden" name="action" value="create">
                        <div class="form-group">
                            <label for="createNomeUnidade">Nome da Unidade</label>
                            <input type="text" class="form-control" id="createNomeUnidade" name="nomeUnidade" required>
                        </div>
                        <div class="form-group">
                            <label for="createIdRegiao">ID da Região</label>
                            <input type="number" class="form-control" id="createIdRegiao" name="idRegiao" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

  <!-- Modal Atualizar -->
<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Atualizar Unidade</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="updateForm" action="process.php" method="post">
                    <div class="form-group">
                        <label for="updateIdUnidade">ID da Unidade</label>
                        <input type="text" class="form-control" id="updateIdUnidade" name="idUnidade" readonly>
                    </div>
                    <input type="hidden" name="action" value="update">
                    <div class="form-group">
                        <label for="updateNomeUnidade">Nome da Unidade</label>
                        <input type="text" class="form-control" id="updateNomeUnidade" name="nomeUnidade" required>
                    </div>
                    <div class="form-group">
                        <label for="updateIdRegiao">ID da Região</label>
                        <input type="number" class="form-control" id="updateIdRegiao" name="idRegiao" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </form>
            </div>
        </div>
    </div>
</div>


    <!-- Modal Restaurar -->
    <div class="modal fade" id="restoreModal" tabindex="-1" role="dialog" aria-labelledby="restoreModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="restoreModalLabel">Restaurar Unidade</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="restoreForm" action="process.php" method="post">
                        <input type="hidden" id="restoreIdUnidade" name="idUnidade" value="">
                        <input type="hidden" name="action" value="restore">
                        <p>Tem certeza de que deseja restaurar esta unidade?</p>
                        <button type="submit" class="btn btn-primary">Restaurar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Deletar -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Deletar Unidade</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="deleteForm" action="process.php" method="post">
                        <input type="hidden" id="deleteIdUnidade" name="idUnidade" value="">
                        <input type="hidden" name="action" value="delete">
                        <p>Tem certeza de que deseja deletar esta unidade?</p>
                        <button type="submit" class="btn btn-danger">Deletar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function openModal(action, id) {
            console.log('Abrindo modal: ' + action + ', ID: ' + id); // Adicione este log para depuração
            if (action === "create") {
                $('#createModal').modal('show');
            } else if (action === "update") {
                // Configurar os campos do modal de atualização
                $.ajax({
                    url: 'get_unidade.php',
                    type: 'GET',
                    data: { id: id },
                    success: function(response) {
                        var data = JSON.parse(response);
                        document.getElementById('updateIdUnidade').value = data.idUnidade || '';
                        document.getElementById('updateNomeUnidade').value = data.nomeUnidade || '';
                        document.getElementById('updateIdRegiao').value = data.idRegiao || '';
                        $('#updateModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error('Erro ao buscar dados da unidade:', error);
                    }
                });
            } else if (action === "restore") {
                // Configurar os campos do modal de restauração
                document.getElementById('restoreIdUnidade').value = id;
                $('#restoreModal').modal('show');
            } else if (action === "delete") {
                // Configurar os campos do modal de deleção
                document.getElementById('deleteIdUnidade').value = id;
                $('#deleteModal').modal('show');
            }
        }


</script>
</body>
</html>