<?php

$id = (isset($_POST['id'])) ? $_POST['id'] : "";
$nom = (isset($_POST['nom'])) ? $_POST['nom'] : "";
$prec = (isset($_POST['prec'])) ? $_POST['prec'] : "";
$desc = (isset($_POST['desc'])) ? $_POST['desc'] : "";
$foto = (isset($_FILES['foto']["name"])) ? $_FILES['foto']["name"] : "";

$accion = (isset($_POST['accion'])) ? $_POST['accion'] : "";

$accionAgreagr = "";
$accionActualizar = $accionEliminar = $accionCancelar = "disabled";
$mostrarModal = false;

include("../Conexion-formularios/Conexiontblproductos.php");

echo "<br/><br/>";

switch ($accion) {
    case "Agregrar":

        $sentencia = $pdo->prepare(" INSERT INTO tblproductos(Nombre,Precio,Descripcion,Foto)
        VALUES (:Nombre,:Precio,:Descripcion,:Foto) ");

        $sentencia->bindParam(':Nombre', $nom);
        $sentencia->bindParam(':Precio', $prec);
        $sentencia->bindParam(':Descripcion', $desc);

        $Fecha = new DateTime();
        $nombreArchivo = ($foto != "") ? $Fecha->getTimestamp() . "_" . $_FILES["foto"]["name"] : "imagen.jpg";

        $tmpFoto = $_FILES["foto"]["tmp_name"];

        if ($tmpFoto != "") {
            move_uploaded_file($tmpFoto, "../ImgProductos/" . $nombreArchivo);
        }

        $sentencia->bindParam(':Foto', $nombreArchivo);
        $sentencia->execute();

        // echo "Se Agrego la fila: ";
        // echo $id;
        // echo "<br/> Agregado";
        break;

    case "Actualizar":

        $sentencia = $pdo->prepare(" UPDATE tblproductos SET
        Nombre=:Nombre,
        Precio=:Precio,
        Descripcion=:Descripcion 
        WHERE IdProductos=:id");

        $sentencia->bindParam(':Nombre', $nom);
        $sentencia->bindParam(':Precio', $prec);
        $sentencia->bindParam(':Descripcion', $desc);
        $sentencia->bindParam(':id', $id);
        $sentencia->execute();

        $Fecha = new DateTime();
        $nombreArchivo = ($foto != "") ? $Fecha->getTimestamp() . "_" . $_FILES["foto"]["name"] : "imagen.jpg";

        $tmpFoto = $_FILES["foto"]["tmp_name"];

        if ($tmpFoto != "") {
            move_uploaded_file($tmpFoto, "../ImgProductos/" . $nombreArchivo);

            $sentencia = $pdo->prepare(" UPDATE tblproductos SET
            Foto=:Foto WHERE IdProductos=:id ");
            $sentencia->bindParam(':Foto', $nombreArchivo);
            $sentencia->bindParam(':id', $id);
            $sentencia->execute();
        }


        header('Location: CrearProductos.php');

        // echo $id;
        // echo "<br/> Se Actualizo";
        break;

    case "Eliminar":

        $sentencia = $pdo->prepare("SELECT Foto FROM tblproductos WHERE IdProductos=:id ");
        $sentencia->bindParam(':id', $id);
        $sentencia->execute();
        $tblproductos = $sentencia->fetch(PDO::FETCH_LAZY);
        print_r($tblproductos);

        if (isset($tblproductos["Foto"])) {
            if (file_exists("../ImgProductos/" . $tblproductos["Foto"])) {
                unlink("../ImgProductos/" . $tblproductos["Foto"]);
            }
        }

        /*
        $sentencia = $pdo->prepare(" DELETE FROM tblproductos WHERE IdProductos=:id");
        $sentencia->bindParam(':id', $id);
        $sentencia->execute();
        */

        header('Location: CrearProductos.php');

        // echo $id;
        // echo "<br/> Se Elimino";
        break;

    case "Cancelar":
        header('Location: CrearProductos.php');

        // echo $id;
        // echo "<br/> Se Cancelo";
        break;

    case "Seleccionar";

        $accionAgreagr = "disabled";
        $accionActualizar = $accionEliminar = $accionCancelar = "";
        $mostrarModal = true;

        $sentencia = $pdo->prepare("SELECT * FROM tblproductos WHERE IdProductos=:id ORDER BY IdProductos DESC");
        $sentencia->bindParam(':id', $id);
        $sentencia->execute();
        $tblproductos = $sentencia->fetch(PDO::FETCH_LAZY);

        $nom = $tblproductos['Nombre'];
        $prec = $tblproductos['Precio'];
        $desc = $tblproductos['Descripcion'];
        $foto = $tblproductos['Foto'];

        break;
}

$sentencia = $pdo->prepare(" SELECT * FROM tblproductos WHERE 1 ORDER BY IdProductos DESC");
$sentencia->execute();
$listaProductos = $sentencia->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="en">

<head>
    <title>OnlineSportsClothes - Crear Productos</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.10.2/css/all.css" integrity="sha384-rtJEYb85SiYWgfpCr0jn174XgJTn4rptSOQsMroFBPQSGLdOC5IbubP6lJ35qoM9" crossorigin="anonymous">

    <link rel="icon" type="image/png" href="./ImgInicio/Logo.png" />

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <img src="../ImgInicio/Logo.png" alt="" width="43px" class="d-inline-block align-text-top">
            <a class="navbar-brand text-white" href="Inicio.php">OnlineSportsClothes</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="../Admin/Dashboard.php">Dashboard</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <hr>
        <h1 align="center">Productos</h1>
        <hr>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Productos</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-row">
                                <label for="">Nombre</label>
                                <input type="text" class="form-control" name="nom" value="<?php echo $nom; ?>" placeholder="" id="nombre" required>
                                <br>

                                <label for="">Precio</label>
                                <input type="decimal" class="form-control" name="prec" value="<?php echo $prec; ?>" placeholder="" id="valor" required>
                                <br>

                                <label for="">Descripcion</label>
                                <input type="text" class="form-control" name="desc" value="<?php echo $desc; ?>" placeholder="" id="descripcion" required>
                                <br>

                                <label for="">Imagen</label>
                                <br>
                                <?php if ($foto != "") { ?>
                                    <br>
                                    <img class="img-thumbnail rounded mx-auto d-block" src="../img-form/<?php echo $foto; ?>" width="100px">
                                    <br>
                                <?php } ?>
                                <input type="file" class="form-control" accept="image/*" name="foto" value="<?php echo $foto; ?>" placeholder="" id="foto">
                                <br>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button value="Agregrar" type="submit" name="accion" class="btn btn-outline-primary">Agregrar</button>
                            <button value="Actualizar" type="submit" name="accion" class="btn btn-outline-dark">Actualizar</button>
                            <button value="Eliminar" type="submit" name="accion" class="btn btn-outline-danger">Eliminar</button>
                            <button value="Cancelar" type="submit" name="accion" class="btn btn-outline-info">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
                Agreagar Registro +
            </button>
        </form>
        <br>
        <div class="row">
            <table class="table">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Foto</th>
                        <th>Nombre Producto</th>
                        <th>Descripcion</th>
                        <th>Precio</th>
                        <th>Accion</th>
                    </tr>
                </thead>
                <?php foreach ($listaProductos as $tblproductos) {  ?>
                    <tr>
                        <td><?php echo $tblproductos['IdProductos']; ?></td>
                        <td><img class="img-thumbnail" width="100px" src="../ImgProductos/<?php echo $tblproductos['Foto']; ?>" /></td>
                        <td><?php echo $tblproductos['Nombre']; ?></td>
                        <td><?php echo $tblproductos['Descripcion']; ?></td>
                        <td><?php echo $tblproductos['Precio']; ?></td>
                        <td>
                            <form action="" method="POST">
                                <input type="hidden" name="id" value="<?php echo $tblproductos['IdProductos'] ?>">
                                <input type="submit" value="Seleccionar" name="accion" class="btn btn-outline-primary">
                                <hr>
                                <button value="Eliminar" type="submit" name="accion" class="btn btn-outline-danger" >Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>

    <!-- Optional JavaScript -->

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>