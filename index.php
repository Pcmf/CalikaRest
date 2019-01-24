<?php
require_once 'class/User.php';
require_once 'class/Artigo.php';
require_once 'class/Cliente.php';
require_once 'class/Cor.php';
require_once 'class/Elemento.php';
require_once 'class/DetPedCor.php';

//POSTS
if  ($_SERVER['REQUEST_METHOD'] == "POST") {
            $postBody = file_get_contents("php://input");
        $postBody = json_decode($postBody);
    //LOG IN
    if ($_GET['url'] == "auth") {
        $user = new User();
        echo $user->checkUser($postBody->username, $postBody->pass);
        http_response_code(200);
   } 
   
// GETS   
} elseif ($_SERVER['REQUEST_METHOD'] == "GET") {
        if ($_GET['url'] == "artigos") {
            $ob = new Artigo();
            if(isset($_GET['id'])){
                echo json_encode($ob->getOne($_GET['id']));
            } else {
                echo json_encode($ob->getAll());
            }
            http_response_code(200);
            
        } elseif ($_GET['url'] == "users") {
            $ob = new User();
            if(!isset($_GET['id'])) {
                echo json_encode($ob->getAll());
            } else {
                echo json_encode($ob->getOne($_GET['id']));
            }
            http_response_code(200);
            
        } elseif ($_GET['url'] == "clientes") {
            $ob = new Cliente();
            if(!isset($_GET['id'])) {
                echo json_encode($ob->getAll());
            } else {
                echo json_encode($ob->getOne($_GET['id']));
            }
            http_response_code(200);
            
        } elseif ($_GET['url'] == "cores") {
            $ob = new Cor();
            if(!isset($_GET['id'])) {
                echo json_encode($ob->getAll());
            } else {
                echo json_encode($ob->getOne($_GET['id']));
            }
            http_response_code(200);
            
        } elseif ($_GET['url'] == "elementos") {
            $ob = new Elemento();
            if(!isset($_GET['id'])) {
                echo json_encode($ob->getAll());
            } else {
                echo json_encode($ob->getOne($_GET['id']));
            }
            http_response_code(200);
            
        }  elseif ($_GET['url'] == "detpedcor") {
            $ob = new DetPedCor();
            if(!isset($_GET['pid'])) {
                echo json_encode($ob->getAll());
            } else {
                echo json_encode($ob->getOne($_GET['pid'], $_GET['mod'], $_GET['lin']));
            }
            http_response_code(200);
        }   
} else {//Fim dos metodos 
    http_response_code(405);
}
