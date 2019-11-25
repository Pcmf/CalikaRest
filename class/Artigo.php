<?php
require_once './db/DB.php';
/**
 * Description of Artigo
 *
 * @author pedro
 */
class Artigo {
    private $db;
    
    public function __construct() {
        $this->db = new DB();
    }
    /**
     * 
     * @return type
     */
    public function getAll() {
        return $this->db->query("SELECT * FROM artigo");
    }
    /**
     * 
     * @param type $id
     * @return type
     */
    public function getOne($id) {
        return $this->db->query("SELECT * FROM artigo WHERE id=:id", array(':id'=>$id));
    } 
    /**
     * 
     * @param type $obj
     * @return type
     */
    public function insert($obj) {
        return $this->db->queryInsert("INSERT INTO artigo(nome, descricao) VALUES(:nome, :descricao)",
                array(':nome'=>$obj->nome, ':descricao'=>$obj->descricao));
    }
    /**
     * 
     * @param type $id
     * @param type $obj
     * @return type
     */
    public function update($id, $obj) {
        return $this->db->queryInsert("UPDATE artigo SET nome=:nome, descricao=:descricao WHERE id=:id ",
                array(':nome'=>$obj->nome, ':descricao'=>$obj->descricao, ':id'=>$id));
    }
}
