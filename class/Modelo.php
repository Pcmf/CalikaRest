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
class Modelo {
    private $db;
    
    public function __construct() {
        $this->db = new DB();
    }
    public function getAll() {
            return $this->db->query("SELECT M.*, P.tema, A.nome "
                . " FROM modelo M "
                . " INNER JOIN pedido P ON P.id=M.pedido AND P.ano=M.ano "
                . " INNER JOIN artigo A ON A.id=M.artigo ");    
    }
    
    public function getByPedido($pid) {
        return $this->db->query("SELECT M.*, A.nome "
                . " FROM modelo M "
                . " LEFT JOIN artigo A ON A.id=M.artigo "
                . " WHERE M.pedido=:pid", array(':pid'=>$pid));
    }
    
    public function getOne($pid, $ano, $mid) {
        return $this->db->query("SELECT M.*, P.tema, A.nome "
                . " FROM modelo M "
                . " INNER JOIN pedido P ON P.id=M.pedido AND P.ano=M.ano "
                . " INNER JOIN artigo A ON A.id=M.artigo "
                . " WHERE P.id=:pid AND M.ano=:ano AND M.id=:mid",
                array(':pid'=>$pid, ':ano'=>$ano, ':mid'=>$mid) );
    }
}
