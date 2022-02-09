<?php
    $host ="localhost";
    $db = "LibreriaSys";
    $usuario = "webSS";
    $contrasena = "iCX2ODJELvxtGkUD";
    try{
        $conexion = new PDO("mysql:host=$host;dbname=$db", $usuario, $contrasena);
    }catch(Exception $ex){
        echo $ex->getMessage();
    }
?>