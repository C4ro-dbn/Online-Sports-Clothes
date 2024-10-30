<?php

session_start();

$mensaje = "";

if (isset($_POST['btnAccion'])) {

    switch ($_POST['btnAccion']) {

        case 'Agregar':

            if (is_numeric(openssl_decrypt($_POST['id'], COD, KEY))) {
                $ID = openssl_decrypt($_POST['id'], COD, KEY);
                $mensaje .= "OK ID Correcto: " . $ID . "<br/>";
            } else {
                $mensaje .= "UPS... ID Incorrecto" . $ID . "<br/>";
            }

            if (is_string(openssl_decrypt($_POST['nombre'], COD, KEY))) {
                $NOMBRE = openssl_decrypt($_POST['nombre'], COD, KEY);
                $mensaje .= "OK Nombre Correcto: " . $NOMBRE . "<br/>";
            } else {
                $mensaje .= "UPS... El Nombre es Incorrecto o Pasa Algo" . "<br/>";
                break;
            }

            if (is_string(openssl_decrypt($_POST['precio'], COD, KEY))) {
                $PRECIO = openssl_decrypt($_POST['precio'], COD, KEY);
                $mensaje .= "OK Precio Correcto: " . $PRECIO . "<br/>";
            } else {
                $mensaje .= "UPS... El Precio es Incorrecto o Pasa Algo" . "<br/>";
                break;
            }

            if (is_string(openssl_decrypt($_POST['cantidad'], COD, KEY))) {
                $CANTIDAD = openssl_decrypt($_POST['cantidad'], COD, KEY);
                $mensaje .= "OK Cantidad Correcto: " . $CANTIDAD . "<br/>";
            } else {
                $mensaje .= "UPS... El Cantidad es Incorrecto o Pasa Algo" . "<br/>";
                break;
            }


            if (!isset($_SESSION['CARRITO'])) {
                $producto = array(
                    'ID' => $ID,
                    'NOMBRE' => $NOMBRE,
                    'PRECIO' => $PRECIO,
                    'CANTIDAD' => $CANTIDAD
                );
                $_SESSION['CARRITO'][0] = $producto;
                $mensaje = "Producto Agregado al carrito <br/>";
            } else {

                $idprod = array_column($_SESSION['CARRITO'], "ID");

                if (in_array($ID, $idprod)) {
                    echo "<script>alert('El prodcuto ya ha sido seleccionado...');</script><br/>";
                    $mensaje = "";
                }

                $NumeroProductos = count($_SESSION['CARRITO']);
                $producto = array(
                    'ID' => $ID,
                    'NOMBRE' => $NOMBRE,
                    'PRECIO' => $PRECIO,
                    'CANTIDAD' => $CANTIDAD
                );
                $_SESSION['CARRITO'][$NumeroProductos] = $producto;
                $mensaje = "Producto Agregado al carrito <br/>";
            }
            //$mensaje = print_r($_SESSION, true);

            break;

        case "Eliminar":

            if (is_numeric(openssl_decrypt($_POST['id'], COD, KEY))) {
                $ID = openssl_decrypt($_POST['id'], COD, KEY);

                foreach ($_SESSION['CARRITO'] as $indice => $producto) {
                    if ($producto['ID'] == $ID) {
                        unset($_SESSION['CARRITO'][$indice]);
                        echo "<script>alert('Elemento borrado...');</script>";
                    }
                }
            } else {
                $mensaje .= "Ups... Id Incorrecto" . $ID . "<br/>";
            }

            break;
    }
}
