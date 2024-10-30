<?php
include 'global/config.php';
include 'global/conexion.php';
include 'carrito.php';
include 'templates/Header.php';
include 'templates/Menu.php';
?>

<hr>

<div class="container">

    <?php

    $ClientID = "AZBpqBy62hotG4yqczZhGBBb7CVkrT8jzTFWNnwonlcS8UNqXzWxj1gaR4Ts9NWldOVjm6BfPMEherjj";
    $Secret = "EAwFdocc7iigKNlKFsq0tNQu1VTQjX_UlrovNu78iQZaZxpCoeu6MYXs_HDzFxtoyiHnsmAAsLetuY-E";

    $Login = curl_init("https://api.sandbox.paypal.com/v1/oauth2/token");

    curl_setopt($Login, CURLOPT_RETURNTRANSFER, TRUE);

    curl_setopt($Login, CURLOPT_USERPWD, $ClientID . ":" . $Secret);

    curl_setopt($Login, CURLOPT_POSTFIELDS, "grant_type=client_credentials");

    $Respuesta = curl_exec($Login);

    $objRespuesta = json_decode($Respuesta);

    $AccessToken = $objRespuesta->access_token;

    //print_r($AccessToken);

    $venta = curl_init("https://api.sandbox.paypal.com/v1/payments/payment/" . $_GET['paymentID']);

    curl_setopt($venta, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $AccessToken));

    curl_setopt($venta, CURLOPT_RETURNTRANSFER, TRUE);

    $RespuestaVenta = curl_exec($venta);

    //print_r($RespuestaVenta);

    $objDatosTransaccion = json_decode($RespuestaVenta);

    $state = $objDatosTransaccion->state;
    $email = $objDatosTransaccion->payer->payer_info->email;

    $total = $objDatosTransaccion->transactions[0]->amount->total;
    $currency = $objDatosTransaccion->transactions[0]->amount->currency;
    $custom = $objDatosTransaccion->transactions[0]->custom;

    $clave = explode("#", $custom);

    $SID = $clave[0];
    $claveVenta = openssl_decrypt($clave[1], COD, KEY);

    curl_close($venta);
    curl_close($Login);

    if ($state == "approved") {
        $mensajePaypal = "<h3>El pago ha sido aprobado</h3>";

        $sentencia = $pdo->prepare("UPDATE `tblventas` 
    SET `PaypalDatos` = :PaypalDatos, `Status` = 'aprobado' 
    WHERE `tblventas`.`IdVentas` = :ID;");

        $sentencia->bindParam(":ID", $claveVenta);
        $sentencia->bindParam(":PaypalDatos", $RespuestaVenta);
        $sentencia->execute();

        $sentencia = $pdo->prepare("UPDATE tblventas SET Status='completo'
    WHERE ClaveTransaccion=:ClaveTransaccion
    AND Total=:TOTAL 
    AND ID=:ID");

        $sentencia->bindParam(':ClaveTransaccion', $SID);
        $sentencia->bindParam(':TOTAL', $total);
        $sentencia->bindParam(':ID', $claveVenta);
        $sentencia->execute();

        $completado = $sentencia->rowCount();
    } else {
        $mensajePaypal = "<h3>Hay un prblema con el pago de PayPal</h3>";
    }

    ?>

    <div class="jumbotron bg-dark text-white">
        <h1 class="display-4" align="center">¡ Listo !</h1>

        <hr class="my-4">

        <h3><?php echo $mensajePaypal; ?></ph3>

        <p>
            <?php
            if ($completado >= 1) {

                $sentencia = $pdo->prepare("SELECT * FROM tblfactura,tblproductos 
                WHERE tblfactura.IdProductos=tblproductos.IdProductos AND tblfactura.IdVentas = :ID ");

                $sentencia->bindParam(':ID', $claveVenta);
                $sentencia->execute();

                $listaProductos = $sentencia->fetchAll(PDO::FETCH_ASSOC);
                print_r($listaProductos);
            }
            ?>
            <br>
            <a class="btn btn-outline-primary" href="Pagina1.php">Regresar a comprar </a>
        </p>

    </div>

</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<?php include 'templates/Footer.php';?>