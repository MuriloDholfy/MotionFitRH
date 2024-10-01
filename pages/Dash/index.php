
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RH</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/dashStyle.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>

<body style="overflow-y: hidden;"
<?php
    // Iniciar a sessão
    session_start();

    // Verificar se o usuário está autenticado

    // Conectar ao banco de dados
    $servername = "50.116.86.123";
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

    // Recuperar os últimos 5 pedidos
    $sql = "SELECT * FROM tbVaga ORDER BY idVaga DESC LIMIT 4";
    $result = $conn->query($sql);

    // Contar pedidos pendentes
    $sqlPendente = "SELECT COUNT(*) AS totalPendente FROM tbVaga WHERE statusVaga = 'Pendente'";
    $resultPendente = $conn->query($sqlPendente);
    $pendente = $resultPendente->fetch_assoc();
    $totalPendente = $pendente['totalPendente'];

    // Contar pedidos aprovados
    $sqlAprovado = "SELECT COUNT(*) AS totalAprovado FROM tbVaga WHERE statusVaga = 'Aprovado'";
    $resultAprovado = $conn->query($sqlAprovado);
    $aprovado = $resultAprovado->fetch_assoc();
    $totalAprovado = $aprovado['totalAprovado'];

    // Contar pedidos rejeitados
    $sqlRejeitado = "SELECT COUNT(*) AS totalRejeitado FROM tbVaga WHERE statusVaga = 'Rejeitado'";
    $resultRejeitado = $conn->query($sqlRejeitado);
    $rejeitado = $resultRejeitado->fetch_assoc();
    $totalRejeitado = $rejeitado['totalRejeitado'];

    
    // Fechar a conexão
    $conn->close();
    ?>
    <div class="container">
        <?php include '../../components/navBar.php'; ?>

        <div class="row p-3">

            <?php include '../../components/sideBar.php'; ?>

            <div class="col-md-11">
                <div class="row col-md-12 justify-content-end ">
                    <!-- <div class=" col-md-2">
                        <div class="select ">
                            <div class="selected" data-default="All" data-one="option-1" data-two="option-2"
                            data-three="option-3">
                            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"
                            class="arrow">
                                    <path
                                    d="M233.4 406.6c12.5 12.5 32.8 12.5 45.3 0l192-192c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L256 338.7 86.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l192 192z">
                                </path>
                            </svg>
                        </div>
                        <div class="options">
                                <div title="Unidade">
                                    <input id="unidade" name="option" type="radio" checked="" />
                                    <label class="option" for="unidade" data-txt="unidade"></label>
                                </div>
                                <div title="option-1">
                                    <input id="option-1" name="option" type="radio" />
                                    <label class="option" for="option-1" data-txt="option-1"></label>
                                </div>
                                <div title="option-2">
                                    <input id="option-2" name="option" type="radio" />
                                    <label class="option" for="option-2" data-txt="option-2"></label>
                                </div>
                                <div title="option-3">
                                    <input id="option-3" name="option" type="radio" />
                                    <label class="option" for="option-3" data-txt="option-3"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class=" calendario col-md-2 ml-5">
                        <input type="date" id="calendario" name="trip-start" />
                    </div>   -->
                </div>
                <div class="row">
                    <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <p class="card-text">Vagas Criadas</p>
                                        </div>
                                        <div class="card-footer">
                                        <small class="text">
                                                    <?php
                                                    // Conectar ao banco de dados
                                                    $conn = new mysqli('50.116.86.123', 'motionfi_contato
', '', 'bdmotion');
                                                    if ($conn->connect_error) {
                                                        die("Conexão falhou: " . $conn->connect_error);
                                                    }
                                                    
                                                    // Contar o número de chamados criados
                                                    $sql = "SELECT COUNT(*) AS total_chamados FROM tbVaga"; // Verifique se 'tbChamados' é o nome correto da tabela
                                                    $result = $conn->query($sql);
                                                    
                                                    if (!$result) {
                                                        die("Erro na consulta SQL: " . $conn->error);
                                                    }
                                                    
                                                    $row = $result->fetch_assoc();
                                                    echo $row['total_chamados'];
                                                    
                                                    $conn->close();
                                                    
                                                    ?>
                                                </small>                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <p class="card-text">Candidatos Criados</p>
                                        </div>
                                        <div class="card-footer">
                                        <small class="text">
                                                    <?php
                                                    // Conectar ao banco de dados
                                                    $conn = new mysqli('50.116.86.123', 'motionfi_contato
', '', 'bdmotion');
                                                    if ($conn->connect_error) {
                                                        die("Conexão falhou: " . $conn->connect_error);
                                                    }
                                                    
                                                    // Contar o número de chamados criados
                                                    $sql = "SELECT COUNT(*) AS total_chamados FROM tbCandidato"; // Verifique se 'tbChamados' é o nome correto da tabela
                                                    $result = $conn->query($sql);
                                                    
                                                    if (!$result) {
                                                        die("Erro na consulta SQL: " . $conn->error);
                                                    }
                                                    
                                                    $row = $result->fetch_assoc();
                                                    echo $row['total_chamados'];
                                                    
                                                    $conn->close();
                                                    
                                                    ?>
                                                </small>                                        
                                            </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="card-feed " style=" font-size: 11px;height: 11rem; " >
                                        <div class="card-body">
                                            <canvas id="chart1" width="200" height="45"></canvas>
                                            </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="card" >
                                        <div class="card-body" >
                                            <p class="card-text">Vagas p/Unidade</p>
                                            <canvas id="chart2"width="200" height="100"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <p class="card-text">Candidatos p/Unidade</p>
                                            <canvas id="chart3"width="200" height="100"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card-grande ">
                                <div class="card-body">
                                    <p class="card-text">Histórico de Vagas</p>
                                </div>
                                <?php
                                // Conectar ao banco de dados
                                $servername = "50.116.86.123";
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

                                // Recuperar os últimos 5 pedidos
                                $sql = "SELECT * FROM tbVaga ORDER BY cargoVaga DESC LIMIT 4";
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
                            <div class="card-grande mt-3">
                                <div class="card-body">
                                    <p class="card-text">Histórico de Candidatos</p>
                                </div>
                                    <?php
                                        // Conectar ao banco de dados
                                        $servername = "50.116.86.123";
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

                                        // Recuperar os últimos 5 pedidos
                                        $sql = "SELECT * FROM tbcandidato ORDER BY nomeCandidato DESC LIMIT 4";
                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo '<div class="card-footer">';
                                                echo '  <small class="card-text">' . $row['nomeCandidato'] . '</small>';
                                                echo '</div>';
                                            }
                                        } else {
                                            echo '<p>Nenhum pedido encontrado.</p>';
                                        }

                                        // Fechar a conexão
                                        $conn->close();
                                ?>
                                </div>
                            </div>
                        </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
document.addEventListener("DOMContentLoaded", function () {
    fetch('data.php')
        .then(response => response.json())
        .then(data => {
            const totalChamados = data.totalChamados;
            const totalCandidatos = data.totalCandidatos;
            const unidades = data.unidades;
            const chamadosPorUnidade = data.chamadosPorUnidade;
            const candidatosPorUnidade = data.candidatosPorUnidade;

            const ctx1 = document.getElementById('chart1').getContext('2d');
            const ctx2 = document.getElementById('chart2').getContext('2d');
            const ctx3 = document.getElementById('chart3').getContext('2d');

            // Dados para o gráfico de linha
            const lineData = {
                labels: unidades,
                datasets: [
                    {
                        label: 'Chamados por Unidade',
                        data: chamadosPorUnidade,
                        borderColor: 'rgb(147, 204, 76)',
                        borderWidth: 1,
                        backgroundColor: 'rgb(147, 204, 76 )',
                    },
                    {
                        label: 'Candidatos por Unidade',
                        data: candidatosPorUnidade,
                        borderColor: 'rgb(246, 246, 246)',
                        borderWidth: 1,
                        backgroundColor: 'rgb(246, 246, 246 )',
                    }
                ]
            };

            // Dados para gráficos de barras (Pedidos por Status)
            const statusData = {
                labels: ['Pendente', 'Aprovado', 'Rejeitado'],
                datasets: [{
                    label: 'Pedidos por Status',
                    data: [data.totalPendente, data.totalAprovado, data.totalRejeitado],
                    borderColor: 'rgb(147, 204, 76)',
                        borderWidth: 1,
                        backgroundColor: 'rgb(147, 204, 76)',
                }]
            };

            new Chart(ctx1, {
                type: 'line',
                data: lineData
            });

            new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: unidades,
                    datasets: [{
                        label: 'Chamados por Unidade',
                        data: chamadosPorUnidade,
                        borderColor: 'rgb(147, 204, 76)',
                        borderWidth: 1,
                        backgroundColor: 'rgb(147, 204, 76)',
                    }]
                }
            });

            new Chart(ctx3, {
                type: 'bar',
                data: {
                    labels: unidades,
                    datasets: [{
                        label: 'Candidatos por Unidade',
                        data: candidatosPorUnidade,
                        borderColor: 'rgb(246, 246, 246)',
                        borderWidth: 1,
                        backgroundColor: 'rgb(246, 246, 246 )',
                    }]
                }
            });
        })
        .catch(error => console.error('Error fetching data:', error));
});

</script>


     