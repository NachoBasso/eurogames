<?php
require_once 'config.php';
require_once '../classes/Juego.php';
require_once 'functions.php';
iniciarSesionSiNoIniciada();

$juego = new Juego();

if (isset($_GET['busqueda'])) {
    $busqueda = limpiarDatos($_GET['busqueda']);
    $resultados = !empty($busqueda) ? $juego->buscarJuego($busqueda) : [];
} else {
    $busqueda = '';
    $resultados = [];
}

if (isset($_POST['agregar_al_carrito'])) {
    $idJuego = $_POST['id_juego'];
    $_SESSION['carrito'][] = $idJuego;
    header("Location: buscarJuegos.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Ignacio Basso">
    <title>Buscando Juegos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body class="bg-eurogames-blanco">
    <main class="mt-5">
        <?php require 'header.php'; ?>
        <div class="container-fluid">
            <h1 class="mr-5 mt-5 mb-1 text-shadow-blue text-center">Resultados de la búsqueda:</h1>
            <div class="row mx-0 d-flex justify-content-center p-1">
                <div class="col-md-3 mt-5 mx-auto register-left p-3">
                    <h1 class="mt-5 text-shadow-black">¿Estás buscando un juego divertido?</h1><br>
                    <h3 class="mt-5">Puedes encontrar el mejor juego dentro de nuestro amplio stock</h3>
                    <form action="listadoJuegos.php">
                        <input type="submit" value="VER JUEGOS" class="mb-5 btn justify-content-center login_btn btn-blue-orange text-white font-weight-bold">
                    </form>
                </div>
                <div class="col-md-8 mt-5 register-right ">
                    <div class="row m-auto d-flex justify-content-center">
                        <?php
                        if (count($resultados) > 0) {
                            echo "<h2 class='mt-4 mb-3 p-5'>Estos juegos pueden ser los que buscas:</h2><div class='row justify-content-center'>";
                            foreach ($resultados as $fila) {
                        ?>
                                <div class="col-md-4 col-sm-6 mb-4 text-center">
                                    <div class="card register-left-grey card-login text-white pr-4 pl-4 m-4">
                                        <img src='<?php echo $fila['foto']; ?>' class='card-img-top pr-5 pl-5 m-4 w-75' alt='<?php echo $fila['nombre_juego']; ?>'>
                                        <hr class='my-4 border-top'>
                                        <div class="card-body">
                                            <h5 class='card-title'><?php echo $fila['nombre_juego']; ?></h5>
                                            <p class="card-text text-center "><?php echo implode(' ', array_slice(explode(' ', $fila['descripcion']), 0, 15)); ?>...
                                                <button class="btn btn-blue-orange " data-bs-toggle="modal" data-bs-target="#modal<?php echo $fila['id_juego']; ?>">VER +</button>
                                            </p>
                                            <hr>
                                            <p class='card-text'><strong>Precio:</strong> <?php echo $fila['precio']; ?></p>
                                          
                                            <form method="post">
                                                <input type="hidden" name="id_juego" value="<?php echo $fila['id_juego']; ?>">
                                                <button type="submit" name="agregar_al_carrito" class="btn btn-orange-blue mb-3 mt-3">AÑADIR AL CARRITO</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                            echo "</div>";
                        } else {
                            echo "<h2 class=' mt-2 py-3 px-5 text-center' >No se encontraron juegos para la consulta ingresada</h2>";
                            echo "<hr class='text-white'>";
                            echo "<h2 class='p-5 mb-3 text-center'>Estos juegos quizás te interesen:</h2>";
                            echo "<div class='row'>";
                            foreach ($juego->seleccionarJuegosRandom(6) as $fila) {
                            ?>
                                <div class='col-md-7 col-sm-6 mb-4'>
                                    <div class='card register-left-grey card-login text-white pr-4 pl-4 m-4'>
                                        <img src='<?php echo $fila['foto']; ?>' class='card-img-top pr-5 pl-5 m-4 w-75' alt='<?php echo $fila['nombre_juego']; ?>'>
                                        <hr class='my-4 border-top'>
                                        <div class='card-body'>
                                            <h5 class='card-title'><?php echo $fila['nombre_juego']; ?></h5>
                                            <p class="card-text text-center "><?php echo implode(' ', array_slice(explode(' ', $fila['descripcion']), 0, 15)); ?>...
                                                <button class="btn btn-blue-orange " data-bs-toggle="modal" data-bs-target="#modal<?php echo $fila['id_juego']; ?>">VER +</button>
                                            </p>
                                            <hr>
                                            <p class='card-text'><strong>Precio:</strong> <?php echo $fila['precio']; ?></p>
  
                                            <form method='post'>
                                                <input type='hidden' name='id_juego' value='<?php echo $fila['id_juego']; ?>'>
                                                <button type="submit" name="agregar_al_carrito" class="btn btn-orange-blue mb-3 mt-3">AÑADIR AL CARRITO</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="modal<?php echo $fila['id_juego']; ?>" tabindex="-1" aria-labelledby="modalLabel<?php echo $fila['id_juego']; ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalLabel<?php echo $fila['id_juego']; ?>"><?php echo $fila['nombre_juego']; ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body fs-5">
                                                <div class="d-flex justify-content-center mx-auto ">
                                                    <img src='<?php echo $fila['foto']; ?>' class='card-img-top pr-5 pl-5 m-4 w-50 justify-content-center mx-auto' alt='<?php echo $fila['nombre_juego']; ?>'>

                                                </div>
                                                <p class="text-black mt-5 border-top border-bottom mb-2"><strong>Descripción:</strong> <?php echo $fila['descripcion']; ?></p>
                                                <p class="text-black  border-bottom mt-2 mb-2"><strong>Precio:</strong> <?php echo "€" . $fila['precio']; ?></p>
                                                <p class="text-black  border-bottom mt-2 mb-2"><strong>Editor:</strong> <?php echo $fila['editor']; ?></p>
                                                <p class="text-black  border-bottom mt-2 mb-2"><strong>Año de Edición:</strong> <?php echo $fila['anio_edicion']; ?></p>
                                                <p class="text-black  border-bottom mt-2 mb-2"><strong>Cantidad de Jugadores:</strong> <?php echo $fila['cantidad_jugadores']; ?></p>
                                                <p class="text-black  border-bottommb mt-2-2"><strong>Duración:</strong> <?php echo $fila['duracion_minutos']; ?> minutos</p>
                                                <p class="text-black  border-bottom mt-2 mb-2"><strong>Edad Mínima:</strong> <?php echo $fila['edad_minima']; ?> años</p>
                                            </div>
                                            <div class="modal-footer">
                                                <form method="post">
                                                    <input type="hidden" name="id_juego" value="<?php echo $fila['id_juego']; ?>">
                                                    <button type="submit" name="agregar_al_carrito" class="btn btn-orange-blue mb-3 mt-3">AÑADIR AL CARRITO</button>
                                                    <button type="button" class="btn btn-blue-orange" data-bs-dismiss="modal">CERRAR</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </main>
    <footer>
        <?php require 'footer.php'; ?>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>