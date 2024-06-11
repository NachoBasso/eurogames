<?php
session_start();
require_once '../classes/Juego.php';
require_once 'functions.php';
require_once '../classes/Pedido.php';
require_once '../classes/Usuario.php';

const IVA = 0.21;
$pedido = new Pedido();
$idSeguimiento = isset($_SESSION['id_seguimiento']) ? limpiarDatos($_SESSION['id_seguimiento']) : "";
$pedidoRealizado = $pedido->obtenerPedidoPorSeguimiento($idSeguimiento);
$usuario = new Usuario();
$idUsuario = $pedidoRealizado[0]['id_usuario'];
$datosUsuario = "";
$datosUsuario = $usuario->obtenerUsuarioporId($idUsuario);
$juego = new Juego();
$juegos[] = "";
$errores[] = "";


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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
    <title>Eurogames - Datos de Facturación</title>
</head>

<body class="bg-eurogames-blanco roboto-mono ">
    <?php include 'header.php'; ?>
    <main>
        <div class="container-fluid mt-5">
            <h1 class='mr-5 mt-2 mb-1 text-shadow-blue text-center'>¡Felicitaciones!</h1>
            <div class="row mt-5 mb-5">
                <div class="col-md-3 ">
                    <div class="container register-left mt-5 mb-5">
                        <h1 class="mt-5 text-shadow-black p-3">¿Te quedaste con ganas de llevarte más juegos?</h1><br>
                        <div class="text-center">
                            <span class="orange-icon mb-3 px-2"><svg class="orange-icon mb-3 px-2" width="100" height="75" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                    <path d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6 .1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z" />
                                </svg></span>
                        </div>
                        <h3 class="mt-5 mb-5">Puedes seguir buscando dentro de nuestro amplio stock</h3>
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
                <div class="col-md-9 ">
                    <div class="card mt-5 register-grey ">
                        <div class="card-header bg-secondary text-white">
                            <h4 class="mb-0 mt-2">Detalle del Pedido</h4>
                        </div>
                        <div class="card-body bg-eurogames-blanco">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <h5 class="card-title  p-2 text-white bg-secondary fw-bolder">Información del Cliente</h5>
                                    <p><strong>Nombre:</strong> <?php echo $nombreUsuario . ' ' . $apellidosUsuario ?></p>
                                    <p><strong>Correo Electrónico:</strong> <?php echo $emailUsuario ?></p>
                                    <p><strong>Teléfono:</strong> <?php echo $telefonoUsuario ?></p>
                                    <p><strong>Dirección:</strong> <?php echo $domicilioUsuario . ', ' . $localidadUsuario . ', ' . $provinciaUsuario; ?></p>
                                    <p><strong>CIF O NIF:</strong> <?php echo $cifNif; ?></p>

                                </div>
                                <div class="col-md-6 fw-bolder border-3">
                                    <h5 class="card-title p-2 fw-bolder text-white bg-secondary">Detalles del Pedido</h5>
                                    <p class="border-3"><strong>Número de pedido:</strong> <?php echo $nroSeguimiento ?></p>
                                    <p><strong>Fecha:</strong> <?php echo $fechaPedido; ?></p>
                                    <p><strong>Método de Pago:</strong> <?php echo $metodoPago; ?></p>
                                    <p><strong>Método de Envío:</strong> <?php echo $metodoEnvio; ?></p>
                                    <p><strong>Estado:</strong> retirar a partir de las próximas 48 hs hábiles. </p>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center">
                                <h5 class=" d-flex justify-content-center card-title table-carrito p-3 text-white bg-secondary">Productos</h5>

                            </div>
                            <div class="d-flex justify-content-center">

                                <div class="table-carrito table-responsive">
                                    <?php $totalPagar = 0; ?>
                                    <table class="table table-striped text-center align-middle">
                                        <thead>
                                            <tr>
                                                <th>Producto</th>
                                                <th>Cantidad</th>
                                                <th>Precio</th>
                                                <th>Subtotal</th>
                                                <th>IVA</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($juegos as $juego) : ?>
                                                <?php
                                                $total = $juego['cantidad'] * $juego['precio'];
                                                $iva = $total * IVA;
                                                $subtotal = $total - $iva;
                                                $totalPagar += $total;
                                                ?>
                                                <tr>
                                                    <td><?php echo $juego['nombre']; ?></td>
                                                    <td><?php echo $juego['cantidad']; ?></td>
                                                    <td><?php echo number_format($juego['precio'], 2) . "€"; ?></td>
                                                    <td><?php echo number_format($subtotal, 2) . "€"; ?></td>
                                                    <td><?php echo number_format($iva, 2) . "€"; ?></td>
                                                    <td><?php echo number_format($total, 2) . "€"; ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="5" class="text-right">Total a Pagar:</th>
                                                <th><?php echo number_format($totalPagar, 2) . "€"; ?></th>
                                            </tr>
                                            <tr>
                                                <th colspan="6" class="text-right bg-secondary text-white fs-5">Aviso Importante: Tiene 15 días hábiles desde la generación de este comprobante para retirar su pedido o se cancelará.</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <a href="listadoJuegos.php" class="btn btn-blue-orange btn-lg mt-3">SEGUIR COMPRANDO</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybBogGzGQ4Y7lPoI7p7fvg6NtA91RnC/xpQTf8OHc5YY5RkCc" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-qb6xKU0HQuE6NlCyYm1DkDJzY3qDEJfbB6qt8RLl8ca5TTWvgyb/9+a1B+AR7pE2" crossorigin="anonymous"></script>
</body>

</html>