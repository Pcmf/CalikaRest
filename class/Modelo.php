<?php

require_once './db/DB.php';
require_once 'Detalhe.php';
require_once 'Cliente.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Modelo
 *
 * @author pedro
 */
class Modelo {

    private $db;
    private $Det; //DetPedCor
    private $Cliente;

    public function __construct() {
        $this->db = new DB();
        $this->Det = new Detalhe();
        $this->Cliente = new Cliente();
    }
    /**
     * 
     * @return type
     */
    public function getAll() {
        return $this->db->query("SELECT M.*, P.tema, A.nome "
                        . " FROM modelo M "
                        . " INNER JOIN pedido P ON P.id=M.pedido AND P.ano=M.ano "
                        . " INNER JOIN artigo A ON A.id=M.artigo ");
    }

    public function getById($id) {
        return $this->db->query("SELECT M.*, A.nome "
                        . " FROM modelo M "
                        . " LEFT JOIN artigo A ON A.id=M.artigo "
                        . " WHERE M.id=:id", array(':id' => $id));
    }
    /**
     * 
     * @param type $pid
     * @return type
     */
    public function getByPedido($pid) {
        return $this->db->query("SELECT M.*, A.nome "
                        . " FROM modelo M "
                        . " LEFT JOIN artigo A ON A.id=M.artigo "
                        . " WHERE M.pedido=:pid", array(':pid' => $pid));
    }
    /**
     * 
     * @param type $pid
     * @param type $ano
     * @param type $mid
     * @return type
     */
    public function getOne($pid, $ano, $mid) {
        return $this->db->query("SELECT M.*, P.tema, A.nome "
                        . " FROM modelo M "
                        . " INNER JOIN pedido P ON P.id=M.pedido AND P.ano=M.ano "
                        . " INNER JOIN artigo A ON A.id=M.artigo "
                        . " WHERE P.id=:pid AND M.ano=:ano AND M.id=:mid",
                        array(':pid' => $pid, ':ano' => $ano, ':mid' => $mid));
    }
    
    /**
     * refInterna
     */
    public function getByRefInt($refInt) {
        return $this->db->query("SELECT M.*, P.tema, A.nome "
                        . " FROM modelo M "
                        . " INNER JOIN pedido P ON P.id=M.pedido AND P.ano=M.ano "
                        . " INNER JOIN artigo A ON A.id=M.artigo "
                        . " WHERE M.refinterna=:refint",
                        [':refint'=>$refInt]);        
    }

    /**
     * Obter modelos com todas as imagens     getByRefInt
     * @param type $pid
     * @return array
     */
    public function getMimgs($pid) {
        $modelos = array();
        $result = $this->db->query("SELECT M.*, A.nome "
                        . " FROM modelo M "
                        . " LEFT JOIN artigo A ON A.id=M.artigo "
                        . " WHERE M.pedido=:pid", array(':pid' => $pid));
        foreach ($result as $modelo) {
            $temp = array();
            $temp['modelo'] = $modelo;
            $temp['imgs'] = $this->getFotosByModelo($modelo->id);
            $temp['dpc'] = $this->Det->getByPidMid($pid, $modelo->id);
            array_push($modelos, $temp);
        }
        return $modelos;
    }

    /**
     * Obter os dados e imagens de um modelo
     */
    public function getAllImgs($mid){
        $modelos = array();
        $result = $this->db->query("SELECT M.*, A.nome "
                        . " FROM modelo M "
                        . " LEFT JOIN artigo A ON A.id=M.artigo "
                        . " WHERE M.id=:id", array(':id' => $mid));
        foreach ($result as $modelo) {
            $temp = array();
            $temp['modelo'] = $modelo;
            $temp['imgs'] = $this->getFotosByModelo($modelo->id);
            $temp['dpc'] = $this->Det->getByPidMid($modelo->pedido, $modelo->id);
            array_push($modelos, $temp);
        }
        return $modelos;
    }        
    

    public function getRefInt($cid, $ano)
    {
        $prefix = $this->Cliente->getPrefix($cid);
        $result = $this->db->query("SELECT MAX(SUBSTR(M.refinterna,LENGTH((SELECT codigo FROM cliente WHERE id=:cid))+1))+1 as maxlinha "
        ." FROM pedido P INNER JOIN modelo M ON M.pedido = P.id WHERE P.clienteId=:cid AND P.ano=:ano",
        [':cid'=>$cid, ':ano'=>$ano]);
        if($result[0]->maxlinha) {
            return $prefix.$result[0]->maxlinha;
        } else {
            
            return $prefix.substr($ano,2).'001';
        }
    }
    
    /**
     * 
     * @param type $obj
     * @return \Exception
     */
    public function insert($obj) {
        $form = $obj->formulario;
        $pedido = $obj->pedido;
        !isset($form->refCliente) ? $form->refCliente = '' : null;
        !isset($form->preview) ? $form->preview = '' : null;
        !isset($form->descricao) ? $form->descricao = '' : null;
        !isset($form->preco) ? $form->preco = 0 : null;
        !isset($obj->foto) ? $obj->foto = '' : null;
        try{
            $result = $this->db->queryInsert("INSERT INTO modelo(ano,refinterna,refcliente,pedido,artigo,foto,descricao,preco,escala) "
                . " VALUES(:ano,:refinterna,:refcliente,:pedido,:artigo,:foto,:descricao,:preco, :escala)",
                [':ano' => $pedido->ano, ':refinterna' => $form->refInterna, ':refcliente' => $form->refCliente,
                    ':pedido' => $pedido->id, ':artigo' => $form->artigo, ':foto' => $obj->foto,
                    ':descricao' => $form->descricao, ':preco' => $form->preco, ':escala' => $form->escala]);
            return $this->db->lastInsertId();
        } catch (Exception $ex) {
            return $ex;
        }
        
    }
    
    /**
     * 
     * @param type $id
     * @param type $param
     * @return type
     */
    public function update($id, $param) {
        if(isset($param->formulario)) {
            $obj = $param->formulario;
        } else {
            $obj = $param;
        }
        !isset($obj->artigo) ? $obj->artigo="": null;
        !isset($obj->refCliente) ? $obj->refCliente="" : null;
        !isset($obj->preco) ? $obj->preco=0 : null;
        !isset($obj->foto) ? $obj->foto="": null;
        !isset($obj->refInterna) ? $obj->refInterna="": null;
        !isset($obj->obsinternas) ? $obj->obsinternas="": null;
        !isset($obj->escala) ? $obj->escala="": null;
        try {
            return $this->db->query("UPDATE modelo SET refinterna=:refinterna, refcliente=:refcliente, artigo=:artigo,"
                . " preco=:preco, escala=:escala, foto=:foto, obsinternas=:obsinternas WHERE id=:id",
                [':refinterna'=>$obj->refInterna,':refcliente'=>$obj->refCliente, ':artigo'=>$obj->artigo,
                 ':preco'=>$obj->preco, ':escala'=>$obj->escala, ':foto'=>$param->foto, ':obsinternas'=>$obj->obsinternas, ':id'=>$id]);
        } catch (Exception $ex) {
            return $ex;
        }
    }
    
    /**
     * @delete one modelo from one pedido
     * @param type $mid  - id do modelo
     * @return type
     */
    public function deleteOne($mid) {
        try {
            $this->deleteImagensModelos($mid);
            $this->deleteDetPedCor($mid);
            return $this->db->query("DELETE FROM modelo WHERE id=:id", [':id'=>$mid]);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    /**
     * Apaga todos os modelos de um pedido
     * @param type $pid
     * @return type
     */
    public function deleteAll($pid) {
        $result = $this->db->query("SELECT id FROM modelo WHERE pedido=:pid", ['pid'=>$pid]);
        foreach ($result as $modelo) {
            $this->deleteImagensModelos($modelo->id);
        }
        try {
            // TO DO - obter os modelos de um pedido e eliminar as imagens de cada um
            return $this->db->queryDelete("DELETE FROM modelo WHERE pedido=:pid", [':pid'=>$pid]);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
            
    }
    
    private function deleteDetPedCor($mid) {
          try {
            $this->db->query("DELETE FROM detpedcor WHERE modelo=:mid",[':mid'=>$mid]);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }      
    }
    
    private function deleteImagensModelos($mid) {
        try {
            $this->db->query("DELETE FROM modeloimagens WHERE id=:mid",[':mid'=>$mid]);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }

        
    }
    /**
     * 
     * @param type $mid
     * @return type
     */
    public function getFotosByModelo($mid) {
        return $this->db->query("SELECT * FROM modeloimagens WHERE id=:mid ", [':mid'=>$mid]);
    }
    
    public function getOneFotoByModelo($mid, $linha) {
        return $this->db->query("SELECT * FROM modeloimagens WHERE id=:mid AND linha=:linha ", [':mid'=>$mid, ':linha'=>$linha]);
    }
    /**
     * 
     * @param type $mid
     * @param type $foto
     * @return type
     */
    public function saveFotoByModelo($mid, $fotos) {
        foreach ($fotos AS $foto){
            try {
//                $result =$this->db->query("SELECT MAX(A.linha)+1 AS linha FROM modeloimagens A WHERE id=:mid",[':mid'=>$mid]);
//                $result[0]->linha ? $linha = $result[0]->linha : $linha=1;
                $linha = $this->getFotoLinha($mid);
                $this->db->queryInsert("INSERT INTO modeloimagens(id, linha, foto) "
                        . " VALUES(:mid, :linha, :foto)",
                        [':mid'=>$mid, ':linha'=>$linha, ':foto'=>$foto]);

            } catch (Exception $exc) {
                return $exc->getTraceAsString();
            }
        }
    }
    /**
     * 
     * @param type $mid
     * @param type $linha
     * @param type $foto
     * @return type
     */
    public function changeFotoBymodelo($mid, $linha, $foto) {
        try {
            return $this->db->query("UPDATE modeloimagens SET foto=:foto WHERE id=:mid AND linha=:linha ",
                    [':mid'=>$mid, ':linha'=>$linha, ':foto'=>$foto]);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    /**
     * 
     * @param type $mid
     * @param type $linha
     * @return type
     */
    public function deleteOneFoto($mid, $linha) {
        try {
            $this->db->queryDelete("DELETE FROM modeloimagens WHERE id=:mid AND linha=:linha",
                    [':mid'=>$mid, ':linha'=>$linha]);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
     }
     /**
      * 
      * @param type $mid
      * @return type
      */
    public function deleteAllFotos($mid) {
        try {
            return $this->db->queryDelete("DELETE FROM modeloimagens WHERE id=:mid ",
                    [':mid'=>$mid]);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
     }
    
        /**
     * 
     * @param type $cid
     * @param type $ano
     * @return type
     */
        public function getRefInterna($pid) {
            $result = $this->db->query("SELECT id, refInterna FROM modelo WHERE pedido=:pid ORDER BY id DESC LIMIT 1", [':pid'=>$pid]);
            if($result){
                return substr($result[0]->refInterna, 0, strlen($result[0]->refInterna)-3)
                        .substr(((intval(substr($result[0]->refInterna, -3)))+1)+1000, -3);
            } else {
                 $result = $this->db->query("SELECT refInterna FROM pedido WHERE id=:pid", [':pid'=>$pid]);
                 return $result[0]->refInterna;
            }
        }
        
        
        public function updateFoto($id, $foto){
            return $this->db->query("UPDATE modelo SET foto=:foto WHERE id=:id", 
                    [':id'=>$id, ':foto'=>$foto]);
        }


        public function updateFotoExt($id, $foto){
            $linha = $this->getFotoLinha($id);
            try {
                return $this->db->query("INSERT into modeloimagens SET id=:id, linha=:linha, foto=:foto", 
                    [':id'=>$id,':linha'=>$linha, ':foto'=>$foto]);
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }


        }   
        
        
        private function getFotoLinha($mid){
                $result =$this->db->query("SELECT MAX(A.linha) AS linha FROM modeloimagens A WHERE id=:mid",[':mid'=>$mid]);
                $result[0]->linha ? $linha = $result[0]->linha +1 : $linha=1;
                return $linha;
        }

}

