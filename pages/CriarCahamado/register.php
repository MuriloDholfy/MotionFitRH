<?php
session_start(); // Inicia a sessão
require_once(__DIR__ . '../../../dao/PedidoContratacaoDao.php');
require_once(__DIR__ . '../../model/Conexao.php');

if (!empty($_POST)) {
    $cargo = $_POST['cargo'];
    $unidade = $_POST['unidade'];
    $tipoVaga = $_POST['tipoVaga'];
    $especialidade = $_POST['especialidade'];
    $diaSemana = $_POST['diaSemana'];
    $horario = $_POST['horario'];
    $grauEmergencia = $_POST['grauEmergencia'];
    $tipoContrato = $_POST['tipoContrato'];
    
    // Aqui você faria a chamada ao DAO para salvar as informações no banco de dados
    $pedidoContratacaoDao = new PedidoContratacaoDao();
    $pedidoContratacaoDao->save($cargo, $unidade, $tipoVaga, $especialidade, $diaSemana, $horario, $grauEmergencia, $tipoContrato);
    
    // Após salvar, redirecione para uma página de sucesso ou exiba uma mensagem
    header('Location: success.php');
    exit();
} else {
    $cargo = '';
    $unidade = '';
    $tipoVaga = '';
    $especialidade = '';
    $diaSemana = '';
    $horario = '';
    $grauEmergencia = '';
    $tipoContrato = '';
}
?>
