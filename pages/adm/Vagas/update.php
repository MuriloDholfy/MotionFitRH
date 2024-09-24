<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "bdmotion");
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$idVaga = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$vaga = null;

if ($idVaga > 0) {
    // Buscando os dados da vaga
    $result = $conn->query("SELECT * FROM tbVaga WHERE idVaga = $idVaga");
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
                    <label for="nome">Nome da Vaga</label>
                    <input type="text" class="form-control" id="nome" name="nome" value="<?php echo $vaga['nomeVaga'] ?? ''; ?>" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="cargo">Cargo</label>
                    <input type="text" class="form-control" id="cargo" name="cargo" value="<?php echo $vaga['cargoVaga'] ?? ''; ?>" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="especialidade">Especialidade</label>
                    <input type="text" class="form-control" id="especialidade" name="especialidade" value="<?php echo $vaga['especialidadeVaga'] ?? ''; ?>" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="dataAbertura">Data de Abertura</label>
                    <input type="date" class="form-control" id="dataAbertura" name="dataAbertura" value="<?php echo $vaga['dataAberturaVaga'] ?? ''; ?>" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="diaSemana">Dia da Semana</label>
                    <input type="text" class="form-control" id="diaSemana" name="diaSemana" value="<?php echo $vaga['diaSemana'] ?? ''; ?>" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="grauEmergencia">Grau de Emergência</label>
                    <input type="text" class="form-control" id="grauEmergencia" name="grauEmergencia" value="<?php echo $vaga['grauEmergencia'] ?? ''; ?>" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="horario">Horário</label>
                    <input type="time" class="form-control" id="horario" name="horario" value="<?php echo $vaga['horarioVaga'] ?? ''; ?>" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="tipoContrato">Tipo de Contrato</label>
                    <input type="text" class="form-control" id="tipoContrato" name="tipoContrato" value="<?php echo $vaga['tipoContrato'] ?? ''; ?>" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="tipoVaga">Tipo de Vaga</label>
                    <input type="text" class="form-control" id="tipoVaga" name="tipoVaga" value="<?php echo $vaga['tipoVaga'] ?? ''; ?>" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="idUnidade">ID da Unidade</label>
                    <input type="number" class="form-control" id="idUnidade" name="idUnidade" value="<?php echo $vaga['idUnidade'] ?? ''; ?>">
                </div>
                <div class="form-group col-md-4">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="Pendente" <?php echo ($vaga['statusVaga'] ?? '') == 'Pendente' ? 'selected' : ''; ?>>Pendente</option>
                        <option value="Aprovado" <?php echo ($vaga['statusVaga'] ?? '') == 'Aprovado' ? 'selected' : ''; ?>>Aprovado</option>
                        <option value="Rejeitado" <?php echo ($vaga['statusVaga'] ?? '') == 'Rejeitado' ? 'selected' : ''; ?>>Rejeitado</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="processo">Processo</label>
                    <select class="form-control" id="processo" name="processo">
                        <option value="Aberto" <?php echo ($vaga['processoVaga'] ?? '') == 'Aberto' ? 'selected' : ''; ?>>Aberto</option>
                        <option value="Fechado" <?php echo ($vaga['processoVaga'] ?? '') == 'Fechado' ? 'selected' : ''; ?>>Fechado</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="aprovador">Aprovador</label>
                    <input type="text" class="form-control" id="aprovador" name="aprovador" value="<?php echo $vaga['aprovadorVaga'] ?? ''; ?>">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Atualizar Vaga</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    <?php else: ?>
        <p>Vaga não encontrada.</p>
        <a href="index.php" class="btn btn-secondary">Voltar</a>
    <?php endif; ?>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
