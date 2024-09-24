
<?php
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

// Verificar se o parâmetro idVaga está definido e é um número
if (isset($_GET['idVaga']) && is_numeric($_GET['idVaga'])) {
    $idVaga = intval($_GET['idVaga']); // Usar intval para evitar SQL Injection

    // Consultar os dados da vaga
    $sql = "SELECT v.*, u.nomeUnidade
            FROM tbVaga v
            JOIN tbUnidade u ON v.idUnidade = u.idUnidade
            WHERE v.idVaga = $idVaga";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $vaga = $result->fetch_assoc();
    } else {
        echo "Vaga não encontrada.";
        exit;
    }
} else {
    echo "ID da vaga inválido.";
    exit;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processo Vaga</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/processoVagaStyle.css">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css'>
    <style>
        /* Adicione seu estilo aqui */
    </style>
</head>
<body>
    <div class="container">
        <?php include '../../components/navBar.php'; ?>
        <div class="row p-3">
            <?php include '../../components/sideBar.php'; ?>
            <div class="col-md-11">
                <div class="card-grande">
                    <div class="card-body">
                        <h1 class="card-text mb-5" style="text-align: left;">Processo Vaga</h1>
                        <div class="progress-container">
                            <div class="progress-bar"></div>
                            <div class="stage"><i class="fas fa-search"></i></div>
                            <div class="stage"><i class="fas fa-check"></i></div>
                            <div class="stage"><i class="fas fa-users"></i></div>
                            <div class="stage"><i class="fas fa-tasks"></i></div>
                            <div class="stage"><i class="fas fa-user-tie"></i></div>
                        </div>
                        <div class="d-flex justify-content-around">
                            <span class="stage-title">Pedido em Análise</span>
                            <span class="stage-title">Pedido Aprovado</span>
                            <span class="stage-title">Recrutamento</span>
                            <span class="stage-title">Seleção</span>
                            <span class="stage-title">DP Pessoal</span> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Definir o estágio atual com base no valor obtido do banco de dados
        const stages = document.querySelectorAll('.stage');
        const progressBar = document.querySelector('.progress-bar');
        let currentStage = 0;

        // Determinar o estágio atual
        const statusMap = {
            'Pedido em Análise': 0,
            'Pedido Aprovado': 1,
            'Recrutamento': 2,
            'Seleção': 3,
            'DP Pessoal': 4
        };

        const processoVaga = "<?php echo htmlspecialchars($vaga['ProcessoVaga']); ?>";
        currentStage = statusMap[processoVaga] || 0;

        // Atualiza a largura da barra de progresso
        progressBar.style.width = (currentStage / (stages.length - 1)) * 100 + '%';

        // Marca os estágios completos
        for (let i = 0; i <= currentStage; i++) {
            stages[i].classList.add('completed');
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
