<nav class="navbar navbar-expand-lg" style="background-color: #34495E;">
    <div class="container">
        <img src="./ImgInicio/Logo.png" alt="" width="43px" class="d-inline-block align-text-top">
        <a class="navbar-brand text-white" href="Inicio.php">OnlineSportsClothes</a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="far fa-grip-lines"></i>
        </button>

        <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <a class="nav-link text-white" href="Tienda.php">Tienda</a>

                <a class="nav-link text-white" href="MostrarCarrito.php">Carrito
                    <i class="fas fa-cart-plus"></i>
                    (
                    <?php
                    echo (empty($_SESSION['CARRITO'])) ? 0 : count(($_SESSION['CARRITO']));
                    ?>
                    )
                </a>
            </ul>

            <a class="nav-link text-white" href="../tienda/login.php">
                Iniciar sesion <i class="fas fa-sign-in-alt"></i>
            </a>
            <a class="nav-link text-white" href="../tienda/registrarse.php">
                Registrarse <i class="fas fa-door-open"> </i>
            </a>
        </div>
    </div>
</nav>