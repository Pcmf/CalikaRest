<?php
require_once 'configs.php';
/**
 * Description of newPHPClass
 *
 * @author pedro
 */
class DB {
    private $pdo;
    private $lastId;
    
    public function __construct() {
        $pdo = new PDO('mysql:host='.host.';dbname='.database.';charset=utf8',username,password);
//        $pdo = new PDO('firebird:dbname=localhost:C:\\Users\pedro\Documents\JSalgado\Backoffice IPATIMUP\APP.FDB;host=localhost','SYSDBA','masterkey');
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $this->pdo = $pdo;
    }
    
    public function query($query,$params=array()){
        $statement = $this->pdo->prepare($query);
        $statement->execute($params);
        
        if(explode(' ',$query)[0]=='SELECT'){
            $data = $statement->fetchAll(PDO::FETCH_OBJ);
            return $data;
        }
    }
    
    public function queryInsert($query, $params=array()) {
        try {
            $this->pdo->beginTransaction();
            $statement = $this->pdo->prepare($query);
            $statement->execute($params);
            $this->lastId = $this->pdo->lastInsertId();
            $this->pdo->commit();
            
        } catch (Exception $exc) {
            $this->pdo->rollBack();
            return $exc;
        }
        
    }
    
    public function lastInsertId() {
        return $this->lastId;
    }
    
    public function queryDelete($query,$params=array()) {
        try {
            $this->pdo->beginTransaction();
            
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            $this->pdo->commit();
            
        } catch (Exception $exc) {
            $this->pdo->rollBack();
            return $exc->getTraceAsString();
        }
    }
}
