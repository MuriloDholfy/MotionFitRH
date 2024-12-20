<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RH</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../css/admStyle.css">
    <link rel="stylesheet" href="../../../css/admLoginStyle.css">
    <link rel="icon" href="./img/icons/navbar/logo.png" type="image/x-icon">
    <!-- Adicionando o link do Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<style>
   body {
    background-image: url('../../../img/bgLogin.png'); 
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
                    
                    <img src="../../../img/adm.png" alt="Imagem de RH" class="card-img mt-5">
                     <a href="../../Login/" class="btn btn-secondary ">
                                <i class="fas fa-arrow-left"></i> 
                    </a> 
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
                                <input placeholder="Digite a senha" type="password" id="senha" name="senha" required>
                            </div>
                            <button class="submit" type="submit">Login</button>
                            <!-- Bot���o de Voltar com ���cone -->
                           
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
