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

// echo "<pre>Tipo de Usuário: " . $_SESSION['tipoUsuario'] . "</pre>";
// echo "<pre>Status de Login: " . ($_SESSION['usuario_logado'] == 1 ? "Logado" : "Deslogado") . "</pre>";
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
    <link rel="stylesheet" href="../../css/dashStyle.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

</head>

<body style="overflow-y: hidden;"


    <div class="container">
        <?php include '../../components/navbar.php'; ?>

        <div class="row p-3">

            <?php include '../../components/sideBar.php'; ?>

            <div class="col-md-11">
                     <div class="row col-md-12 justify-content-start">
                            <div class="container mb-4">
                                <!-- Botão Exportar no canto direito com ícone -->
                                <button onclick="window.location.href='export.php';" class="btn-green btn btn  mx-3"  style="position: absolute; top: 0px; right: 0;">
                                    <i class="fas fa-download"></i> Exportar
                                </button>

                            </div>
                        </div>
                <div class="row ">
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
                                                    $conn = new mysqli('50.116.86.120', 'motionfi_sistemaRH', '@Motion123', 'motionfi_bdmotion');
                                                    if ($conn->connect_error) {
                                                        die("Conexão falhou: " . $conn->connect_error);
                                                    }
                                                    
                                                    // Contar o número de chamados criados
                                                    $sql = "SELECT COUNT(*) AS total_chamados FROM tbvaga"; // Verifique se 'tbChamados' é o nome correto da tabela
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
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <p class="card-text">Candidatos Criados</p>
                                        </div>
                                        <div class="card-footer">
                                        <small class="text">
                                                    <?php
                                                    // Conectar ao banco de dados
                                                    $conn = new mysqli('50.116.86.120', 'motionfi_sistemaRH', '@Motion123', 'motionfi_bdmotion');
                                                    if ($conn->connect_error) {
                                                        die("Conexão falhou: " . $conn->connect_error);
                                                    }
                                                    
                                                    // Contar o número de chamados criados
                                                    $sql = "SELECT COUNT(*) AS total_chamados FROM tbcandidato"; // Verifique se 'tbChamados' é o nome correto da tabela
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
                               
                                $servername = "50.116.86.120";
                                $username = "motionfi_sistemaRH";
                                $password = "@Motion123"; // **ALTERE IMEDIATAMENTE** por segurança
                                $dbname = "motionfi_bdmotion";
                                // Criar conexão
                                $conn = new mysqli($servername, $username, $password, $dbname);

                                // Checar conexão
                                if ($conn->connect_error) {
                                    die("Conexão falhou: " . $conn->connect_error);
                                }

                                // Recuperar os últimos 5 pedidos
                                $sql = "SELECT * FROM tbvaga ORDER BY cargoVaga DESC LIMIT 4";
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
                                       
                                        $servername = "50.116.86.120";
                                        $username = "motionfi_sistemaRH";
                                        $password = "@Motion123"; // **ALTERE IMEDIATAMENTE** por segurança
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


     