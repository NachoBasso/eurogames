<?php
require_once "functions.php";
;require_once '../classes/Juego.php';

$juego = new Juego();
$juegos = $juego->listarJuegos();

$editores = $juego->obtenerEditores();
$categorias = $juego->obtenerCategorias();

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
    <div class="container-fluid mt-5">
        <div class="row mt-5 mb-5">
            <div class="col-md-3 ">
                <div class="container-fluid register-left mb-5">
                    <form method="GET" class="mb-5 p-5">
                        <div class="input-group">
                            <label for="editor">Filtrar por editor:</label>
                            <select class="custom-select" id="editor" name="editor" onchange="this.form.submit()">
                                <option value="Todos" <?php echo (!isset($_GET['editor']) || (isset($_GET['editor']) && $_GET['editor'] === "Todos")) ? 'selected' : ''; ?>>Todos</option>
                                <?php foreach ($editores as $editor) : ?>
                                    <option value="<?php echo $editor; ?>" <?php echo (isset($_GET['editor']) && $_GET['editor'] === $editor) ? 'selected' : ''; ?>><?php echo $editor; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="input-group mt-3">
                            <label for="categoria">Filtrar por categoría:</label>
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
                            <label for="precio">Filtrar por Precio:</label>
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
                            <label for="duracion">Filtrar por Duración:</label>
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
                            <label for="edad_minima">Filtrar por Edad Mínima:</label>
                            <select class="custom-select" id="edad_minima" name="edad_minima">
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
            <div class="col-md-9">
                <div class="container register-right  justify-content-center">
                    <h2 class="roboto-mono text-white mb-5">ESTOS SON NUESTROS JUEGOS:</h2>
                    <form method="GET" class="mb-4  p-5">
                        <div class="input-group d-flex justify-content-end">
                            <label for="orden" class="text-white p-1">ORDENADOR POR:</label>
                            <select class="custom-select" id="orden" name="orden" onchange="this.form.submit()">
                                <option value="precio_asc" <?php echo (isset($_GET['orden']) && $_GET['orden'] === "precio_asc") ? 'selected' : ''; ?>>Precio: Menor a Mayor</option>
                                <option value="precio_desc" <?php echo (isset($_GET['orden']) && $_GET['orden'] === "precio_desc") ? 'selected' : ''; ?>>Precio: Mayor a Menor</option>
                                <option value="nombre_asc" <?php echo (isset($_GET['orden']) && $_GET['orden'] === "nombre_asc") ? 'selected' : ''; ?>>Nombre: A a Z</option>
                                <option value="nombre_desc" <?php echo (isset($_GET['orden']) && $_GET['orden'] === "nombre_desc") ? 'selected' : ''; ?>>Nombre: Z a A</option>
                            </select>
                        </div>
                    </form>
                    <div class="row">
                        <?php while ($fila = is_object($juegos) ? $juegos->fetch(PDO::FETCH_ASSOC) : null) : ?>
                            <div class="col-md-6 mb-4">
                                <div class="card card-login text-white pr-4 pl-4 m-4">
                                    <img src="<?php echo $fila['foto']; ?>" class="card-img-top pr-5 pl-5 m-4 w-50 text-center" alt="<?php echo $fila['nombre_juego']; ?>">
                                    <hr class="my-4 border-top">
                                    <div class="card-body">
                                        <h5 class="card-title text-white"><?php echo $fila['nombre_juego']; ?></h5>
                                        <p class="card-text text-white"><?php echo $fila['descripcion']; ?></p>
                                        <hr class="my-4 border-top">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item card-text">Editor: <?php echo $fila['editor']; ?></li>
                                            <li class="list-group-item card-text">Año de Edición: <?php echo $fila['anio_edicion']; ?></li>
                                            <li class="list-group-item card-text">Max. Jugadores: <?php echo $fila['cantidad_jugadores']; ?></li>
                                            <li class="list-group-item card-text">Edad Mínima: <?php echo $fila['edad_minima']; ?></li>
                                            <li class="list-group-item card-text">Duración: <?php echo $fila['duracion_minutos']; ?></li>
                                            <li class="list-group-item card-text">Precio: <?php echo $fila['precio']; ?></li>
                                        </ul>
                                        <hr class="my-4 border-top">
                                        <div class="d-flex justify-content-center mt-6">
                                            <button type="button" class="btn btn-warning text-white mt-3 btn-lg font-weight-bold justify-content-center">Agregar al carrito</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>