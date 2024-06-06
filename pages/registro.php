<?php
session_start();
require_once '../classes/Usuario.php';
require_once 'functions.php';
require_once 'config.php';

if (isset($_SESSION['id_usuario'])) {
    header('Location: index.php');
    exit();
}

$errores = [];
$mensajeExito = '';
$usuario = new Usuario();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = isset($_POST['nombre']) ? limpiarDatos($_POST['nombre']) : "";
    $apellido = isset($_POST['apellido']) ? limpiarDatos($_POST['apellido']) : "";
    $email = isset($_POST['email']) ? limpiarDatos($_POST['email']) : "";
    $password = isset($_POST['password']) ? limpiarDatos($_POST['password']) : "";
    $repetirPassword = isset($_POST['repetirPassword']) ? limpiarDatos($_POST['repetirPassword']) : "";
    $telefono = isset($_POST['telefono']) ? limpiarDatos($_POST['telefono']) : "";
    $terminos = isset($_POST['terminos']) ? limpiarDatos($_POST['terminos']) : "";

    if (empty($nombre)) {
        $errores['nombre'] = 'El nombre es obligatorio.';
    }
    if (empty($apellido)) {
        $errores['apellido'] = 'El apellido es obligatorio.';
    }
    if (empty($email)) {
        $errores['email'] = 'El email es obligatorio.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores['email'] = 'El formato del email no es válido.';
    }
    if (empty($password)) {
        $errores['password'] = 'La contraseña es obligatoria.';
    }

    if (empty($repetirPassword)) {
        $errores['repetirPassword'] = 'El campo repetir password es obligatorio.';
    } elseif ($repetirPassword != $password) {
        $errores['repetirPassword'] = 'El password ingresado y el campo de repetir password no coinciden.';
    }

    if (empty($telefono)) {
        $errores['telefono'] = 'El teléfono es obligatorio.';
    }

    if (empty($terminos)) {
        $errores['terminos'] = 'Debe aceptar los términos y condiciones.';
    }

    // Validar el reCAPTCHA - ESTE ES EL QUE VIMOS EN CLASE
    if (!isset($_POST['g-recaptcha-response'])) {
        $errores['recaptcha'] = 'Por favor, complete el reCAPTCHA.';
    } else {
        $recaptcha = $_POST['g-recaptcha-response'];
        $secret_key = RECAPTCHA_SECRET_KEY;
        $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $recaptcha;
        $response = file_get_contents($url);
        $response = json_decode($response);

        if (!$response->success) {
            $errores['recaptcha'] = 'Error en el reCAPTCHA.';
        }
    }

    if (empty($errores)) {
        $resultado = $usuario->crearUsuario($nombre, $apellido, $email, $password, $telefono, 0, null, null);

        if ($resultado == "Usuario creado correctamente") {
            $usuarioInfo = $usuario->obtenerUsuarioPorEmail($email);
            $_SESSION['nombre_usuario'] = $nombre;
            $_SESSION['id_usuario'] = $usuarioInfo['id_usuario'];

            header('Location: index.php');
            exit();
        } else {
            $errores['general'] = $resultado;
        }
    } else {
        error_log("Errores encontrados: " . print_r($errores, true));
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
    <title>Eurogames - Nuevo Usuario</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body class="bg-eurogames-blanco">
    <?php require 'header.php'; ?>
    <main>
        <div class="container-fluid mt-5">
            <div class="row justify-content-center mt-5">
                <div class="col-md-4 mb-4 order-md-1 order-1 register-left text-white p-4 mt-5">
                    <h1 class="text-center mt-5 mb-5 text-shadow-black">¿Eres nuevo en Eurogames?</h1>
                    <span class="orange-icon">
                        <svg class="orange-header" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="100" height="100" fill="orange">
                            <path d="M64 416L168.6 180.7c15.3-34.4 40.3-63.5 72-83.7l146.9-94c3-1.9 6.5-2.9 10-2.9C407.7 0 416 8.3 416 18.6v1.6c0 2.6-.5 5.1-1.4 7.5L354.8 176.9c-1.9 4.7-2.8 9.7-2.8 14.7c0 5.5 1.2 11 3.4 16.1L448 416H240.9l11.8-35.4 40.4-13.5c6.5-2.2 10.9-8.3 10.9-15.2s-4.4-13-10.9-15.2l-40.4-13.5-13.5-40.4C237 276.4 230.9 272 224 272s-13 4.4-15.2 10.9l-13.5 40.4-40.4 13.5C148.4 339 144 345.1 144 352s4.4 13 10.9 15.2l40.4 13.5L207.1 416H64zM279.6 141.5c-1.1-3.3-4.1-5.5-7.6-5.5s-6.5 2.2-7.6 5.5l-6.7 20.2-20.2 6.7c-3.3 1.1-5.5 4.1-5.5 7.6s2.2 6.5 5.5 7.6l20.2 6.7 6.7 20.2c1.1 3.3 4.1 5.5 7.6 5.5s6.5-2.2 7.6-5.5l6.7-20.2 20.2-6.7c3.3-1.1 5.5-4.1 5.5-7.6s-2.2-6.5-5.5-7.6l-20.2-6.7-6.7-20.2zM32 448H480c17.7 0 32 14.3 32 32s-14.3 32-32 32H32c-17.7 0-32-14.3-32-32s14.3-32 32-32z" />
                        </svg>
                    </span>
                    <h3 class="text-center mt-5 mb-5">Si no tienes un usuario, te invitamos a que te registres ahora</h3>
                    <hr>
                    <h3 class="text-center mt-5 mb-5">¿Ya estás registrado? Puedes iniciar sesión aquí</h3>
                    <form action="login.php">
                        <button type="submit" class="btn btn-orange-blue fw-bolder mt-2 px-5">INICIAR SESION</button>
                    </form>
                </div>
                <div class="col-md-7 mb-4 order-md-2 order-2 register-right text-white p-4 ms-md-4 mt-5">
                    <div class="justify-content-center">
                        <h2 class="text-center mt-5 mb-5 text-shadow-black">REGISTRO DE NUEVO USUARIO</h2>
                        <hr>
                    </div>
                    <?php if (!empty($errores['general'])) : ?>
                        <div class="alert alert-danger mt-2 p-1 fs-5 fw-bold">
                            <?php echo htmlspecialchars($errores['general']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($mensajeExito)) : ?>
                        <div class="alert alert-success">
                            <?php echo htmlspecialchars($mensajeExito); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (empty($mensajeExito)) : ?>
                        <form class="row g-3" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="col-md-6 mb-2 mt-5">
                                <label for="inputNombre" class="mb-2 fw-bolder">Nombre:</label>
                                <input type="text" class="form-control <?php echo isset($errores['nombre']) ? 'is-invalid' : ''; ?>" id="inputNombre" name="nombre" placeholder="Ej: Juan" value="<?php echo htmlspecialchars($nombre ?? ''); ?>">
                                <?php if (isset($errores['nombre'])) : ?>
                                    <div class="alert alert-warning mt-2 p-1 fs-5 fw-bold" role="alert">
                                        <?php echo htmlspecialchars($errores['nombre']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6 mb-2 mt-5">
                                <label for="apellido" class="mb-2 fw-bolder">Apellidos:</label>
                                <input type="text" class="form-control <?php echo isset($errores['apellido']) ? 'is-invalid' : ''; ?>" id="apellido" placeholder="Ej: Pérez Pérez" name="apellido" value="<?php echo htmlspecialchars($apellido ?? ''); ?>">
                                <?php if (isset($errores['apellido'])) : ?>
                                    <div class="alert alert-warning mt-2 p-1 fs-5 fw-bold" role="alert">
                                        <?php echo htmlspecialchars($errores['apellido']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6  mb-2 mt-5">
                                <label for="email" class="mb-2 fw-bolder">Email:</label>
                                <input type="email" class="form-control <?php echo isset($errores['email']) ? 'is-invalid' : ''; ?>" id="email" name="email" placeholder="Ej: ejemplo@ejemplo.com" value="<?php echo htmlspecialchars($email ?? ''); ?>">
                                <?php if (isset($errores['email'])) : ?>
                                    <div class="alert alert-warning mt-2 p-1 fs-5 fw-bold" role="alert">
                                        <?php echo htmlspecialchars($errores['email']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6  mb-2 mt-5">
                                <label for="telefono" class="mb-2 fw-bolder">Teléfono:</label>
                                <input type="tel" value="<?= isset($telefono) ? htmlspecialchars($telefono) : '' ?>" class="form-control  <?php echo isset($errores['telefono']) ? 'is-invalid' : ''; ?>" id="telefono" name="telefono" placeholder="Ej: 123456789" maxlength="12" minlength="6">
                                <?php if (isset($errores['telefono'])) : ?>
                                    <div class="alert alert-warning mt-2  p-1 fs-5 fw-bold" role="alert">
                                        <?php echo htmlspecialchars($errores['telefono']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6  mb-2 mt-5">
                                <label for="password" class="mb-2 fw-bolder">Password:</label>
                                <input type="password" class="form-control <?php echo isset($errores['password']) ? 'is-invalid' : ''; ?>" id="password" name="password">
                                <?php if (isset($errores['password'])) : ?>
                                    <div class="alert alert-warning mt-2 p-1 fs-5 fw-bold" role="alert">
                                        <?php echo htmlspecialchars($errores['password']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6  mb-2 mt-5">
                                <label for="repetirPassword" class="mb-2 fw-bolder">Repetir password:</label>
                                <input type="password" class="form-control <?php echo isset($errores['repetirPassword']) ? 'is-invalid' : ''; ?>" id="repetirPassword" name="repetirPassword">
                                <?php if (isset($errores['repetirPassword'])) : ?>
                                    <div class="alert alert-warning mt-2 p-1 fs-5 fw-bold" role="alert">
                                        <?php echo htmlspecialchars($errores['repetirPassword']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-12  d-flex justify-content-center ">
                                <div class="form-check mt-4">
                                    <input class="form-check-input mx-2 mt-2" type="checkbox" id="terminos" name="terminos">
                                    <label class="form-check-label" for="terminos">
                                        Acepto los <a href="#" data-bs-toggle="modal" data-bs-target="#terminosModal"><span class="login text-center fw-bolder text-shadow-black">TÉRMINOS Y CONDICIONES</span></a>
                                    </label>
                                    <?php if (isset($errores['terminos'])) : ?>
                                        <div class="alert alert-warning mt-2 p-1 fs-5 fw-bold" role="alert">
                                            <?php echo htmlspecialchars($errores['terminos']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-12 justify-content-center ">
                                <div class="form-check mt-4  justify-content-center ">
                                    <div class="g-recaptcha d-flex justify-content-center mt-5" data-sitekey="6LdASospAAAAAPE1pV4MDk-GJURScVLBY3cfhBeY"></div>
                                    <?php if (isset($errores['recaptcha'])) : ?>
                                        <div class="d-flex justify-content-center mt-5 mb-3">
                                            <div class="alert alert-warning mt-2 p-1 text-center align-items-center  fs-5 fw-bold" role="alert">
                                                <?php echo htmlspecialchars($errores['recaptcha']); ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="d-flex justify-content-center mt-5 mb-3">
                                    <button type="submit" class="btn btn-orange-black mt-3 mb-3 justify-content-center btn-lg font-weight-bold">REGISTRARSE</button>
                                </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
    <?php include 'footer.php'; ?>

    <div class="modal fade" id="terminosModal" tabindex="-1" aria-labelledby="terminosModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg ">
            <div class="modal-content bg-eurogames-blanco fw-bolder">
                <div class="modal-header">
                    <h5 class="modal-title" id="terminosModalLabel">Términos y Condiciones</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php
                    $terminosPath = __DIR__ . '/../docs/terminosYCondiciones.txt';
                    if (file_exists($terminosPath)) {
                        $terminos = file_get_contents($terminosPath);
                        echo nl2br(htmlspecialchars($terminos));
                    } else {
                        echo 'No se encontraron los términos y condiciones.';
                    }
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-blue-orange" data-bs-dismiss="modal" id="aceptarTerminos">ACEPTO</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="../js/app.js">
        document.getElementById('terminos').addEventListener('click', function() {
            if (this.checked) {
                var myModal = new bootstrap.Modal(document.getElementById('terminosModal'), {
                    keyboard: false
                });
                myModal.show();
            }
        });
    </script>



</body>

</html>