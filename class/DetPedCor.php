<?php
require_once './db/DB.php';
/**
 * Description of DetPedCor
 *
 * @author pedro
 */
class DetPedCor {
    private $db;
    
    public function __construct() {
        $this->db = new DB();
    }
    /**
     * 
     * @return type
     */
    public function getAll() {
        return $this->db->query("SELECT * FROM detpedcor");
    }
    /**
     * 
     * @param type $id
     * @return type
     */
    public function getOne($pid,$mod,$lin) {
        return $this->db->query("SELECT * FROM detpedcor WHERE pedido=:pedido AND modelo=;modelo AND linha=:linha ", 
                array(':pedido'=>$pid, ':modelo'=>$mid, ':linha'=>$lin));
    }    
}
