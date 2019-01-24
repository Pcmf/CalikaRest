<?php
require_once './db/DB.php';
/**
 * Description of Elemento
 *
 * @author pedro
 */
class Elemento {
    private $db;
    
    public function __construct() {
        $this->db = new DB();
    }
    /**
     * 
     * @return type
     */
    public function getAll() {
        return $this->db->query("SELECT * FROM elemento");
    }
    /**
     * 
     * @param type $id
     * @return type
     */
    public function getOne($id) {
        return $this->db->query("SELECT * FROM elemento WHERE id=:id", array(':id'=>$id));
    }    
}
