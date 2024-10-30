<?php
include 'global/config.php';
include 'global/conexion.php';
include 'Carrito.php';
include 'templates/Header.php';
include 'templates/Menu.php';
?>
<hr>
<div class="container">

    <?php if ($mensaje != "") { ?>
        <div class="alert alert-primary">
            <center>
                <?php echo $mensaje; ?>
                <?php echo "<br>" ?>
                <a href="MostrarCarrito.php" class="btn btn-outline-primary"> Ver Carrito </a>
            </center>
        </div>
    <?php } ?>

    <div class="row">
        <?php
        $sentecia = $pdo->prepare(" SELECT * FROM tblproductos WHERE 1 ORDER BY IdProductos DESC");
        $sentecia->execute();
        $listaProductos = $sentecia->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <?php foreach ($listaProductos as $producto) { ?>
            <div class="col-3">
                <div class="card-group">
                    <div class="card border-0">
                        <div class="card-header">
                            <img class="card-img-top" src="./ImgProductos/<?php echo $producto['Foto']; ?>" width="250px" height="280px">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $producto['Nombre']; ?></h5>
                            <p class="card-text">
                                Precio: $ <?php echo $producto['Precio']; ?>
                            </p>
                        </div>
                        <div class="card-footer">
                            <form action="" method="post">
                                <input type="hidden" name="id" id="id" value="<?php echo openssl_encrypt($producto['IdProductos'], COD, KEY); ?>">
                                <input type="hidden" name="nombre" id="nombre" value="<?php echo openssl_encrypt($producto['Nombre'], COD, KEY); ?>">
                                <input type="hidden" name="precio" id="precio" value="<?php echo openssl_encrypt($producto['Precio'], COD, KEY); ?>">
                                <input type="hidden" name="cantidad" id="cantidad" value="<?php echo openssl_encrypt(1, COD, KEY); ?> ">
                                <button type="submit" value="Agregar" name="btnAccion" class="btn btn-outline-primary btn-block">Agregar al carrito</button>
                            </form>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<?php
include 'templates/Footer.php';
?>