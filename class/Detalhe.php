<?php
require_once './db/DB.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Detalhe
 *
 * @author pedro
 */
class Detalhe {
    private $db;
    
    public function __construct() {
        $this->db = new DB();
    }
    /**
     * Obtem uma linha de um pedido/modelo
     * @param type $pid
     * @param type $mid
     * @param type $lin
     * @return type
     */
    public function getByPidMidLin($pid, $mid, $lin){
        $result = $this->db->query("SELECT * FROM detalhe WHERE pedido=:pid AND modelo=:mid ANDlinha=:lin",
                [':pid'=>$pid, ':mid'=>$mid, ':lin'=>lin]);
        $result[0]->qtys = json_decode($result[0]->qtys);
    }
    /**
     * Obtem as linhas de um pedido/modelo
     * @param type $pid
     * @param type $mid
     * @return type
     */
    public function getByPidMid($pid, $mid) {
        $result = $this->db->query("SELECT D.*, C1.nome AS ncor1, C2.nome AS ncor2,"
                . " E1.nome AS nelem1, CE1.nome AS nelem1cor,"
                . " E2.nome AS nelem2, CE2.nome AS nelem2cor,"
                . " E3.nome AS nelem3, CE3.nome AS nelem3cor"
                . " FROM detalhe D"
                . " LEFT JOIN cor C1 ON C1.id=D.cor1"
                . " LEFT JOIN cor C2 ON C2.id=D.cor2"
                . " LEFT JOIN elemento E1 ON E1.id=D.elem1"
                . " LEFT JOIN cor CE1 ON CE1.id=D.elem1cor"
                . " LEFT JOIN elemento E2 ON E2.id=D.elem2"
                . " LEFT JOIN cor CE2 ON CE2.id=D.elem2cor"
                . " LEFT JOIN elemento E3 ON E3.id=D.elem3"
                . " LEFT JOIN cor CE3 ON CE3.id=D.elem3cor"                
                . " WHERE pedido=:pid AND modelo=:mid",
                [':pid'=>$pid, ':mid'=>$mid]);  
        $resp = array();
        foreach ($result AS $ln){
            $ln->qtys = json_decode($ln->qtys);
            array_push($resp,$ln);
        }
        return $resp;
    }
    /**
     * Obtem todas as linhas de um pedido
     * @param type $pid
     * @return type
     */
    public function getByPid($pid) {
        return $this->db->query("SELECT * FROM detalhe WHERE pedido=:pid",
                [':pid'=>$pid]);        
    }    
    /**
     * Insere uma linha
     * @param type $pid
     * @param type $mid
     * @param type $obj
     * @return type
     */
    public function insertLine($pid, $mid, $obj) {
        $linha = $this->getLine($pid, $mid);
        // Verificar obj
        $obj = $this->checkObj($obj);
        try {
            $this->db->queryInsert("INSERT INTO detalhe(pedido, modelo, linha, cor1, cor2,"
                . " elem1, elem1cor, elem2, elem2cor, elem3, elem3cor, qtys) "
                . "VALUES(:pid, :mid, :linha, :cor1, :cor2, :elem1, :elem1cor, :elem2, :elem2cor,"
                . " :elem3, :elem3cor, qtys=:qtys)",
                [':pid'=>$pid, ':mid'=>$mid, ':linha'=>$linha, ':cor1'=>$obj->cor1,
                    ':cor2'=>$obj->cor2, ':elem1'=>$obj->elem1, ':elem1cor'=>$obj->elem1cor,
                    ':elem2'=>$obj->elem2, ':elem2cor'=>$obj->elem2cor, ':elem3'=>$obj->elem3,
                    ':elem3cor'=>$obj->elem3cor, ':qtys'=> json_encode($obj->qtys) ]);
            return $linha;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
            return null;
        }
        
    }
    
    /**
     * Atualiza uma linha do detalhe
     * @param type $pid
     * @param type $mid
     * @param type $lin
     * @param type $obj
     * @return type
     */
    public function updateLine($pid, $mid, $lin, $obj) {
        $obj = $this->checkObj($obj);
        return $this->db->query("UPDATE detalhe SET cor1=:cor1, cor2=:cor2, elem1=:elem1, elem1cor=:elem1cor,"
                . " elem2=:elem2, elem2cor=:elem2cor, elem3=:elem3, elem3cor=:elem3cor, qtys=:qtys "
                . " WHERE pedido=:pid AND modelo=:mid AND linha=:linha ",
                [':pid'=>$pid, ':mid'=>$mid, ':linha'=>$lin, ':cor1'=>$obj->cor1,
                    ':cor2'=>$obj->cor2, ':elem1'=>$obj->elem1, ':elem1cor'=>$obj->elem1cor,
                    ':elem2'=>$obj->elem2, ':elem2cor'=>$obj->elem2cor, ':elem3'=>$obj->elem3, 
                    ':elem3cor'=>$obj->elem3cor, ':qtys'=> json_encode($obj->qtys)]);
        
    }
    
    /**
     * Delete by linha
     * @param type $pid
     * @param type $mid
     * @param type $lin
     * @return type
     */
    public function deleteByLine($pid, $mid, $lin) {
        return $this->db->query("DELETE FROM detalhe WHERE pedido=:pid AND modelo=:mid AND linha=:lin ",
                [':pid'=>$pid, ':mid'=>$mid, ':linha'=>$lin]);
    }
    /**
     * Delete by Modelo
     * @param type $pid
     * @param type $mid
     * @return type
     */
    public function deleteByModelo($pid, $mid) {
        return $this->db->query("DELETE FROM detalhe WHERE pedido=:pid AND modelo=:mid ",
                [':pid'=>$pid, ':mid'=>$mid]);        
    }
    /**
     * Delete by Pedido
     * @param type $pid
     * @return type
     */
    public function deleteByPedido($pid) {
        return $this->db->query("DELETE FROM detalhe WHERE pedido=:pid",
                [':pid'=>$pid]);        
    }
    
    
    /**
     * obter a linha seguinte
     * @param type $pid
     * @param type $mid
     * @return int
     */
    private function getLine($pid, $mid){
        $result = $this->db->query("SELECT max(linha) AS linha FROM detalhe WHERE pedido=:pid AND modelo=:mid",
                [':pid'=>$pid, ':mid'=>$mid]); 
        if($result[0]->linha){
            return $result[0]->linha+1;
        }
        return 1;
    }
    /**
     * Validar os campos que possam não estar definidos
     * @param type $obj
     * @return type
     */
    private function checkObj($obj){
        !isset($obj->cor1) ? $obj->cor1= 0 : null;
        !isset($obj->cor2) ? $obj->cor2= 0 : null;
        !isset($obj->elem1) ? $obj->elem1= 0 : null;
        !isset($obj->elem1cor) ? $obj->elem1cor= 0 : null;
        !isset($obj->elem2) ? $obj->elem2= 0 : null;
        !isset($obj->elem2cor) ? $obj->elem2cor= 0 : null;
        !isset($obj->elem3) ? $obj->elem3= 0 : null;
        !isset($obj->elem3cor) ? $obj->elem3cor= 0 : null;   
        !isset($obj->qtys) ? $obj->qtys= '{}' : null;   
        return $obj;
    }
}
