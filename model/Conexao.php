<?php 
    class Conexao{
        public static function conectar(){
            //informação de banco de dados
            $servidor = '50.116.86.123/pages/Login/';
            $banco = 'bdmotion';//nome do database
            $usuario = 'motionfi_contato';
            $senha='';

            //função para conectar o servidor
            $conexao = new PDO("mysql:dbname=".$banco.";host=".$servidor,$usuario, $senha);

            $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO:: ERRMODE_EXCEPTION);
            $conexao->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            return $conexao;

        }

    }



?>