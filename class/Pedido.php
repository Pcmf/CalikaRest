<?php
require_once './db/DB.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Modelo
 *
 * @author pedro
 */
class Pedido {
    private $db;
    
    public function __construct() {
        $this->db = new DB();
    }
    
    public function getAll() {
        return $this->db->query("SELECT P.*, C.nome AS nomeCliente, S.situacao AS nomeSituacao "
                . " FROM pedido P"
                . " INNER JOIN cliente C ON C.id=P.clienteId "
                . " INNER JOIN situacao S ON S.id=P.situacao");
    }
    
    public function getOne($pid, $ano) {
        return $this->db->query("SELECT P.*, C.nome AS nomeCliente, S.situacao AS nomeSituacao "
                . " FROM pedido P"
                . " INNER JOIN cliente C ON C.id=P.clienteId "
                . " INNER JOIN situacao S ON S.id=P.situacao"
                . " WHERE P.id=:pid AND P.ano=:ano",
                array(':pid'=>$pid, ':ano'=>$ano ));
    }
    
    public function getByCltYearSts($cid, $ano, $status) {
        return $this->db->query("SELECT P.*, M.refinterna "
                . " FROM pedido P "
                . " INNER JOIN modelo M ON M.pedido=P.id AND M.ano=P.ano "
                . " WHERE P.clienteId=:cid AND P.ano=:ano AND P.situacao=:status GROUP BY P.tema",
                [':cid'=>$cid, ':ano'=>$ano, ':status'=>$status]);
    }
}
