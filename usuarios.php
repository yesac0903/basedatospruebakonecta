<?php
require_once 'clases/respuestas.class.php';
require_once 'clases/usuarios.class.php';

$_respuestas = new respuestas;
$_usuarios = new usuarios;

if($_SERVER['REQUEST_METHOD'] == "GET"){

    if(isset($_GET["page"])){
        $pagina = $_GET["page"];
        $listaUsuarios = $_usuarios->listaUsuarios($pagina);
        header("Content-Type: application/json");
        echo json_encode($listaUsuarios);
        http_response_code(200);
        }else if(isset($_GET['id'])){
        $IdUsuario = $_GET['id'];
        $datosUsuario = $_usuarios->obtenerUsuarioId($IdUsuario);
        header("Content-Type: application/json");
        echo json_encode($datosUsuario);
        http_response_code(200);
        }

    }else if($_SERVER['REQUEST_METHOD'] == "POST"){
    //recibimos los datos enviados
    $postBody = file_get_contents("php://input");

    //enviamos los datos al manejador
    $datosArray = $_usuarios->post($postBody);
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
      $datosArray = $_usuarios->put($postBody);
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
        if(isset($headers["token"]) && isset($headers["IdUsuario"])){
            //recibimos los datos enviados por el header
            $send = [
                "token" => $headers["token"],
                "IdUsuario" =>$headers["IdUsuario"]
            ];
            $postBody = json_encode($send);
        }else{
            //recibimos los datos enviados
            $postBody = file_get_contents("php://input");
        }
        
        //enviamos datos al manejador
        $datosArray = $_usuarios->delete($postBody);
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