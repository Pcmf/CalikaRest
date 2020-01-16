<?php

require_once 'class/User.php';
require_once 'class/MyEmpresa.php';
require_once 'class/Artigo.php';
require_once 'class/Cliente.php';
require_once 'class/Cor.php';
require_once 'class/Elemento.php';
require_once 'class/Escala.php';
require_once 'class/DetPedCor.php';
require_once 'class/Modelo.php';
require_once 'class/Pedido.php';
require_once 'class/Detalhe.php';

require_once 'class/Convertor.php';

function checkToken($token) {
    $obj = new User();
    return $obj->checkToken($token)->result;
}

$headers = apache_request_headers();
if ($_GET['url'] != "auth" && checkToken($headers['token']) == 0) {
    http_response_code(401);
} else {


//POSTS
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $postBody = file_get_contents("php://input");
        $postBody = json_decode($postBody);
        //LOG IN
        if ($_GET['url'] == "auth") {
            $user = new User();
            echo json_encode($user->checkUser($postBody->username, $postBody->password));
            http_response_code(200);
        } elseif ($_GET['url'] == "artigos") {
            $ob = new Artigo();
            $resp = $ob->insert($postBody);
            if ($resp) {
                http_response_code(304);
            } else {
                http_response_code(200);
            }
            echo json_encode($resp);
        } elseif ($_GET['url'] == "cor") {
            $ob = new Cor();
            $resp = $ob->insert($postBody);
            if ($resp) {
                echo json_encode($resp);
                http_response_code(304);
            } else {
                echo json_encode($resp);
                http_response_code(200);
            }
        } elseif ($_GET['url'] == "modelos") {
            $ob = new Modelo();
            echo json_encode($ob->insert($postBody));
            http_response_code(200);
        } elseif ($_GET['url'] == "det") {
            $ob = new Detalhe();
            echo json_encode($ob->insertLine($_GET['pid'], $_GET['mid'], $postBody));
            http_response_code(200);
        } else {
            http_response_code(500);
        }



// GETS   
    } elseif ($_SERVER['REQUEST_METHOD'] == "GET") {

        if ($_GET['url'] == "artigos") {
            $ob = new Artigo();
            if (isset($_GET['id'])) {
                echo json_encode($ob->getOne($_GET['id']));
            } else {
                echo json_encode($ob->getAll());
            }
            http_response_code(200);
        } elseif ($_GET['url'] == "users") {
            $ob = new User();
            if (!isset($_GET['id'])) {
                echo json_encode($ob->getAll());
            } else {
                echo json_encode($ob->getOne($_GET['id']));
            }
            http_response_code(200);
        } elseif ($_GET['url'] == "clientes") {
            $ob = new Cliente();
            if (!isset($_GET['id'])) {
                echo json_encode($ob->getAll());
            } else {
                echo json_encode($ob->getOne($_GET['id']));
            }
            http_response_code(200);
        } elseif ($_GET['url'] == "cores") {
            $ob = new Cor();
            if (!isset($_GET['id'])) {
                echo json_encode($ob->getAll());
            } else {
                echo json_encode($ob->getOne($_GET['id']));
            }
            http_response_code(200);
        } elseif ($_GET['url'] == "elementos") {
            $ob = new Elemento();
            if (!isset($_GET['id'])) {
                echo json_encode($ob->getAll());
            } else {
                echo json_encode($ob->getOne($_GET['id']));
            }
            http_response_code(200);
        } elseif ($_GET['url'] == "escalas") {
            $ob = new Escala();
            if (!isset($_GET['id'])) {
                echo json_encode($ob->getAll());
            } else {
                echo json_encode($ob->getOne($_GET['id']));
            }
            http_response_code(200);
        } elseif ($_GET['url'] == "det") {
            $ob = new Detalhe();
            if (isset($_GET['lin'])) {
                echo json_encode($ob->getByPidMidLin($_GET['pid'], $_GET['mid'], $_GET['lin']));
            } elseif (isset($_GET['mid'])) {
                echo json_encode($ob->getByPidMid($_GET['pid'], $_GET['mid']));
            } else {
                echo json_encode($ob->getByPid($_GET['pid']));
            }
            http_response_code(200);
        } elseif ($_GET['url'] == "detpedcor") {
            $ob = new DetPedCor();
            if (!isset($_GET['pid'])) {
                echo json_encode($ob->getAll());
            } elseif (isset($_GET['lin'])) {
                echo json_encode($ob->getOne($_GET['pid'], $_GET['mod'], $_GET['lin']));
            } else {
                echo json_encode($ob->getPidMod($_GET['pid'], $_GET['mod']));
            }
            http_response_code(200);
        } elseif ($_GET['url'] == "modelos") {
            $ob = new Modelo();
            if (isset($_GET['mid'])) {
                echo json_encode($ob->getOne($_GET['pid'], $_GET['mid'], $_GET['ano']));
            } else {
                echo json_encode($ob->getByPedido($_GET['pid']));
            }
            http_response_code(200);
        } elseif ($_GET['url'] == "pedidobysts") {
            $ob = new Pedido();
            echo json_encode($ob->getByCltYearSts($_GET['cid'], $_GET['ano'], $_GET['status']));
            http_response_code(200);
        } elseif ($_GET['url'] == "pedidoref") {
            $ob = new Pedido();
            echo json_encode($ob->getRefInterna($_GET['cid'], $_GET['ano']));
            http_response_code(200);
        } elseif ($_GET['url'] == "pedido") {
            $ob = new Pedido();
            echo json_encode($ob->getOne($_GET['pid']));
            http_response_code(200);
        } elseif ($_GET['url'] == "pedidosbysts") {
            $ob = new Pedido();
            echo json_encode($ob->getByCltYearSts($_GET['cid'], $_GET['ano'], $_GET['status']));
            http_response_code(200);
        } elseif ($_GET['url'] == "ref") {
            $ob = new Modelo();
            echo json_encode($ob->getRefInterna($_GET['pid']));
            http_response_code(200);
        } elseif ($_GET['url'] == "mfotos") {
            $ob = new Modelo();
            if (isset($_GET['linha'])) {
                echo json_encode($ob->getOneFotoByModelo($_GET['mid'], $_GET['linha']));
            } else {
                echo json_encode($ob->getFotosByModelo($_GET['mid']));
            }
            http_response_code(200);
        } elseif ($_GET['url'] == "empresa") {
            $ob = new MyEmpresa();
            echo json_encode($ob->getAll());
            http_response_code(200);
        } elseif ($_GET['url'] == "mimgs") {
            $ob = new Modelo();
            echo json_encode($ob->getMimgs($_GET['pid']));
            http_response_code(200);
        }

// PUT  pedidobysts
    } elseif ($_SERVER['REQUEST_METHOD'] == "PUT") {
        $postBody = file_get_contents("php://input");
        $postBody = json_decode($postBody);

        if ($_GET['url'] == "cor") {
            $ob = new Cor();
            $resp = $ob->update($_GET['id'], $postBody);
            if ($resp) {
                http_response_code(304);
            } else {
                http_response_code(200);
            }
            echo json_encode($resp);
        } elseif ($_GET['url'] == "artigos") {
            $ob = new Artigo();
            $resp = $ob->update($_GET['id'], $postBody);
            if ($resp) {
                http_response_code(304);
            } else {
                http_response_code(200);
            }
            echo json_encode($resp);
        } elseif ($_GET['url'] == "pedidos") {
            $ob = new Pedido();
            echo json_encode($ob->createPedido($_GET['cid'], $postBody));
            http_response_code(200);
        } elseif ($_GET['url'] == "pedido") {
            $ob = new Pedido();
            echo json_encode($ob->editPedido($_GET['pid'], $postBody));
            http_response_code(200);
        } elseif ($_GET['url'] == "modelo") {
            $ob = new Modelo();
            echo json_encode($ob->update($_GET['id'], $postBody));
            http_response_code(200);
        } elseif ($_GET['url'] == "det") {
            $ob = new Detalhe();
            echo json_encode($ob->updateLine($_GET['pid'], $_GET['mid'], $_GET['lin'], $postBody));
            http_response_code(200);
        } elseif ($_GET['url'] == "detpedcor") {
            $ob = new DetPedCor();
            if (isset($_GET['lin']))
                echo json_encode($ob->insertLin($_GET['pid'], $_GET['mod'], $_GET['lin'], $postBody));
            http_response_code(200);
        } elseif ($_GET['url'] == "conv") {
            $ob = new Convertor();
            if ($_GET['tabela'] == 'cliente') {
                echo json_encode($ob->converterClientes());
            } elseif ($_GET['tabela'] == 'pedido') {
                echo json_encode($ob->converterPedidos());
            } elseif ($_GET['tabela'] == 'modelo') {
                echo json_encode($ob->converterModelo());
            }
            http_response_code(200);
        } elseif ($_GET['url'] == "mfotos") {
            $ob = new Modelo();
            if (isset($_GET['linha'])) {
                echo json_encode($ob->changeFotoBymodelo($_GET['mid'], $_GET['linha'], $postBody));
            } else {
                echo json_encode($ob->saveFotoByModelo($_GET['mid'], $postBody));
            }
            http_response_code(200);
        } else {
            http_response_code(201);
        }

        // DELETE
    } elseif ($_SERVER['REQUEST_METHOD'] == "DELETE") {

        if ($_GET['url'] == "pedido") {
            $ob = new Pedido();
            echo json_encode($ob->deletePedido($_GET['pid']));
            http_response_code(200);
        } elseif ($_GET['url'] == "modelos") {
            $ob = new Modelo();
            echo json_encode($ob->deleteAll($_GET['pid']));
            http_response_code(200);
        } elseif ($_GET['url'] == "modelo") {
            $ob = new Modelo();
            echo json_encode($ob->deleteOne($_GET['id']));
            http_response_code(200);
        } elseif ($_GET['url'] == "mfotos") {
            $ob = new Modelo();
            if (isset($_GET['linha'])) {
                echo json_encode($ob->deleteOneFoto($_GET['mid'], $_GET['linha']));
            } else {
                echo json_encode($ob->deleteAllFotos($_GET['mid']));
            }
            http_response_code(200);
        } elseif ($_GET['url'] == "det") {
            $ob = new Detalhe();
            if (isset($_GET['lin'])) {
                echo json_encode($ob->deleteByLine($_GET['pid'], $_GET['mod'], $_GET['lin']));
            } elseif (isset($_GET['mid'])) {
                echo json_encode($ob->deleteByModelo($_GET['pid'], $_GET['mod']));
            } else {
                echo json_encode($ob->deleteByPedido($_GET['pid']));
            }
            http_response_code(200);
        } elseif ($_GET['url'] == "detpedcor") {
            $ob = new DetPedCor();
            if (isset($_GET['lin'])) {
                echo json_encode($ob->deleteLine($_GET['pid'], $_GET['mod'], $_GET['lin']));
            }
        }
    } else {//Fim dos metodos 
        http_response_code(405);
    }
}