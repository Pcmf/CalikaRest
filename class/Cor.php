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
}
