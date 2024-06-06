<?php
require_once 'functions.php';
require_once 'config.php';

$errores = [];
$mensajeExito = '';
const MJE_INPUT = "Comentarios:";
const METODO_VALIDO = "POST";

if ($_SERVER["REQUEST_METHOD"] == METODO_VALIDO) {

    $nombreYApellido = isset($_POST['nombreYApellido']) ? limpiarDatos($_POST['nombreYApellido']) : "";
    $email = isset($_POST['email']) ? limpiarDatos($_POST['email']) : "";
    $telefono = isset($_POST['telefono']) ? limpiarDatos($_POST['telefono']) : "";
    $comentarios = isset($_POST['comentarios']) ? limpiarDatos($_POST['comentarios']) : "";

    if (empty($nombreYApellido)) {
        $errores['nombreYApellido'] = 'El nombre y apellido son obligatorios.';
    }
    if (empty($email)) {
        $errores['email'] = 'El email es obligatorio.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores['email'] = 'El formato del email no es válido.';
    }

    if (empty($telefono)) {
        $errores['telefono'] = 'El teléfono es obligatorio.';
    }

    if (empty($comentarios) || $comentarios == MJE_INPUT) {
        $errores['comentarios'] = 'Debe escribir un comentario.';
    }

    // Validar el reCAPTCHA
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
        $mensajeExito = 'Muchas gracias por escribirnos.' . "\n" . 'Nos estaremos comunicando a la brevedad.';
        // Si me da tiempo voy a crear una tabla contacto donde se guarde todo lo de este formulario.
        // Ademas le crearía un botón en el header al administrador para que pueda ver los mensajes
        // con un alert con mensajes no leidos (y que los pueda ir borrando).
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Ignacio Basso">
    <title>Contacto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body class="bg-eurogames-blanco roboto-mono">

    <?php require_once 'header.php'; ?>

    <main>
        <div class="container mt-5">
            <div class="row justify-content-center mt-5">
                <div class="col-md-4 mb-4 order-md-1 order-1 register-left text-white p-4 mt-5">
                    <h1 class="text-center mt-5 mb-5 text-shadow-black">CONTACTO</h1><br>
                    
                    <h3 class="text-shadow-black mt-5 mb-5">¿Tienes alguna consulta?<br><br> Por favor completa el siguiente formulario </h3><br>
                    <span class="orange-icon mt-5 mb-5">                    
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="100" height="100" fill="orange"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M16.1 260.2c-22.6 12.9-20.5 47.3 3.6 57.3L160 376V479.3c0 18.1 14.6 32.7 32.7 32.7c9.7 0 18.9-4.3 25.1-11.8l62-74.3 123.9 51.6c18.9 7.9 40.8-4.5 43.9-24.7l64-416c1.9-12.1-3.4-24.3-13.5-31.2s-23.3-7.5-34-1.4l-448 256zm52.1 25.5L409.7 90.6 190.1 336l1.2 1L68.2 285.7zM403.3 425.4L236.7 355.9 450.8 116.6 403.3 425.4z"/></svg>
                </span><br><br>
                        <h3 class="mt-5 mb-5">No pierdas la oportunidad de contactarnos.</h3><br><br>
                    <span class="orange-icon">                    
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width="200" height="200" fill="orange"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M128 0C110.3 0 96 14.3 96 32V224h96V192c0-35.3 28.7-64 64-64H480V32c0-17.7-14.3-32-32-32H128zM256 160c-17.7 0-32 14.3-32 32v32h96c35.3 0 64 28.7 64 64V416H576c17.7 0 32-14.3 32-32V192c0-17.7-14.3-32-32-32H256zm240 64h32c8.8 0 16 7.2 16 16v32c0 8.8-7.2 16-16 16H496c-8.8 0-16-7.2-16-16V240c0-8.8 7.2-16 16-16zM64 256c-17.7 0-32 14.3-32 32v13L187.1 415.9c1.4 1 3.1 1.6 4.9 1.6s3.5-.6 4.9-1.6L352 301V288c0-17.7-14.3-32-32-32H64zm288 84.8L216 441.6c-6.9 5.1-15.3 7.9-24 7.9s-17-2.8-24-7.9L32 340.8V480c0 17.7 14.3 32 32 32H320c17.7 0 32-14.3 32-32V340.8z"/></svg>
                </span> 
                </div>
                <div class="col-md-7 mb-4 order-md-2 order-2 register-right text-white p-4 ms-md-4 mt-5">
                    <div class="justify-content-center">
                        <h2 class="text-center roboto-mono mt-2 mb-5 text-shadow-black">
                            Ingresa todos tus datos y te responderemos a la brevedad
                        </h2>
                        <hr>
                    </div>
                    <?php if ($mensajeExito): ?>
        <div class="alert alert-success mt-4 p-5 text-center fs-5 fw-bold" role="alert" >
            <?php echo htmlspecialchars($mensajeExito); ?>
        </div>
    <?php endif; ?>
                    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
    <div class="form-row">
        <div class="form-group col-md-12">
            <label for="nombreYApellido">Nombre y apellido</label>
            <input type="text" value="<?= isset($nombreYApellido) ? htmlspecialchars($nombreYApellido) : '' ?>" class="form-control mt-2 <?php echo isset($errores['nombreYApellido']) ? 'is-invalid' : ''; ?>" id="nombreYApellido" name="nombreYApellido" placeholder="Ingrese el nombre de la persona física o jurídica" maxlength="30" minlength="5" >
            <?php if (isset($errores['nombreYApellido'])): ?>
                <div class="alert alert-warning mt-2 p-1 fs-5 fw-bold" role="alert" >
                    <?php echo htmlspecialchars($errores['nombreYApellido']); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="form-group mt-2">
        <label for="email">Email</label>
        <input type="email" value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" class="form-control mt-2 <?php echo isset($errores['email']) ? 'is-invalid' : ''; ?>" id="email" name="email" placeholder="Ej: ejemplo@correo.com" maxlength="30" minlength="5">
        <?php if (isset($errores['email'])): ?>
            <div class="alert alert-warning mt-2 p-1 fs-5 fw-bold" role="alert" >
                <?php echo htmlspecialchars($errores['email']); ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="form-group mt-2">
        <label for="telefono">Teléfono</label>
        <input type="tel" value="<?= isset($telefono) ? htmlspecialchars($telefono) : '' ?>" class="form-control mt-2 <?php echo isset($errores['telefono']) ? 'is-invalid' : ''; ?>" id="telefono" name="telefono" placeholder="Ej: 123456789" maxlength="12" minlength="6">
        <?php if (isset($errores['telefono'])): ?>
            <div class="alert alert-warning mt-2 p-1 fs-5 fw-bold" role="alert" >
                <?php echo htmlspecialchars($errores['telefono']); ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="form-group mt-4">
        <label for="comentarios">Comentarios</label>
        <textarea class="form-control custom-area mt-2 <?php echo isset($errores['comentarios']) ? 'is-invalid' : ''; ?>" id="comentarios" name="comentarios" placeholder="Comentarios:" ><?= isset($comentarios) ? htmlspecialchars($comentarios) : '' ?></textarea>
        <?php if (isset($errores['comentarios'])): ?>
            <div class="alert alert-warning mt-2 p-1 fs-5 fw-bold" role="alert">
                <?php echo htmlspecialchars($errores['comentarios']); ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="g-recaptcha d-flex justify-content-center mt-5" data-sitekey="6LdASospAAAAAPE1pV4MDk-GJURScVLBY3cfhBeY"></div>
    <?php if (isset($errores['recaptcha'])): ?>
        <div class="alert alert-warning mt-2 p-1 fs-5 fw-bold" role="alert" >
            <?php echo htmlspecialchars($errores['recaptcha']); ?>
        </div>
    <?php endif; ?>
    <div class="d-flex justify-content-center mt-5 mb-3">
        <button type="submit" class="btn btn-orange-black mt-3 mb-3 justify-content-center btn-lg font-weight-bold">ENVIAR</button>
    </div>
</form>
                </div>
            </div>
        </div>
    </main>

    <?php require 'footer.php'; ?>
   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/0163624b84.js" crossorigin="anonymous"></script>
</body>
</html>