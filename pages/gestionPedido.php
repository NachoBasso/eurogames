<?php
session_start();
require_once '../classes/Usuario.php'; 
require_once 'functions.php';


$errores = [];
$mensajeExito = '';
$resultado = "";

$resumenCompra = isset($_SESSION['resumen_compra']) && !empty($_SESSION['resumen_compra']) ? $_SESSION['resumen_compra'] : "";
$carrito = isset($_SESSION['carrito']) && !empty($_SESSION['carrito']) ? $_SESSION['carrito'] : "";
$juegos = $_SESSION['resumen_compra']['juegos'];
$id_usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;

if ($id_usuario) {
    $usuario = new Usuario();
    $datos_usuario = $usuario->listarDatosPersonales($id_usuario);
    $nombreUsuario = $datos_usuario['nombre_usuario'];
    $apellidosUsuario = $datos_usuario['apellidos_usuario'];
    $emailUsuario = $datos_usuario['email'];
    $telefonoUsuario = $datos_usuario['telefono'];

    if (!$datos_usuario) {
        echo "<script>console.log('Error al obtener los datos del usuario');</script>";
    }
} else {
    echo "<script>console.log('ID de usuario no definido en la sesión');</script>";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nombre = isset($_POST['nombre']) ? limpiarDatos($_POST['nombre']) : "";
    $apellidos = isset($_POST['apellidos']) ? limpiarDatos($_POST['apellidos']) : "";
    $email = isset($_POST['email']) ? limpiarDatos($_POST['email']) : "";
    $domicilio = isset($_POST['domicilio']) ? limpiarDatos($_POST['domicilio']) : "";
    $cifONif = isset($_POST['cifONif']) ? limpiarDatos($_POST['cifONif']) : "";
    $telefono = isset($_POST['telefono']) ? limpiarDatos($_POST['telefono']) : "";
    $localidad = isset($_POST['localidad']) ? limpiarDatos($_POST['localidad']) : "";
    $provincia = isset($_POST['provincia']) ? limpiarDatos($_POST['provincia']) : "";
    $codigoPostal = isset($_POST['codigoPostal']) ? limpiarDatos($_POST['codigoPostal']) : "";
    $nombreDestinatario = isset($_POST['nombreDestinatario']) ? limpiarDatos($_POST['nombreDestinatario']) : "";
    $apellidosDestinatario = isset($_POST['apellidosDestinatario']) ? limpiarDatos($_POST['apellidosDestinatario']) : "";
    $domicilioDestinatario = isset($_POST['domicilioDestinatario']) ? limpiarDatos($_POST['domicilioDestinatario']) : "";
    $telefonoDestinatario = isset($_POST['telefonoDestinatario']) ? limpiarDatos($_POST['telefonoDestinatario']) : "";
    $localidadDestinatario = isset($_POST['localidadDestinatario']) ? limpiarDatos($_POST['localidadDestinatario']) : "";
    $provinciaDestinatario = isset($_POST['provinciaDestinatario']) ? limpiarDatos($_POST['provinciaDestinatario']) : "";
    $codigoPostalDestinatario = isset($_POST['codigoPostalDestinatario']) ? limpiarDatos($_POST['codigoPostalDestinatario']) : "";

    if (empty($nombre)) {
        $errores['nombre'] = 'El nombre es obligatorio.';
    }
    if (empty($apellidos)) {
        $errores['apellidos'] = 'El campo apellidos es obligatorio.';
    }
    if (empty($email)) {
        $errores['email'] = 'El email es obligatorio.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores['email'] = 'El formato del email no es válido.';
    }
    if (empty($domicilio)) {
        $errores['domicilio'] = 'El domicilio es obligatoria.';
    }

    if (empty($telefono)) {
        $errores['telefono'] = 'El teléfono es obligatorio.';
    }
    if (empty($cifONif)) {
        $errores['cifONif'] = 'El CIF / NIF es obligatorio.';
    }

    if (empty($provincia)) {
        $errores['provincia'] = 'Campo obligatorio.';
    }

    if (empty($codigoPostal)) {
        $errores['codigoPostal'] = 'Campo obligatorio.';
    }

    if (empty($localidad)) {
        $errores['localidad'] = 'Campo obligatorio.';
    }
    if (empty($nombreDestinatario)) {
        $errores['nombreDestinatario'] = 'El nombre es obligatorio.';
    }
    if (empty($apellidosDestinatario)) {
        $errores['apellidosDestinatario'] = 'El campo apellidos es obligatorio.';
    }

    if (empty($domicilioDestinatario)) {
        $errores['domicilioDestinatario'] = 'El domicilio es obligatoria.';
    }

    if (empty($telefonoDestinatario)) {
        $errores['telefonoDestinatario'] = 'El teléfono es obligatorio.';
    }

    if (empty($provinciaDestinatario)) {
        $errores['provinciaDestinatario'] = 'Campo obligatorio.';
    }

    if (empty($localidadDestinatario)) {
        $errores['localidadDestinatario'] = 'Campo obligatorio.';
    }
    if (empty($codigoPostalDestinatario)) {
        $errores['codigoPostalDestinatario'] = 'Campo obligatorio.';
    }


    if (empty($errores)) {
        $_SESSION['facturacion'] = $_POST;
        $resultado = "Factura creada correctamente";

        header('Location: index.php');
        exit();

        /*PUEDO CREAR UNA FACTURA*/
    } else {
        $errores['general'] = $resultado;
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/probando.css">
    <title>Eurogames - Datos de Facturación</title>
</head>

<body class="bg-eurogames-blanco roboto-mono ">
    <?php include 'header.php'; ?>
    <main class="mt-5">
        <div class="container-fluid mt-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="register-right text-white mt-2 py-5">
                        <h2 class="mb-5 text-shadow-black mt-2 text-center">FINALIZAR COMPRA</h2>
                        <hr>
                        <?php if (!empty($errores['general'])) : ?>
                            <div class="alert alert-danger">
                                <?php echo htmlspecialchars($errores['general']); ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($mensajeExito)) : ?>
                            <div class="alert alert-success">
                                <?php echo htmlspecialchars($mensajeExito); ?>
                            </div>
                        <?php endif; ?>
                        <?php if (empty($mensajeExito)) : ?>
                            <div class="row">
                                <div class="col-md-6 mx-auto p-4">
                                    <form class="row g-3 p-2 form-mobile" method="post" id="1" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                        <div class="d-flex justify-content-center mt-2 mb-3">
                                            <h3 class="text-white text-shadow-black text-center">Datos Personales para Facturación</h3>
                                        </div>
                                        <!-- Esta parte del código fue reformulada por chatGPT -->
                                        <div class="form-check mb-3 mr-5">
                                            <input type="checkbox" class="form-check-input" id="usarInfoGuardada" onclick="rellenarDatos(this)"
                                                data-nombre="<?= isset($nombreUsuario) ? htmlspecialchars($nombreUsuario) : '' ?>" 
                                                data-apellidos="<?= isset($apellidosUsuario) ? htmlspecialchars($apellidosUsuario) : '' ?>" 
                                                data-email="<?= isset($emailUsuario) ? htmlspecialchars($emailUsuario) : '' ?>" 
                                                data-telefono="<?= isset($telefonoUsuario) ? htmlspecialchars($telefonoUsuario) : '' ?>" checked>
                                            <label class="form-check-label" for="usarInfoGuardada">Usar mi información guardada</label>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="nombre">Nombre</label>
                                            <input type="text" class="form-control <?php echo isset($errores['nombre']) ? 'is-invalid' : ''; ?>" id="nombre" name="nombre" placeholder="Ej: Juan" value="<?php echo htmlspecialchars($nombreUsuario); ?>">
                                            <?php if (isset($errores['nombre'])) : ?>
                                                <div class="alert alert-warning mt-2 p-1 fw-bold" role="alert">
                                                    <?php echo htmlspecialchars($errores['nombre']); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="apellidos">Apellidos</label>
                                            <input type="text" class="form-control <?php echo isset($errores['apellidos']) ? 'is-invalid' : ''; ?>" id="apellidos" placeholder="Ej: Pérez Pérez" name="apellidos" value="<?php echo htmlspecialchars($apellidosUsuario); ?>">
                                            <?php if (isset($errores['apellidos'])) : ?>
                                                <div class="alert alert-warning mt-2 p-1 fw-bold" role="alert">
                                                    <?php echo htmlspecialchars($errores['apellidos']); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control <?php echo isset($errores['email']) ? 'is-invalid' : ''; ?>" id="email" name="email" placeholder="Ej: ejemplo@ejemplo.com" value="<?php echo htmlspecialchars($emailUsuario); ?>">
                                            <?php if (isset($errores['email'])) : ?>
                                                <div class="alert alert-warning mt-2 p-1 fw-bold" role="alert">
                                                    <?php echo htmlspecialchars($errores['email']); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="telefono">Teléfono</label>
                                            <input type="tel" value="<?= isset($telefonoUsuario) ? htmlspecialchars($telefonoUsuario) : '' ?>" class="form-control  <?php echo isset($errores['telefono']) ? 'is-invalid' : ''; ?>" id="telefono" name="telefono" placeholder="Ej: 123456789" maxlength="12" minlength="6">
                                            <?php if (isset($errores['telefono'])) : ?>
                                                <div class="alert alert-warning mt-2 p-1 fw-bold" role="alert">
                                                    <?php echo htmlspecialchars($errores['telefono']); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="cifONif">CIF / NIF</label>
                                            <input type="text" value="<?= isset($cifONif) ? htmlspecialchars($cifONif) : '' ?>" class="form-control  <?php echo isset($errores['cifONif']) ? 'is-invalid' : ''; ?>" id="cifONif" name="cifONif" placeholder="Ej: 123456789" maxlength="9" minlength="8">
                                            <?php if (isset($errores['cifONif'])) : ?>
                                                <div class="alert alert-warning mt-2 p-1 fw-bold" role="alert">
                                                    <?php echo htmlspecialchars($errores['cifONif']); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="direccion">Domicilio</label>
                                            <input type="text" value="<?= isset($domicilio) ? htmlspecialchars($domicilio) : '' ?>" class="form-control  <?php echo isset($errores['domicilio']) ? 'is-invalid' : ''; ?>" id="domicilio" name="domicilio" placeholder="Ej: Mi Calle 5, Piso 1 Depto 2" minlength="6">
                                            <?php if (isset($errores['domicilio'])) : ?>
                                                <div class="alert alert-warning mt-2 p-1 fw-bold" role="alert">
                                                    <?php echo htmlspecialchars($errores['domicilio']); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-4  mb-2">
                                            <label for="localidad">Localidad</label>
                                            <input type="text" class="form-control <?php echo isset($errores['localidad']) ? 'is-invalid' : ''; ?>" id="localidad" placeholder="Ej: Madrid" name="localidad" value="<?php echo htmlspecialchars($localidad ?? ''); ?>">
                                            <?php if (isset($errores['localidad'])) : ?>
                                                <div class="alert alert-warning mt-2 p-1 fw-bold" role="alert">
                                                    <?php echo htmlspecialchars($errores['localidad']); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-4  mb-2">
                                            <label for="provincia">Provincia</label>
                                            <input type="text" class="form-control <?php echo isset($errores['provincia']) ? 'is-invalid' : ''; ?>" id="provincia" placeholder="Ej: Madrid" name="provincia" value="<?php echo htmlspecialchars($provincia ?? ''); ?>">
                                            <?php if (isset($errores['provincia'])) : ?>
                                                <div class="alert alert-warning mt-2 p-1 fw-bold" role="alert">
                                                    <?php echo htmlspecialchars($errores['provincia']); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label for="codigoPostal">CP</label>
                                            <input type="text" class="form-control <?php echo isset($errores['codigoPostal']) ? 'is-invalid' : ''; ?>" id="codigoPostal" placeholder="Ej: 28001" name="codigoPostal" value="<?php echo htmlspecialchars($codigoPostal ?? ''); ?>">
                                            <?php if (isset($errores['codigoPostal'])) : ?>
                                                <div class="alert alert-warning mt-2 p-1 fw-bold" role="alert">
                                                    <?php echo htmlspecialchars($errores['codigoPostal']); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <hr>

                                        <div class="d-flex justify-content-center mt-2">
                                            <h3 class="text-white text-shadow-black">Datos de Envío</h3>
                                        </div>
                                        <div class="form-check mb-3 mr-5">
                                            <input type="checkbox" class="form-check-input" id="usarInfoPersonal" onclick="copiarDatosPersonales()">
                                            <label class="form-check-label" for="usarInfoPersonal">Usar datos personales para facturación </label>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="nombreDestinatario">Nombre</label>
                                            <input type="text" class="form-control <?php echo isset($errores['nombreDestinatario']) ? 'is-invalid' : ''; ?>" id="nombreDestinatario" name="nombreDestinatario" placeholder="Ej: Juan" value="<?php echo htmlspecialchars($nombreDestinatario ?? ''); ?>">
                                            <?php if (isset($errores['nombreDestinatario'])) : ?>
                                                <div class="alert alert-warning mt-2 p-1 fw-bold" role="alert">
                                                    <?php echo htmlspecialchars($errores['nombreDestinatario']); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="apellidosDestinatario">Apellidos</label>
                                            <input type="text" class="form-control <?php echo isset($errores['apellidosDestinatario']) ? 'is-invalid' : ''; ?>" id="apellidosDestinatario" placeholder="Ej: Pérez Pérez" name="apellidosDestinatario" value="<?php echo htmlspecialchars($apellidosDestinatario ?? ''); ?>">
                                            <?php if (isset($errores['apellidosDestinatario'])) : ?>
                                                <div class="alert alert-warning mt-2 p-1 fw-bold" role="alert">
                                                    <?php echo htmlspecialchars($errores['apellidosDestinatario']); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="telefono">Teléfono</label>
                                            <input type="tel" value="<?= isset($telefonoDestinatario) ? htmlspecialchars($telefonoDestinatario) : '' ?>" class="form-control  <?php echo isset($errores['telefonoDestinatario']) ? 'is-invalid' : ''; ?>" id="telefonoDestinatario" name="telefonoDestinatario" placeholder="Ej: 123456789" maxlength="12" minlength="6">
                                            <?php if (isset($errores['telefono'])) : ?>
                                                <div class="alert alert-warning mt-2 p-1 fw-bold" role="alert">
                                                    <?php echo htmlspecialchars($errores['telefonoDestinatario']); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="direccionDestinatario">Domicilio</label>
                                            <input type="text" value="<?= isset($domicilioDestinatario) ? htmlspecialchars($domicilioDestinatario) : '' ?>" class="form-control  <?php echo isset($errores['domicilioDestinatario']) ? 'is-invalid' : ''; ?>" id="domicilioDestinatario" name="domicilioDestinatario" placeholder="Ej: Mi Calle 5, Piso 1 Depto 2" minlength="6">
                                            <?php if (isset($errores['domicilioDestinatario'])) : ?>
                                                <div class="alert alert-warning mt-2 p-1 fw-bold" role="alert">
                                                    <?php echo htmlspecialchars($errores['domicilioDestinatario']); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="localidadDestinatario">Localidad</label>
                                            <input type="text" class="form-control <?php echo isset($errores['localidadDestinatario']) ? 'is-invalid' : ''; ?>" id="localidadDestinatario" placeholder="Ej: Madrid" name="localidadDestinatario" value="<?php echo htmlspecialchars($localidadDestinatario ?? ''); ?>">
                                            <?php if (isset($errores['localidadDestinatario'])) : ?>
                                                <div class="alert alert-warning mt-2 p-1 fw-bold" role="alert">
                                                    <?php echo htmlspecialchars($errores['localidadDestinatario']); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="provinciaDestinatario">Provincia</label>
                                            <input type="text" class="form-control <?php echo isset($errores['provinciaDestinatario']) ? 'is-invalid' : ''; ?>" id="provinciaDestinatario" placeholder="Ej: Madrid" name="provinciaDestinatario" value="<?php echo htmlspecialchars($provinciaDestinatario ?? ''); ?>">
                                            <?php if (isset($errores['provinciaDestinatario'])) : ?>
                                                <div class="alert alert-warning mt-2 p-1 fw-bold" role="alert">
                                                    <?php echo htmlspecialchars($errores['provinciaDestinatario']); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="codigoPostalDestinatario">CP</label>
                                            <input type="text" class="form-control <?php echo isset($errores['codigoPostalDestinatario']) ? 'is-invalid' : ''; ?>" id="codigoPostalDestinatario" placeholder="Ej: 28001" name="codigoPostalDestinatario" value="<?php echo htmlspecialchars($codigoPostalDestinatario ?? ''); ?>">
                                            <?php if (isset($errores['codigoPostalDestinatario'])) : ?>
                                                <div class="alert alert-warning mt-2 p-1 fw-bold" role="alert">
                                                    <?php echo htmlspecialchars($errores['codigoPostal']); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-12">
                                            <hr>

                                            <div class="d-flex justify-content-center mt-2">
                                                <h3 class="text-white text-shadow-black mb-4">Método de Envío</h3>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="radio" name="metodoEnvio" id="recogerLocal" value="recogerLocal">
                                                <label class="form-check-label text-white" for="recogerLocal">Recoge en nuestro local por €0</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="metodoEnvio" id="envioDomicilio" value="envioDomicilio" checked>
                                                <label class="form-check-label text-white" for="envioDomicilio">Envío a domicilio a €0</label>
                                            </div>


                                        </div>
                                        <div class="col-md-12">
    <hr>
    <div class="d-flex justify-content-center mt-2">
        <h3 class="text-white text-shadow-black">Método de Pago</h3>
    </div>
    <div class="row mt-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center bg-light">
                    <i class="fas fa-money-bill-wave fa-3x text-success mb-3"></i>
                    <h5 class="card-title mb-4 ">Pago en Efectivo</h5>
                    <p class="card-text mb-5">Paga en efectivo al momento de la entrega.</p>
                    <div class="form-check">
                        <input class="form-check-input custom-radio-input " type="radio" name="metodoPago" id="pagoEfectivo" value="efectivo" checked>
                        <label class="form-check-label" for="pagoEfectivo"></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center bg-light-secondary">
                    <i class="fas fa-university fa-3x text-primary mb-3"></i>
                    <h5 class="card-title mb-4">Transferencia Bancaria</h5>
                    <p class="card-text mb-5">Realiza una transferencia a nuestra cuenta.</p>
                    <div class="form-check">
                        <input class="form-check-input custom-radio-input" type="radio" name="metodoPago" 
                                id="pagoTransferencia" value="transferencia">
                        <label class="form-check-label" for="pagoTransferencia">Seleccionar</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="infoTransferencia" style="display: none;" class="col-md-12 mt-3">
    <hr>
    <h4 class="text-white text-center">Información para Transferencia Bancaria</h4>
    <ul class="list-group mt-3">
        <li class="list-group-item">Banco: Banco BASSO </li>
        <li class="list-group-item">IBAN: ES00 0001 0002 0003 0004 0005</li>
        <li class="list-group-item">Beneficiario: Eurogames S.A.</li>
        <li class="list-group-item">Concepto: Tu número de pedido</li>
    </ul>
</div>
                                </div>
                                <div class="col-md-6 mx-auto p-4">
                                    <h3 class="text-white text-shadow-black text-center mb-5">Detalles de tu compra</h3>

                                    <div class="card mt-5">
                                        <div class="card-body bg-eurogames-blanco">
                                            <?php foreach ($juegos as $juego) : ?>
                                                <div class="d-flex justify-content-center">
                                                    <img src="<?php echo $juego['foto']; ?>" class="img-fluid text-center mt-3" alt="<?php echo $juego['nombre_juego']; ?>" style="max-width: 100px;">
                                                </div>
                                                <div class="d-flex justify-content-center ">
                                                    <h5 class="fw-bolder mt-3 mb-5"><?php echo $juego['nombre_juego']; ?></h5>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table table-striped  text-center align-middle">
                                                        <thead >
                                                            <tr class="border-5">
                                                                <th>Cantidad</th>
                                                                <th>Precio</th>
                                                                <th>Subtotal</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr class="border-5">
                                                                <td><?php echo $juego['cantidad']; ?></td>
                                                                <td>€<?php echo number_format($juego['precio'], 2); ?></td>
                                                                <td>€<?php echo number_format($juego['precio'] * $juego['cantidad'], 2); ?></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <hr class="border-5">
                                                </div>
                                            <?php endforeach; ?>
                                        </div>

                                    </div>
                                    <h3 class="text-white text-shadow-black text-center mt-4">Resumen de la Compra</h3>
                                    <div class="card mt-3 mr-4 bg-eurogames-blanco">
                                        <div class="card-body bg-eurogames-blanco">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item">Subtotal: <span class="justify-content-end">€<?php echo $resumenCompra['subtotal']; ?></span></li>
                                                <li class="list-group-item">Gastos de Envío: €<?php echo $resumenCompra['envio']; ?></li>
                                                <li class="list-group-item">Impuestos (21%): €<?php echo $resumenCompra['impuestos']; ?></li>
                                                <li class="list-group-item bg-warning text-shadow-dark">Total a Pagar: €<?php echo $resumenCompra['total']; ?></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center mt-5 mb-3">
                                    <button type="submit" class="btn btn-orange-black mt-3 mb-3 justify-content-center btn-lg font-weight-bold">CONFIRMAR EL PEDIDO</button>
                                </div>
                                </form>
                            <?php endif; ?>
                            </div>
                    </div>
                </div>
            </div>

    </main>
    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="../js/app.js"></script>
</body>

</html>