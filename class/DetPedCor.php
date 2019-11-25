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
    public function getPidModLin($pid,$mod,$lin) {
        return $this->db->query("SELECT * FROM detpedcor WHERE pedido=:pedido AND modelo=:modelo AND linha=:linha ", 
                array(':pedido'=>$pid, ':modelo'=>$mid, ':linha'=>$lin));
    }
    /**
     * 
     * @param type $pid
     * @param type $mod
     * @return type
     */
    public function getPidMod($pid, $mod) {
        return $this->db->query("SELECT * FROM detpedcor WHERE pedido=:pedido AND modelo=:modelo", 
                array(':pedido'=>$pid, ':modelo'=>$mid));
    }   
    /**
     * 
     * @param type $pid
     * @return type
     */
    public function getPid($pid) {
        return $this->db->query("SELECT * FROM detpedcor WHERE pedido=:pedido", 
                array(':pedido'=>$pid));
    } 
    /**
     * 
     * @param type $obj
     * @return type
     */
    public function insertLin($obj) {
        $result = $this->db->query("SELECT max(linha) AS lin FROM detpedcor WHERE pedido=:pid AND modelo=:mid ",
                array(':pid'=>$obj->pid, ':mid'=>$obj->mid));
        $linha = 0;
        if($result){
            $linha = $result[0]['lin']+1;
        }
        return $this->db->query("INSERT INTO detpedcor(pedido, modelo, linha, cor1, cor2, elem1,elem2, elem3, qtys) "
                . " VALUES(:pedido, :modelo, :linha, :cor1, :cor2, :elem1, :elem2, :elem3, :qtys)",
                array(':pedido'=>$obj->pedido, ':modelo'=>$obj->modelo, ':linha'=>$linha, ':cor1'=>$obj->cor1,
                    ':cor2'=>$obj->cor2, ':elem1'=>$obj->elem1,':elem2'=>$obj->elem2, ':elem3'=>$obj->elem3, ':qtys'=>$obj->qtys));
    }
    /**
     * 
     * @param type $linha
     * @param type $obj
     * @return type
     */
    public function update($linha, $obj) {
        return $this->db->query("UPDATE detpedcor SET cor1=:cor1, cor2=:cor2, elem1=:elem1, elem2=:elem2, elem3=:elem3, qtys=:qtys "
                . " WHERE pedido=:pid AND modelo=:mid AND linha=:lin ",
                array(':pid'=>$obj->pedido, ':mid'=>$obj->modelo, ':linha'=>$linha,':cor1'=>$obj->cor1, ':cor2'=>$obj->cor2,
                    ':elem1'=>$obj->elem1, ':elem2'=>$obj->elem2, ':elem3'=>$obj->elem3, ':qtys'=>$obj->qtys));
    }
}
