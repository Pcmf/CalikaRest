<?php
    require_once './db/DB.php';
/**
 * Description of detalhe
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
        return $this->db->query("SELECT * FROM detalhe");
    }
    /**
     * 
     * @param type $id
     * @return type
     */
    public function getPidModLin($pid,$mid,$lin) {
        return $this->db->query("SELECT * FROM detalhe WHERE pedido=:pedido AND modelo=:modelo AND linha=:linha ", 
                array(':pedido'=>$pid, ':modelo'=>$mid, ':linha'=>$lin));
    }
    /**
     * 
     * @param type $pid
     * @param type $mod
     * @return type
     */
    public function getPidMod($pid, $mid) {
        $result = $this->db->query("SELECT D.*, C.nome AS cor_1, C2.nome AS cor_2"
                . " FROM detalhe D "
                . " LEFT JOIN cor C ON D.cor1=C.id "
                . " LEFT JOIN cor C2 ON D.cor2=C2.id "
                . " WHERE D.pedido=:pedido AND D.modelo=:modelo", 
                array(':pedido'=>$pid, ':modelo'=>$mid));
        $resp = array();
        foreach ($result AS $row1){
            $row1->elem1 = json_decode($row1->elem1);
            $row1->elem2 = json_decode($row1->elem2);
            $row1->elem3 = json_decode($row1->elem3);
            $row1->qtys = json_decode($row1->qtys);
            array_push($resp, $row1);
        }
        return $resp;
    }   
    /**
     * 
     * @param type $pid
     * @return type
     */
    public function getPid($pid) {
        return $this->db->query("SELECT * FROM detalhe WHERE pedido=:pedido", 
                array(':pedido'=>$pid));
    } 
    /**
     * 
     * @param type $obj
     * @return type
     */
    public function insertLin($pid, $mid, $lin, $obj) {
        $result = $this->db->query("SELECT max(linha) AS linha, count(*) AS linhas FROM detalhe WHERE pedido=:pid AND modelo=:mid ",
                array(':pid'=>$pid, ':mid'=>$mid));
        $linha = 0;
        $linhas = 0; 
        if($result){
            $linha = $result[0]->linha +1;
        }
        if($obj->linha=="") {
            $this->db->queryInsert("INSERT INTO detalhe(pedido, modelo, linha, cor1, cor2, elem1,elem2, elem3, qtys) "
                . " VALUES(:pedido, :modelo, :linha, :cor1, :cor2, :elem1, :elem2, :elem3, :qtys)",
                array(':pedido'=>$obj->pedido, ':modelo'=>$obj->modelo, ':linha'=>$linha, ':cor1'=>$obj->cor1,
                    ':cor2'=>$obj->cor2, ':elem1'=>json_encode($obj->elem1), ':elem2'=>json_encode($obj->elem2),
                    ':elem3'=>json_encode($obj->elem3), ':qtys'=>json_encode($obj->qtys)));
            return $linha;
        } else {
            return $this->update($obj->linha, $obj);
        }
    }
    /**
     * 
     * @param type $linha
     * @param type $obj
     * @return type
     */
    public function update($linha, $obj) {
        $this->db->query("UPDATE detalhe SET cor1=:cor1, cor2=:cor2, elem1=:elem1, elem2=:elem2, elem3=:elem3, qtys=:qtys "
                . " WHERE pedido=:pid AND modelo=:mid AND linha=:linha ",
                array(':pid'=>$obj->pedido, ':mid'=>$obj->modelo, ':linha'=>$linha,':cor1'=>$obj->cor1, ':cor2'=>$obj->cor2,
                    ':elem1'=>json_encode($obj->elem1), ':elem2'=>json_encode($obj->elem2),
                    ':elem3'=>json_encode($obj->elem3), ':qtys'=>json_encode($obj->qtys)));
        return $linha;
    }
    
    
    public function deleteLine($pid, $mid, $lin) {
        return $this->db->query("DELETE FROM detalhe WHERE pedido=:pid AND modelo=:mid AND linha=:lin ",
                [':pid'=>$pid, ':mid'=>$mid, ':lin'=>$lin]);
    }
}
