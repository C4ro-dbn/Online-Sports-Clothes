<?php
include 'global/config.php';
include 'global/conexion.php';
include 'carrito.php';
include 'templates/Header.php';
include 'templates/Menu.php';
?>
<hr>
<div id="login">
    <div class="container">
        <div id="login-row" class="row justify-content-center align-items-center">
            <div id="login-column" class="col-md-6">
                <div class="login-box col-md-12">
                    <form id="login" class="form" action="login.php" method="post">
                        <h3 class="text-center text-info">Iniciar sesión</h3>
                        <div class="form-group">
                            <label for="username" class="text-info">Correo Electronico:</label><br>
                            <input type="email" name="Email" id="Email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="password" class="text-info">Contraseña:</label><br>
                            <input type="password" name="Contraseña" id="Contraseña" class="form-control">
                        </div>
                        <div class="form-group">
                            <input type="submit" name="" class="btn btn-info btn-md" value="Ingresar">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>