<?php
// Configurações do banco de dados
$servername = "50.116.86.120";
$username = "motionfi_sistemaRH";
$password = "@Motion123";
$dbname = "motionfi_bdmotion";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Checar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Definir cabeçalho para o arquivo CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=relatorio_completo.csv');

// Abrir a saída para escrita no arquivo CSV
$output = fopen('php://output', 'w');

// Função para escrever a seção de dados no CSV
function writeSection($output, $title, $headers, $sql, $conn) {
    fputcsv($output, [$title]);
    fputcsv($output, $headers);

    // Executar a consulta e escrever os dados no CSV
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            fputcsv($output, $row);
        }
    } else {
        fputcsv($output, ['Nenhum dado encontrado']);
    }

    fputcsv($output, []); // Adicionar uma linha em branco entre as seções
}

// Vagas Criadas
writeSection($output, 'Vagas Criadas', ['ID da Vaga', 'Nome da Vaga', 'Cargo da Vaga', 'Data de Abertura', 'Status da Vaga'], "
    SELECT idVaga, nomeVaga, cargoVaga, dataAberturaVaga, statusVaga 
    FROM tbvaga
    ORDER BY dataAberturaVaga DESC
", $conn);

// Candidatos Criados
writeSection($output, 'Candidatos Criados', ['ID do Candidato', 'Nome do Candidato', 'Email', 'Telefone', 'Data de Registro'], "
    SELECT idCandidato, nomeCandidato, emailCandidato, telefoneCandidato, dataRegistro 
    FROM tbcandidato
    ORDER BY dataRegistro DESC
", $conn);

// Histórico de Vagas
writeSection($output, 'Histórico de Vagas', ['ID da Vaga', 'Nome da Vaga', 'Cargo da Vaga', 'Processo da Vaga', 'Data de Abertura'], "
    SELECT idVaga, nomeVaga, cargoVaga, ProcessoVaga, dataAberturaVaga
    FROM tbvaga
    ORDER BY dataAberturaVaga DESC
", $conn);

// Histórico de Candidatos
writeSection($output, 'Histórico de Candidatos', ['ID do Candidato', 'Nome do Candidato', 'Cargo da Vaga', 'Data de Entrevista', 'Status da Triagem'], "
    SELECT tbcandidato.idCandidato, tbcandidato.nomeCandidato, tbvaga.cargoVaga, tbcandidato.dataEntrevista, tbcandidato.triagemCandidato
    FROM tbcandidato
    LEFT JOIN tbvaga ON tbcandidato.idVaga = tbvaga.idVaga
    ORDER BY tbcandidato.dataRegistro DESC
", $conn);

// Vagas por Unidade
writeSection($output, 'Vagas por Unidade', ['ID da Unidade', 'Nome da Unidade', 'Número de Vagas'], "
    SELECT tbvaga.idUnidade, tbunidade.nomeUnidade, COUNT(tbvaga.idVaga) as numVagas
    FROM tbvaga
    LEFT JOIN tbunidade ON tbvaga.idUnidade = tbunidade.idUnidade
    GROUP BY tbvaga.idUnidade
", $conn);

// Candidatos por Unidade
writeSection($output, 'Candidatos por Unidade', ['ID da Unidade', 'Nome da Unidade', 'Número de Candidatos'], "
    SELECT tbcandidato.idUnidade, tbunidade.nomeUnidade, COUNT(tbcandidato.idCandidato) as numCandidatos
    FROM tbcandidato
    LEFT JOIN tbunidade ON tbcandidato.idUnidade = tbunidade.idUnidade
    GROUP BY tbcandidato.idUnidade
", $conn);

// Fechar a conexão com o banco de dados e o arquivo CSV
$conn->close();
fclose($output);
?>
