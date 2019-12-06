<?php

require_once './db/DB.php';
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

    public function __construct() {
        $this->db = new DB();
    }

    public function getAll() {
        return $this->db->query("SELECT M.*, P.tema, A.nome "
                        . " FROM modelo M "
                        . " INNER JOIN pedido P ON P.id=M.pedido AND P.ano=M.ano "
                        . " INNER JOIN artigo A ON A.id=M.artigo ");
    }

    public function getByPedido($pid) {
        return $this->db->query("SELECT M.*, A.nome "
                        . " FROM modelo M "
                        . " LEFT JOIN artigo A ON A.id=M.artigo "
                        . " WHERE M.pedido=:pid", array(':pid' => $pid));
    }

    public function getOne($pid, $ano, $mid) {
        return $this->db->query("SELECT M.*, P.tema, A.nome "
                        . " FROM modelo M "
                        . " INNER JOIN pedido P ON P.id=M.pedido AND P.ano=M.ano "
                        . " INNER JOIN artigo A ON A.id=M.artigo "
                        . " WHERE P.id=:pid AND M.ano=:ano AND M.id=:mid",
                        array(':pid' => $pid, ':ano' => $ano, ':mid' => $mid));
    }

    public function insert($obj) {
        $form = $obj->formulario;
        $pedido = $obj->pedido;
        !isset($form->refCliente) ? $form->refCliente = '' : null;
        !isset($form->preview) ? $form->preview = '' : null;
        !isset($form->descricao) ? $form->descricao = '' : null;
        !isset($form->preco) ? $form->preco = 0 : null;
        try{
            $result = $this->db->query("INSERT INTO modelo(ano,refinterna,refcliente,pedido,artigo,foto,descricao,preco,escala) "
                . " VALUES(:ano,:refinterna,:refcliente,:pedido,:artigo,:foto,:descricao,:preco, :escala)",
                [':ano' => $pedido->ano, ':refinterna' => $form->refInterna, ':refcliente' => $form->refCliente,
                    ':pedido' => $pedido->id, ':artigo' => $form->artigo->id, ':foto' => $obj->foto,
                    ':descricao' => $form->descricao, ':preco' => $form->preco, ':escala' => $form->escala->id]);
            return $this->db->lastInsertId();
        } catch (Exception $ex) {
            return $ex;
        }

    }

}
