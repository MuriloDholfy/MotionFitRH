<?php
    $servername = "50.116.86.120";
    $username = "motionfi_sistemaRH";
    $password = "@Motion123"; // **ALTERE IMEDIATAMENTE** por seguran���a
    $dbname = "motionfi_bdmotion";
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Conex���o falhou: " . $conn->connect_error);
    }
    
    $idUsuario = $_POST['idUsuario'];
    $sql = "SELECT idUsuario, nome, email, senha, tipoUsuario FROM tbusuario WHERE idUsuario=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $data = $result->fetch_assoc();
    
    echo json_encode($data);
    
    $stmt->close();
    $conn->close();
?>
