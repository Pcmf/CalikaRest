<?php
require_once './db/DB.php';
require_once 'Cliente.php';
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
    private $Cliente;
    
    public function __construct() {
        $this->db = new DB();
        $this->Cliente = new Cliente();
    }
    /**
     * 
     * @return type
     */
    public function getAll() {
        return $this->db->query("SELECT P.*, C.nome AS nomeCliente, S.situacao AS nomeSituacao "
                . " FROM pedido P"
                . " INNER JOIN cliente C ON C.id=P.clienteId "
                . " INNER JOIN situacao S ON S.id=P.situacao");
    }
    /**
     * 
     * @param type $pid
     * @param type $ano
     * @return type
     */
    public function getOne($pid) {
        return $this->db->query("SELECT P.*, C.nome AS nomeCliente, S.situacao AS nomeSituacao "
                . " FROM pedido P"
                . " INNER JOIN cliente C ON C.id=P.clienteId "
                . " INNER JOIN situacao S ON S.id=P.situacao"
                . " WHERE P.id=:pid",
                array(':pid'=>$pid))[0];
    }
    /**
     * 
     * @param type $cid
     * @param type $ano
     * @param type $status
     * @return type
     */
    public function getByCltYearSts($cid, $ano, $status) {
        return $this->db->query("SELECT P.*, M.refinterna "
                . " FROM pedido P "
                . " LEFT JOIN modelo M ON M.pedido=P.id AND M.ano=P.ano "
                . " WHERE P.clienteId=:cid AND P.ano=:ano AND P.situacao=:status GROUP BY P.tema",
                [':cid'=>$cid, ':ano'=>$ano, ':status'=>$status]);
    }
    
    /**
     * 
     * @param type $cid
     * @param type $obj
     * @return type
     */
    public function createPedido($cid, $obj) {
        !isset($obj->refcliente) ? $obj->refcliente='' : null;
        !isset($obj->descricao) ? $obj->descricao='' : null;
        try {
            $this->db->queryInsert("INSERT INTO pedido(clienteId, ano, refInterna, refCliente, tema, descricao, foto, datapedido, situacao) "
                        . " VALUES(:clienteId, :ano, :refInterna, :refCliente, :tema, :descricao, :foto, NOW(), 1)",
                            [':clienteId'=>$cid, ':ano'=>$obj->anoTema, ':refCliente'=>$obj->refCliente, 
                             ':refInterna'=>$this->getRefInterna($cid, $obj->anoTema),
                             ':tema'=>$obj->tema, ':descricao'=>$obj->descricao , ':foto'=>$obj->foto]);
            return $this->db->lastInsertId();
            
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }


        
    }
    
    private function getRefInterna($cid, $ano) {
        $cliente = $this->Cliente->getOne($cid);
        $pedidoCardinal = $this->db->query("SELECT COUNT(*)+1 AS cardinal FROM pedido WHERE ano=:ano AND clienteId=:cid",
                [':ano'=>$ano, ':cid'=>$cid]);
        return $cliente->codigo.($ano-2000).$pedidoCardinal[0]->cardinal;
    }
}
