
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
            border: 1px solid #dee2e6;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: .75rem;
            vertical-align: top;
            border: 1px solid #dee2e6; 
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
                            <td><a href="processoVaga.php?idVaga=<?php echo htmlspecialchars($row['idVaga']); ?>" class="btn-green btn btn-sm">Detalhes<i class="fas fa-search"></i></a></td>
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
    <script>
    // Salvar a posição de rolagem no localStorage antes de enviar o formulário ou mudar de página
    document.querySelectorAll('.page-link').forEach(function (element) {
        element.addEventListener('click', function () {
            localStorage.setItem('scrollPosition', window.scrollY);
        });
    });

    // Verificar se há uma posição de rolagem salva e restaurá-la
    window.onload = function () {
        var scrollPosition = localStorage.getItem('scrollPosition');
        if (scrollPosition) {
            window.scrollTo(0, scrollPosition);
            localStorage.removeItem('scrollPosition'); // Remover após restaurar para evitar que afete outras páginas
        }
    };
</script>

</body>

</html>
