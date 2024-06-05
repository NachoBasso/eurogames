<?php
session_start();

require_once 'config.php';
require_once '../classes/Conexion.php';
require_once '../classes/Usuario.php';
require_once '../classes/Pedido.php';
require_once 'functions.php';

if (empty($_SESSION['nombre_usuario'])) {
    header('Location: login.php');
    exit();
}

$nombreUsuario = $_SESSION['nombre_usuario'];

$conexion = new Conexion();
$db = $conexion->getConBD();

if ($db === null) {
    die("Error de conexión a la base de datos");
}

$idUsuario = $_SESSION['id_usuario'];

$usuario = new Usuario();
$datosPersonales = $usuario->listarDatosPersonales($idUsuario);

$pedido = new Pedido();
$resultadoPedidos = $pedido->listarPedidosPorUsuario($idUsuario);

$mensaje = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['cambiar_contrasena'])) {
        $contrasenaActual = $_POST['contrasena_actual'];
        $nuevaContrasena = $_POST['nueva_contrasena'];
        $confirmarContrasena = $_POST['confirmar_contrasena'];

        if ($usuario->validarPassword($idUsuario, $contrasenaActual)) {
            if ($nuevaContrasena === $confirmarContrasena) {
                $usuario->actualizarPassword($idUsuario, $nuevaContrasena);
                $mensaje = "¡La contraseña se ha cambiado correctamente!";
            } else {
                $mensaje = "Las nuevas contraseñas no coinciden.";
            }
        } else {
            $mensaje = "La contraseña actual es incorrecta.";
        }
    } elseif (isset($_POST['guardar_cambios'])) {
        $nombre = limpiarDatos($_POST['nombre_usuario']);
        $apellido = limpiarDatos($_POST['apellidos_usuario']);
        $email = limpiarDatos($_POST['email']);
        $telefono = limpiarDatos($_POST['telefono']);

        $errores = [];

        if (empty($nombre)) {
            $errores['nombre_usuario'] = 'El nombre es obligatorio.';
        } elseif (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚ\s]+$/", $nombre)) {
            $errores['nombre_usuario'] = 'El nombre solo puede contener letras y espacios.';
        }

        if (empty($apellido)) {
            $errores['apellidos_usuario'] = 'El apellido es obligatorio.';
        } elseif (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚ\s]+$/", $apellido)) {
            $errores['apellidos_usuario'] = 'El apellido solo puede contener letras y espacios.';
        }

        if (empty($email)) {
            $errores['email'] = 'El email es obligatorio.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errores['email'] = 'El formato del email no es válido.';
        }

        if (empty($telefono)) {
            $errores['telefono'] = 'El teléfono es obligatorio.';
        } elseif (!preg_match("/^\d{10}$/", $telefono)) {
            $errores['telefono'] = 'El teléfono debe tener 10 dígitos.';
        }

        if (empty($errores)) {
            $usuario = new Usuario();
            $exito = $usuario->editarUsuario($idUsuario, $nombre, $apellido, $email, $telefono);

            if ($exito) {
                header('Location: perfilUuario.php?mensaje=Datos actualizados correctamente.');
                exit();
            } else {
                $mensaje = "Error al actualizar los datos. Por favor, inténtalo de nuevo.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/probando.css">
</head>
<body class="bg-eurogames-blanco">
    <?php require_once 'header.php'; ?>

    <div class="container mt-5">
    <div class="col-md-12  text-black roboto-mono  mt-4  ">
            <h1 class="text-center text-shadow-blue p-2 mt-2">¡HOLA, <?php echo htmlspecialchars($nombreUsuario); ?>!</h1>
        </div>
        <h2 class="mt-4  text-black">Pedidos Anteriores</h2>
        <div class="table-responsive register-grey text-center">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID PEDIDO</th>
                        <th>Fecha de Compra</th>
                        <th>Juego</th>
                        <th>Foto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Total</th>
                        <th>Factura</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultadoPedidos as $pedido) : ?>
                        <tr>
                            <td><?php echo $pedido['id_pedido']; ?></td>
                            <td><?php echo $pedido['fecha_pedido']; ?></td>
                            <td><?php echo $pedido['nombre_juego']; ?></td>
                            <td><img src="<?php echo $pedido['foto']; ?>" alt="Imagen del juego" width="50"></td>
                            <td><?php echo $pedido['precio']; ?></td>
                            <td><?php echo $pedido['cantidad']; ?></td>
                            <td>€ <?php echo $pedido['cantidad'] * $pedido['precio']; ?></td>
                            <td><button class="btn btn-success">
                                    <i class="fas fa-file-invoice"></i> 
                                </button></td>
                        </tr>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <h2 class="mt-4 text-black">Datos Personales</h2>
        <div id="datos-personales" class="text-center">
            <table class="table register-grey table-striped">
                <tbody>
                <?php foreach ($datosPersonales as $campo => $valor) : ?>
                            <tr>
                                <td>
                                    <?php 
                                    if ($campo == 'nombre_usuario') {
                                        echo 'Nombre';
                                    } elseif ($campo == 'apellidos_usuario') {
                                        echo 'Apellidos';
                                    } else {
                                        echo $campo;
                                    }
                                    ?>
                                </td>
                                <td><input type="text" name="<?php echo $campo; ?>" value="<?php echo htmlspecialchars($valor); ?>"></td>
                            </tr>
                        <?php endforeach; ?>
                    <tr>
                        <td colspan="2">
                            <button id="btn-modificar" class="btn btn-primary mt-3">Modificar</button>
                        </td>
                    </tr>
                </tbody>
            </table>        
        </div>
        
        <div id="form-modificar" class="form-modificar mt-3 register-grey" style="display:none;">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <table class="table">
                    <tbody>
                        <?php foreach ($datosPersonales as $campo => $valor) : ?>
                            <tr>
                                <td><?php echo $campo; ?></td>
                                <td><input type="text" name="<?php echo $campo; ?>" value="<?php echo htmlspecialchars($valor); ?>"></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr id="tr-contrasena" style="display:none;">
                            <td>Contraseña Actual</td>
                            <td><input type="password" class="form-control" name="contrasena_actual" required></td>
                        </tr>
                        <tr id="tr-nueva-contrasena" style="display:none;">
                            <td>Nueva Contraseña</td>
                            <td><input type="password" class="form-control" name="nueva_contrasena" required></td>
                        </tr>
                        <tr id="tr-confirmar-contrasena" style="display:none;">
                            <td>Confirmar Nueva Contraseña</td>
                            <td><input type="password" class="form-control" name="confirmar_contrasena" required></td>
                        </tr>
                        <tr>
                            <td>
                                <button type="button" id="btn-mostrar-contrasena" class="btn btn-secondary">Cambiar Contraseña</button>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-primary" name="guardar_cambios">Guardar Cambios</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>

    <?php require_once 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="../js/app.js"></script>
    <script>
        document.getElementById("btn-modificar").addEventListener("click", function() {
            var formModificar = document.getElementById("form-modificar");
            var formDatosPersonales = document.getElementById("datos-personales");

            // Mostrar el formulario de modificar datos personales
            formModificar.style.display = "block";
            // Ocultar el formulario de cambiar contraseña
            document.getElementById("tr-contrasena").style.display = "none";
            document.getElementById("tr-nueva-contrasena").style.display = "none";
            document.getElementById("tr-confirmar-contrasena").style.display = "none";

            // Ocultar el formulario de datos personales
            formDatosPersonales.style.display = "none";
        });

        document.getElementById("btn-mostrar-contrasena").addEventListener("click", function() {
            // Mostrar el formulario de cambiar contraseña
            document.getElementById("tr-contrasena").style.display = "table-row";
            document.getElementById("tr-nueva-contrasena").style.display = "table-row";
            document.getElementById("tr-confirmar-contrasena").style.display = "table-row";
        });
    </script>

</body>
</html>
