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
}
