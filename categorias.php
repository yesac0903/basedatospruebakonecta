<?php
require_once 'clases/respuestas.class.php';
require_once 'clases/categorias.class.php';

$_respuestas = new respuestas;
$_categorias = new categorias;

if($_SERVER['REQUEST_METHOD'] == "GET"){

    if(isset($_GET["page"])){
        $pagina = $_GET["page"];
        $listaCategorias = $_categorias->listaCategorias($pagina);
        header("Content-Type: application/json");
        echo json_encode($listaCategorias);
        http_response_code(200);
        }else if(isset($_GET['id'])){
        $IdCategoria = $_GET['id'];
        $datosCategoria = $_categorias->obtenerCategoria($IdCategoria);
        header("Content-Type: application/json");
        echo json_encode($datosCategoria);
        http_response_code(200);
        }

    }else if($_SERVER['REQUEST_METHOD'] == "POST"){
    //recibimos los datos enviados
    $postBody = file_get_contents("php://input");

    //enviamos los datos al manejador
    $datosArray = $_categorias->post($postBody);
    //delvovemos una respuesta 
    header('Content-Type: application/json');
     if(isset($datosArray["result"]["error_id"])){
         $responseCode = $datosArray["result"]["error_id"];
         http_response_code($responseCode);
     }else{
         http_response_code(200);
     }
     echo json_encode($datosArray);
    }else if($_SERVER['REQUEST_METHOD'] == "PUT"){
      //recibimos los datos enviados
      $postBody = file_get_contents("php://input");
      //enviamos datos al manejador
      $datosArray = $_categorias->put($postBody);
        //delvovemos una respuesta 
     header('Content-Type: application/json');
     if(isset($datosArray["result"]["error_id"])){
         $responseCode = $datosArray["result"]["error_id"];
         http_response_code($responseCode);
     }else{
         http_response_code(200);
     }
     echo json_encode($datosArray);
  }else if($_SERVER['REQUEST_METHOD'] == "DELETE"){

        $headers = getallheaders();
        if(isset($headers["token"]) && isset($headers["IdCategoria"])){
            //recibimos los datos enviados por el header
            $send = [
                "token" => $headers["token"],
                "IdCategoria" =>$headers["IdCategoria"]
            ];
            $postBody = json_encode($send);
        }else{
            //recibimos los datos enviados
            $postBody = file_get_contents("php://input");
        }
        
        //enviamos datos al manejador
        $datosArray = $_categorias->delete($postBody);
        //delvovemos una respuesta 
        header('Content-Type: application/json');
        if(isset($datosArray["result"]["error_id"])){
            $responseCode = $datosArray["result"]["error_id"];
            http_response_code($responseCode);
            
        }else{
            http_response_code(200);
        }
        echo json_encode($datosArray);
       

    }else{
    header('Content-Type: application/json');
    $datosArray = $_categorias->error_405();
    echo json_encode($datosArray);
    }




?>