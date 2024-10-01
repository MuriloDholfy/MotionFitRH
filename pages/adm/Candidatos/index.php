<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Candidatos</title>
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
    </style>
</head>
<body>

    <!-- Menu lateral com seta para voltar ao login -->
    <?php include '../../../components/admSidebar.php'; ?>

    <div class="content">
        <h1>Lista de Candidatos</h1>

        <!-- Tabela de Candidatos -->
        <div style="overflow-x: auto;">
            <span class='crud-icons create' title='Criar Novo' onclick='openCreateModal()'>&#43;</span>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Triagem</th>
                        <th>Email</th>              
                        <th>Telefone</th>
                        <th>id Unidade</th>
                        <th>Data da Entrevista</th>
                        <th>Dt. Apro. Entrevista</th>
                        <th>Data de Registro</th>
                        <th>Registro</th>
                        <th>Caju</th>
                        <th>Ponto</th>
                        <th>Contrato Assinado</th>
                        <th>Uniforme</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    // Conectando ao banco de dados MySQL
                    $servername = "50.116.86.123/pages/Login/";
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

                    // Pegando o número da página atual
                    $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
                    $offset = ($pagina - 1) * $itensPorPagina;

                    // Consulta SQL para contar o número total de candidatos
                    $sqlTotal = "SELECT COUNT(*) AS total FROM tbCandidato";
                    $resultTotal = $conn->query($sqlTotal);
                    if (!$resultTotal) {
                        die("Erro na consulta SQL: " . $conn->error);
                    }
                    $rowTotal = $resultTotal->fetch_assoc();
                    $totalCandidatos = $rowTotal['total'];

                    // Calculando o número total de páginas
                    $totalPaginas = ceil($totalCandidatos / $itensPorPagina);

                    // Consulta SQL para buscar os candidatos
                    $sql = "SELECT idCandidato, nomeCandidato, triagemCandidato, emailCandidato, telefoneCandidato, idUnidade, dataEntrevista, dataAprovacaoEntrevista, dataRegistro, registro, caju, ponto, contratoAssinado, uniforme FROM tbCandidato LIMIT $itensPorPagina OFFSET $offset";
                    $result = $conn->query($sql);

                    // Verificando se há resultados
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["idCandidato"] . "</td>";
                            echo "<td>" . $row["nomeCandidato"] . "</td>";
                            echo "<td>" . $row["triagemCandidato"] . "</td>";
                            echo "<td>" . $row["emailCandidato"] . "</td>";
                            echo "<td>" . $row["telefoneCandidato"] . "</td>";
                            echo "<td>" . $row["idUnidade"] . "</td>";
                            echo "<td>" . $row["dataEntrevista"] . "</td>";
                            echo "<td>" . $row["dataAprovacaoEntrevista"] . "</td>";
                            echo "<td>" . $row["dataRegistro"] . "</td>";
                            echo "<td>" . $row["registro"] . "</td>";
                            echo "<td>" . $row["caju"] . "</td>";
                            echo "<td>" . $row["ponto"] . "</td>";
                            echo "<td>" . $row["contratoAssinado"] . "</td>";
                            echo "<td>" . $row["uniforme"] . "</td>";
                            echo "<td style='width: 85px;'> 
                                    <span class='crud-icons update' title='Atualizar' onclick='openUpdateModal(" . $row['idCandidato'] . ")'>&#9998;</span>
                                    <span class='crud-icons restore' title='Restaurar' onclick='restoreUser(" . $row['idCandidato'] . ")'>&#8634;</span>
                                    <span class='crud-icons delete' title='Deletar' onclick='openDeleteModal(" . $row['idCandidato'] . ")'>&#128465;</span>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='15'>Nenhum candidato encontrado.</td></tr>";
                    }

                    // Fechando a conexão
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
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
    <!-- Modal Criar Candidato -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Criar Novo Candidato</h5>
                </div>
                <div class="modal-body">
                    <form id="createForm">
                        <div class="mb-3">
                            <label for="nomeCandidato" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nomeCandidato" required>
                        </div>
                        <div class="mb-3">
                            <label for="emailCandidato" class="form-label">Email</label>
                            <input type="email" class="form-control" id="emailCandidato" required>
                        </div>
                        <div class="mb-3">
                            <label for="telefoneCandidato" class="form-label">Telefone</label>
                            <input type="text" class="form-control" id="telefoneCandidato" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" onclick="createCandidato()">Criar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Atualizar Candidato -->
    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Atualizar Candidato</h5>
                </div>
                <div class="modal-body">
                    <form id="updateForm">
                        <input type="hidden" id="updateIdCandidato">
                        <div class="mb-3">
                            <label for="updateNomeCandidato" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="updateNomeCandidato" required>
                        </div>
                        <div class="mb-3">
                            <label for="updateEmailCandidato" class="form-label">Email</label>
                            <input type="email" class="form-control" id="updateEmailCandidato" required>
                        </div>
                        <div class="mb-3">
                            <label for="updateTelefoneCandidato" class="form-label">Telefone</label>
                            <input type="text" class="form-control" id="updateTelefoneCandidato" required>
                        </div>
                        <!-- Adicione outros campos conforme necessário -->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" onclick="updateCandidato()">Atualizar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Deletar Candidato</h5>
                </div>
                <div class="modal-body">
                    <p>Você tem certeza que deseja deletar este candidato?</p>
                    <input type="hidden" id="deleteIdCandidato">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-danger" onclick="deleteCandidato()">Deletar</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        function openCreateModal() {
            $('#createModal').modal('show');
        }

        function createCandidato() {
            const nome = $('#nomeCandidato').val();
            const email = $('#emailCandidato').val();
            const telefone = $('#telefoneCandidato').val();

            $.ajax({
                type: "POST",
                url: "process.php",
                data: {
                    acao: 'criar',
                    nomeCandidato: nome,
                    emailCandidato: email,
                    telefoneCandidato: telefone
                },
                success: function (response) {
                    alert(response);
                    location.reload();
                }
            });
        }

        function openUpdateModal(id) {
            $.ajax({
                type: "POST",
                url: "process.php",
                data: {
                    acao: 'buscar',
                    idCandidato: id
                },
                success: function (response) {
                    const candidato = JSON.parse(response);
                    $('#updateIdCandidato').val(candidato.idCandidato);
                    $('#updateNomeCandidato').val(candidato.nomeCandidato);
                    $('#updateEmailCandidato').val(candidato.emailCandidato);
                    $('#updateTelefoneCandidato').val(candidato.telefoneCandidato);
                    $('#updateModal').modal('show');
                }
            });
        }

        function updateCandidato() {
            const id = $('#updateIdCandidato').val();
            const nome = $('#updateNomeCandidato').val();
            const email = $('#updateEmailCandidato').val();
            const telefone = $('#updateTelefoneCandidato').val();

            $.ajax({
                type: "POST",
                url: "process.php",
                data: {
                    acao: 'atualizar',
                    idCandidato: id,
                    nomeCandidato: nome,
                    emailCandidato: email,
                    telefoneCandidato: telefone
                },
                success: function (response) {
                    alert(response);
                    location.reload();
                }
            });
        }

        function openDeleteModal(id) {
            $('#deleteIdCandidato').val(id);
            $('#deleteModal').modal('show');
        }

        function deleteCandidato() {
            const id = $('#deleteIdCandidato').val();
            $.ajax({
                type: "POST",
                url: "process.php",
                data: {
                    acao: 'deletar',
                    idCandidato: id
                },
                success: function (response) {
                    alert(response);
                    location.reload();
                }
            });
        }
    </script>
</body>
</html>
