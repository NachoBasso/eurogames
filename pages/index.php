<?php
session_start();
require_once 'config.php';
require_once '../classes/Juego.php';
require_once 'functions.php';

$juego = new Juego();

$listadoJuegos = $juego->listarJuegos();
$juegosOferta = $juego->listarJuegosPorCategoria("oferta");
$juegosNovedades = $juego->listarJuegosPorCategoria("novedad");


if (isset($_POST['agregar_al_carrito'])) {
  $idJuego = $_POST['id_juego'];
  $_SESSION['carrito'][] = $idJuego;
  header("Location: index.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="author" content="Ignacio Basso">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" 
        integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
  <link rel="stylesheet" href="../css/style.css">
  <title>Eurogames - Inicio</title>

</head>

<body class="roboto-mono bg-eurogames-blanco">
  <?php require 'header.php'; ?>
  <main>
    <div class="col-md-12">
      <div class="carousel-container">
        <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleSlidesOnly" data-bs-slide-to="0" 
                    class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleSlidesOnly" data-bs-slide-to="1" 
                    aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleSlidesOnly" data-bs-slide-to="2" 
                    aria-label="Slide 3"></button>
          </div>
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img src="../img/dados.png" class="d-block w-100 h-150 img-dados" alt="imagen dados">
              <div class="carousel-caption">
                <h5 class="text-shadow">A TIRAR LOS DADOS</h5>
                <p class="fw-bolder mt-2">¡Un poco de azar para dar vueltas las cosas!</p>
                <a href="listadoJuegos.php" class="btn boton-magico roboto-mono mt-3 mb-3">VER JUEGOS</a>
              </div>
            </div>
            <div class="carousel-item">
              <img src="../img/exploracion.png" class="d-block w-100 h-150" alt="imagen tablero">
              <div class="carousel-caption">
                <h5 class="text-shadow">DIVERSIÓN EN EL TABLERO</h5>
                <p class="fw-bolder mt-2">¿Tienes alma de explorador?...¡Estos juegos son para tí!</p>
                <a href="listadoJuegos.php" class="btn boton-magico roboto-mono mt-3 mb-3">VER JUEGOS</a>
              </div>
            </div>
            <div class="carousel-item">
              <img src="../img/cartas.png" class="d-block w-100 h-150" alt="imagen losetas">
              <div class="carousel-caption">
                <h5 class="text-shadow">JUEGA CON CARTAS O LOSETAS</h5>
                <p class="fw-bolder mt-2">¿Prefieres administrar recursos?... ¡Arma tu estrategia!</p>
                <a href="listadoJuegos.php" class="btn boton-magico roboto-mono mt-3 mb-3">VER JUEGOS</a>
              </div>
            </div>
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleSlidesOnly" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleSlidesOnly" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </button>
        </div>
      </div>
    </div>
    <div class="container-fluid mt-5 mb-5">
      
      <div class="row mt-5 mb-5 p-5">
        <div class="col-md-4 mb-4 border-orange box-blue text-white p-4 rounded mt-5">
          <h1 class="text-center justify-content-center align-content-center fw-bolder mt-2 mb-5 p-3 carousel-headline text-black">DESCUBRE NUESTRA FAMOSA LUDOTECA</h1>
          <hr>
          <div class="p-2">
            <h3 class="text-center text-black fw-bolder mt-3 roboto">Te mostramos una amplia selección de Eurogames para que puedas vivir una experiencia de juego excepcional.</h3>
          </div>
          <div class="text-center">
            <a href="listadoJuegos.php" class="btn btn-blue-orange mb-5 mt-5 text-center">VER JUEGOS</a>
        </div>
        <div class="text-center">
          <a href="listadoJuegos.php">
            <span class="orange-icon"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="100" height="75"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                <path d="M464 6.1c9.5-8.5 24-8.1 33 .9l8 8c9 9 9.4 23.5 .9 33l-85.8 95.9c-2.6 2.9-4.1 6.7-4.1 10.7V176c0 8.8-7.2 16-16 16H384.2c-4.6 0-8.9 1.9-11.9 5.3L100.7 500.9C94.3 508 85.3 512 75.8 512c-8.8 0-17.3-3.5-23.5-9.8L9.7 459.7C3.5 453.4 0 445 0 436.2c0-9.5 4-18.5 11.1-24.8l111.6-99.8c3.4-3 5.3-7.4 5.3-11.9V272c0-8.8 7.2-16 16-16h34.6c3.9 0 7.7-1.5 10.7-4.1L464 6.1zM432 288c3.6 0 6.7 2.4 7.7 5.8l14.8 51.7 51.7 14.8c3.4 1 5.8 4.1 5.8 7.7s-2.4 6.7-5.8 7.7l-51.7 14.8-14.8 51.7c-1 3.4-4.1 5.8-7.7 5.8s-6.7-2.4-7.7-5.8l-14.8-51.7-51.7-14.8c-3.4-1-5.8-4.1-5.8-7.7s2.4-6.7 5.8-7.7l51.7-14.8 14.8-51.7c1-3.4 4.1-5.8 7.7-5.8zM87.7 69.8l14.8 51.7 51.7 14.8c3.4 1 5.8 4.1 5.8 7.7s-2.4 6.7-5.8 7.7l-51.7 14.8L87.7 218.2c-1 3.4-4.1 5.8-7.7 5.8s-6.7-2.4-7.7-5.8L57.5 166.5 5.8 151.7c-3.4-1-5.8-4.1-5.8-7.7s2.4-6.7 5.8-7.7l51.7-14.8L72.3 69.8c1-3.4 4.1-5.8 7.7-5.8s6.7 2.4 7.7 5.8zM208 0c3.7 0 6.9 2.5 7.8 6.1l6.8 27.3 27.3 6.8c3.6 .9 6.1 4.1 6.1 7.8s-2.5 6.9-6.1 7.8l-27.3 6.8-6.8 27.3c-.9 3.6-4.1 6.1-7.8 6.1s-6.9-2.5-7.8-6.1l-6.8-27.3-27.3-6.8c-3.6-.9-6.1-4.1-6.1-7.8s2.5-6.9 6.1-7.8l27.3-6.8 6.8-27.3c.9-3.6 4.1-6.1 7.8-6.1z" />
              </svg> </span>
            <span class="orange-icon"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width="100" height="75"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                <path d="M352 124.5l-51.9-13c-6.5-1.6-11.3-7.1-12-13.8s2.8-13.1 8.7-16.1l40.8-20.4L294.4 28.8c-5.5-4.1-7.8-11.3-5.6-17.9S297.1 0 304 0H416h32 16c30.2 0 58.7 14.2 76.8 38.4l57.6 76.8c6.2 8.3 9.6 18.4 9.6 28.8c0 26.5-21.5 48-48 48H538.5c-17 0-33.3-6.7-45.3-18.7L480 160H448v21.5c0 24.8 12.8 47.9 33.8 61.1l106.6 66.6c32.1 20.1 51.6 55.2 51.6 93.1C640 462.9 590.9 512 530.2 512H496 432 32.3c-3.3 0-6.6-.4-9.6-1.4C13.5 507.8 6 501 2.4 492.1C1 488.7 .2 485.2 0 481.4c-.2-3.7 .3-7.3 1.3-10.7c2.8-9.2 9.6-16.7 18.6-20.4c3-1.2 6.2-2 9.5-2.2L433.3 412c8.3-.7 14.7-7.7 14.7-16.1c0-4.3-1.7-8.4-4.7-11.4l-44.4-44.4c-30-30-46.9-70.7-46.9-113.1V181.5v-57zM512 72.3c0-.1 0-.2 0-.3s0-.2 0-.3v.6zm-1.3 7.4L464.3 68.1c-.2 1.3-.3 2.6-.3 3.9c0 13.3 10.7 24 24 24c10.6 0 19.5-6.8 22.7-16.3zM130.9 116.5c16.3-14.5 40.4-16.2 58.5-4.1l130.6 87V227c0 32.8 8.4 64.8 24 93H112c-6.7 0-12.7-4.2-15-10.4s-.5-13.3 4.6-17.7L171 232.3 18.4 255.8c-7 1.1-13.9-2.6-16.9-9s-1.5-14.1 3.8-18.8L130.9 116.5z" />
              </svg></span>
            <span class="orange-icon"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="100" height="75"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                <path d="M489.2 287.9h-27.4c-2.6 0-4.6 2-4.6 4.6v32h-36.6V146.2c0-2.6-2-4.6-4.6-4.6h-27.4c-2.6 0-4.6 2-4.6 4.6v32h-36.6v-32c0-2.6-2-4.6-4.6-4.6h-27.4c-2.6 0-4.6 2-4.6 4.6v32h-36.6v-32c0-6-8-4.6-11.7-4.6v-38c8.3-2 17.1-3.4 25.7-3.4 10.9 0 20.9 4.3 31.4 4.3 4.6 0 27.7-1.1 27.7-8v-60c0-2.6-2-4.6-4.6-4.6-5.1 0-15.1 4.3-24 4.3-9.7 0-20.9-4.3-32.6-4.3-8 0-16 1.1-23.7 2.9v-4.9c5.4-2.6 9.1-8.3 9.1-14.3 0-20.7-31.4-20.8-31.4 0 0 6 3.7 11.7 9.1 14.3v111.7c-3.7 0-11.7-1.4-11.7 4.6v32h-36.6v-32c0-2.6-2-4.6-4.6-4.6h-27.4c-2.6 0-4.6 2-4.6 4.6v32H128v-32c0-2.6-2-4.6-4.6-4.6H96c-2.6 0-4.6 2-4.6 4.6v178.3H54.8v-32c0-2.6-2-4.6-4.6-4.6H22.8c-2.6 0-4.6 2-4.6 4.6V512h182.9v-96c0-72.6 109.7-72.6 109.7 0v96h182.9V292.5c.1-2.6-1.9-4.6-4.5-4.6zm-288.1-4.5c0 2.6-2 4.6-4.6 4.6h-27.4c-2.6 0-4.6-2-4.6-4.6v-64c0-2.6 2-4.6 4.6-4.6h27.4c2.6 0 4.6 2 4.6 4.6v64zm146.4 0c0 2.6-2 4.6-4.6 4.6h-27.4c-2.6 0-4.6-2-4.6-4.6v-64c0-2.6 2-4.6 4.6-4.6h27.4c2.6 0 4.6 2 4.6 4.6v64z" />
              </svg></span>
          </a>
        </div>
      </div>
      <div class="col-md-7 mb-4 order-md-2 order-2 border-orange box-eurogames-grey text-white p-4 rounded ms-md-4 mt-5">
        <div id="randomGamesCarousel" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner">
            <?php
            foreach ($listadoJuegos as $key => $juego) : ?>
              <div class="carousel-item <?php echo $key === 0 ? 'active' : ''; ?>">
                <h2 class="card-title text-center text-shadow mb-4 mt-4"><?php echo $juego['nombre_juego']; ?></h2>
                <img src="<?php echo $juego['foto']; ?>" class="d-block w-50 h-50 mx-auto" alt="...">
                <h5 class="card-text text-center "><?php echo implode(' ', array_slice(explode(' ', $juego['descripcion']), 0, 15)); ?>... 
                    <button class="btn btn-blue-orange " data-bs-toggle="modal" 
                            data-bs-target="#juegoModal<?php echo $juego['id_juego']; ?>">VER +</button></h5>
                <form method="post">
                  <input type="hidden" name="id_juego" value="<?php echo $juego['id_juego']; ?>">
                  <button type="submit" name="agregar_al_carrito" class="btn btn-orange-blue mb-3 mt-3">AÑADIR AL CARRITO</button>
                </form>
              </div>
          </div>
          <div class="modal fade" id="juegoModal<?php echo $juego['id_juego']; ?>" tabindex="-1" 
                aria-labelledby="juegoModalLabel<?php echo $juego['id_juego']; ?>" 
              aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title text-black" id="juegoModalLabel<?php echo $juego['id_juego']; ?>">
                      <?php echo $juego['nombre_juego']; ?></h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <img src="<?php echo $juego['foto']; ?>" class="d-block w-50 h-50 mx-auto mb-3"
                       alt="<?php echo $juego['nombre_juego']; ?>">
                  <hr>
                  <p class="text-black mt-5"><strong>Descripción:</strong> <?php echo $juego['descripcion']; ?></p>
                  <hr>
                  <p class="text-black"><strong>Precio:</strong> <?php echo "€". $juego['precio']; ?></p>
                  <p class="text-black"><strong>Editor:</strong> <?php echo $juego['editor']; ?></p>
                  <p class="text-black"><strong>Año de Edición:</strong> <?php echo $juego['anio_edicion']; ?></p>
                  <p class="text-black"><strong>Cantidad de Jugadores:</strong> <?php echo $juego['cantidad_jugadores']; ?></p>
                  <p class="text-black"><strong>Duración:</strong> <?php echo $juego['duracion_minutos']; ?> minutos</p>
                  <p class="text-black"><strong>Edad Mínima:</strong> <?php echo $juego['edad_minima']; ?> años</p>
                </div>
                <div class="modal-footer">
                  <form method="post">
                    <input type="hidden" name="id_juego" value="<?php echo $juego['id_juego']; ?>">
                    <button type="submit" name="agregar_al_carrito" class="btn btn-orange-blue">AÑADIR AL CARRITO</button>
                    <button type="button" class="btn btn-blue-orange" data-bs-dismiss="modal">CERRAR</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#randomGamesCarousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#randomGamesCarousel" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>
            </div>
    </div>
    </div>
    </div>
    </div>

    <div class="row">
      <div class="col-md-6 mt-5 mb-5">
        <h2 class="text-center text-black text-shadow-blue mb-5">OFERTAS </h2>
        <div id="carouselOferta" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner">
            <?php foreach ($juegosOferta as $index => $images) : ?>
              <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?> ">
                <img src="<?php echo $images['foto']; ?>" class="d-flex w-50 justify-content-center mx-auto" alt="<?php echo $juego['nombre_juego']; ?>">
                <div class="d-flex justify-content-center mt-3">
                  <form method="post">
                    <input type="hidden" name="id_juego" value="<?php echo $juego['id_juego']; ?>">
                    <button type="submit" name="agregar_al_carrito" class="btn btn-orange-blue mb-5">AÑADIR AL CARRITO</button>
                  </form>
                  <button class="btn btn-blue-orange mx-2 mb-5" data-bs-toggle="modal" data-bs-target="#juegoModal<?php echo $juego['id_juego']; ?>">VER +</button>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#carouselOferta" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#carouselOferta" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Siguiente</span>
          </button>
        </div>
      </div>
      <div class="col-md-6 mt-5 mb-5">
        <h2 class="text-center text-black text-shadow-blue mb-5">NOVEDADES</h2>
        <div id="carouselNovedades" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner">
            <?php foreach ($juegosNovedades as $index => $image) : ?>
              <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?> ">
                <img src="<?php echo $image['foto']; ?>" class="d-flex w-50  justify-content-center mx-auto" alt="novedad">
                <div class="d-flex justify-content-center mt-3">
                  <form method="post">
                    <input type="hidden" name="id_juego" value="<?php echo $juego['id_juego']; ?>">
                    <button type="submit" name="agregar_al_carrito" class="btn btn-orange-blue  mb-5">AÑADIR AL CARRITO</button>
                  </form>
                  <button class="btn btn-blue-orange mx-2 mb-5 mt-1" data-bs-toggle="modal" data-bs-target="#juegoModal<?php echo $juego['id_juego']; ?>">VER +</button>
                </div>
              </div>
            <?php endforeach; ?>
          </div>

          <button class="carousel-control-prev" type="button" data-bs-target="#carouselNovedades" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#carouselNovedades" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Siguiente</span>
          </button>
        </div>
      </div>
    </div>
    </div>
  </main>
  <script src="https://kit.fontawesome.com/0163624b84.js" crossorigin="anonymous"></script> <?php require 'footer.php' ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="../js/app.js"></script>
</body>

</html>