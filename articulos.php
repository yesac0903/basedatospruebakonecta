<?php
require_once 'clases/respuestas.class.php';
require_once 'clases/articulos.class.php';

$_respuestas = new respuestas;
$_articulos = new articulos;

if($_SERVER['REQUEST_METHOD'] == "GET"){

    if(isset($_GET["page"])){
        $pagina = $_GET["page"];
        $listaArticulos = $_articulos->listaArticulos($pagina);
        header("Content-Type: application/json");
        echo json_encode($listaArticulos);
        http_response_code(200);
        }else if(isset($_GET['id'])){
        $IdArticulos = $_GET['id'];
        $datosArticulo = $_articulos->obtenerArticulo($IdArticulos);
        header("Content-Type: application/json");
        echo json_encode($datosArticulo);
        http_response_code(200);
        }

    }else if($_SERVER['REQUEST_METHOD'] == "POST"){
    //recibimos los datos enviados
    $postBody = file_get_contents("php://input");

    //enviamos los datos al manejador
    $datosArray = $_articulos->post($postBody);
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
      $datosArray = $_articulos->put($postBody);
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
        if(isset($headers["token"]) && isset($headers["IdArticulos"])){
            //recibimos los datos enviados por el header
            $send = [
                "token" => $headers["token"],
                "IdArticulos" =>$headers["IdArticulos"]
            ];
            $postBody = json_encode($send);
        }else{
            //recibimos los datos enviados
            $postBody = file_get_contents("php://input");
        }
        
        //enviamos datos al manejador
        $datosArray = $_articulos->delete($postBody);
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
    $datosArray = $_respuestas->error_405();
    echo json_encode($datosArray);
    }




?>