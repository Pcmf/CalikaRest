<?php
require_once './db/DB.php';
/**
 * Description of Cor
 *
 * @author pedro
 */
class Cor {
    private $db;
    
    public function __construct() {
        $this->db = new DB();
    }
    /**
     * 
     * @return type
     */
    public function getAll() {
        return $this->db->query("SELECT * FROM cor");
    }
    /**
     * 
     * @param type $id
     * @return type
     */
    public function getOne($id) {
        return $this->db->query("SELECT * FROM cor WHERE id=:id", array(':id'=>$id));
    }  
    /**
     * 
     * @param type $obj
     * @return type
     */
    public function insert($obj) {
        return $this->db->queryInsert("INSERT INTO cor(nome, ref) VALUES(:nome, :ref)", 
                array(':nome'=>$obj->nome, ':ref'=>$obj->ref));
    }
    /**
     * 
     * @param type $id
     * @param type $obj
     * @return type
     */
    public function update($id, $obj) {
        
        return $this->db->queryInsert("UPDATE cor SET nome=:nome, ref=:ref WHERE id=:id", 
                array(':nome'=>$obj->nome,':ref'=>$obj->ref,':id'=>$id));
    }
}
