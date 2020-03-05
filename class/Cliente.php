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

    public function getPrefix($id){

        return $this->db->query("SELECT codigo from cliente where id=:id", [':id'=>$id])[0]->codigo;
        
    }

    /**
     * 
     * @param type $obj
     * @return type
     */
    public function insert($obj) {
        return $this->db->query("INSERT INTO cliente(codigo, nome, email ,contacto, responsavel, imagem) "
                . " VALUES(:codigo, :nome, :email, :contacto, :responsavel, :imagem)",
                array(':codigo'=>$obj->codigo, ':nome'=>$obj->nome, ':email'=>$obj->email
                ,':contacto'=>$obj->contacto,':responsavel'=>$obj->responsavel, ':imagem'=>$obj->imagem));
    }
    /**
     * 
     * @param type $id
     * @param type $obj
     * @return type
     */
    public function update($id, $obj) {
        return $this->db->query("UPDATE cliente SET codigo=:codigo, nome=:nome, email=:email"
                . " ,contacto=:contacto, responsavel=:responsavel, imagem=:imagem WHERE id=:id",
                array(':codigo'=>$obj->codigo, ':nome'=>$obj->nome, ':email'=>$obj->email,
                    ':contacto'=>$obj->contacto, ':responsavel'=>$obj->responsavel, ':imagem'=>$obj->imagem,
                     ':id'=>$id));
    }
}
