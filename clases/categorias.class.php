<?php
require_once "conexion/conexion.php";
require_once "respuestas.class.php";


class categorias extends conexion{
	 private $table = "categoria";
	 private $NombreCategoria = "";
	 private $IdCategoria = "";
	 private $token="";
     



	 public function listaCategorias($pagina = 1){
        $inicio  = 0 ;
        $cantidad = 50;
        if($pagina > 1){
            $inicio = ($cantidad * ($pagina - 1)) +1 ;
            $cantidad = $cantidad * $pagina;
        }
        $query = "SELECT IdCategoria,NombreCategoria FROM " . $this->table . " limit $inicio,$cantidad";
        $datos = parent::obtenerDatos($query);
        return ($datos);
    }

     public function obtenerCategoria($id){
        $query = "SELECT * FROM " . $this->table . " WHERE IdCategoria = '$id'";
        return parent::obtenerDatos($query);

    }

     public function post($json){
        $_respuestas = new respuestas;
        $datos = json_decode($json,true);

                if(!isset($datos['NombreCategoria'])){
                    return $_respuestas->error_400();
                }else{
                    $this->NombreCategoria = $datos['NombreCategoria'];
                    $resp = $this->insertarCategoria();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "IdCategoria" => $resp
                        );
                        return $respuesta;
                    }else{
                        return $_respuestas->error_500();
                    }
                }
        }
      
        private function insertarCategoria(){
        $query = "INSERT INTO " . $this->table . " (NombreCategoria)
        values
        ('" . $this->NombreCategoria . "')"; 
        print_r($query);
        $resp = parent::nonQueryId($query);
        if($resp){
             return $resp;
        }else{
            return 0;
             }

       }
        public function put($json){
        $_respuestas = new respuestas;
        $datos = json_decode($json,true);

         if(!isset($datos['token'] )){
                return $_respuestas->error_401();
         }else{
            $this->token = $datos['token'];
            $arrayToken =   $this->buscarToken();
            
            if($arrayToken){

                if(!isset($datos['IdCategoria'])){
                    return $_respuestas->error_400();
                }else{
                    $this->IdCategoria = $datos['IdCategoria']; 
                    if(isset($datos['NombreCategoria'])) { $this->NombreCategoria = $datos['NombreCategoria']; }
                    $resp = $this->modificarCategoria();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "IdCategoria" => $this->IdCategoria
                        );
                        return $respuesta;
                    }else{
                        return $_respuestas->error_500();
                    }
                }

            }else{
                return $_respuestas->error_401("El Token que envio es invalido o ha caducado");
            }
        }
      }


        private function modificarCategoria(){
        $query = "UPDATE " . $this->table . " SET NombreCategoria ='" . $this->NombreCategoria . "' WHERE IdCategoria = '" . $this->IdCategoria . "'"; 
        print_r($query);
        $resp = parent::nonQuery($query);
        if($resp >= 1){
             return $resp;
          }else{
            return 0;
          }
        }

        public function delete($json){
        $_respuestas = new respuestas;
        $datos = json_decode($json,true);

        if(!isset($datos['token'])){
            return $_respuestas->error_401();
        }else{
            $this->token = $datos['token'];
            $arrayToken =   $this->buscarToken();
            if($arrayToken){

                if(!isset($datos['IdCategoria'])){
                    return $_respuestas->error_400();
                }else{
                    $this->IdCategoria = $datos['IdCategoria'];
                    $resp = $this->eliminarCategoria();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "IdCategoria" => $this->IdCategoria);
                        return $respuesta;
                    }else{
                        return $_respuestas->error_500();
                    }
                }

            }else{
                return $_respuestas->error_401("El Token que envio es invalido o ha caducado");
            }
        }



     
     }


        private function eliminarCategoria(){
        $query = "DELETE FROM " . $this->table . " WHERE IdCategoria = '" . $this->IdCategoria . "'";
        $resp = parent::nonQuery($query);
        print_r($query);
        if($resp >= 1 ){
            return $resp;
        }else{
            return 0;
        }
    }

        private function buscarToken(){
        $query = "SELECT  TokenId,IdUsuario,Estado from usuarios_token WHERE Token = '" . $this->token . "' AND Estado = 'Activo'";
        $resp = parent::obtenerDatos($query);
        if($resp){
            return $resp;
        }else{
            return 0;
        }
    }


        private function actualizarToken($tokenid){
        $date = date("Y-m-d H:i");
        $query = "UPDATE usuarios_token SET Fecha = '$date' WHERE TokenId = '$tokenid' ";
        $resp = parent::nonQuery($query);
        if($resp >= 1){
            return $resp;
        }else{
            return 0;
        }
    }



}

?>