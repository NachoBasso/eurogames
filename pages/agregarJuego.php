<?php
include_once 'Conexion.php';
include_once 'functions.php';
require_once '../classes/Juego.php';

$juego = new Juego();
$errores = array();
$mensajeExito = '';
$resultado = "";
$rutaDestino = "";
const MAX_DESCRIPCION = 200;
$categorias = $juego->obtenerCategorias();
$juegos = $juego->listarJuegos();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombreJuego = isset($_POST['nombre']) ? limpiarDatos($_POST['nombre']) : "";
    $precio = isset($_POST['precio']) ? limpiarDatos($_POST['precio']) : "";
    $descripcion = isset($_POST['descripcion']) ? limpiarDatos($_POST['descripcion']) : "";
    $stock = isset($_POST['stock']) ? limpiarDatos($_POST['stock']) : "";
    $editor = isset($_POST['editor']) ? limpiarDatos($_POST['editor']) : "";
    $anioEdicion = isset($_POST['anioEdicion']) ? limpiarDatos($_POST['anioEdicion']) : "";
    $cantidadJugadores = isset($_POST['cantidadJugadores']) ? limpiarDatos($_POST['cantidadJugadores']) : "";
    $foto = $_FILES['foto']['name'];
    $edadMinima = isset($_POST['edadMinima']) ? limpiarDatos($_POST['edadMinima']) : "";
    $duracion = isset($_POST['duracion']) ? limpiarDatos($_POST['duracion']) : "";
    $nombreCategoria = isset($_POST['nombreCategoria']) ? limpiarDatos($_POST['nombreCategoria']) : "";


    if (!empty($nombreJuego)) {
        foreach ($juegos as $juego) {
            if (strtolower($juego['nombre_juego']) == strtolower($nombreJuego)) {
                $errores['nombre'] = "ya existe un juego con el nombre que pretende ingresar.";
            }
        }
    } else {
        $errores['nombre'] = "Debe agregar el nombre del juego.";
    }

    if (empty($precio)) {
        $errores['precio'] = "Debe agregar el precio.";
    }

    if (empty($descripcion)) {
        $errores['descripcion'] = "Debe agregar la descripción del juego.";
    } else if (strlen($descripcion) > MAX_DESCRIPCION) {
        $errores['descripcion'] = "Debe ingresar un máximo de " . MAX_DESCRIPCION . " caracteres.";
    }

    if (empty($stock)) {
        $errores['stock'] = "Debe ingresar el stock.";
    }

    if (empty($editor)) {
        $errores['editor'] = "Debe agregar el nombre del editor.";
    }

    if (empty($edadMinima)) {
        $errores['edadMinima'] = "Debe agregar la edad mínima de juego.";
    }

    if (empty($cantidadJugadores)) {
        $errores['cantidadJugadores'] = "Debe agregar la cantidad de jugadores posible.";
    }

    if (empty($duracion)) {
        $errores['duracion'] = "Debe agregar la duración del juego.";
    }
    if (empty($anioEdicion)) {
        $errores['anioEdicion'] = "Debe agregar el año de edición.";
    }

    if (empty($nombreCategoria)) {
        $errores['nombreCategoria'] = "Debe agregar una de las categorías.";
    }

    if (empty($foto)) {
        $errores['foto'] = "Debe agregar una foto por cada juego";
    } else {
        $nombreFoto = isset($_FILES['foto']['name']) ? limpiarDatos($_FILES['foto']['name']) : "";
        $rutaDestino = '../img/' . uniqid() . '-' . $nombreFoto;



        if (!empty($_FILES["foto"]["name"])) {
            $nombreArchivo = htmlspecialchars($_FILES["foto"]["name"]);
            $tamanioArchivo = $_FILES["foto"]["size"];
            $tipoArchivo = $_FILES["foto"]["type"];
            $ubicacionTemporal = $_FILES["foto"]["tmp_name"];
            $permitidos = array("image/jpeg", "image/png", "image/gif");
            if (in_array($tipoArchivo, $permitidos)) {
                list($ancho, $alto) = getimagesize($ubicacionTemporal);
                $anchoMaximo = 800;
                $altoMaximo = 600;
                if ($tipoArchivo === "image/jpeg") {
                    $imagenOriginal = imagecreatefromjpeg($ubicacionTemporal);
                } else {
                    $imagenOriginal = imagecreatefrompng($ubicacionTemporal);
                }

                if ($ancho > $anchoMaximo || $alto > $altoMaximo) {
                    $imagenRedimensionada = imagecreatetruecolor($anchoMaximo, $altoMaximo);
                    imagecopyresampled($imagenRedimensionada, $imagenOriginal, 0, 0, 0, 0, $anchoMaximo, $altoMaximo, $ancho, $alto);
                    imagedestroy($imagenOriginal);
                    $directorioDestino = "../img/";
                    $rutaDestino = $directorioDestino . uniqid() . $nombreArchivo;
                    imagejpeg($imagenRedimensionada, $rutaDestino);
                    imagedestroy($imagenRedimensionada);
                } else {
                    $directorioDestino = "../img/";
                    $rutaDestino = $directorioDestino . uniqid() . $nombreArchivo;
                    imagejpeg($imagenOriginal, $rutaDestino);
                    imagedestroy($imagenOriginal);
                }
            } else {
                echo "<p>El tipo de archivo seleccionado no está permitido. Por favor, seleccione una imagen.</p>";
            }
        } else {
            echo "<p>No se ha seleccionado ningún archivo.</p>";
        }
    }
    if (empty($errores)) {
        $nuevoJuego = new Juego();
        $nuevoJuego->crearJuego($nombreJuego, $precio, $descripcion, $stock, $editor, $anioEdicion, $cantidadJugadores, $rutaDestino, $edadMinima, $duracion, $nombreCategoria);
        $mensajeExito = "El juego $nombreJuego ha sido agregado correctamente.";
    } else {
        $resultado = "Error al crear el juego en la base de datos.";
    }
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Ignacio Basso">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
    <title>Eurogames - Agregar Juegos</title>
</head>

<body class="bg-eurogames-blanco roboto-mono">
    <?php include 'header.php'; ?>
    <main class="mt-5 justify-content-center">
        <div class="container col-md-7 mt-5 ">
            <div class=" register-right text-white mt-2 py-5">
                <h2 class="mb-5 text-shadow-black mt-2 text-center">AGREGAR JUEGOS</h2>
                <hr>

                <?php if (!empty($mensajeExito)) : ?>
                    <div class="alert alert-success w-50 mx-auto fs-5 text-center">
                        <?php echo htmlspecialchars($mensajeExito); ?>
                        <div class="d-flex justify-content-center text-center mt-3 mb-3 ">
                            <div class="p-3"><a href="agregarJuego.php" class="btn btn-orange-black fw-bold">AGREGAR +</a></div>
                            <div class="p-3"><a href="administrador.php" class="btn btn-orange-black fw-bolder px-4">VOLVER</a></div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (empty($mensajeExito)) : ?>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data" class="row g-3 p-4">
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre del Juego:</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Ej: Catan" value="<?= isset($nombreJuego) ? limpiarDatos($nombreJuego) : '' ?>">
                            <?php if (!empty($errores['nombre'])) : ?>
                                <div class="alert alert-warning mt-2 p-1 fw-bold">
                                    <?php echo htmlspecialchars($errores['nombre']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label for="foto" class="form-label">Foto:</label>
                            <input type="file" id="foto" name="foto" class="form-control" placeholder="Ej: catan.jpg" accept=".jpeg, .jpg, .png" value="<?= isset($foto) ? limpiarDatos($foto) : '' ?>">
                            <?php if (!empty($errores['foto'])) : ?>
                                <div class="alert alert-warning mt-2 p-1 fw-bold">
                                    <?php echo htmlspecialchars($errores['foto']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label for="stock" class="form-label">Stock:</label>
                            <input type="number" id="stock" name="stock" min="1" class="form-control" placeholder="Ej: 20" value="<?= isset($stock) ? limpiarDatos($stock) : '' ?>">
                            <?php if (!empty($errores['stock'])) : ?>
                                <div class="alert alert-warning mt-2 p-1 fw-bold">
                                    <?php echo htmlspecialchars($errores['stock']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label for="precio" class="form-label">Precio:</label>
                            <input type="number" id="precio" name="precio" class="form-control" min="1" step="0.01" placeholder="Ej: 50.99" value="<?= isset($precio) ? limpiarDatos($precio) : '' ?>">
                            <?php if (!empty($errores['precio'])) : ?>
                                <div class="alert alert-warning mt-2 p-1 fw-bold">
                                    <?php echo htmlspecialchars($errores['precio']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label for="editor" class="form-label">Editor:</label>
                            <input type="text" id="editor" name="editor" class="form-control" placeholder="Ej: Devir" value="<?= isset($editor) ? limpiarDatos($editor) : '' ?>">
                            <?php if (!empty($errores['editor'])) : ?>
                                <div class="alert alert-warning mt-2 p-1 fw-bold">
                                    <?php echo htmlspecialchars($errores['editor']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label for="anioEdicion" class="form-label">Año de Edición:</label>
                            <input type="text" id="anioEdicion" name="anioEdicion" class="form-control" placeholder="Ej: 1998" value="<?= isset($anioEdicion) ? limpiarDatos($anioEdicion) : '' ?>">
                            <?php if (!empty($errores['anioEdicion'])) : ?>
                                <div class="alert alert-warning mt-2 p-1 fw-bold">
                                    <?php echo htmlspecialchars($errores['anioEdicion']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label for="cantidadJugadores" class="form-label">Cantidad de Jugadores:</label>
                            <input type="text" id="cantidadJugadores" name="cantidadJugadores" class="form-control" placeholder="Ej: 2-5" value="<?= isset($cantidadJugadores) ? limpiarDatos($cantidadJugadores) : '' ?>">
                            <?php if (!empty($errores['cantidadJugadores'])) : ?>
                                <div class="alert alert-warning mt-2 p-1 fw-bold">
                                    <?php echo htmlspecialchars($errores['cantidadJugadores']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label for="edadMinima" class="form-label">Edad Mínima:</label>
                            <input type="number" id="edadMinima" name="edadMinima" min="0" class="form-control" placeholder="Ej: 10 " value="<?= isset($edadMinima) ? limpiarDatos($edadMinima) : '' ?>">
                            <?php if (!empty($errores['edadMinima'])) : ?>
                                <div class="alert alert-warning mt-2 p-1 fw-bold">
                                    <?php echo htmlspecialchars($errores['edadMinima']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label for="duracion" class="form-label">Duración en minutos:</label>
                            <input type="text" id="duracion" name="duracion" placeholder="Ej: 90" class="form-control" value="<?= isset($duracion) ? limpiarDatos($duracion) : '' ?>">
                            <?php if (!empty($errores['duracion'])) : ?>
                                <div class="alert alert-warning mt-2 p-1 fw-bold">
                                    <?php echo htmlspecialchars($errores['duracion']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label for="nombreCategoria" class="form-label">Categoría:</label>
                            <select id="nombreCategoria" name="nombreCategoria" class="form-select">
                                <option value="">Seleccionar categoría</option>
                                <?php foreach ($categorias as $categoria) : ?>
                                    <option value="<?php echo htmlspecialchars($categoria['id_categoria']); ?>">
                                        <?php echo htmlspecialchars($categoria['nombre_categoria']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (!empty($errores['nombreCategoria'])) : ?>
                                <div class="alert alert-warning mt-2 p-1 fw-bold">
                                    <?php echo htmlspecialchars($errores['nombreCategoria']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-12">

                            <label for="descripcion" class="form-label">Descripción:</label>
                            <textarea id="descripcion" name="descripcion" rows="4" max-length="200" placeholder="Ej: Catan es un juego del editor Devir..." class="form-control"><?= isset($descripcion) ? limpiarDatos($descripcion) : '' ?></textarea>
                            <?php if (!empty($errores['descripcion'])) : ?>
                                <div class="alert alert-warning mt-2 p-1 fw-bold">
                                    <?php echo htmlspecialchars($errores['descripcion']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-12 text-center ">
                            <button type="submit" class="btn btn-orange-blue fw-bold mt-2">AGREGAR JUEGO</button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </main>
    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="../js/app.js"></script>
</body>

</html>