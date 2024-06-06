<?php 
require_once 'functions.php';
iniciarSesionSiNoIniciada(); 
$nombreUsuario = isset($_SESSION['nombre_usuario']) ? limpiarDatos($_SESSION['nombre_usuario']) : null;
$cantidadEnCarrito = isset($_SESSION['carrito']) ? count($_SESSION['carrito']) : 0;
$esAdministrador = isset($_SESSION['es_administrador']) ? limpiarDatos($_SESSION['es_administrador']) : null;
?>


<header class=" roboto-mono fixed-top"> 
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="../img/logo.png" alt="logo-eurogames" width="150" class="d-inline-block">
            </a>
            <button class="navbar-toggler pl-5" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" 
                    aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse w-100" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 mt-1">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="./listadoJuegos.php">Juegos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="listadoJuegos.php?categoria=novedad">Novedades</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="listadoJuegos.php?categoria=oferta">Ofertas</a>
                    </li>
                </ul>
                <form class="d-flex flex-column flex-lg-row align-items-center justify-content-center justify-content-lg-start me-lg-5 ms-lg-0"
                      action="buscarJuegos.php" method="GET">
                    <div class="input-group mb-1">
                        <input class="form-control" type="search" placeholder="Busca tu juego" name="busqueda">
                        <button class="btn btn-naranja-outline-success " type="submit">BUSCAR</button>
                    </div>
                </form>
                <div class="mr-5">
                    <div class="d-flex align-items-center justify-content-center justify-content-lg-end ml-5">
                        <?php if ($nombreUsuario): ?>
                            <button class="btn btn-naranja-outline-success mt-2 d-flex align-items-center 
                                            justify-content-center mb-2 same-height-button" 
                            onclick="window.location.href='<?php echo $esAdministrador ?
                                                         'administrador.php' : 'perfilUsuario.php'; ?>'" rel="nofollow">
                                ¡Hola <?php echo htmlspecialchars($nombreUsuario); ?>!
                            </button>
                            <?php if (!$esAdministrador): ?>
                                <button class="btn btn-naranja-outline-success mt-2 d-flex align-items-center 
                                                justify-content-center ms-2 mb-2 same-height-button"
                                 onclick="window.location.href='./carrito.php'" rel="nofollow">
                                    <svg  class="orange-header"xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="30" height="30" >
                                        <path d="M0 24C0 10.7 10.7 0 24 0H69.5c22 0 41.5 12.8 50.6 32h411c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3H170.7l5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5H488c13.3 0 24 10.7 24 24s-10.7 24-24 24H199.7c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5H24C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z"/>
                                    </svg>
                                    <?php if ($cantidadEnCarrito > 0): ?>
                                        <span class="badge rounded-pill bg-danger"><?php echo $cantidadEnCarrito; ?></span>
                                    <?php endif; ?>
                                </button>
                            <?php endif; ?>
                            <button class="btn btn-naranja-outline-success mt-2 d-flex align-items-center justify-content-center ms-2 mb-2 same-height-button" onclick="window.location.href='./logout.php'" rel="nofollow">
                                <svg class="orange-header"xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="30" height="30" >
                                    <path d="M497 273L329 441c-15 15-41 4.5-41-17v-96H152c-13.3 0-24-10.7-24-24v-96c0-13.3 10.7-24 24-24h136V88c0-21.4 25.9-32 41-17l168 168c9.3 9.4 9.3 24.6 0 34zM192 436v-40c0-6.6-5.4-12-12-12H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h84c6.6 0 12-5.4 12-12V76c0-6.6-5.4-12-12-12H96c-53 0-96 43-96 96v192c0 53 43 96 96 96h84c6.6 0 12-5.4 12-12z"/>
                                </svg>
                            </button>
                        <?php else: ?>
                            <button class="btn btn-naranja-outline-success mt-2 d-flex align-items-center justify-content-center mb-2 same-height-button" onclick="window.location.href='./perfilUsuario.php'" rel="nofollow">
                                <svg class="orange-header" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="30" height="30" >
                                    <path d="M64 416L168.6 180.7c15.3-34.4 40.3-63.5 72-83.7l146.9-94c3-1.9 6.5-2.9 10-2.9C407.7 0 416 8.3 416 18.6v1.6c0 2.6-.5 5.1-1.4 7.5L354.8 176.9c-1.9 4.7-2.8 9.7-2.8 14.7c0 5.5 1.2 11 3.4 16.1L448 416H240.9l11.8-35.4 40.4-13.5c6.5-2.2 10.9-8.3 10.9-15.2s-4.4-13-10.9-15.2l-40.4-13.5-13.5-40.4C237 276.4 230.9 272 224 272s-13 4.4-15.2 10.9l-13.5 40.4-40.4 13.5C148.4 339 144 345.1 144 352s4.4 13 10.9 15.2l40.4 13.5L207.1 416H64zM279.6 141.5c-1.1-3.3-4.1-5.5-7.6-5.5s-6.5 2.2-7.6 5.5l-6.7 20.2-20.2 6.7c-3.3 1.1-5.5 4.1-5.5 7.6s2.2 6.5 5.5 7.6l20.2 6.7 6.7 20.2c1.1 3.3 4.1 5.5 7.6 5.5s6.5-2.2 7.6-5.5l6.7-20.2 20.2-6.7c3.3-1.1 5.5-4.1 5.5-7.6s-2.2-6.5-5.5-7.6l-20.2-6.7-6.7-20.2zM32 448H480c17.7 0 32 14.3 32 32s-14.3 32-32 32H32c-17.7 0-32-14.3-32-32s14.3-32 32-32z"/>
                                </svg>
                                Iniciar<br> Sesión
                            </button>
                            <button class="btn btn-naranja-outline-success mt-2 d-flex align-items-center justify-content-center ms-2 mb-2 same-height-button" onclick="window.location.href='./carrito.php'" rel="nofollow">
                                <svg class="orange-header"xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="30" height="30">
                                    <path d="M0 24C0 10.7 10.7 0 24 0H69.5c22 0 41.5 12.8 50.6 32h411c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3H170.7l5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5H488c13.3 0 24 10.7 24 24s-10.7 24-24 24H199.7c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5H24C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z"/>
                                </svg>
                                <?php if ($cantidadEnCarrito > 0): ?>
                                    <span class="badge rounded-pill bg-danger"><?php echo $cantidadEnCarrito; ?></span>
                                <?php endif; ?>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
