<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RH</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/loginStyle.css">
    <link rel="icon" href="./img/icons/navbar/logo.png" type="image/x-icon">

    <style>
        body {
            background-image: url('./img/bgLogin.png'); 
            background-size: cover;
            background-position: center; 
            background-repeat: no-repeat; 
        }

        .card-grande {
            font-size: 20px;
            background-color: rgba(255, 255, 255, 0);
            padding: 1em;
            z-index: 5;
            border-radius: 10px;
            max-width: 900px;
            transition: 200ms ease-in-out;
            box-shadow: 0px 0px 0px rgba(0, 0, 0, 0)
        }

        /* Estilo para o indicador de carregamento */
        #loading {
            position: fixed;
            top: 60%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: none; /* Ocultar inicialmente */
            z-index: 10; /* Acima do card */
        }
    </style>
</head>
<body>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="card card-grande">
            <div class="row no-gutters">
                <div class="direito">
                    <img src="./img/LOGO.png" alt="Imagem de RH" class="card-img mt-5">
                </div>
            </div>
        </div>
    </div>

    <!-- Indicador de carregamento -->
    <div id="loading">
        <div class="spinner-border text-light" role="status">
            <span class="sr-only">Carregando...</span>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <script>
        // Exibir o carregamento e redirecionar após 2 segundos
        document.addEventListener('DOMContentLoaded', function() {
            // Mostrar o indicador de carregamento
            document.getElementById('loading').style.display = 'block';

            // Redirecionar após 2 segundos
            setTimeout(function() {
                window.location.href = 'pages/Login/'; // Redireciona para a página de login
            }, 2000); // 2000 ms = 2 segundos
        });
    </script>
</body>
</html>
