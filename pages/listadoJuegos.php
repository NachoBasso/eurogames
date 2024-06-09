<?php
session_start();

require_once "functions.php";
require_once '../classes/Juego.php';

$juego = new Juego();
$juegos = $juego->listarJuegos();
$listarJuegos = $juego->listarJuegos();

$editores = $juego->obtenerEditores();
$categorias = $juego->obtenerCategorias();
$busqueda = isset($_GET['busqueda']) ? limpiarDatos($_GET['busqueda']) : '';
$orden = isset($_GET['orden']) ? limpiarDatos($_GET['orden']) : '';
$editorSeleccionado = isset($_GET['editor']) ? limpiarDatos($_GET['editor']) : null;
$categoriaSeleccionada = isset($_GET['categoria']) ? limpiarDatos($_GET['categoria']) : null;
$ordenSeleccionado = isset($_GET['orden']) ? limpiarDatos($_GET['orden']) : null;
$precioFiltrado = isset($_GET['precio']) ? limpiarDatos($_GET['precio']) : null;
$duracionFiltrada = isset($_GET['duracion']) ? limpiarDatos($_GET['duracion']) : null;
$edadFiltrada = isset($_GET['edad']) ? limpiarDatos($_GET['edad']) : null;

// Aplicar filtros y ordenación si se han seleccionado
if ($editorSeleccionado !== "Todos" || $categoriaSeleccionada !== "Todos" || $ordenSeleccionado || $precioFiltrado || $duracionFiltrada || $edadFiltrada) {
    $editorFiltrado = $editorSeleccionado !== "Todos" ? $editorSeleccionado : null;
    $categoriaFiltrada = $categoriaSeleccionada !== "Todos" ? $categoriaSeleccionada : null;
    $juegos = $juego->listarJuegosFiltrados($editorFiltrado, $categoriaFiltrada, $ordenSeleccionado, $edadFiltrada, $duracionFiltrada, $precioFiltrado);
} else {
    $juegos = $juego->listarJuegos();
}

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

if (!empty($_GET['busqueda'])) {
    $resultados = $juego->ordenarJuegos($juegos, $orden);
}
if (isset($_POST['agregar_al_carrito'])) {
    echo $_POST['id_juego'];
    $idJuego = $_POST['id_juego'];
    $_SESSION['carrito'][] = $idJuego;
    header("Location: listadoJuegos.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Ignacio Basso">
    <title>Eurogames - Listado de Juegos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body class="bg-eurogames-blanco">
    <?php require 'header.php'; ?>
    <div class="container mt-5">
        <div class="row mt-5">
            <h2 class="text-black mb-5 text-shadow-blue text-center mt-5">¡Bienvenid@ a nuestra ludotecta!</h2>
            
            <div class="col-md-12 segundo">
                <form method="GET" class=" p-1">
                    <div class="input-group d-flex justify-content-end">
                        <label for="orden" class="text-black text-shadow-bold ">ORDENAR POR:</label>
                        <select class="custom-select" id="orden" name="orden" onchange="this.form.submit()">
                            <option value="precio_asc" <?php echo (isset($_GET['orden']) && $_GET['orden'] === "precio_asc") ? 'selected' : ''; ?>>Precio: Menor a Mayor</option>
                            <option value="precio_desc" <?php echo (isset($_GET['orden']) && $_GET['orden'] === "precio_desc") ? 'selected' : ''; ?>>Precio: Mayor a Menor</option>
                            <option value="nombre_asc" <?php echo (isset($_GET['orden']) && $_GET['orden'] === "nombre_asc") ? 'selected' : ''; ?>>Nombre: A a Z</option>
                            <option value="nombre_desc" <?php echo (isset($_GET['orden']) && $_GET['orden'] === "nombre_desc") ? 'selected' : ''; ?>>Nombre: Z a A</option>
                        </select>
                        <input type="hidden" name="busqueda" value="<?php echo htmlspecialchars($_GET['busqueda'] ?? ''); ?>">
                    </div>
                </form>
            </div>
            
            <div class="col-md-3 primero mb-5  ">
                <div class="container-fluid register-left mt-5 ">
                    <p class="text-white roboto-mono fs-2 text-start ">Filtrar por:</p>
                    <form method="GET" class=" p-5">
                        <div class="input-group roboto-mono">
                            <label for="editor" class="px-1  fw-bolder">Editor:</label>
                            <select class="custom-select" id="editor" name="editor" onchange="this.form.submit()">
                                <option value="Todos" <?php echo (!isset($_GET['editor']) || (isset($_GET['editor']) && $_GET['editor'] === "Todos")) ? 'selected' : ''; ?>>Todos</option>
                                <?php foreach ($editores as $editor) : ?>
                                    <option value="<?php echo $editor; ?>" <?php echo (isset($_GET['editor']) && $_GET['editor'] === $editor) ? 'selected' : ''; ?>><?php echo $editor; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="input-group mt-3">
                            <label for="categoria" class="px-1  fw-bolder">Categoria:</label>
                            <select class="custom-select" id="categoria" name="categoria" onchange="this.form.submit()">
                                <option value="Todos" <?php echo (!isset($_GET['categoria']) || (isset($_GET['categoria']) && $_GET['categoria'] === "Todos")) ? 'selected' : ''; ?>>Todos</option>
                                <?php
                                $categoriasFiltradas = ['dados', 'tablero', 'carta', 'oferta', 'novedad'];
                                foreach ($categoriasFiltradas as $categoria) : ?>
                                    <option value="<?php echo $categoria; ?>" <?php echo (isset($_GET['categoria']) && $_GET['categoria'] === $categoria) ? 'selected' : ''; ?>><?php echo ucfirst($categoria); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="input-group mt-3">
                            <label for="precio" class="px-1  fw-bolder">Precio:</label>
                            <select class="custom-select" id="precio" name="precio">
                                <option value="">Todos</option>
                                <?php
                                $preciosFiltrados = ['0-20', '20-50', '50+'];
                                foreach ($preciosFiltrados as $precio) : ?>
                                    <option value="<?php echo $precio; ?>" <?php echo (isset($_GET['precio']) && $_GET['precio'] === $precio) ? 'selected' : ''; ?>><?php echo $precio; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="input-group mt-3">
                            <label for="duracion" class="px-1  fw-bolder">Duración:</label>
                            <select class="custom-select" id="duracion" name="duracion">
                                <option value="">Todas</option>
                                <?php
                                $duracionesFiltradas = ['0-30', '30-60', '60+'];
                                foreach ($duracionesFiltradas as $duracion) : ?>
                                    <option value="<?php echo $duracion; ?>" <?php echo (isset($_GET['duracion']) && $_GET['duracion'] === $duracion) ? 'selected' : ''; ?>><?php echo $duracion; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="input-group mt-3">
                            <label for="edad_minima" class="px-1  fw-bolder">Edad:</label>
                            <select class="custom-select px-3" id="edad_minima" name="edad_minima">
                                <option value="">Todas</option>
                                <?php
                                $edadesMinimasFiltradas = ['0-7', '8-12', '12+'];
                                foreach ($edadesMinimasFiltradas as $edad) : ?>
                                    <option value="" <?php echo (isset($_GET['edad_minima']) && $_GET['edad_minima'] === $edad) ? 'selected' : ''; ?>><?php echo $edad; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-9 mt-3 tercero">
                <div class="row d-flex justify-content-center ">
                    <?php foreach ($listarJuegos as $key => $juego) : ?>
                        <div class="col-md-5 col-sm-5 mb-5 text-black register-grey-buscar">
                            <table class="table ">
                                <tbody>
                                    <tr class="border-3">
                                        <td class="fw-bolder fs-5"><?php echo $juego['nombre_juego']; ?></td>
                                        <td class="fw-bolder fs-5 text-end"><?php echo $juego['precio'] . "€"; ?></td>
                                    </tr>
                                    <tr class="border-3 text-center">
                                        <td colspan="2" class="bg-eurogames-blanco">
                                            <img src='<?php echo $juego['foto']; ?>' class="w-50 h-50 text-center" alt='<?php echo $juego['nombre_juego']; ?>'>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-black fw-bolder  fs-5 border-0 text-center bg-eurogames-blanco" colspan="2"><?php echo substr($juego['descripcion'], 0, 100) . " ... "; ?></td>
                                    </tr>
                                    <tr class="border-bottom-5 mb-5">
                                        <td colspan="2" class="text-black text-center bg-eurogames-blanco ">
                                            <button class="btn btn-blue-orange " data-bs-toggle="modal" data-bs-target="#juegoModal<?php echo $juego['id_juego']; ?>">VER +</button>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-center bg-eurogames-blanco">
                                            <?php if ($juego['stock'] > 0) : ?>
                                                <form method="post">
                                                    <input type="hidden" name="id_juego" value="<?php echo isset($juego['id_juego']) ? $juego['id_juego'] : ''; ?>">
                                                    <button type="submit" name="agregar_al_carrito" class="btn btn-orange-blue mb-3 mt-3" <?php echo !isset($juego['id_juego']) ? 'disabled' : ''; ?>>AÑADIR AL CARRITO</button>
                                                </form>
                                        </td>
                                    <?php else : ?>
                                        <button type="button" class="btn btn-secondary mt-3 btn-lg font-weight-bold justify-content-center" disabled>Sin stock</button>
                                        </td>
                                    <?php endif; ?>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal fade" id="juegoModal<?php echo $juego['id_juego']; ?>" tabindex="-1" aria-labelledby="juegoModalLabel<?php echo $juego['id_juego']; ?>" aria-hidden="true">
                            <div class="modal-dialog ">
                                <div class="modal-content bg-eurogames-blanco">
                                    <div class="modal-header d-flex justify-content-between align-items-center">
                                        <h5 class="modal-title text-black fs-2" id="juegoModalLabel<?php echo $juego['id_juego']; ?>">
                                            <?php echo $juego['nombre_juego']; ?>
                                        </h5>
                                        <div class="ms-auto">
                                            <p class="modal-title text-black-50 fs-3 mt-2 mb-2">
                                                <strong class="text-black"><?php echo "€" . $juego['precio']; ?></strong>
                                            </p>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body fs-5">
                                        <img src="<?php echo $juego['foto']; ?>" class="d-block w-50 h-50 mx-auto mb-3" alt="<?php echo $juego['nombre_juego']; ?>">
                                        <hr>
                                        <p class="text-black-50 mt-5 border-top border-bottom mb-2"><strong class="text-black">Descripción:</strong> <?php echo $juego['descripcion']; ?></p>
                                        <p class="text-black-50  border-bottom mt-2 mb-2"><strong class="text-black">Editor:</strong> <?php echo $juego['editor']; ?></p>
                                        <p class="text-black-50  border-bottom mt-2 mb-2"><strong class="text-black">Año de Edición:</strong> <?php echo $juego['anio_edicion']; ?></p>
                                        <p class="text-black-50  border-bottom mt-2 mb-2"><strong class="text-black">Jugadores:</strong> <?php echo $juego['cantidad_jugadores']; ?></p>
                                        <p class="text-black-50  border-bottom-3 mb mt-2-2"><strong class="text-black">Duración:</strong> <?php echo $juego['duracion_minutos']; ?> minutos</p>
                                        <p class="text-black-50  mt-2 mb-2"><strong class="text-black">Edad Mínima:</strong> <?php echo $juego['edad_minima']; ?> años</p>
                                        <?php
                                        foreach ($categorias as $key => $categoria) {
                                            if ($categoria['id_categoria'] == $juego['id_categoria']) {
                                                $nombreCategoria = $categoria['nombre_categoria'];
                                            }
                                        }
                                        ?>
                                        <p class="text-black  mt-2 mb-2"><strong>Categoria: </strong><?php echo $nombreCategoria; ?> </p>

                                    </div>
                                    <div class="modal-footer">
                                        <form method="post">
                                            <input type="hidden" name="id_juego" value="<?php echo $juego['id_juego']; ?>">
                                            <?php if ($juego['stock'] > 0) : ?>
                                                <button type="submit" name="agregar_al_carrito" class="btn btn-orange-blue">AÑADIR AL CARRITO</button>
                                            <?php else : ?>
                                                <button type="button" class="btn btn-secondary mt-3 btn font-weight-bold justify-content-center" disabled>Sin stock</button>
                                            <?php endif; ?>
                                            <button type="button" class="btn btn-blue-orange" data-bs-dismiss="modal">CERRAR</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- </div> -->
    <?php require 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>