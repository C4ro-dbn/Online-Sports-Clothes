<?php
include 'templates/Header.php';
include 'templates/Menu.php';
?>

<hr>
<div id="registrarse">
    <div class="container">
        <div id="registrarse-row" class="row justify-content-center align-items-center">
            <div id="registrarse-column" class="col-md-6">
                <div class="registrarse-box col-md-12">
                    <form id="registrarse-form" class="form" action="registrarse.php" method="post">
                        <h3 class="text-center text-info">Registrarse</h3>
                        <div class="form-group">
                            <label for="username" class="text-info">Correo Electronico:</label><br>
                            <input type="email" name="Email" id="Email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="email" class="text-info">Contraseña:</label><br>
                            <input type="password" name="Contraseña" id="Contraseña" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="password" class="text-info">Confirmar Contraseña:</label><br>
                            <input type="password" name="confirmar_contraseña" id="confirmar_contraseña" class="form-control">
                        </div>
                        <div class="form-group">
                            <input type="submit" name="submit" class="btn btn-info btn-md" value="Enviar">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>