<?php
session_start();
require_once "functions.php";
require_once '../classes/Juego.php';

$juego = new Juego();
$juegos = $juego->listarJuegos();
$juegosSinFiltrar = $juego->listarJuegos();

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
    $_SESSION['pedido_agregado']['estado'] = "por pedir";
    header("Location: listadoJuegos.php");
    exit;
}

if (!empty($pedidoRealizado) && !empty($datosUsuario)) {
    $nombreUsuario = isset($datosUsuario['nombre_usuario']) ? limpiarDatos($datosUsuario['nombre_usuario']) : "";
    $apellidosUsuario = isset($datosUsuario['apellidos_usuario']) ? limpiarDatos($datosUsuario['apellidos_usuario']) : "";
    $emailUsuario = isset($datosUsuario['email']) ? limpiarDatos($datosUsuario['email']) : "";
    $telefonoUsuario = isset($datosUsuario['telefono']) ? limpiarDatos($datosUsuario['telefono']) : "";
    $domicilioUsuario = isset($pedidoRealizado[0]['direccion_facturacion']) ? limpiarDatos($pedidoRealizado[0]['direccion_facturacion']) : "";
    $cifNif = isset($pedidoRealizado[0]['cif_nif']) ? limpiarDatos($pedidoRealizado[0]['cif_nif']) : "";
    $localidadUsuario = isset($pedidoRealizado[0]['localidad']) ? limpiarDatos($pedidoRealizado[0]['localidad']) : "";
    $provinciaUsuario = isset($pedidoRealizado[0]['provincia']) ? limpiarDatos($pedidoRealizado[0]['provincia']) : "";
    $codigoPostalUsuario = isset($pedidoRealizado[0]['codigo_postal']) ? limpiarDatos($pedidoRealizado[0]['codigo_postal']) : "";
    $nombreDestinatario = isset($pedidoRealizado[0]['nombre_destinatario']) ? limpiarDatos($pedidoRealizado[0]['nombre_destinatario']) : "";
    $telefonoDestinatario = isset($pedidoRealizado[0]['telefono_destinatario']) ? limpiarDatos($pedidoRealizado[0]['telefono_destinatario']) : "";
    $domicilioDestinatario = isset($pedidoRealizado[0]['domicilio_destinatario']) ? limpiarDatos($pedidoRealizado[0]['domicilio_destinatario']) : "";
    $apellidosDestinatario = isset($pedidoRealizado[0]['apellidos_destinatario']) ? limpiarDatos($pedidoRealizado[0]['apellidos_destinatario']) : "";
    $localidadDestinatario = isset($pedidoRealizado[0]['localidad_destinatario']) ? limpiarDatos($pedidoRealizado[0]['localidad_destinatario']) : "";
    $provinciaDestinatario = isset($pedidoRealizado[0]['provincia_destinatario']) ? limpiarDatos($pedidoRealizado[0]['provincia_destinatario']) : "";
    $codigoPostalDestinatario = isset($pedidoRealizado['codigo_postal_destinatario']) ? limpiarDatos($pedidoRealizado[0]['codigo_postal_destinatario']) : "";
    $metodoPago = isset($pedidoRealizado[0]['metodo_pago']) ? limpiarDatos($pedidoRealizado[0]['metodo_pago']) : "";
    $metodoEnvio = isset($pedidoRealizado[0]['metodo_envio']) ? limpiarDatos($pedidoRealizado[0]['metodo_envio']) : "";
    $fechaPedido = isset($pedidoRealizado[0]['fecha_pedido']) ? limpiarDatos($pedidoRealizado[0]['fecha_pedido']) : "";
    $nroSeguimiento = isset($pedidoRealizado[0]['nro_seguimiento']) ? limpiarDatos($pedidoRealizado[0]['nro_seguimiento']) : "";
    $detallesJuegos = [];

    foreach ($pedidoRealizado as $detalle) {
        $idJuego = $detalle['id_juego'];
        $cantidad = $detalle['cantidad'];
        $obtenerJuego = $juego->obtenerJuegoPorId($idJuego);

        if (!empty($obtenerJuego)) {
            if (!isset($detallesJuegos[$idJuego])) {
                $detallesJuegos[$idJuego] = [
                    'id_juego' => $idJuego,
                    'nombre' => $obtenerJuego['nombre_juego'],
                    'precio' => $obtenerJuego['precio'],
                    'cantidad' => 0
                ];
            }
            $detallesJuegos[$idJuego]['cantidad'] += $cantidad;
        }
    }
    $juegos = array_values($detallesJuegos);
} else {
    $errores['general'] = "Error: No se encontraron datos suficientes para generar la factura.";
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
                    <div class="input-group d-flex justify-content-end fs-5 roboto-mono">
                        <label for="orden" class="text-black fs-5 fw-bolder">ORDENAR POR:</label>
                        <select class="custom-select" id="orden" name="orden" onchange="this.form.submit()">
                            <option class="fs-5"value="precio_asc" <?php echo (isset($_GET['orden']) && $_GET['orden'] === "precio_asc") ? 'selected' : ''; ?>>Precio: Menor a Mayor</option>
                            <option class="fs-5"value="precio_desc" <?php echo (isset($_GET['orden']) && $_GET['orden'] === "precio_desc") ? 'selected' : ''; ?>>Precio: Mayor a Menor</option>
                            <option class="fs-5"value="nombre_asc" <?php echo (isset($_GET['orden']) && $_GET['orden'] === "nombre_asc") ? 'selected' : ''; ?>>Nombre: A a Z</option>
                            <option class="fs-5"value="nombre_desc" <?php echo (isset($_GET['orden']) && $_GET['orden'] === "nombre_desc") ? 'selected' : ''; ?>>Nombre: Z a A</option>
                        </select>
                        <input type="hidden" name="busqueda" value="<?php echo htmlspecialchars($_GET['busqueda'] ?? ''); ?>">
                    </div>
                </form>
            </div>

            <div class="col-md-3 primero mb-5  ">
                <div class="container-fluid register-left mt-5 ">
                    <p class="text-white roboto-mono fs-2 text-start ">Filtrar por:</p>
                    <form method="GET" class="mb-5 p-4 roboto-mono fs-5">
                        <div class="input-group">
                            <label class="p-1"for="editor">Editor:</label>
                            <select class="custom-select w-100" id="editor" name="editor" onchange="this.form.submit()">
                                <option value="Todos" <?php echo (!isset($_GET['editor']) || (isset($_GET['editor']) && $_GET['editor'] === "Todos")) ? 'selected' : ''; ?>>Todos</option>
                                <?php foreach ($editores as $editor) : ?>
                                    <option class="fs-5" value="<?php echo $editor; ?>" <?php echo (isset($_GET['editor']) && $_GET['editor'] === $editor) ? 'selected' : ''; ?>><?php echo $editor; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="input-group mt-3">
                            <label for="categoria">Categoría:</label>                            
                            <select class="custom-select w-100" id="categoria" name="categoria" onchange="this.form.submit()">
                                <option class="fs-5" value="Todos" <?php echo (!isset($_GET['categoria']) || (isset($_GET['categoria']) && $_GET['categoria'] === "Todos")) ? 'selected' : ''; ?>>Todos</option>
                                <?php
                                $categoriasFiltradas = ['dados', 'tablero', 'carta', 'oferta', 'novedad'];
                                foreach ($categoriasFiltradas as $categoria) : ?>
                                    <option value="<?php echo $categoria; ?>" <?php echo (isset($_GET['categoria']) && $_GET['categoria'] === $categoria) ? 'selected' : ''; ?>><?php echo ucfirst($categoria); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="input-group mt-3 roboto-mono">
                            <label class="p-1 fs-5"for="precio">Precio:</label>
                            <select class="custom-select w-100" id="precio" name="precio" onchange="this.form.submit()">
                                <option class="fs-5"value="Todos" <?php echo (!isset($_GET['precio']) || (isset($_GET['precio']) && $_GET['precio'] === "Todos")) ? 'selected' : ''; ?>>Todos</option>
                                <option class="fs-5"value="20" <?php echo (isset($_GET['precio']) && $_GET['precio'] === "20") ? 'selected' : ''; ?>>Desde 5$ a 20$</option>
                                <option class="fs-5"value="50" <?php echo (isset($_GET['precio']) && $_GET['precio'] === "50") ? 'selected' : ''; ?>>Desde 20$ a 50$</option>
                                <option class="fs-5"value="51" <?php echo (isset($_GET['precio']) && $_GET['precio'] === "51") ? 'selected' : ''; ?>>Más de 50$</option>
                            </select>
                        </div>
                        <div class="input-group mt-3">
                            <label class="p-1" for="duracion">Duración:</label>
                            <select class="custom-select w-100 fs-5" id="duracion" name="duracion" onchange="this.form.submit()">
                                <option class="fs-5"value="Todos" <?php echo (!isset($_GET['duracion']) || (isset($_GET['duracion']) && $_GET['duracion'] === "Todos")) ? 'selected' : ''; ?>>Todos</option>
                                <option class="fs-5"value="30" <?php echo (isset($_GET['duracion']) && $_GET['duracion'] === "30") ? 'selected' : ''; ?>>Hasta 30 min</option>
                                <option class="fs-5"value="60" <?php echo (isset($_GET['duracion']) && $_GET['duracion'] === "60") ? 'selected' : ''; ?>>Más de 30 hasta 60 min</option>
                                <option class="fs-5"value="61" <?php echo (isset($_GET['duracion']) && $_GET['duracion'] === "61") ? 'selected' : ''; ?>>Más de 60 min</option>
                            </select>
                        </div>
                        <div class="input-group mt-3">
                            <label class="p-1" for="edad_minima">Edad mínima:</label>
                            <select class="custom-select w-100" id="edad_minima" name="edad_minima" onchange="this.form.submit()">
                                <option value="Todos" <?php echo (!isset($_GET['edad_minima']) || (isset($_GET['edad_minima']) && $_GET['edad_minima'] === "Todos")) ? 'selected' : ''; ?>>Todos</option>
                                <option value="6" <?php echo (isset($_GET['edad_minima']) && $_GET['edad_minima'] === "6") ? 'selected' : ''; ?>>A partir de 6 años</option>
                                <option value="12" <?php echo (isset($_GET['edad_minima']) && $_GET['edad_minima'] === "12") ? 'selected' : ''; ?>>Entre 6 a 12 años</option>
                                <option value="13" <?php echo (isset($_GET['edad_minima']) && $_GET['edad_minima'] === "13") ? 'selected' : ''; ?>>Más de 12 años</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-9 mt-3 tercero">
                <div class="row d-flex justify-content-center ">
                    <?php while ($juego = is_object($juegos) ? $juegos->fetch(PDO::FETCH_ASSOC) : null) : ?>
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
                                        <p class="text-black-50 border-bottom mt-2 mb-2"><strong class="text-black">Año de Edición:</strong> <?php echo $juego['anio_edicion']; ?></p>
                                        <p class="text-black-50  border-bottom mt-2 mb-2"><strong class="text-black">Jugadores:</strong> <?php echo $juego['cantidad_jugadores']; ?></p>
                                        <p class="text-black-50  border-bottom-3 mb mt-2-2"><strong class="text-black">Duración:</strong> <?php echo $juego['duracion_minutos']; ?> minutos</p>
                                        <p class="text-black-50  mt-2 mb-2"><strong class="text-black">Edad Mínima:</strong> <?php echo $juego['edad_minima']; ?> años</p>
                                        <?php if (!empty($juego['nombre_categoria'])) {
                                                    $nombreCategoria = $juego['nombre_categoria'];
                                                    ?><p class="text-black  mt-2 mb-2"><strong>Categoria: </strong><?php echo $nombreCategoria; ?> </p>
                                                            <?php 
                                                } else {
                                                    $nombreCategoria = "";
                                                    foreach ($categorias as $key => $categoria) {
                                                        if ($categoria['id_categoria'] == $juego['id_categoria']) {
                                                            $nombreCategoria = $categoria['nombre_categoria'];
                                                            ?><p class="text-black  mt-2 mb-2"><strong>Categoria: </strong><?php echo $nombreCategoria; ?> </p>
                                                            <?php break;
                                                        }
                                                    }
                                                }?>
                                        
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
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>
    <?php require 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>