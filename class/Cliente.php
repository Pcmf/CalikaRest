<?php
require_once './db/DB.php';
/**
 * Description of Cliente
 *
 * @author pedro
 */
class Cliente {
    private $db;
    
    public function __construct() {
        $this->db = new DB();
    }
    /**
     * 
     * @return type
     */
    public function getAll() {
        return $this->db->query("SELECT * FROM cliente");
    }
    /**
     * 
     * @param type $id
     * @return type
     */
    public function getOne($id) {
        return $this->db->query("SELECT * FROM cliente WHERE id=:id", array(':id'=>$id));
    }    
}
