<?php
    include("template/cabecera.php");
    include("administrador/config/bd.php");

    $sentSQL = $conexion->prepare("SELECT * FROM libros;");
    $sentSQL->execute();
    $listaLibros = $sentSQL->fetchAll(PDO::FETCH_ASSOC);
?>
<?php
foreach($listaLibros as $libro){ ?>
    <div class="col-md-3">
        <div class="card">
            <img class="img-thumbnail rounded mx-auto d-block" width="400" src="<?php echo "../../img/".$libro['Imagen']; ?>">
            <div class="card-body">
                <h4 class="card-title"><?php echo $libro['NombreLibro']; ?></h4>
                <!-- <a name="" id="" class="btn btn-primary" href="#" role="button">Ver más</a> -->
            </div>
        </div>
    </div>
<?php 
} ?>
<?php
    include("template/pie.php");
?>