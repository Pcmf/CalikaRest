<?php
require_once './db/DB.php';
/**
 * Description of Cor
 *
 * @author pedro
 */
class Escala {
    private $db;
    
    public function __construct() {
        $this->db = new DB();
    }
    /**
     * 
     * @return type
     */
    public function getAll() {
        return $this->db->query("SELECT * FROM escala");
    }
    /**
     * 
     * @param type $id
     * @return type
     */
    public function getOne($id) {
        return $this->db->query("SELECT * FROM escala WHERE id=:id", array(':id'=>$id));
    }  
    /**
     * 
     * @param type $obj
     * @return type
     */
    public function insert($obj) {
        return $this->db->queryInsert("INSERT INTO cor(nome, tamanhos) VALUES(:nome, :tamanhos)", 
                array(':nome'=>$obj->nome, ':ref'=>$obj->tamanhos));
    }
    /**
     * 
     * @param type $id
     * @param type $obj
     * @return type
     */
    public function update($id, $obj) {
        
        return $this->db->queryInsert("UPDATE cor SET nome=:nome, tamanhos=:tamanhos WHERE id=:id", 
                array(':nome'=>$obj->nome,':tamanhos'=>$obj->tamanhos,':id'=>$id));
    }
}
