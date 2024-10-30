<?php
include 'global/config.php';
include 'global/conexion.php';
include 'Carrito.php';
include 'templates/Header.php';
include 'templates/Menu.php';
?>

<hr>
<div class="container">

    <?php
    if ($_POST) {
        $total = 0;
        $SID = session_id();
        $Correo = $_POST['email'];

        foreach ($_SESSION['CARRITO'] as $indice => $producto) {
            $total = $total + ($producto['PRECIO'] * $producto['CANTIDAD']);
        }
        $sentencia = $pdo->prepare("INSERT INTO `tblventas` 
            (`IdVentas`, `ClaveTransccion`, `PaypalDatos`, `Fecha`, `Correo`, `Total`, `Status`) 
            VALUES (NULL, :ClaveTransaccion,'', NOW(), :Correo, :Total, 'pendiente'); ");

        $sentencia->bindParam(":ClaveTransaccion", $SID);
        $sentencia->bindParam(":Correo", $Correo);
        $sentencia->bindParam(":Total", $total);
        $sentencia->execute();
        $idVenta = $pdo->lastInsertId();

        foreach ($_SESSION['CARRITO'] as $indice => $producto) {
            $sentencia = $pdo->prepare("INSERT INTO `tblfactura` 
            (`IdFactura`, `IdVentas`, `IdProductos`, `PrecioUnitario`, `Cantidad`) 
            VALUES (NULL, :IdVentas, :IdProductos, :PrecioUnitario, :Cantidad); ");

            $sentencia->bindParam(":IdVentas", $idVenta);
            $sentencia->bindParam(":IdProductos", $producto['ID']);
            $sentencia->bindParam(":PrecioUnitario", $producto['PRECIO']);
            $sentencia->bindParam(":Cantidad", $producto['CANTIDAD']);
            $sentencia->execute();
        }

        //echo "<h3>" . $total . "</h3>";
    }
    ?>

    <script src="https://www.paypalobjects.com/api/checkout.js"></script>

    <div class="jumbotron text-center">
        <h1 class="display-4">¡Paso Final!</h1>
        <hr class="my-4">
        <p class="lead">
            Estas a punto de pagar con paypal la cantidad de:
            <h4>$<?php echo number_format($total, 3) ?></h4>
            <div id="paypal-button-container"></div>
        </p>
        <p>
            Los productos seran entregados una vez sea procesado el pago <br>
            <strong>(Para aclaraciones contactese a :dubanfelipecaro@gmail.com)</strong>
        </p>
    </div>

</div>

<link rel="stylesheet" href="tienda/css/style-btnpaypal.css">

<script>
    paypal.Button.render({
        env: 'sandbox', // sandbox | production
        style: {
            label: 'checkout', // checkout | credit | pay | buynow | generic
            size: 'large', // small | medium | large | responsive
            shape: 'pill', // pill | rect
            color: 'blue' // gold | blue | silver | black
        },

        // PayPal Client IDs - replace with your own
        // Create a PayPal app: https://developer.paypal.com/developer/applications/create

        client: {
            sandbox: 'AZBpqBy62hotG4yqczZhGBBb7CVkrT8jzTFWNnwonlcS8UNqXzWxj1gaR4Ts9NWldOVjm6BfPMEherjj',
            production: 'AeDljrMpr1QHOdEdbyN4ER-eroY3pF7rBCaAQe_-MM0yw4Tkyn3b9QvBR2fs79_taYs_7KZ2s2lVnKhV'
        },

        // Wait for the PayPal button to be clicked

        payment: function(data, actions) {
            return actions.payment.create({
                payment: {
                    transactions: [{
                        amount: {
                            total: '<?php echo $total; ?>',
                            currency: 'USD'
                        },
                        description: "Compra de productos a Online Sport Clothes:$<?PHP echo number_format($total, 3); ?>",
                        custom: "<?php echo $SID; ?>#<?php echo openssl_encrypt($idVenta, COD, KEY); ?>"
                    }]
                }
            });
        },

        onAuthorize: function(data, actions) {
            return actions.payment.execute().then(function() {
                //window.alert('Payment Completado');
                console.log(data);
                window.location = "verificador.php?paymentToken=" + data.paymentToken+"&paymentID="+data.paymentID;
            });
        }

    }, '#paypal-button-container');
</script>


<?php
include 'templates/Footer.php';
?>