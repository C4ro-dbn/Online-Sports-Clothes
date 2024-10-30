<?php
include 'global/config.php';
include 'Carrito.php';
include 'templates/Header.php';
include 'templates/Menu.php';
?>
<hr>
<div class="container">
    <?php if (!empty($_SESSION['CARRITO'])) { ?>
        <table class="table table-hover">
            <thead class="thead">
                <h4 align="center">LISTA DE PRODUCTOS DEL CARRITO</h4>
                <tr>
                    <th width="40%">Descripcion</th>
                    <th width="15%" class="text-center">Cantidad</th>
                    <th width="20%" class="text-center">Precio</th>
                    <th width="20%" class="text-center">Total</th>
                    <th width="5%">--</th>
                </tr>
            </thead>
            <tbody>
                <?php $total = 0; ?>
                <?php foreach ($_SESSION['CARRITO'] as $indice => $producto) { ?>
                    <tr>
                        <td width="40%"> <?php echo $producto['NOMBRE'] ?> </td>
                        <td width="15%" class="text-center"> <?php echo $producto['CANTIDAD'] ?> </td>
                        <td width="20%" class="text-center"> $ <?php echo $producto['PRECIO'] ?> </td>
                        <td width="20%" class="text-center"> $ <?php echo number_format($producto['PRECIO'] * $producto['CANTIDAD']); ?> </td>
                        <td width="5%">

                            <form action="" method="post">
                                <input type="hidden" name="id" id="id" value="<?php echo openssl_encrypt($producto['ID'], COD, KEY); ?>">
                                <button type="submit" value="Eliminar" name="btnAccion" class="btn btn-outline-primary">Eliminar</button>
                            </form>

                        </td>
                    </tr>
                    <?php $total = $total + ($producto['PRECIO'] * $producto['CANTIDAD']); ?>
                <?php } ?>
                <tr>
                    <td colspan="3" align="right">
                        <h4>Total</h4>
                    </td>
                    <td align="right">
                        <h4>$<?php echo number_format($total, 2); ?></h4>
                    </td>
                    <td></td>
                </tr>

                <tr>
                    <td colspan="5">
                        <form action="pagar.php" method="post">
                            <div class="alert alert-primary" role="alert">
                                <div class="form-group">
                                    <label for="my-input">Correo de Contacto:</label>
                                    <input id="email" class="form-control" type="email" name="email" placeholder="Por Favor Ingrese Su Correo" required>
                                </div>
                                <small class="form-text text-muted" id="emailHelp">Los productos se enviaran a este Correo</small>
                            </div>
                            <button class="btn btn-danger btn-lg btn-block" type="submit" value="proceder" name="btnAccion">
                                Procede a paga   >>>
                            </button>
                        </form>
                    </td>
                </tr>

            </tbody>
        </table>
    <?php } else { ?>
        <div class="alert alert-danger" role="alert">
            No hay productos en el carrito...
        </div>
    <?php } ?>
</div>
<?php include 'templates/Footer.php'; ?>