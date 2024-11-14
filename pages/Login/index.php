<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RH</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/loginStyle.css">
    <link rel="icon" href="./img/icons/navbar/logo.png" type="image/x-icon">

</head>
<style>
   body {
    background-image: url('../../img/bgLogin.png'); 
    background-size: cover;
    background-position: center; 
    background-repeat: no-repeat; 
    }
    .esquerdo {
        height: 100%;
    }
    .card-body {
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .card-grande {
        font-size: 20px;
        background-color: rgba(255, 255, 255, 0);
        padding: 1em;
        z-index: 5;
        height: 25rem;
        width: 50rem;
        border-radius: 10px;
        max-width: 900px;
        transition: 200ms ease-in-out;
        box-shadow: 0px 0px 0px rgba(0, 0, 0, 0)
    }
    
    label {
    text-align: left;
    display: block;
    margin-bottom: .3rem;
    font-size: .9rem;
    font-weight: bold;
    color: #fff;
    transition: color .3s cubic-bezier(.25,.01,.25,1) 0s;
    }
    
</style>
<body>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="card card-grande">
            <div class="row no-gutters w-100">
                <div class="direito col-md-6">
                    <img src="../../img/LOGO.png" alt="Imagem de RH" class="card-img mt-5">
                </div>
                <div class="esquerdo col-md-6">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <form class="form" action="process.php" method="POST">
                            <h2 class="form-title">Sign in</h2>
                            <div class="input-container">
                                <label for="email">EMAIL</label>
                                <input placeholder="Digite o email" type="email" id="email" name="email" required>
                            </div>
                            <div class="input-container">
                                <label for="senha">PASSWORD</label>
                                <input type="password" name="senha" id="senha" placeholder="Digite sua nova senha" required>
                            </div>
                            <button class="submit" type="submit">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <scripy>
        window.onload = function() {
        var currentUrl = window.location.href;
        var allowedUrl = "/pagina-restrita"; // A URL válida
    
        if (currentUrl !== allowedUrl) {
            window.location.href = allowedUrl; // Redireciona para a URL permitida
        }
    };
    
    </scripy>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
