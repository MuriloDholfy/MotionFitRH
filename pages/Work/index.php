<?php
session_start();

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

// Inicializar a variável tipoUsuario
$tipoUsuario = '';

// Verifica se o usuário está logado
if (isset($_SESSION['usuario_logado']) && $_SESSION['usuario_logado'] == true) {
    $tipoUsuario = $_SESSION['tipoUsuario'];
} else {
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

        <?php include '../../components/navbar.php'; ?>

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

                        <div id="component-container"></div>
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
                    <a href="./index.php" class="btn-red btn">Confirmar</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
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

        // Verifica o tipo de usuário e exibe o modal de acesso negado, se necessário
        document.addEventListener("DOMContentLoaded", function() {
            <?php if (isset($tipoUsuario) && ($tipoUsuario === 'gerente' || $tipoUsuario === 'gerenteRegional')): ?>
                // Exibe o modal de acesso negado
                $('#acessoNegadoModal').modal('show');
                setTimeout(function() {
                    window.location.href = '../Home/'; // Redireciona para a página após exibir o modal
                }, 1000); // Atraso de 1 segundo para garantir que o usuário veja o modal
            <?php endif; ?>
        });
    </script>
</body>
</html>
