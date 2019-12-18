<?php
require_once './db/DB.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MyEmpresa
 *
 * @author pedro
 */
class MyEmpresa {
    private $db;
    
    public function __construct() {
        $this->db = new DB();
    }
    
    public function getAll() {
        return $this->db->query("SELECT * FROM myempresa")[0];
    }
}
