<?php

require_once './db/DB.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Convertor
 *
 * @author pedro
 */
class Convertor {

    private $db;

    public function __construct() {
        $this->db = new DB();
    }

    public function converterClientes() {
        $result = $this->db->query("SELECT id, logo FROM cliente");

        foreach ($result AS $ln) {
            $path = 'C:/Angular/CalikaBaby/src/assets/img/logos/' . $ln->logo;
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            $this->db->query("UPDATE cliente SET imagem=:imagem WHERE id=:id",
                    [':imagem' => $base64, ':id' => $ln->id]);
        }
    }

    public function converterPedidos() {
        $result = $this->db->query("SELECT id, imagem FROM pedido");

        foreach ($result AS $ln) {
            $path = 'C:/Angular/CalikaBaby/src/assets/img/temas/' . $ln->imagem;
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            $this->db->query("UPDATE pedido SET foto=:foto WHERE id=:id",
                    [':foto' => $base64, ':id' => $ln->id]);
        }
    }
    
    public function converterModelo() {
        $result = $this->db->query("SELECT id, mainimg, imagens FROM modelo");

        foreach ($result AS $ln) {
            $path = 'C:/Angular/CalikaBaby/src/assets/img/modelos/'.$ln->mainimg;
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            $this->db->query("UPDATE pedido SET foto=:foto WHERE id=:id",
                    [':foto'=>$base64, ':id'=>$ln->id]);
            $imagens = json_decode($ln->imagens);
            if($imagens){
                $linha = 1;
                foreach ($imagens as $img) {
                    $path2 = 'C:/Angular/CalikaBaby/src/assets/img/modelos/'. $img;
                    $type2 = pathinfo($path2, PATHINFO_EXTENSION);
                    $data2 = file_get_contents($path2);
                    $base642 = 'data:image/' . $type2 . ';base64,' . base64_encode($data2);
                    $this->db->query("INSERT INTO modeloimagens(id, linha, foto) VALUES(:id, :linha, :foto) ",
                    [':foto'=>$base642, ':id'=>$ln->id, ':linha'=>$linha++ ] );
                }
            }
        }
    }

}
