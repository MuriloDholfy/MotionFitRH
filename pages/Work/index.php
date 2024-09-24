
<?php
session_start();

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

// Inicializar a variável tipoUsuario
$tipoUsuario = '';

// Verifica se o usuário está logado
if (isset($_SESSION['user_id'])) {
    // Recuperar o tipo de usuário do banco de dados
    $user_id = intval($_SESSION['user_id']);
    $sql = "SELECT tipoUsuario FROM tbUsuario WHERE idUsuario = $user_id";
    $result = $conn->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $tipoUsuario = $row['tipoUsuario'];
            $_SESSION['tipoUsuario'] = $tipoUsuario;
        } else {
            $_SESSION['access_denied'] = true;
        }
    } else {
        die("Erro na consulta: " . $conn->error);
    }
} else {
    $_SESSION['access_denied'] = true;
}

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
    <link rel="stylesheet" href="../../css/workStyle.css">
</head>

<body>
    <div class="container">
        <?php include '../../components/navBar.php'; ?>

        <div class="row p-3">
            <?php include '../../components/sideBar.php'; ?>

            <div class="col-md-11">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="card-radio">
                                  
                                    <div class="radio-inputs">
                                        <label class="radio">
                                            <input type="radio" name="radio" id="Rec" onclick="loadComponent(this.id)">
                                            <span class="name">Recrutamento</span>
                                        </label>
                                        
                                        <label class="radio">
                                            <input type="radio" name="radio" id="Dpp" onclick="loadComponent(this.id)">
                                            <span class="name">DP Pessoal</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="component-container">
                            <!-- Conteúdo dinâmico será carregado aqui -->
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Acesso Negado -->
    <div class="modal fade" id="acessoNegadoModal" tabindex="-1" role="dialog" aria-labelledby="acessoNegadoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="color: red;">
                    <h5 class="modal-title" id="acessoNegadoModalLabel">Acesso Negado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Você não tem permissão para acessar esta página.</p>
                </div>
                <div class="modal-footer">
                    <a href="./index.php" class="btn-red btn ">Confirmar</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function loadComponent(id) {
            if (id) {
                const xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState === 4 && this.status === 200) {
                        document.getElementById("component-container").innerHTML = this.responseText;
                    }
                };
                xhttp.open("GET", "../../components/" + id + ".php", true);
                xhttp.send();
            } else {
                document.getElementById("component-container").innerHTML = "";
            }
        }

        // Verifica o tipo de usuário e abre o modal se necessário
        document.addEventListener("DOMContentLoaded", function() {
            <?php if (isset($tipoUsuario) && ($tipoUsuario === 'gerente' || $tipoUsuario === 'gerenteRegional')): ?>
                // Exibe o modal de acesso negado
                $('#acessoNegadoModal').modal('show');
                setTimeout(function() {
                    window.location.href = '../Home/'; // Redireciona para a página após exibir o modal
                }, 1000); // Atraso de 1 segundo para garantir que o usuário veja o modal
                unset($_SESSION['access_denied']); // Limpa o sinalizador após exibir 
            <?php endif; ?>
        });
    </script>
</body>

</html>
