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
        return $this->db->query("SELECT * FROM cliente WHERE id=:id", array(':id'=>$id))[0];
    } 
    /**
     * 
     * @param type $obj
     * @return type
     */
    public function insert($obj) {
        return $this->db->query("INSERT INTO cliente(codigo, valorinicial, nome, email ,contacto,responsavel, pasta, logo) "
                . " VALUES(:codigo, :valorinicial, :nome, :email, :contacto, :responsavel, :pasta, :logo)",
                array(':codigo'=>$obj->codigo, ':valorinicial'=>$obj->valorinicial, ':nome'=>$obj->nome, ':email'=>$obj->email
                ,':contacto'=>$obj->contacto,':responsavel'=>$obj->responsavel, ':pasta'=>$obj->pasta, ':logo'=>$obj->logo));
    }
    /**
     * 
     * @param type $id
     * @param type $obj
     * @return type
     */
    public function update($id, $obj) {
        return $this->db->query("UPDATE cliente SET codigo=:codigo, valorinicial=:valorinicial, nome=:nome, email=:email"
                . " ,contacto=:contacto, responsavel=:responsavel, pasta=:pasta, logo=:logo WHERE id=:id",
                array(':codigo'=>$obj->codigo, ':valorinicial'=>$obj->valorinicial, ':nome'=>$obj->nome, ':email'=>$obj->email,
                    ':contacto'=>$obj->contacto, ':responsavel'=>$obj->responsavel, ':pasta'=>$obj->pasta, ':logo'=>$obj->logo, ':id'=>$id));
    }
}
