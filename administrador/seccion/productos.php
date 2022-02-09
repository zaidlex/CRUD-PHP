<?php include("../template/cabecera.php"); include("../config/bd.php");?>
<?php
    $libroNombre = isset($_POST["NomLibro"])? $_POST["NomLibro"] : "";
    $accion = isset($_POST["accion"])? $_POST["accion"] : "";
    $id = isset($_POST["LibroID"])? $_POST["LibroID"] : "";
    $libroImgNombre = isset($_FILES["ImgLibro"]["name"])? $_FILES["ImgLibro"]["name"] : "";

    switch ($accion){
        case "agregar":
            $fecha = new DateTime();
            $nomArchivo = ($libroImgNombre != "")?$fecha->getTimestamp()."_".$_FILES["ImgLibro"]["name"]: "SinImagen.jpg";
            $tmpImagen = $_FILES["ImgLibro"]["tmp_name"];
            if($tmpImagen != ""){
                move_uploaded_file($tmpImagen, "../../img/".$nomArchivo);
            }

            //INSERT INTO libros (NombreLibro, Imagen) VALUES ('Python', 'python.jpg');
            $sentSQL = $conexion->prepare("INSERT INTO libros (NombreLibro, Imagen) VALUES (:nombre, :imagen);");
            $sentSQL->bindParam(':nombre',$libroNombre);
            $sentSQL->bindParam(':imagen',$nomArchivo);
            $sentSQL->execute();
            header("Location: productos.php");
            break;

        case "modificar":
            $sentSQL = $conexion->prepare("UPDATE libros set NombreLibro=:nombre WHERE ID=:id;");
            $sentSQL->bindParam(':nombre',$libroNombre);
            $sentSQL->bindParam(':id',$id);
            $sentSQL->execute();
            
            if($libroImgNombre != ""){
                // Crea el nuevo archivo y lo guarda
                $fecha = new DateTime();
                $nomArchivo = $fecha->getTimestamp()."_".$_FILES["ImgLibro"]["name"];
                $tmpImagen = $_FILES["ImgLibro"]["tmp_name"];
                if($tmpImagen != ""){
                    move_uploaded_file($tmpImagen, "../../img/".$nomArchivo);
                }

                //Obtiene el archivo antiguo y lo borra si existe
                $sentSQL = $conexion->prepare("SELECT Imagen FROM libros WHERE ID=:id;");
                $sentSQL->bindParam(':id',$id);
                $sentSQL->execute();
                
                $libroSelec = $sentSQL->fetch(PDO::FETCH_LAZY);
                $libroImgNombre = $libroSelec['Imagen'];

                if(isset($libroImgNombre) && $libroImgNombre != "SinImagen.jpg"){
                    if(file_exists("../../img/".$libroImgNombre)){
                        unlink("../../img/".$libroImgNombre);
                    }
                }

                $sentSQL = $conexion->prepare("UPDATE libros SET Imagen=:imagen WHERE ID=:id;");
                $sentSQL->bindParam(':imagen',$nomArchivo);
                $sentSQL->bindParam(':id',$id);
                $sentSQL->execute();
            }

            header("Location: productos.php");
            break;

        case "cancelar":
            header("Location: productos.php");
            break;

        case "seleccionar":
            $sentSQL = $conexion->prepare("SELECT * FROM libros WHERE ID=:id;");
            $sentSQL->bindParam(':id',$id);
            $sentSQL->execute();

            $libroSelec = $sentSQL->fetch(PDO::FETCH_LAZY);
            $id = $libroSelec['ID'];
            $libroNombre = $libroSelec['NombreLibro'];
            $libroImgNombre = $libroSelec['Imagen'];
            break;

        case "borrar":
            $sentSQL = $conexion->prepare("SELECT Imagen FROM libros WHERE ID=:id;");
            $sentSQL->bindParam(':id',$id);
            $sentSQL->execute();
            
            $libroSelec = $sentSQL->fetch(PDO::FETCH_LAZY);
            $libroImgNombre = $libroSelec['Imagen'];

            if(isset($libroImgNombre) && $libroImgNombre != "SinImagen.jpg"){
                if(file_exists("../../img/".$libroImgNombre)){
                    unlink("../../img/".$libroImgNombre);
                }
            }

            $sentSQL = $conexion->prepare("DELETE FROM libros WHERE ID=:id;");
            $sentSQL->bindParam(':id',$id);
            $sentSQL->execute();
            header("Location:productos.php");
            break;
    }

    $sentSQL = $conexion->prepare("SELECT * FROM libros;");
    $sentSQL->execute();
    $listaLibros = $sentSQL->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="col-md-5"> 
    <div class="card">
        <div class="card-header bg-info">
            <h3>Datos del libro</h3>
        </div>
        <div class="card-body"> 
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
            <div class="form-group">
                <?php
                if($accion == "seleccionar"){
                    echo "<label>ID del libro: $id</label>";
                    echo "<input type=\"hidden\" name=\"LibroID\" value=\"$id\">";
                    echo "<br>";
                }
                ?>

                <label for="NomLibro">Nombre del libro</label>
                <input type="text" class="form-control" id="NomLibro" name="NomLibro" <?php echo ($accion == "seleccionar")?"value=\"".$libroNombre."\"":null;?> placeholder="Nombre" required>
                <br>
                <?php
                if ($accion == "seleccionar"){
                    echo "<img class=\"imgthumbnail rounded\" width=\"200\" src=\"../../img/".$libroImgNombre."\">"; 
                    echo "<br>";
                }
                ?>
                
                <label for="ImgLibro">Imagen</label>
                <input type="file" class="form-control" id="ImgLibro" name="ImgLibro" placeholder="Imagen del libro" <?php echo ($accion != "seleccionar")?"required":null;?>>
                <br>
            </div>

            <div class="btn-group" role="group" aria-label="">
                <button type="submit" class="btn btn-success" name="accion" value="agregar" <?php echo ($accion=="seleccionar")?"disabled":"" ;?> >Agregar</button>
                <button type="submit" class="btn btn-warning" name="accion" value="modificar" <?php echo ($accion!="seleccionar")?"disabled":"" ;?> >Modificar</button>
                <button type="submit" class="btn btn-info" name="accion" value="cancelar"<?php echo ($accion!="seleccionar")?"disabled":"" ;?> >Cancelar</button>
            </div>
        </form>
        </div>
    </div> 
</div>

<div class="col-md-7">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach($listaLibros as $libro){ ?>
            <tr>
                <td scope="row"><?php echo $libro['ID']; ?></td>
                <td><?php echo $libro['NombreLibro']; ?></td>
                <td><img class="imgthumbnail rounded" width="50" src="<?php echo "../../img/".$libro['Imagen']; ?>" ></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="LibroID" id="LibroID" value="<?php echo $libro['ID']; ?>">
                        <button type="submit" class="btn btn-primary" name="accion" value="seleccionar">Seleccionar</button>
                    </form>
                    <form method="POST">
                        <input type="hidden" name="LibroID" id="LibroID" value="<?php echo $libro['ID']; ?>">
                        <button type="submit" class="btn btn-danger" name="accion" value="borrar">Borrar</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include("../template/pie.php"); ?>