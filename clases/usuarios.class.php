<?php
require_once "conexion/conexion.php";
require_once "respuestas.class.php";


class usuarios extends conexion{
	 private $table = "usuarios";
	 private $Nombre = "";
	 private $IdUsuario = "";
	 private $CorreoElectronico = "";
     private $Password = "";
	 private $NumeroMovil = "";
	 private $TipoUsuario = "";
	 private $FechaCreacion = "";
     private $FechaActualizacion = "";
	 private $token="";
     



	 public function listaUsuarios($pagina = 1){
        $inicio  = 0 ;
        $cantidad = 50;
        if($pagina > 1){
            $inicio = ($cantidad * ($pagina - 1)) +1 ;
            $cantidad = $cantidad * $pagina;
        }
        $query = "SELECT IdUsuario,Nombre,CorreoElectronico,NumeroMovil,TipoUsuario FROM " . $this->table . " limit $inicio,$cantidad";
        $datos = parent::obtenerDatos($query);
        return ($datos);
    }

     public function obtenerUsuarioId($id){
        $query = "SELECT * FROM " . $this->table . " WHERE IdUsuario = '$id'";
        return parent::obtenerDatos($query);

    }

     public function post($json){
        $_respuestas = new respuestas;
        $datos = json_decode($json,true);

                if(!isset($datos['Nombre']) || !isset($datos['CorreoElectronico']) || !isset($datos['Password']) ||      !isset($datos['NumeroMovil'])){
                    return $_respuestas->error_400();
                }else{
                    $this->Nombre = $datos['Nombre'];
                    $this->CorreoElectronico = $datos['CorreoElectronico'];
                    $this->Password = md5($datos['Password']);
                    $this->NumeroMovil = $datos['NumeroMovil'];
                    $this->FechaCreacion = date("Y-m-d H:i");
                    if(isset($datos['TipoUsuario'])) { $this->TipoUsuario = $datos['TipoUsuario']; }


                    $resp = $this->insertarUsuario();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "IdUsuario" => $resp
                        );
                        return $respuesta;
                    }else{
                        return $_respuestas->error_500();
                    }
                }
        }
      
        private function insertarUsuario(){
            $Estado="Activo";
        $query = "INSERT INTO " . $this->table . " (Nombre,CorreoElectronico,Password,NumeroMovil,TipoUsuario,FechaCreacion,Estado)
        values
        ('" . $this->Nombre . "','" . $this->CorreoElectronico . "','" . $this->Password . "','"  . $this->NumeroMovil . "','" . $this->TipoUsuario . "','" . $this->FechaCreacion . "','" . $Estado . "')"; 
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

                if(!isset($datos['IdUsuario'])){
                    return $_respuestas->error_400();
                }else{
                    $this->IdUsuario = $datos['IdUsuario'];
                    $this->FechaActualizacion = date("Y-m-d H:i");
                    if(isset($datos['Nombre'])) { $this->Nombre = $datos['Nombre']; }
                    if(isset($datos['CorreoElectronico'])) { $this->CorreoElectronico = $datos['CorreoElectronico']; }
                    if(isset($datos['Password'])) { $this->Password =md5($datos['Password']); }
                    if(isset($datos['NumeroMovil'])) { $this->NumeroMovil = $datos['NumeroMovil']; }
                    if(isset($datos['TipoUsuario'])) { $this->TipoUsuario = $datos['TipoUsuario']; }
                    

                    $resp = $this->modificarUsuario();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "IdUsuario" => $this->IdUsuario
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


        private function modificarUsuario(){
        $query = "UPDATE " . $this->table . " SET Nombre ='" . $this->Nombre . "', CorreoElectronico = '" . $this->CorreoElectronico . "', Password = '" . $this->Password . "', NumeroMovil = '" .
        $this->NumeroMovil . "', TipoUsuario = '" . $this->TipoUsuario . "', FechaActualizacion = '" . $this->FechaActualizacion . "' WHERE IdUsuario = '" . $this->IdUsuario . "'"; 
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

                if(!isset($datos['IdUsuario'])){
                    return $_respuestas->error_400();
                }else{
                    $this->IdUsuario = $datos['IdUsuario'];
                    $resp = $this->eliminarUsuario();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "IdUsuario" => $this->IdUsuario);
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


        private function eliminarUsuario(){
        $query = "DELETE FROM " . $this->table . " WHERE IdUsuario = '" . $this->IdUsuario . "'";
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