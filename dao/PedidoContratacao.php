<?php
require_once (__DIR__.'/../model/PedidoContratacao.php');
require_once (__DIR__.'/../config/Database.php');

class PedidoContratacaoDao {
    
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    public function insert(PedidoContratacao $pedido) {
        try {
            $sql = "INSERT INTO pedidos_contratacao (cargo, unidade, tipo_vaga, especialidade, dia_semana, horario, grau_emergencia, tipo_contrato)
                    VALUES (:cargo, :unidade, :tipo_vaga, :especialidade, :dia_semana, :horario, :grau_emergencia, :tipo_contrato)";
            $stmt = $this->conn->prepare($sql);

            $stmt->bindValue(':cargo', $pedido->getCargo());
            $stmt->bindValue(':unidade', $pedido->getUnidade());
            $stmt->bindValue(':tipo_vaga', $pedido->getTipoVaga());
            $stmt->bindValue(':especialidade', $pedido->getEspecialidade());
            $stmt->bindValue(':dia_semana', $pedido->getDiaSemana());
            $stmt->bindValue(':horario', $pedido->getHorario());
            $stmt->bindValue(':grau_emergencia', $pedido->getGrauEmergencia());
            $stmt->bindValue(':tipo_contrato', $pedido->getTipoContrato());

            $stmt->execute();
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Erro ao inserir pedido de contratação: " . $e->getMessage());
        }
    }

    public function update($id, PedidoContratacao $pedido) {
        try {
            $sql = "UPDATE pedidos_contratacao SET cargo = :cargo, unidade = :unidade, tipo_vaga = :tipo_vaga, especialidade = :especialidade,
                    dia_semana = :dia_semana, horario = :horario, grau_emergencia = :grau_emergencia, tipo_contrato = :tipo_contrato
                    WHERE id = :id";
            $stmt = $this->conn->prepare($sql);

            $stmt->bindValue(':cargo', $pedido->getCargo());
            $stmt->bindValue(':unidade', $pedido->getUnidade());
            $stmt->bindValue(':tipo_vaga', $pedido->getTipoVaga());
            $stmt->bindValue(':especialidade', $pedido->getEspecialidade());
            $stmt->bindValue(':dia_semana', $pedido->getDiaSemana());
            $stmt->bindValue(':horario', $pedido->getHorario());
            $stmt->bindValue(':grau_emergencia', $pedido->getGrauEmergencia());
            $stmt->bindValue(':tipo_contrato', $pedido->getTipoContrato());
            $stmt->bindValue(':id', $id);

            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erro ao atualizar pedido de contratação: " . $e->getMessage());
        }
    }

    public function delete($id) {
        try {
            $sql = "DELETE FROM pedidos_contratacao WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erro ao deletar pedido de contratação: " . $e->getMessage());
        }
    }

    public function selectById($id) {
        try {
            $sql = "SELECT * FROM pedidos_contratacao WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                $pedido = new PedidoContratacao();
                $pedido->setId($result['id']);
                $pedido->setCargo($result['cargo']);
                $pedido->setUnidade($result['unidade']);
                $pedido->setTipoVaga($result['tipo_vaga']);
                $pedido->setEspecialidade($result['especialidade']);
                $pedido->setDiaSemana($result['dia_semana']);
                $pedido->setHorario($result['horario']);
                $pedido->setGrauEmergencia($result['grau_emergencia']);
                $pedido->setTipoContrato($result['tipo_contrato']);
                
                return $pedido;
            }
            return null;
        } catch (PDOException $e) {
            throw new Exception("Erro ao selecionar pedido de contratação: " . $e->getMessage());
        }
    }

    public function selectAll() {
        try {
            $sql = "SELECT * FROM pedidos_contratacao";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $pedidos = [];

            foreach ($result as $row) {
                $pedido = new PedidoContratacao();
                $pedido->setId($row['id']);
                $pedido->setCargo($row['cargo']);
                $pedido->setUnidade($row['unidade']);
                $pedido->setTipoVaga($row['tipo_vaga']);
                $pedido->setEspecialidade($row['especialidade']);
                $pedido->setDiaSemana($row['dia_semana']);
                $pedido->setHorario($row['horario']);
                $pedido->setGrauEmergencia($row['grau_emergencia']);
                $pedido->setTipoContrato($row['tipo_contrato']);
                
                $pedidos[] = $pedido;
            }
            return $pedidos;
        } catch (PDOException $e) {
            throw new Exception("Erro ao selecionar todos os pedidos de contratação: " . $e->getMessage());
        }
    }
}
?>
