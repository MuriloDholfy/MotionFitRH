<?php
// Conex達o com o banco de dados
$conn = new mysqli('50.116.86.120', 'motionfi_sistemaRH', '@Motion123', 'motionfi_bdmotion');

if ($conn->connect_error) {
    die("Conex達o falhou: " . $conn->connect_error);
}

$idVaga = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$vaga = null;

if ($idVaga > 0) {
    // Buscando os dados da vaga
    $result = $conn->query("SELECT * FROM tbvaga WHERE idVaga = $idVaga");
    $vaga = $result->fetch_assoc();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Atualizar Vaga</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <h1>Atualizar Vaga</h1>

    <?php if ($vaga): ?>
        <form method="POST" action="process.php?action=update">
            <input type="hidden" name="idVaga" value="<?php echo $vaga['idVaga']; ?>">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="cargo">Cargo</label>
                    <input type="text" class="form-control" id="cargo" name="cargo" value="<?php echo $vaga['cargoVaga']; ?>" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="especialidade">Especialidade</label>
                    <input type="text" class="form-control" id="especialidade" name="especialidade" value="<?php echo $vaga['especialidadeVaga']; ?>" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="tipo">Tipo</label>
                    <input type="text" class="form-control" id="tipo" name="tipo" value="<?php echo $vaga['tipoVaga']; ?>" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="contrato">Tipo de Contrato</label>
                    <input type="text" class="form-control" id="contrato" name="contrato" value="<?php echo $vaga['tipoContrato']; ?>" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="grauEmergencia">Grau de Emerg棚ncia</label>
                    <input type="text" class="form-control" id="grauEmergencia" name="grauEmergencia" value="<?php echo $vaga['grauEmergencia']; ?>" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="Pendente" <?php echo $vaga['statusVaga'] == 'Pendente' ? 'selected' : ''; ?>>Pendente</option>
                        <option value="Aprovado" <?php echo $vaga['statusVaga'] == 'Aprovado' ? 'selected' : ''; ?>>Aprovado</option>
                        <option value="Rejeitado" <?php echo $vaga['statusVaga'] == 'Rejeitado' ? 'selected' : ''; ?>>Rejeitado</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Atualizar Vaga</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    <?php else: ?>
        <p>Vaga n達o encontrada.</p>
        <a href="index.php" class="btn btn-secondary">Voltar</a>
    <?php endif; ?>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
