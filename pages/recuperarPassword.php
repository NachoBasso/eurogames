<?php
session_start();
require_once '../classes/Usuario.php';
require_once 'functions.php';
require_once 'config.php';
require_once '../classes/Pedido.php';
require_once '../classes/Juego.php';


$errores = [];
$mensajeExito = '';

if (!empty($_SESSION['nombre_usuario'])) {
    header('Location: index.php');
    exit();
}

$usuario = new Usuario();
$idUsuario = $usuario->getIdUsuario();
$datosPersonales = $usuario->obtenerUsuarioPorId($idUsuario);
$datosUsuario = $usuario->obtenerUsuarioporId($idUsuario);


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = isset($_POST['email']) ? limpiarDatos($_POST['email']) : "";
    if (isset($_POST['email'])) {
        if (empty($email)) {
            $errores['email'] = 'El email es obligatorio.';
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errores['email'] = 'El formato del email no es válido.';
        } else if (!$usuario->usuarioExiste($email)) {
            $errores['email'] = 'No existe un usuario con esa cuenta. Por favor registrarse';
        }
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
        $mensajeExito = "A la brevedad le enviaremos un enlace a su email para actualizar su contraseña";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Ignacio Basso">
    <title>Recupera tu contraseña</title>
    <meta name="author" content="Ignacio Basso">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <title>Eurogames - Recuperar Contraseña</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body class="roboto-mono bg-eurogames-blanco">
    <header>
        <?php
        require_once 'header.php';
        ?>
    </header>
    <div class="container register">
        <div class="">
            <div class="row justify-content-center mt-3">
                <div class="col-md-4 mb-4 order-md-1 order-1 register-left  text-white p-4  mt-5">
                    <h1>¿Olvidaste tu contraseña?</h1><br>
                    <h3 style="color:white">CERO DRAMAS. En unos simples pasos la puedes recuperar</h3>
                    <span class="orange-icon">
                        <svg class="orange-header" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="100" height="100" fill="orange">
                            <path d="M64 416L168.6 180.7c15.3-34.4 40.3-63.5 72-83.7l146.9-94c3-1.9 6.5-2.9 10-2.9C407.7 0 416 8.3 416 18.6v1.6c0 2.6-.5 5.1-1.4 7.5L354.8 176.9c-1.9 4.7-2.8 9.7-2.8 14.7c0 5.5 1.2 11 3.4 16.1L448 416H240.9l11.8-35.4 40.4-13.5c6.5-2.2 10.9-8.3 10.9-15.2s-4.4-13-10.9-15.2l-40.4-13.5-13.5-40.4C237 276.4 230.9 272 224 272s-13 4.4-15.2 10.9l-13.5 40.4-40.4 13.5C148.4 339 144 345.1 144 352s4.4 13 10.9 15.2l40.4 13.5L207.1 416H64zM279.6 141.5c-1.1-3.3-4.1-5.5-7.6-5.5s-6.5 2.2-7.6 5.5l-6.7 20.2-20.2 6.7c-3.3 1.1-5.5 4.1-5.5 7.6s2.2 6.5 5.5 7.6l20.2 6.7 6.7 20.2c1.1 3.3 4.1 5.5 7.6 5.5s6.5-2.2 7.6-5.5l6.7-20.2 20.2-6.7c3.3-1.1 5.5-4.1 5.5-7.6s-2.2-6.5-5.5-7.6l-20.2-6.7-6.7-20.2zM32 448H480c17.7 0 32 14.3 32 32s-14.3 32-32 32H32c-17.7 0-32-14.3-32-32s14.3-32 32-32z" />
                        </svg>
                    </span>
                </div>
                <div class="col-md-7 mb-4 order-md-2 order-2 register-right text-white p-4  ms-md-4 mt-5">
                    <div class="tab-content btn-naranja-outline-success" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="mt-3 card  card-login">
                                <div class="card-header justify-content-center">
                                    <h3 class="text-center"> Ingresa tu cuenta de correo electrónico y recibirás un enlace de restablecimiento de tu contraseña </h3>
                                </div>
                                <?php if (!empty($mensajeExito)) : ?>
                                    <div class="alert alert-success justify-content-center fs-5 text-center roboto-mono text-black">
                                        <?php echo htmlspecialchars($mensajeExito);
                                        echo "<script>
                    setTimeout(function() {
                        window.location.href = 'index.php';
                    }, 4000);
                  </script>";
                                        ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (empty($mensajeExito)) : ?>
                                    <div class="mt-3 card-body mb-5">
                                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                            <div class="input-group form-group ">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text orange-icon fs-2"><i class="fa-solid fa-at" style="color:#1d4c66;"></i></span>
                                                </div>
                                                <input type="text" placeholder="Email" class="form-control <?php echo isset($errores['email']) ? 'is-invalid' : (($_SERVER["REQUEST_METHOD"] == "POST") ? 'is-valid' : ''); ?>" id="email" name="email" value="<?php echo htmlspecialchars($email ?? '') ?>">
                                            </div>
                                            <?php if (isset($errores['email'])) : ?>
                                                <div class="alert alert-warning mt-2 p-1 fs-5 fw-bold d-flex justify-content-center" role="alert">
                                                    <?php echo htmlspecialchars($errores['email']); ?>
                                                </div>
                                            <?php endif; ?>
                                            <div class="form-check mt-4  justify-content-center ">
                                                <div class="g-recaptcha d-flex justify-content-center mt-5" name="g-recaptcha-response" data-sitekey="6LdASospAAAAAPE1pV4MDk-GJURScVLBY3cfhBeY"></div>
                                                <?php if (isset($errores['recaptcha'])) : ?>
                                                    <div class="d-flex justify-content-center mt-5 mb-3">
                                                        <div class="alert alert-warning mt-2 p-1 text-center align-items-center fs-5 fw-bold d-flex justify-content-center" role="alert">
                                                            <?php echo htmlspecialchars($errores['recaptcha']); ?>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                    </div>
                                    <br>
                                    <div class="card-footer">
                                        <div class="d-flex justify-content-center ">
                                            <input type="submit" value="RECUPERAR CONTRASEÑA" class="btn btn-orange-black fw-bolder px-5">
                                        </div>
                                    </div>
                                    </form>
                                    <?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <?php require 'footer.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
</body>

</html>