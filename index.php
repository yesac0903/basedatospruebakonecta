<?php
require_once "clases/conexion/conexion.php";
$conexion = new conexion();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API - Prueba Konecta</title>
    <link rel="stylesheet" href="assets/estilo.css" type="text/css">
</head>
<body>
<div  class="container">
    <h1>Api Prueba Konecta</h1>
    <div class="divbody">
        <h3>Auth - login</h3>
        <code>
           POST  /auth
           <br>
           {
               <br>
               "Usuario" :"",  -> REQUERIDO
               <br>
               "Password": "" -> REQUERIDO
               <br>
            }
        
        </code>
    </div>      
    <div class="divbody">   
        <h3>Usuarios</h3>
        <code>
           GET  /usuarios?page=$numeroPagina
           <br>
           GET  /usuarios?id=$idPaciente
        </code>
        <code>
           POST  /usuarios
           <br> 
           {
            <br> 
               "Nombre" : "",               -> REQUERIDO
               <br> 
               "CorreoElectronico" : "",                  -> REQUERIDO
               <br> 
               "Password":"",                 -> REQUERIDO
               <br> 
               "NumeroMovil" :"",             -> REQUERIDO
               <br>         
               "token" : ""                 -> REQUERIDO        
               <br>       
           }
        </code>
        <code>
           PUT  /Usuarios
           <br> 
           {
            <br> 
               "Nombre" : "",               
               <br> 
               "CorreoElectronico" : "",                  
               <br> 
               "Password":"",                 
               <br> 
               "NumeroMovil" :"",             
               <br>  
               "TipoUsuario" : "",               
               <br>       
               "IdUsuario" : ""   -> REQUERIDO
               <br>
           }
        </code>
        <code>
           DELETE  /pacientes
           <br> 
           {   
               <br>    
               "token" : "",                -> REQUERIDO        
               <br>       
               "IdUsuario" : ""   -> REQUERIDO
               <br>
           }
        </code>
    </div>
</div>
    
</body>
</html>