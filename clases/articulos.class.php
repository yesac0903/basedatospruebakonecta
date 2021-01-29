<?php
require_once "conexion/conexion.php";
require_once "respuestas.class.php";


class articulos extends conexion{
	 private $table = "articulos";
	 private $Titulo = "";
	 private $IdArticulos = "";
     private $IdCategoria = "";
	 private $TextoCorto = "";
     private $TextoLargo = "";
	 private $ImagenUrl = "";
	 private $FechaCreacion = "";
     private $FechaActualizacion = "";
	 private $token="";
     



	 public function listaArticulos($pagina = 1){
        $inicio  = 0 ;
        $cantidad = 3;
        if($pagina > 1){
            $inicio = ($cantidad * ($pagina - 1)) +1 ;
            $cantidad = $cantidad * $pagina;
        }
        $query = "SELECT IdArticulos,Titulo,TextoCorto,TextoLargo,FechaCreacion,FechaActualizacion FROM " . $this->table . " limit $inicio,$cantidad";
        $datos = parent::obtenerDatos($query);
        return ($datos);
    }

     public function obtenerArticulo($id){
        $query = "SELECT * FROM " . $this->table . " WHERE IdArticulos = '$id'";
        return parent::obtenerDatos($query);

    }

     public function post($json){
        $_respuestas = new respuestas;
        $datos = json_decode($json,true);

                if(!isset($datos['Titulo']) || !isset($datos['TextoCorto']) || !isset($datos['TextoLargo']) ||      !isset($datos['IdCategoria'])){
                    return $_respuestas->error_400();
                }else{
                    $this->Titulo = $datos['Titulo'];
                    $this->TextoCorto = $datos['TextoCorto'];
                    $this->TextoLargo = $datos['TextoLargo'];
                    $this->IdCategoria = $datos['IdCategoria'];
                    $this->FechaCreacion = date("Y-m-d H:i");

                    $resp = $this->insertarArticulo();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "IdArticulos" => $resp
                        );
                        return $respuesta;
                    }else{
                        return $_respuestas->error_500();
                    }
                }
        }
      
        private function insertarArticulo(){
            
        $query = "INSERT INTO " . $this->table . " (Titulo,TextoCorto,TextoLargo,IdCategoria,FechaCreacion)
        values
        ('" . $this->Titulo . "','" . $this->TextoCorto . "','" . $this->TextoLargo . "','"  . $this->IdCategoria . "','" . $this->FechaCreacion . "')"; 
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

                if(!isset($datos['IdArticulos'])){
                    return $_respuestas->error_400();
                }else{
                    $this->IdArticulos = $datos['IdArticulos'];
                    $this->FechaActualizacion = date("Y-m-d H:i");
                    if(isset($datos['Titulo'])) { $this->Titulo = $datos['Titulo']; }
                    if(isset($datos['TextoCorto'])) { $this->TextoCorto = $datos['TextoCorto']; }
                    if(isset($datos['TextoLargo'])) { $this->TextoLargo =$datos['TextoLargo']; }
                    if(isset($datos['IdCategoria'])) { $this->IdCategoria = $datos['IdCategoria']; }
                    
                    

                    $resp = $this->modificarArticulo();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "IdArticulos" => $this->IdArticulos
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


        private function modificarArticulo(){
        $query = "UPDATE " . $this->table . " SET Titulo ='" . $this->Titulo . "', TextoCorto = '" . $this->TextoCorto . "', TextoLargo = '" . $this->TextoLargo . "', IdCategoria = '" .
        $this->IdCategoria . "', FechaActualizacion = '" . $this->FechaActualizacion . "' WHERE IdArticulos = '" . $this->IdArticulos . "'"; 
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

                if(!isset($datos['IdArticulos'])){
                    return $_respuestas->error_400();
                }else{
                    $this->IdArticulos = $datos['IdArticulos'];
                    $resp = $this->eliminarArticulo();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "IdArticulos" => $this->IdArticulos);
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


        private function eliminarArticulo(){
        $query = "DELETE FROM " . $this->table . " WHERE IdArticulos = '" . $this->IdArticulos . "'";
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