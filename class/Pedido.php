<?php
require_once './db/DB.php';
require_once 'Cliente.php';
require_once 'Modelo.php';
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
    private $Modelo;
    
    public function __construct() {
        $this->db = new DB();
        $this->Cliente = new Cliente();
        $this->Modelo = new Modelo();
        
    }

    /**
     * @description get grouped by client/status
     * @return array 
     */
    public function getByStatusAllClients(){
        return $this->db->query("SELECT DISTINCT P.situacao AS status, C.nome AS name, C.id AS clienteId, count(P.situacao) AS qty "
        . " FROM pedido P LEFT JOIN cliente C ON C.id = P.clienteId GROUP BY P.situacao, C.nome ORDER BY C.nome, P.situacao");
    }

        /**
     * 
     * @param type $cid
     * @param type $status
     * @return order []
     */
    public function getOrdersByClientStatus($cid, $status) {
        return $this->db->query("SELECT P.*, C.nome AS nomeCliente, S.situacao AS nomeSituacao "
                . " FROM pedido P"
                . " INNER JOIN cliente C ON C.id=P.clienteId "
                . " INNER JOIN situacao S ON S.id=P.situacao"
                . " WHERE P.clienteId=:cid AND P.situacao=:status",
                array(':cid'=>$cid, ':status'=>$status));
    }

            /**
     * 
     * @param type $status
     * @return order []
     */
    public function getOrdersByStatus($status) {
        return $this->db->query("SELECT P.*, C.nome AS nomeCliente, S.situacao AS nomeSituacao "
                . " FROM pedido P"
                . " INNER JOIN cliente C ON C.id=P.clienteId "
                . " INNER JOIN situacao S ON S.id=P.situacao"
                . " WHERE P.situacao=:status",
                array(':status'=>$status));
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
     * @description get all pedidos by client id
     * @return type
     */
    public function getAllByClientId($cid) {
        return $this->db->query("SELECT P.* "
                . " FROM pedido P"
                . " WHERE P.clienteId=:cid",
                array(':cid'=>$cid));
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
        !isset($obj->refInterna) ? $obj->refInterna='' : null;
        !isset($obj->refcliente) ? $obj->refcliente='' : null;
        !isset($obj->descricao) ? $obj->descricao='' : null;
        try {
            $this->db->queryInsert("INSERT INTO pedido(clienteId, ano, refInterna, refCliente, tema, descricao, foto, datapedido, situacao) "
                        . " VALUES(:clienteId, :ano, :refInterna, :refCliente, :tema, :descricao, :foto, NOW(), 1)",
                            [':clienteId'=>$cid, ':ano'=>$obj->anoTema, ':refCliente'=>$obj->refCliente, 
                             ':refInterna'=>$obj->refInterna,':tema'=>$obj->tema, ':descricao'=>$obj->descricao ,
                              ':foto'=>$obj->foto]);
            return $this->db->lastInsertId();
            
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }
    
    /**
     * 
     */
    public function editPedido($pid, $obj) {
        $obj = $this->checkObjFields($pid, $obj);
        return $this->db->query("UPDATE pedido SET clienteId=:clienteId, ano=:ano, refInterna=:refInterna,"
                . "refCliente=:refCliente, tema=:tema, descricao=:descricao, foto=:foto, situacao=:situacao WHERE id=:pid",
                [':clienteId'=>$obj->clienteId, ':ano'=>$obj->ano, ':refInterna'=>$obj->refInterna, 
                 ':refCliente'=>$obj->refCliente, ':tema'=>$obj->tema, ':descricao'=>$obj->descricao,
                 ':foto'=>$obj->foto, ':situacao'=>$obj->situacao , ':pid'=>$pid]);
        
    }

    public function updateFoto($pid, $obj){
        return $this->db->query("UPDATE pedido SET foto=:foto WHERE id=:pid",
        [':pid'=>$pid, ':foto'=>$obj]);
    }
    
    public function deletePedido($pid) {
        // APAGAR MODELOS DESTE PEDIDO
        $this->Modelo->deleteAll($pid);
        return $this->db->query("DELETE FROM pedido WHERE id=:pid", [':pid'=>$pid]);
        
    }
    
    
    
    /**
     * 
     * @param type $cid
     * @param type $ano
     * @return type
     */
    public function getRefInterna($cid, $ano) {
        $cliente = $this->Cliente->getOne($cid);
        $pedidoCardinal = $this->db->query("SELECT COUNT(*)+1 AS cardinal FROM pedido WHERE ano=:ano AND clienteId=:cid",
                [':ano'=>$ano, ':cid'=>$cid]);
        if($pedidoCardinal[0]->cardinal){
            return $cliente->codigo.($ano-2000).substr(($pedidoCardinal[0]->cardinal+1000), -3);    
        } else {
            return $cliente->codigo.($ano-2000).'000';
        }
        
    }
    
    
    private function checkObjFields($pid, $obj){
        $old = $this->getOne($pid);
        !isset($obj->clienteId) ? ($old->clienteId ? $obj->clienteId = $old->clienteId : $obj->clienteId = 'NULL') :null; 
        !isset($obj->ano) ? ($old->ano ? $obj->ano = $old->ano : $obj->ano = 'NULL') :null; 
        !isset($obj->refInterna) ? ($old->refInterna ? $obj->refInterna = $old->refInterna : $obj->refInterna = 'NULL') :null; 
        !isset($obj->refCliente) ? ($old->refCliente ? $obj->refCliente = $old->refCliente : $obj->refCliente = 'NULL') :null; 
        !isset($obj->tema) ? ($old->tema ? $obj->tema = $old->tema : $obj->tema = 'NULL') :null; 
        !isset($obj->descricao) ? ($old->descricao ? $obj->descricao = $old->descricao : $obj->descricao = 'NULL') :null; 
        !isset($obj->foto) ? ($old->foto ? $obj->foto = $old->foto : $obj->foto = 'NULL') :null; 
        !isset($obj->situacao) ? ($old->situacao ? $obj->situacao = $old->situacao : $obj->situacao = 'NULL') :null; 
        
        return $obj;
    }
}
