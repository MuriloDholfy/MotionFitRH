<?php

class PedidoContratacao {
    private $id;
    private $cargo;
    private $unidade;
    private $tipoVaga;
    private $especialidade;
    private $diaSemana;
    private $horario;
    private $grauEmergencia;
    private $tipoContrato;

    // Getters e Setters
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getCargo() {
        return $this->cargo;
    }

    public function setCargo($cargo) {
        $this->cargo = $cargo;
    }

    public function getUnidade() {
        return $this->unidade;
    }

    public function setUnidade($unidade) {
        $this->unidade = $unidade;
    }

    public function getTipoVaga() {
        return $this->tipoVaga;
    }

    public function setTipoVaga($tipoVaga) {
        $this->tipoVaga = $tipoVaga;
    }

    public function getEspecialidade() {
        return $this->especialidade;
    }

    public function setEspecialidade($especialidade) {
        $this->especialidade = $especialidade;
    }

    public function getDiaSemana() {
        return $this->diaSemana;
    }

    public function setDiaSemana($diaSemana) {
        $this->diaSemana = $diaSemana;
    }

    public function getHorario() {
        return $this->horario;
    }

    public function setHorario($horario) {
        $this->horario = $horario;
    }

    public function getGrauEmergencia() {
        return $this->grauEmergencia;
    }

    public function setGrauEmergencia($grauEmergencia) {
        $this->grauEmergencia = $grauEmergencia;
    }

    public function getTipoContrato() {
        return $this->tipoContrato;
    }

    public function setTipoContrato($tipoContrato) {
        $this->tipoContrato = $tipoContrato;
    }
}

?>
