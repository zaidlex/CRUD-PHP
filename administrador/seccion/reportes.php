<?php
    session_start();
    if(!isset($_SESSION['usuario'])){
        header('Location:../index.php');
    }else{
        if($_SESSION['usuario'] == "OK"){
            $nomUsuario = $_SESSION['nomUsuario'];
        }
    }
    // cargar el html en una variable
    ob_start();

    include("../config/bd.php");
    $sentSQL = $conexion->prepare("SELECT * FROM libros;");
    $sentSQL->execute();
    $listaLibros = $sentSQL->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <style>
        .table-striped>tbody>tr:nth-child(odd)>td,
        .table-striped>tbody>tr:nth-child(odd)>th {
            background-color: #8AC6BA;
        }
        .table-striped>tbody>tr:nth-child(even)>td,
        .table-striped>tbody>tr:nth-child(even)>th {
            background-color: #9CC68A;
        }
    </style>
    <title>Reporte de libros</title>
</head>
<body>
<h1 class="text-center">Reporte de libros</h1>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th class="text-center">ID</th>
                <th class="text-center">Nombre del Libro</th>
                <th class="text-center">Portada</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach($listaLibros as $libro){ ?>
            <tr>
                <td class="text-center" scope="row"><?php echo $libro['ID']; ?></td>
                <td class="text-center"><?php echo $libro['NombreLibro']; ?></td>
                <?php
                    // por si no funciona con e link al servidor
                    $imagenBase64 = "data:image/png;base64," . base64_encode(file_get_contents("../../img/".$libro['Imagen']));
                ?>
                <td class="text-center"><img class="imgthumbnail rounded" width="200" src="<?php echo $imagenBase64?>" ></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

</body>
</html>

<?php
    // guardando el contenido html en la variable
    $html = ob_get_clean();
    // Usando la libreria dompdf
    require_once '../bibliotecas/dompdf/autoload.inc.php';
    use Dompdf\Dompdf;

    // configurando las opciones de dompdf
    $dompdf = new Dompdf();
    $options = $dompdf->getOptions();
    // remote activado, para acceder imágenes remotas o del mismo servidor
    $options->set(array('isRemoteEnabled' => true));
    $dompdf->setOptions($options);

    // cargando la variable html a dompdf
    $dompdf->loadHtml($html);
    // configurando el tipo de pdf o papel, ('letter') - ('A4', 'landscape')
    $dompdf->setPaper('letter');
    $dompdf->render();

    // Para mostrar el archivo en el navegador
    $dompdf->stream("reporte de libros.pdf",['Attachment' => false]);
    // Para descargar el archivo
    //$dompdf->stream("reporte de libros.pdf",array("attachmen" => true));
?>