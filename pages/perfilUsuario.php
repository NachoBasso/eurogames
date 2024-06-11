<?php
session_start();
require_once '../classes/Usuario.php';
require_once 'functions.php';
require_once 'config.php';
require_once '../classes/Pedido.php';
require_once '../classes/Juego.php';


$mensaje = '';
$errores = [];
$mensajeExito = '';
$idUsuario = $_SESSION['id_usuario'];

if (empty($_SESSION['nombre_usuario'])) {
    header('Location: login.php');
    exit();
}

const IVA = 0.21;
$juego= new Juego();
$usuario = new Usuario();
$datosPersonales = $usuario->obtenerUsuarioPorId($idUsuario);
$PasswordAntigua = $datosPersonales['password'];
$pedido = new Pedido();
$pedidoRealizado = $pedido->obtenerPedidoPorUsuario($idUsuario);
$resultadoPedidos = $pedido->listarPedidosPorUsuario($idUsuario);
$datosUsuario = $usuario->obtenerUsuarioporId($idUsuario);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = isset($_POST['nombre']) ? limpiarDatos($_POST['nombre']) : "";
    $apellido = isset($_POST['apellido']) ? limpiarDatos($_POST['apellido']) : "";
    $email = isset($_POST['email']) ? limpiarDatos($_POST['email']) : "";
    $password = isset($_POST['password']) ? limpiarDatos($_POST['password']) : "";
    $repetirPassword = isset($_POST['repetirPassword']) ? limpiarDatos($_POST['repetirPassword']) : "";
    $telefono = isset($_POST['telefono']) ? limpiarDatos($_POST['telefono']) : "";
    $guardarDatos = isset($_POST['guardarDatos']) ? limpiarDatos($_POST['guardarDatos']) : "";
    $guardarPassword = isset($_POST['guardarPassword']) ? limpiarDatos($_POST['guardarPassword']) : "";
    $passwordNueva = isset($_POST['passwordNueva']) ? limpiarDatos($_POST['passwordNueva']) : "";
    $passwordAnterior = isset($_POST['passwordAnterior']) ? limpiarDatos($_POST['passwordAnterior']) : "";

    if (isset($_POST['guardarDatos'])) {

        if (empty($nombre)) {
            $errores['nombre'] = 'El nombre es obligatorio.';
        }
        if (empty($apellido)) {
            $errores['apellido'] = 'El apellido es obligatorio.';
        }
        if (empty($email)) {
            $errores['email'] = 'El email es obligatorio.';
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errores['email'] = 'El formato del email no es válido.';
        } else if ($usuario->usuarioExiste($email) && $usuario->obtenerUsuarioPorId($idUsuario)['email']!= $email) {
            $errores['email'] = 'Ya existe un usuario con esa cuenta.';
        }
        if (empty($telefono)) {
            $errores['telefono'] = 'El teléfono es obligatorio.';
        } else if ($telefono < 10 && $telefono > 13) {
            $errores['telefono'] = "Debe ingresar un teléfono entre 10 y 12 caracteres.";
        }
    }
    if (isset($_POST['guardarPassword'])) {

        if (empty($passwordAnterior)) {
            $errores['password'] = 'La contraseña es obligatoria.';
        } else if ($passwordAnterior != $passwordAntigua) {
            $errores['password'] = 'Su password anterior no es correcta';
        }
        if (empty($passwordNueva)) {
            $errores['passwordNueva'] = 'La contraseña es obligatoria.';
        } else if (strlen($passwordNueva) < 6 || strlen($passwordNueva) > 15) {
            $errores['passwordNueva'] = 'Su password debe tener de 6 a 15 caracteres  no es correcta';
        }

        if (empty($repetirPassword)) {
            $errores['repetirPassword'] = 'El campo repetir password es obligatorio.';
        } elseif ($repetirPassword != $passwordNueva) {
            $errores['repetirPassword'] = 'El password ingresado y el campo de repetir password no coinciden.';
        }
    }

    if (empty($errores)) {
        if (isset($_POST['guardarDatos'])) {
            $usuario = new Usuario();
            $resultado = $usuario->editarUsuario($idUsuario, $nombre, $apellido, $email, $telefono);

            if ($resultado) {
                $_SESSION['nombre_usuario']=$nombre;

                header('Location: perfilUsuario.php');
                exit();
            } else {
                $errores['general'] = "No se han podido editar los datos. Vuelva a intentarlo.";
            }
            if (isset($_POST['guardarPassword'])) {

                $resultado = $datosPeronsales->setPassword($passwordNueva);

                if ($resultado) {

                    header('Location: perfilUsuario.php');
                    exit();
                } else {
                    $errores['general'] = "No se han podido editar los datos. Vuelva a intentarlo.";
                }
            }
        }
    } else {
        error_log("Errores encontrados: " . print_r($errores, true));
    }
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
    <title>Perfil de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body class="bg-eurogames-blanco">
    <?php require_once 'header.php'; ?>
    <main>
        <div class="container roboto-mono mt-5">

            <?php if (!empty($errores['general'])) : ?>
                <div class="alert alert-warning mt-2 p-1 fs-5 fw-bold d-flex justify-content-center">
                    <?php echo htmlspecialchars($errores['general']); ?>
                </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-md-12 d-flex justify-content-center text-black roboto-mono  mt-4  ">
                    <h1 class="text-center text-shadow-blue p-2 mt-4">¡HOLA, <?php echo htmlspecialchars(strtoupper($nombreUsuario)); ?>!</h1>
                </div>
                <div class="col-md-4  text-black roboto-mono  mt-4  ">
                    <div class="table-carrito table-striped register-orange w-100">
                        <h5 class="mt-3 justify-content-center card-title table-carrito p-3 w-100 text-white bg-secondary">Tus pedidos</h5>

                        <table class=" roboto-mono table table-striped text-center align-middle">
                            <thead>
                                <tr>
                                    <th>ID Pedido</th>
                                    <th>Fecha de Compra</th>
                                    <th>Juego</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($resultadoPedidos as $pedido) : ?>
                                    <tr>
                                        <td><?php echo $pedido['id_pedido']; ?></td>
                                        <td><?php echo $pedido['fecha_pedido']; ?></td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <img src="<?php echo $pedido['foto']; ?>" class="w-25 text-center mt-3" alt="<?php echo $pedido['nombre_juego']; ?>" style="max-width: 100px;">
                                            </div>
                                            <div class="d-flex justify-content-center ">
                                                <h5 class="fw-bolder mt-3 mb-5"><?php echo $pedido['nombre_juego']; ?></h5>
                                            </div>
                                        </td>
                                        <td><button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalFactura<?php echo $pedido['id_pedido']; ?>">
                                                <i class="fas fa-file-invoice"></i>
                                            </button></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Modal para mostrar la factura -->
                <div class="modal fade" id="modalFactura<?php echo $pedido['id_pedido']; ?>" tabindex="-1" aria-labelledby="modalFacturaLabel<?php echo $pedido['id_pedido']; ?>" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-secondary text-white">
                                <h4 class="modal-title" id="modalFacturaLabel<?php echo $pedido['id_pedido']; ?>">Detalle del Pedido</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body bg-eurogames-blanco">
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
                                        <p class="border-3"><strong>Número de pedido:</strong> <?php echo $pedido['id_pedido'] ?></p>
                                        <p><strong>Fecha:</strong> <?php echo $pedido['fecha_pedido']; ?></p>
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
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
         
        <div class="col-md-2 "></div>
        <div class="col-md-6">
            <div id="datos-personales" class="text-center register-orange">
                <h5 class="mt-5 justify-content-center card-title table-carrito p-3 w-100 text-white bg-secondary">Datos Personales</h5>

                <table class="table table-striped table-font-size">
                    <tbody>
                        <tr>
                            <td class="border-0">Nombre:</td>
                            <td class="border-0 text-end"><?php echo $datosPersonales['nombre_usuario']; ?></td>
                        </tr>
                        <tr>
                            <td class="border-0">Apellidos:</td>
                            <td class="border-0 text-end"><?php echo $datosPersonales['apellidos_usuario']; ?></td>
                        </tr>
                        <tr class="fw-bold border-3">
                            <td class="border-0">Email:</td>
                            <td class="border-0 text-end"><?php echo $datosPersonales['email']; ?></td>
                        </tr>
                        <tr class="fw-bold border-3">
                            <td class="border-0">Teléfono:</td>
                            <td class="border-0 text-end"><?php echo $datosPersonales['telefono']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center">
                <button type="button" id="btn-editar" class="btn btn-blue-orange mt-2 mx-2" data-bs-toggle="modal" data-bs-target="#modalModificar">MODIFICAR</button>
                <button type="button" id="btn-editar" class="btn btn-blue-orange mt-2" data-bs-toggle="modal" data-bs-target="#modalCambiarContrasena">CAMBIAR CONTRASEÑA</button>
            </div>
        </div>
        <div class="modal fade" id="modalModificar" tabindex="-1" aria-labelledby="modalModificarLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalModificarLabel">Modificar Datos Personales</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body bg-eurogames-blanco">

                        <div class="d-flex justify-content-center">


                        </div>
                        <?php if (empty($mensajeExito)) : ?>
                            <form class="row g-3" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                <div class="col-md-6 mb-2 mt-5">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text orange-icon fs-2"><i class="fa-solid fa-user" style="color:#1d4c66;"></i></span>
                                        </div>
                                        <input type="text" placeholder="Nombre" class="form-control <?php echo isset($errores['nombre']) ? 'is-invalid' : (($_SERVER["REQUEST_METHOD"] == "POST") ? 'is-valid' : ''); ?>" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre ?? '') ?>">
                                    </div>
                                    <?php if (isset($errores['nombre'])) : ?>
                                        <div class="alert alert-warning mt-2 p-1 fs-5 fw-bold d-flex justify-content-center" role="alert">
                                            <?php echo htmlspecialchars($errores['nombre']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6 mb-2 mt-5">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text orange-icon fs-2"><i class="fa-solid fa-user" style="color:#1d4c66;"></i></span>
                                        </div>
                                        <input type="text" placeholder="Apellido" class="form-control <?php echo isset($errores['apellido']) ? 'is-invalid' : (($_SERVER["REQUEST_METHOD"] == "POST") ? 'is-valid' : ''); ?>" id="apellido" name="apellido" value="<?php echo htmlspecialchars($apellido ?? '') ?>">
                                    </div>
                                    <?php if (isset($errores['apellido'])) : ?>
                                        <div class="alert alert-warning mt-2 p-1 fs-5 fw-bold d-flex justify-content-center" role="alert">
                                            <?php echo htmlspecialchars($errores['apellido']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6  mb-2 mt-5">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text orange-icon fs-2"><i class="fa-solid fa-envelope" style="color:#1d4c66;"></i></span>
                                        </div>
                                        <input type="text" placeholder="Email" class="form-control <?php echo isset($errores['email']) ? 'is-invalid' : (($_SERVER["REQUEST_METHOD"] == "POST") ? 'is-valid' : ''); ?>" id="email" name="email" value="<?php echo htmlspecialchars($email ?? '') ?>">
                                    </div>
                                    <?php if (isset($errores['email'])) : ?>
                                        <div class="alert alert-warning mt-2 p-1 fs-5 fw-bold d-flex justify-content-center" role="alert">
                                            <?php echo htmlspecialchars($errores['email']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6  mb-2 mt-5">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text orange-icon fs-2"><i class="fa-solid fa-phone" style="color:#1d4c66;"></i></span>
                                        </div>
                                        <input type="text" placeholder="Telefono" class="form-control <?php echo isset($errores['telefono']) ? 'is-invalid' : (($_SERVER["REQUEST_METHOD"] == "POST") ? 'is-valid' : ''); ?>" id="telefono" name="telefono" value="<?php echo htmlspecialchars($telefono ?? '') ?>">
                                    </div>
                                    <?php if (isset($errores['telefono'])) : ?>
                                        <div class="alert alert-warning mt-2  p-1 fs-5 fw-bold d-flex justify-content-center" role="alert">
                                            <?php echo htmlspecialchars($errores['telefono']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="guardarDatos" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade " id="modalCambiarContrasena" tabindex="-1" aria-labelledby="modalCambiarContrasenaLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content ">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCambiarContrasenaLabel">Cambiar Contraseña</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body bg-eurogames-blanco ">
                        <div class="d-flex justify-content-center">
                        </div>
                        <?php if (empty($mensajeExito)) : ?>
                            <form class="row g-3" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text orange-icon fs-2"><i class="fa-solid fa-key" style="color:#1d4c66;"></i></span>
                                    </div>
                                    <input type="password" placeholder="Contraseña actual" class="form-control <?php echo isset($errores['passwordAnterior']) ? 'is-invalid' : (($_SERVER["REQUEST_METHOD"] == "POST") ? 'is-valid' : ''); ?>" id="passwordAnterior" name="passwordAnterior" value="<?php echo htmlspecialchars($passwordAnterior ?? '') ?>">
                                </div>
                                <?php if (isset($errores['password'])) : ?>
                                    <div class="alert alert-warning mt-2 p-1 fs-5 fw-bold d-flex justify-content-center" role="alert">
                                        <?php echo htmlspecialchars($errores['password']); ?>
                                    </div>
                                <?php endif; ?>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text orange-icon fs-2"><i class="fa-solid fa-key" style="color:#1d4c66;"></i></span>
                                    </div>
                                    <input type="password" placeholder="Nueva contraseña" class="form-control <?php echo isset($errores['passwordNueva']) ? 'is-invalid' : (($_SERVER["REQUEST_METHOD"] == "POST") ? 'is-valid' : ''); ?>" id="passwordNueva" name="passwordNueva" value="<?php echo htmlspecialchars($passwordNueva ?? '') ?>">
                                </div>
                                <?php if (isset($errores['passwordNueva'])) : ?>
                                    <div class="alert alert-warning mt-2 p-1 fs-5 fw-bold d-flex justify-content-center" role="alert">
                                        <?php echo htmlspecialchars($errores['passwordNueva']); ?>
                                    </div>
                                <?php endif; ?>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text orange-icon fs-2"><i class="fa-solid fa-key" style="color:#1d4c66;"></i></span>
                                    </div>
                                    <input type="password" placeholder="Repetir contraseña" class="form-control <?php echo isset($errores['repetirPassword']) ? 'is-invalid' : (($_SERVER["REQUEST_METHOD"] == "POST") ? 'is-valid' : ''); ?>" id="repetirPassword" name="repetirPassword" value="<?php echo htmlspecialchars($repetirPassword ?? '') ?>">
                                </div>
                                <?php if (isset($errores['repetirPassword'])) : ?>
                                    <div class="alert alert-warning mt-2 p-1 fs-5 fw-bold text-center d-flex justify-content-center" role="alert">
                                        <?php echo htmlspecialchars($errores['repetirPassword']); ?>
                                    </div>
                                <?php endif; ?>
                    </div>
                    </form>
                <?php endif; ?>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="guardarPassword" class="btn btn-primary">Guardar Cambios</button>
                </div>
                </div>
            </div>
        </div>
        </div>
    </main>
    <?php require_once 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="../js/app.js"></script>
</body>

</html>