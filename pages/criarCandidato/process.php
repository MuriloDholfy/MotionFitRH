<?php
// Iniciar a sessão
session_start();

// Conectar ao banco de dados
$servername = "50.116.86.123";
$username = "motionfi_contato
";
$password = "68141096@Total";

$dbname = "motionfi_bdmotion";
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Obter dados do formulário
$nome = $_POST['nome'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$triagem = $_POST['triagem'];
$unidade = $_POST['unidade'];  // Recebe o valor enviado pelo formulário
$vaga = $_POST['vaga']; // Recebe o valor da vaga selecionada

// Verificar se o idUnidade é numérico ou um nome
if (is_numeric($unidade)) {
    // Se $unidade é um ID, verificar se existe
    $sql_check = "SELECT idUnidade FROM tbUnidade WHERE idUnidade = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("i", $unidade);
} else {
    // Se $unidade é um nome, verificar se existe
    $sql_check = "SELECT idUnidade FROM tbUnidade WHERE nomeUnidade = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $unidade);
}

$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    // Se o idUnidade existe, obter o idUnidade correspondente
    $stmt_check->bind_result($idUnidade);
    $stmt_check->fetch();
    $stmt_check->close(); // Fechar aqui se a unidade já existe

    // Inserir dados na tabela tbCandidato
    $sql = "INSERT INTO tbCandidato (nomeCandidato, emailCandidato, telefoneCandidato, triagemCandidato, idUnidade, idVaga) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssii", $nome, $email, $telefone, $triagem, $idUnidade, $vaga);
    
    if ($stmt->execute()) {
        $message = "Candidato cadastrado com sucesso!";
    } else {
        $message = "Erro ao cadastrar candidato: " . $stmt->error;
    }
} else {
    $stmt_check->close(); // Fechar aqui se a unidade não existe

    // Se o idUnidade não existir, insira uma nova unidade e use o ID gerado
    $sql_insert_unidade = "INSERT INTO tbUnidade (nomeUnidade) VALUES (?)";
    $stmt_insert_unidade = $conn->prepare($sql_insert_unidade);
    $stmt_insert_unidade->bind_param("s", $unidade);

    if ($stmt_insert_unidade->execute()) {
        $idUnidade = $stmt_insert_unidade->insert_id; // Obter o ID gerado para a nova unidade
        $stmt_insert_unidade->close();

        // Agora insira os dados na tabela tbCandidato usando o $idUnidade
        $sql = "INSERT INTO tbCandidato (nomeCandidato, emailCandidato, telefoneCandidato, triagemCandidato, idUnidade, idVaga) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssii", $nome, $email, $telefone, $triagem, $idUnidade, $vaga);
        
        if ($stmt->execute()) {
            $message = "Candidato cadastrado com sucesso!";
        } else {
            $message = "Erro ao cadastrar candidato: " . $stmt->error;
        }
    } else {
        $message = "Erro ao inserir unidade: " . $stmt_insert_unidade->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensagem</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{
            background-image: url('../../img/bgLogin.png'); /* Caminho para a imagem de fundo */
            background-repeat: no-repeat; /* Evita que a imagem se repita */    
            margin: 0;
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .modal-body {
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Modal -->
    <div class="modal fade" id="messageModal" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="messageModalLabel">Mensagem</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php echo $message; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function(){
            $("#messageModal").modal('show');
            setTimeout(function() {
                window.location.href = '../consultarCandidato/consultarCandidatos.php'; // Redireciona após exibir o modal
            }, 1000);
        });
    </script>
</body>
</html>
