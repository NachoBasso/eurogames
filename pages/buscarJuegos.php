<?php
require_once 'config.php';
require_once '../classes/Juego.php';
require_once 'functions.php';
iniciarSesionSiNoIniciada();

$juego = new Juego();
$categorias = $juego->obtenerCategorias();
$nombreCategoria = "";
$juegosRandom = $juego->seleccionarJuegosRandom(6);
$busqueda = isset($_GET['busqueda']) ? limpiarDatos($_GET['busqueda']) : '';
$orden = isset($_GET['orden']) ? limpiarDatos($_GET['orden']) : '';


if (empty($_GET['busqueda'])) {
    $resultados = $juego->ordenarJuegos($juegosRandom, $orden);
} else {
    $resultados = $juego->buscarJuego($busqueda, $orden);
    if (empty($resultados)) {
        $resultados = $juego->ordenarJuegos($juegosRandom, $orden);
    } else {
        $resultados = $juego->buscarJuego($busqueda, $orden);
    }
}

if (isset($_POST['agregar_al_carrito'])) {
    $idJuego = $_POST['id_juego'];
    $_SESSION['carrito'][] = $idJuego;
    $_SESSION['pedido_agregado']['estado'] = "por pedir";
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="../css/style.css">
</head>

<body class="bg-eurogames-blanco roboto-mono">
    <main class="mt-5">
        <?php require 'header.php'; ?>
        <div class="container-fluid mt-5">
            <h1 class='mr-5 mt-2 mb-1 text-shadow-blue text-center'>Resultados de tu búsqueda</h1>
            <div class="row mt-5 mb-5">
                <div class="col-md-3 ">
                    <div class="container-fluid register-left mt-5 mb-5">
                        <h1 class="mt-5 text-shadow-black p-3">¿Estás buscando un juego divertido?</h1><br>
                        <div class="text-center">
                            <span class="orange-icon mb-3 px-2"><svg class="orange-icon mb-3 px-2" width="100" height="75" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                    <path d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6 .1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z" />
                                </svg></span>
                        </div>
                        <h3 class="mt-5 mb-5">Puedes encontrar el mejor juego dentro de nuestro amplio stock</h3>
                        <div class="text-center">
                            <span class="orange-icon mb-3 px-2"><svg class="orange-icon mb-3 px-2" width="100" height="75" viewBox="0 0 512.00 512.00" xmlns="http://www.w3.org/2000/svg" fill="#FFAA50" stroke="#FFAA50" stroke-width="13.312000000000001">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path fill="#1d4c66" d="M192.215 39.565c-48.32 6.48-43.031 58.948-42.874 75.82l93.895 13.622c1.004-35.647 5.621-59.868 13.545-76.27 2.186-4.523 4.704-8.455 7.469-11.836zm107.012 2.48c-1.17.023-2.36.098-3.57.226-8.898.937-15.873 4.232-22.669 18.296-6.795 14.065-11.916 39.331-12.095 80.59l-.026 5.95-5.484 2.306c-50.104 21.083-82.1 39.796-100.16 55.334-18.06 15.538-21.362 26.305-19.541 34.088 1.82 7.783 10.734 16.375 25.353 23.44 14.62 7.064 33.96 12.409 52.951 15.634l11.293 1.918-83.045 192.608 120.118-32.106 51.136-112.484 51.045 86.37 113.43-30.929-86.887-142.223 10.647-3.617c15.087-5.126 32.096-12.698 45.673-21.45 13.578-8.753 23.156-18.91 25.407-26.64 1.125-3.864.992-7.055-1.028-11.146-2.02-4.09-6.362-9.116-14.296-14.488-15.869-10.744-45.623-22.39-93.485-32.809l-6.672-1.453-.398-6.816c-.762-13.052-6.589-35.803-17.293-53.27-9.366-15.284-21.176-26.136-36.961-27.242a38.595 38.595 0 0 0-3.443-.086zm-170.685 87.41c-79.404 44.697-85.83 54.663-86.757 71.6-2.209 40.397 42.597 46.791 89.698 59.957-6.026-5.765-11.447-10.033-13.329-18.075-3.82-16.327 4.783-34.158 25.328-51.834 17.067-14.684 43.279-30.404 80.907-47.62zM93.291 271.788c-22.445 48.26-38.283 103.023-59.254 153.977l87.723 43.328 17.77-35.852 57.154-132.556zm222.283 94.963l-15.752 34.648 43.543 12.375z"></path>
                                    </g>
                                </svg> </span>
                            <span class="orange-icon mb-3 px-2"><svg class="orange-icon mb-3 px-2" width="100" height="75" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                    <path d="M128.7 195.3l-82.8-51.8c-8-5-19-2.2-22.9 6.5A254.2 254.2 0 0 0 .5 239.3C-.1 248.4 7.6 256 16.7 256h97.1c8 0 14.1-6.3 15-14.2 1.1-9.3 3.2-18.3 6.2-26.9 2.6-7.3 .3-15.5-6.3-19.6zM319 8C298.9 2.8 277.8 0 256 0s-42.9 2.8-63 8c-9.2 2.4-13.9 12.6-10.4 21.4l37.5 104A16 16 0 0 0 235.1 144h41.8c6.8 0 12.8-4.2 15.1-10.6l37.5-104c3.5-8.8-1.2-19-10.4-21.4zM112 288H16c-8.8 0-16 7.2-16 16v64c0 8.8 7.2 16 16 16h96c8.8 0 16-7.2 16-16v-64c0-8.8-7.2-16-16-16zm0 128H16c-8.8 0-16 7.2-16 16v64c0 8.8 7.2 16 16 16h96c8.8 0 16-7.2 16-16v-64c0-8.8-7.2-16-16-16zm77.3-283.7l-36.3-90.8c-3.5-8.8-14.1-13-22.4-8.3a257.3 257.3 0 0 0 -71.6 59.9c-6.1 7.3-3.9 18.5 4.2 23.5l82.9 51.8c6.5 4.1 14.7 2.6 20.1-2.8 5.2-5.2 10.8-9.9 16.8-14.1 6.3-4.4 9.2-12.2 6.3-19.3zM398.2 256h97.1c9.1 0 16.7-7.6 16.2-16.7a254.1 254.1 0 0 0 -22.5-89.3c-3.9-8.6-14.9-11.5-22.9-6.5l-82.8 51.8c-6.6 4.1-8.9 12.2-6.3 19.6 3 8.6 5.2 17.6 6.2 26.9 .9 7.9 7.1 14.2 15 14.2zm54.9-162.9a257.3 257.3 0 0 0 -71.6-59.9c-8.3-4.7-18.9-.5-22.4 8.3l-36.3 90.8c-2.9 7.1 0 14.9 6.3 19.3 6 4.2 11.6 8.9 16.8 14.1 5.4 5.4 13.6 6.9 20.1 2.8l82.9-51.8c8.1-5 10.3-16.2 4.2-23.5zM496 288h-96c-8.8 0-16 7.2-16 16v64c0 8.8 7.2 16 16 16h96c8.8 0 16-7.2 16-16v-64c0-8.8-7.2-16-16-16zm0 128h-96c-8.8 0-16 7.2-16 16v64c0 8.8 7.2 16 16 16h96c8.8 0 16-7.2 16-16v-64c0-8.8-7.2-16-16-16zM240 177.6V472c0 4.4 3.6 8 8 8h16c4.4 0 8-3.6 8-8V177.6c-5.2-.9-10.5-1.6-16-1.6s-10.8 .7-16 1.6zm-64 41.5V472c0 4.4 3.6 8 8 8h16c4.4 0 8-3.6 8-8V189.4c-12.8 7.5-23.8 17.5-32 29.8zm128-29.8V472c0 4.4 3.6 8 8 8h16c4.4 0 8-3.6 8-8V219.1c-8.2-12.3-19.2-22.3-32-29.8z" />
                                </svg> </span>
                            <a href="listadoJuegos.php" class="btn btn-blue-orange mb-5 mt-5 text-center">VER JUEGOS</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 mt-5 ">
                    <div class="row  d-flex justify-content-center ">
                        <h2 class="text-black fw-bolder fs-1 text-center  px-5 mb-5">No se encontraron juegos para la consulta ingresada. Estos juegos quizás te interesen:</h2>
                        <?php
                        if (count($resultados) > 0) {
                        ?> <form method="GET" class="mb-1">
                                <div class="input-group d-flex justify-content-end fs-5 roboto-mono">
                                    <label for="orden" class="text-black fs-5 fw-bolder">ORDENAR POR:</label>
                                    <select class="custom-select  " id="orden" name="orden" onchange="this.form.submit()">
                                        <option value="precio_asc"  <?php echo (isset($_GET['orden']) && $_GET['orden'] === "precio_asc") ? 'selected' : ''; ?>>Precio: Menor a Mayor</option>
                                        <option value="precio_desc"  <?php echo (isset($_GET['orden']) && $_GET['orden'] === "precio_desc") ? 'selected' : ''; ?>>Precio: Mayor a Menor</option>
                                        <option value="nombre_asc"  <?php echo (isset($_GET['orden']) && $_GET['orden'] === "nombre_asc") ? 'selected' : ''; ?>>Nombre: A a la Z</option>
                                        <option value="nombre_desc"  <?php echo (isset($_GET['orden']) && $_GET['orden'] === "nombre_desc") ? 'selected' : ''; ?>>Nombre: Z a la A</option>
                                    </select>
                                    <input type="hidden" name="busqueda" value="<?php echo htmlspecialchars($_GET['busqueda'] ?? ''); ?>">

                                </div>
                            </form>
                            <?php foreach ($resultados as $fila) { ?>
                                <div class="col-md-5 col-sm-5 mb-5 text-black register-grey-buscar">
                                    <table class="table ">
                                        <tbody>
                                            <tr class="border-3">
                                                <td class="fw-bolder fs-5"><?php echo $fila['nombre_juego']; ?></td>
                                                <td class="fw-bolder fs-5 text-end"><?php echo $fila['precio'] . "€"; ?></td>
                                            </tr>
                                            <tr class="border-3 text-center">
                                                <td colspan="2" class="bg-eurogames-blanco">
                                                    <img src='<?php echo $fila['foto']; ?>' class="w-50 h-50 text-center" alt='<?php echo $fila['nombre_juego']; ?>'>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-black fw-bolder  fs-5 border-0 text-center bg-eurogames-blanco" colspan="2"><?php echo substr($fila['descripcion'], 0, 100) . " ... "; ?></td>
                                            </tr>
                                            <tr class="border-bottom-5 mb-5">
                                                <td colspan="2" class="text-black text-center bg-eurogames-blanco ">
                                                    <button class="btn btn-blue-orange" data-bs-toggle="modal" data-bs-target="#modalCoincidencia<?php echo $fila['id_juego']; ?>">VER +</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" class="text-center bg-eurogames-blanco">
                                                    <form method="post">
                                                        <input type="hidden" name="id_juego" value="<?php echo $fila['id_juego']; ?>">
                                                        <?php if ($fila['stock'] > 0) : ?>
                                                            <button type="submit" name="agregar_al_carrito" class="btn btn-orange-blue mb-3 mt-3" <?php echo !isset($fila['id_juego']) ? 'disabled' : ''; ?>>AÑADIR AL CARRITO</button>
                                                        <?php else : ?>
                                                            <button type="button" class="btn btn-secondary mt-3 btn-md p-2 font-weight-bold justify-content-center" disabled>SIN STOCK</button>
                                                </td>
                                            <?php endif; ?>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal fade" id="modalCoincidencia<?php echo $fila['id_juego']; ?>" tabindex="-1" aria-labelledby="modalCoincidenciaLabel<?php echo $fila['id_juego']; ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content bg-eurogames-blanco">
                                            <div class="modal-header d-flex justify-content-between align-items-center">
                                                <h5 class="modal-title text-black fs-2" id="modalCoincidenciaLabel<?php echo $fila['id_juego']; ?>">
                                                    <?php echo $fila['nombre_juego']; ?>
                                                </h5>
                                                <div class="ms-auto">
                                                    <p class="modal-title text-black-50 fs-3 mt-2 mb-2">
                                                        <strong class="text-black"><?php echo "€" . $fila['precio']; ?></strong>
                                                    </p>
                                                </div>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body fs-5">
                                                <div class="d-flex justify-content-center mx-auto ">
                                                    <img src='<?php echo $fila['foto']; ?>' class="d-block w-50 h-50 mx-auto mb-3" alt='<?php echo $fila['nombre_juego']; ?>'>
                                                </div>
                                                <p class="text-black-50 mt-5 border-top border-bottom mb-2"><strong class="text-black">Descripción:</strong> <?php echo $fila['descripcion']; ?></p>
                                                <p class="text-black-50  border-bottom mt-2 mb-2"><strong class="text-black">Editor:</strong> <?php echo $fila['editor']; ?></p>
                                                <p class="text-black-50  border-bottom mt-2 mb-2"><strong class="text-black">Año de Edición:</strong> <?php echo $fila['anio_edicion']; ?></p>
                                                <p class="text-black-50  border-bottom mt-2 mb-2"><strong class="text-black">Cantidad de Jugadores:</strong> <?php echo $fila['cantidad_jugadores']; ?></p>
                                                <p class="text-black-50  border-bottom mb mt-2-2"><strong class="text-black">Duración:</strong> <?php echo $fila['duracion_minutos']; ?> minutos</p>
                                                <p class="text-black-50  border-bottom mt-2 mb-2"><strong class="text-black">Edad Mínima:</strong> <?php echo $fila['edad_minima']; ?> años</p>
                                                <?php if (isset($fila['nombre_categoria'])) {
                                                    $nombreCategoria = $fila['nombre_categoria'];
                                                } else {
                                                    $nombreCategoria = "";
                                                    foreach ($categorias as $key => $categoria) {
                                                        if ($categoria['id_categoria'] == $fila['id_categoria']) {
                                                            $nombreCategoria = $categoria['nombre_categoria'];
                                                            break;
                                                        }
                                                    }
                                                }
                                                echo "<p class='text-black-50 mt-2 mb-2'><strong class='text-black'>Categoría:</strong> $nombreCategoria</p>";
                                                ?>
                                            </div>
                                            <div class="modal-footer">
                                                <form method="post">
                                                    <?php if ($fila['stock'] > 0) : ?>

                                                        <input type="hidden" name="id_juego" value="<?php echo $fila['id_juego']; ?>">
                                                        <button type="submit" name="agregar_al_carrito" class="btn btn-orange-blue mb-3 mt-3" <?php echo !isset($fila['id_juego']) ? 'disabled' : ''; ?>>AÑADIR AL CARRITO</button>
                                                    <?php else : ?>
                                                        <button type="button" class="btn btn-secondary mt-3 btn-md p-2 font-weight-bold justify-content-center" disabled>SIN STOCK</button>
                                                    <?php endif; ?>
                                                    <button type="button" class="btn btn-blue-orange" data-bs-dismiss="modal">CERRAR</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                        } else {
                            echo "<h2 class='text-black fw-bolder fs-1 text-center  px-5 mb-5'>No se encontraron juegos para la consulta ingresada. Estos juegos quizás te interesen:</h2>";
                            ?><form method="GET" class="mb-1">
                                <div class="input-group d-flex justify-content-end">
                                    <label for="orden" class="text-black fs-5 p-1">ORDENAR POR:</label>
                                    <select class="custom-select" id="orden" name="orden" onchange="this.form.submit()">
                                        <option value="precio_asc" <?php echo (isset($_GET['orden']) && $_GET['orden'] === "precio_asc") ? 'selected' : ''; ?>>Precio: Menor a Mayor</option>
                                        <option value="precio_desc" <?php echo (isset($_GET['orden']) && $_GET['orden'] === "precio_desc") ? 'selected' : ''; ?>>Precio: Mayor a Menor</option>
                                        <option value="nombre_asc" <?php echo (isset($_GET['orden']) && $_GET['orden'] === "nombre_asc") ? 'selected' : ''; ?>>Nombre: A a Z</option>
                                        <option value="nombre_desc" <?php echo (isset($_GET['orden']) && $_GET['orden'] === "nombre_desc") ? 'selected' : ''; ?>>Nombre: Z a A</option>
                                    </select>
                                    <input type="hidden" name="busqueda" value="<?php echo htmlspecialchars($_GET['busqueda'] ?? ''); ?>">

                                </div>
                            </form>
                            <?php foreach ($juegosRandom as $fila) { ?>
                                <div class="col-md-5 col-sm-5 mb-5 text-black register-grey-buscar">
                                    <table class="table ">
                                        <tbody>
                                            <tr class="border-3">
                                                <td class="fw-bolder fs-5"><?php echo $fila['nombre_juego']; ?></td>
                                                <td class="fw-bolder fs-5 text-end"><?php echo $fila['precio'] . "€"; ?></td>
                                            </tr>
                                            <tr class="border-3 text-center">
                                                <td colspan="2" class="bg-eurogames-blanco">
                                                    <img src='<?php echo $fila['foto']; ?>' class="w-50 h-50 text-center" alt='<?php echo $fila['nombre_juego']; ?>'>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-black fw-bolder  fs-5 border-0 text-center bg-eurogames-blanco" colspan="2"><?php echo substr($fila['descripcion'], 0, 100) . " ... "; ?></td>
                                            </tr>
                                            <tr class="border-bottom-5 mb-5">
                                                <td colspan="2" class="text-black text-center bg-eurogames-blanco ">
                                                    <button class="btn btn-blue-orange" data-bs-toggle="modal" data-bs-target="#JuegoModal<?php echo $fila['id_juego']; ?>">VER +</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" class="text-center bg-eurogames-blanco">
                                                    <?php if ($fila['stock'] > 0) : ?>
                                                        <form method="post">
                                                            <input type="hidden" name="id_juego" value="<?php echo isset($fila['id_juego']) ? $fila['id_juego'] : ''; ?>">
                                                            <button type="submit" name="agregar_al_carrito" class="btn btn-orange-blue mb-3 mt-3" <?php echo !isset($fila['id_juego']) ? 'disabled' : ''; ?>>AÑADIR AL CARRITO</button>
                                                        </form>
                                                </td>
                                            <?php else : ?>
                                                <button type="button" class="btn btn-secondary mt-3 btn-md p-2 font-weight-bold justify-content-center" disabled>SIN STOCK</button>
                                                </td>
                                            <?php endif; ?>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="modal fade" id="juegoModal<?php echo $fila['id_juego']; ?>" tabindex="-1" aria-labelledby="juegoModalLabel<?php echo $fila['id_juego']; ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content bg-eurogames-blanco">
                                            <div class="modal-header d-flex justify-content-between align-items-center">
                                                <h5 class="modal-title text-black fs-2" id="juegoModalLabel<?php echo $fila['id_juego']; ?>">
                                                    <?php echo $fila['nombre_juego']; ?>
                                                </h5>
                                                <div class="ms-auto">
                                                    <p class="modal-title text-black-50 fs-3 mt-2 mb-2">
                                                        <strong class="text-black"><?php echo "€" . $fila['precio']; ?></strong>
                                                    </p>
                                                </div>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body fs-5">
                                                <div class="d-flex justify-content-center mx-auto ">
                                                    <img src='<?php echo $fila['foto']; ?>' class="d-block w-50 h-50 mx-auto mb-3" alt='<?php echo $fila['nombre_juego']; ?>'>
                                                </div>
                                                <p class="text-black mt-5 border-top border-bottom mb-2"><strong>Descripción:</strong> <?php echo $fila['descripcion']; ?></p>
                                                <p class="text-black  border-bottom mt-2 mb-2"><strong>Editor:</strong> <?php echo $fila['editor']; ?></p>
                                                <p class="text-black  border-bottom mt-2 mb-2"><strong>Año de Edición:</strong> <?php echo $fila['anio_edicion']; ?></p>
                                                <p class="text-black  border-bottom mt-2 mb-2"><strong>Cantidad de Jugadores:</strong> <?php echo $fila['cantidad_jugadores']; ?></p>
                                                <p class="text-black  border-bottom mb mt-2-2"><strong>Duración:</strong> <?php echo $fila['duracion_minutos']; ?> minutos</p>
                                                <p class="text-black  border-bottom mt-2 mb-2"><strong>Edad Mínima:</strong> <?php echo $fila['edad_minima']; ?> años</p>
                                                <?php
                                                foreach ($categorias as $key => $categoria) {
                                                    if ($categoria['id_categoria'] == $fila['id_categoria']) {

                                                        $nombreCategoria = $categoria['nombre_categoria'];
                                                    }
                                                }
                                                ?>
                                                <p class="text-black  mt-2 mb-2"><strong>Categoria: </strong><?php echo $nombreCategoria; ?> </p>
                                            </div>
                                            <div class="modal-footer">
                                                <?php if ($fila['stock'] > 0) : ?>
                                                    <form method="post">
                                                        <input type="hidden" name="id_juego" value="<?php echo $fila['id_juego']; ?>">
                                                        <button type="submit" name="agregar_al_carrito" class="btn btn-orange-blue mb-3 mt-3" <?php echo !isset($fila['id_juego']) ? 'disabled' : ''; ?>>AÑADIR AL CARRITO</button>
                                                    <?php else : ?>
                                                        <button type="button" class="btn btn-secondary mt-3 btn-md p-2 font-weight-bold justify-content-center" disabled>SIN STOCK</button>
                                                    <?php endif; ?>
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
    </main>
    <footer>
        <?php require 'footer.php'; ?>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>