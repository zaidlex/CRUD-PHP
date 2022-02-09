<?php
    session_start();
    if($_POST['usuario'] == "admUser" && $_POST['contrasena'] == "Sys345AD"){
        $_SESSION['usuario'] = "OK";
        $_SESSION['nomUsuario']  = "Zaid";
        header('Location:inicio.php');
    }else{
        $mensajeError = "Error: Usuario ó contraseña incorrectos.";
    }
?>
<!doctype html>
<html lang="en">
  <head>
    <title>Administración</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/css/bootstrap.min.css">
  </head>
  <body>
      <br><br><br>

      <div class="container">
          <div class="row">
              <div class="col-md-4"></div>

              <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-info">
                            Login
                        </div>
                        <div class="card-body">
                            <?php
                            if(isset($mensajeError)){ ?>
                                <div class="alert alert-warning" role="alert">
                                    <strong><?php echo $mensajeError; ?></strong>
                                </div>
                            <?php } ?>
                            <form method="POST">
                            <div class = "form-group">
                            <label for="exampleInputEmail1">Usuario</label>
                            <input type="text" class="form-control" id="usuario" name="usuario" aria-describedby="emailHelp" placeholder="Ingresa el usuario">
                            <small id="usuarioHelp" class="form-text text-muted">No compartir tu usuario con alguien más.</small>
                            </div>

                            <div class="form-group">
                            <label for="exampleInputPassword1">Contraseña</label>
                            <input type="password" class="form-control" id="contrasena" name="contrasena" placeholder="Ingresa la contraseña">
                            </div>
                            <br>
                            <button type="submit" class="btn btn-primary">Ingresar</button>
                            </form>
                        </div>
                    </div>
              </div>
          </div>
      </div>
  </body>
</html>