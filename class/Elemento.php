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
    
    public function insert($obj) {
        return $this->db->query("INSERT INTO elemento(nome, descricao) VALUES(:nome,:descricao)", 
                array(':nome'=>$obj->nome, ':descricao'=>$obj->descricao));
    }
    
    public function update($id,$obj) {
        return $this->db->query("UPDATE elemento SET nome=:nome, descricao=:descricao WHERE id=:id ", 
                array(':nome'=>$obj->nome, ':descricao'=>$obj->descricao, ':id'=>$id ));
    }
}
