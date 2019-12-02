<?php
require_once 'class/User.php';
require_once 'class/Artigo.php';
require_once 'class/Cliente.php';
require_once 'class/Cor.php';
require_once 'class/Elemento.php';
require_once 'class/DetPedCor.php';
require_once 'class/Modelo.php';
require_once 'class/Pedido.php';

function checkToken($token) {
    $obj = new User();
    return $obj->checkToken($token)->result;
}


$headers =apache_request_headers();
if($_GET['url'] != "auth" && checkToken($headers['token'])==0){
    http_response_code(401);
} else {


//POSTS
if  ($_SERVER['REQUEST_METHOD'] == "POST") {
        $postBody = file_get_contents("php://input");
        $postBody = json_decode($postBody);
    //LOG IN
    if ($_GET['url'] == "auth") {
        $user = new User();
        echo json_encode($user->checkUser($postBody->username, $postBody->password));
        http_response_code(200);
   } elseif ($_GET['url']=="artigos") {
       $ob = new Artigo();
       $resp = $ob->insert($postBody);
       if($resp){
           http_response_code(304);
       } else {
            http_response_code(200);
       }
       echo json_encode($resp);
   } elseif ($_GET['url']=="cor") {
       $ob = new Cor();
       $resp = $ob->insert($postBody);
       if($resp){
           echo json_encode($resp);
           http_response_code(304);
       } else {
           echo json_encode($resp);
            http_response_code(200);
       }
   } else {
       http_response_code(500);
   
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
            
        } elseif ($_GET['url'] == "modelo") {
            $ob = new Modelo();
            if(isset($_GET['mid'])) {
                echo json_encode($ob->getOne($_GET['pid'],$_GET['mid'],$_GET['ano']));
            } elseif(!isset($_GET['mid']) && isset ($_GET['pid'])) {
                echo json_encode($ob->getByPedido($_GET['pid'], $_GET['ano']));
            } else {
                echo json_encode($ob->getAll());
            }
            http_response_code(200);
            
        } elseif ($_GET['url'] == "pedidobysts") {
            $ob = new Pedido();
            echo json_encode($ob->getByCltYearSts($_GET['cid'], $_GET['ano'], $_GET['status']));
            http_response_code(200);
            
        }  elseif ($_GET['url'] == "pedido") {
            $ob = new Pedido();
            if(isset($_GET['ano'])) {
                echo json_encode($ob->getOne($_GET['pid'],$_GET['ano']));
            } else {
                echo json_encode($ob->getAll());
            }
            http_response_code(200);
        } 
// PUT  pedidobysts
} elseif ($_SERVER['REQUEST_METHOD'] == "PUT") {  
        $postBody = file_get_contents("php://input");
        $postBody = json_decode($postBody); 
        
        if($_GET['url']=="cor"){
            $ob = new Cor();
            $resp = $ob->update($_GET['id'], $postBody);
            if($resp){
                http_response_code(304);
            } else {
                http_response_code(200);
            }
            echo json_encode($resp);
        } elseif ($_GET['url']=="artigos") {
            $ob = new Artigo();
            $resp = $ob->update($_GET['id'], $postBody);
            if($resp){
                http_response_code(304);
            } else {
                http_response_code(200);
            }
            echo json_encode($resp);
    } else {
        http_response_code(401);
    }
} else {//Fim dos metodos 
    http_response_code(405);
}
}