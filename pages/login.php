<?php
session_start();
require_once '../classes/Usuario.php';
require_once 'functions.php';
require_once 'config.php';

$errores = array();
const METODO_VALIDO = "POST";
const MIN_USUARIO = 5;
const MAX_USUARIO = 30;
const MIN_PASSWORD = 5;
const MAX_PASSWORD = 30;


/* Todo el código relacionado con el login de Google es una copia adaptada de la página 
 https://techareatutorials.com/login-with-google-account-in-php/ */
// Configuración de Google OAuth
require_once '../vendor/autoload.php';
$clientID = CLIENT_ID;
$clientSecret = CLIENT_SECRET;
$redirectUri = REDIRECT_URI;

// Crear objeto Google_Client
$client = new Google\Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

// Verificar si el usuario ha iniciado sesión con Google
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token['access_token']);

    // Obtener la información del perfil de Google
    $google_oauth = new Google\Service\Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();
    $emailGoogle =  $google_account_info->email;
    $name =  $google_account_info->name;

    // Verificar si el correo electrónico existe en la base de datos
    $usuario = new Usuario();
    if ($usuario->usuarioExiste($emailGoogle)) {
        $usuarioInfo = $usuario->obtenerUsuarioPorEmail($emailGoogle);
        $_SESSION['nombre_usuario'] = $usuarioInfo['nombre_usuario'];
        $_SESSION['id_usuario'] = $usuarioInfo['id_usuario'];
        $_SESSION['es_administrador'] = $usuarioInfo['es_administrador'];
        if ($usuarioInfo['es_administrador'] == 1) {
            header('Location: administrador.php');
            exit();
        } else {
            if ($_SESSION['inicio_desde_carrito'] == true) {
                header('Location: gestionPedido.php');
                exit();
            } else {
                header('Location: index.php');
                exit();
            }
        }
    } else {
        $errores[] = 'El correo electrónico de Google no está registrado. Por favor, inicie sesión con su cuenta habitual o cree un nuevo usuario.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = new Usuario();
    $email = isset($_POST['email']) ? limpiarDatos($_POST['email']) : "";
    $password = isset($_POST['password']) ? limpiarDatos($_POST['password']) : "";

    if (empty($email) && !empty($password)) {
        $errores['email'] = "Debe ingresar el email del usuario.";
    } else if (!empty($email) && (strlen($email) < MIN_USUARIO || strlen($email) > MAX_USUARIO)) {
        $errores['email'] = "El email debe tener de 5 a 30 caracteres.";
    }

    if (empty($password) && !empty($email)) {
        $errores['password'] = "Debe ingresar la contraseña.";
    } else if (!empty($password) && (strlen($password) < MIN_PASSWORD || strlen($password) > MAX_PASSWORD)) {
        $errores['password'] = "Su contraseña debe tener de 5 a 30 caracteres.";
    }

    if (!empty($email) && !empty($password)) {
        if ($usuario->usuarioExiste($email)) {
            $usuarioInfo = $usuario->obtenerUsuarioPorEmail($email);
            if (password_verify($password, $usuarioInfo['password'])) {
                $_SESSION['nombre_usuario'] = $usuarioInfo['nombre_usuario'];
                $_SESSION['id_usuario'] = $usuarioInfo['id_usuario'];
                $_SESSION['es_administrador'] = $usuarioInfo['es_administrador'];
                if ($usuarioInfo['es_administrador'] == 1) {
                    header('Location: administrador.php');
                    exit();
                } else {
                    if ($_SESSION['inicio_desde_carrito'] == true) {
                        header('Location: gestionPedido.php');
                        exit();
                    } else {
                        header('Location: index.php');
                        exit();
                    }
                }
            } else {
                $errores['general'] = 'El usuario o la contraseña son incorrectas. Inténtelo nuevamente.';
            }
        } else {
            $errores['general'] = 'El usuario o la contraseña son incorrectas. Inténtelo nuevamente.';
        }
    }

    if (empty($email) && empty($password)) {
        $errores['general'] = "Debe ingresar su email y contraseña.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Ignacio Basso">
    <title>Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../css/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body class="roboto-mono bg-eurogames-blanco">

    <?php
    require_once 'header.php';
    ?>

    <main>
        <div class="container mt-5">
            <div class="row justify-content-center mt-5">
                <div class="col-md-4 mb-4 order-md-1 order-1 register-left  text-white p-4  mt-5">
                    <h1 class="text-center mt-5 mb-5 text-shadow-black">Bienvenido a Eurogames</h1>
                    <span class="orange-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width="100" height="100" fill="orange">
                            <!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                            <path d="M274.9 34.3c-28.1-28.1-73.7-28.1-101.8 0L34.3 173.1c-28.1 28.1-28.1 73.7 0 101.8L173.1 413.7c28.1 28.1 73.7 28.1 101.8 0L413.7 274.9c28.1-28.1 28.1-73.7 0-101.8L274.9 34.3zM200 224a24 24 0 1 1 48 0 24 24 0 1 1 -48 0zM96 200a24 24 0 1 1 0 48 24 24 0 1 1 0-48zM224 376a24 24 0 1 1 0-48 24 24 0 1 1 0 48zM352 200a24 24 0 1 1 0 48 24 24 0 1 1 0-48zM224 120a24 24 0 1 1 0-48 24 24 0 1 1 0 48zm96 328c0 35.3 28.7 64 64 64H576c35.3 0 64-28.7 64-64V256c0-35.3-28.7-64-64-64H461.7c11.6 36 3.1 77-25.4 105.5L320 413.8V448zM480 328a24 24 0 1 1 0 48 24 24 0 1 1 0-48z" />
                        </svg>
                    </span>
                    <h3 class="text-center mt-5 mb-5">Si no tienes un usuario, te invitamos a que te registres ahora</h3>
                    <form action="registro.php">
                        <button type="submit" class="btn btn-orange-blue  fw-bolder mt-5 px-5">NUEVO USUARIO</button>
                    </form>
                </div>
                <div class="col-md-7 mb-4 order-md-2 order-2 register-right text-white p-4  ms-md-4 mt-5">
                    <div class="justify-content-center">
                        <h2 class="text-center mt-2 mb-5 text-shadow-black">¿Ya tienes una cuenta?</h2>
                        <h3 class="text-center roboto mb-5 ">Inicia sesión para recuperar tu configuración</h3>
                        <hr>
                    </div>
                    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                    <div class="d-flex justify-content-center">

                        <?php if (!empty($errores['general'])) : ?>
                            <div class="alert alert-warning mt-2 p-1 fs-5 fw-bold w-75 d-flex justify-content-center">
                                <?php echo htmlspecialchars($errores['general']); ?>
                            </div>
                        <?php endif; ?>
                        </div>
                        <div class="input-group form-group mt-5 m-auto w-75">
                            <div class="input-group-prepend">
                            <span class="input-group-text orange-icon fs-2"><i class="fa-solid fa-user" style="color:#1d4c66;"></i></span>

                            </div>
                            <input type="text" class="form-control shadow <?= isset($errores['email']) ? 'is-invalid' : (isset($email) && !empty($email) && !isset($errores['email']) ? 'is-valid' : '') ?>" id="email" name="email" placeholder="Email" value="<?= isset($email) ? $email : '' ?>">                        </div>
                        <div class="d-flex justify-content-center">
                                <?php if (isset($errores['email'])) : ?>
                                    <div class="alert alert-warning mt-2 p-1 fs-5 fw-bold d-flex justify-content-center w-75" role="alert">
                                        <?php echo htmlspecialchars($errores['email']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                        <div class="input-group form-group mt-5 m-auto w-75">
                            <div class="input-group-prepend">
                                <span class="input-group-text orange-icon fs-2"><i class="fa-solid fa-key" style="color: #1d4c66;"></i></span>
                            </div>
                            <input type="password" class="form-control <?= isset($errores['password']) ? 'is-invalid' : (($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($errores['password']) && !empty($password)) ? 'is-valid' : '') ?>" placeholder="Contraseña" id="password" name="password" value="<?= htmlspecialchars($password ?? '') ?>" maxlength="30" minlength="5">
                            
                        </div>
                        <div class="d-flex justify-content-center">
                        <?php if (isset($errores['password'])) : ?>
                                <div class="alert alert-warning mt-2 p-1 fs-5 fw-bold w-75 d-flex justify-content-center" role="alert">
                                    <?php echo htmlspecialchars($errores['password']); ?>
                                </div>
                            <?php endif; ?>
                            </div>
                        <div class="form-group d-flex justify-content-center mt-5">
                            <button type="submit" class="btn btn-orange-black fw-bolder px-5">INICIAR SESIÓN</button>
                        </div>
                        <div class="d-flex justify-content-center mt-2 ">
                            <hr class="w-50">
                        </div>
                        <div class="form-group d-flex justify-content-center mt-2 mb-4">
                            <a href="<?= $client->createAuthUrl() ?>" class="btn btn-orange-black px-4">
                                <i class="fab fa-google text-white"></i> Accede con Google</a>
                        </div>
                    </form>
                    <hr>
                    <div class="card-footer mt-4">
                        <div class="d-flex justify-content-center links">
                            <p class="text-center mt-2">¿NO TIENES UNA CUENTA?</p>
                            <a href="registro.php" class="login text-center fw-bolder text-shadow-black">¡REGÍSTRATE AHORA!</a>
                        </div>
                        <div class="d-flex justify-content-center">
                            <a href="recuperarPassword.php" class="login2">¿OLVIDASTE TU CONTRASEÑA?</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php require 'footer.php' ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
</body>

</html>