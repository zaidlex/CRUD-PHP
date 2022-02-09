<?php
    session_start();
    if(!isset($_SESSION['usuario'])){
        header('Location:../index.php');
    }else{
        if($_SESSION['usuario'] == "OK"){
            $nomUsuario = $_SESSION['nomUsuario'];
        }
    }
?>
<!doctype html>
<html lang="en">
  <head>
    <title>Administrador</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <title>Administrador</title>
  </head>
  <body>
    <?php
        $url = "http://".$_SERVER['HTTP_HOST'];
    ?>
    <nav class="navbar navbar-expand navbar-dark bg-primary">
        <ul class="nav navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="<?php echo $url."/administrador/inicio.php";?>">Inicio</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url."/administrador/seccion/productos.php";?>">Libros</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url."/administrador/seccion/cerrar.php";?>">Cerrar</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url;?>">Ver sitio web</a>
            </li>
        </ul>
    </nav>
    <br>
    <div class="container">
        <div class="row">