<?php
session_start();

require_once '../classes/Conexion.php';
require_once '../classes/Juego.php';
require_once 'functions.php';

$juego = new Juego();

const IVA = 0.21;


if (isset($_POST['accion'])) {
    $idJuego = isset($_POST['id_juego']) ? limpiarDatos($_POST['id_juego']) : "";
    $stockDisponible = $juego->obtenerStockPorId($idJuego);

    if ($_POST['accion'] == 'aumentar') {
        $cantidadActual = array_count_values($_SESSION['carrito'])[$idJuego] ?? 0;
        if ($cantidadActual < $stockDisponible) {
            $_SESSION['carrito'][] = $idJuego;
        } else {
            $_SESSION['mensaje_stock'] = "No puedes agregar más de este producto. Stock disponible: $stockDisponible.";
        }
    } elseif ($_POST['accion'] == 'disminuir') {
        $indice = array_search($idJuego, $_SESSION['carrito']);
        if ($indice !== false) {
            unset($_SESSION['carrito'][$indice]);
            $_SESSION['carrito'] = array_values($_SESSION['carrito']);
        }
    }
}
//Brindado por CHATGPT
if (isset($_POST['eliminar'])) {
    $idJuego = $_POST['eliminar'];
    $_SESSION['carrito'] = array_diff($_SESSION['carrito'], array($idJuego));
    $_SESSION['carrito'] = array_values($_SESSION['carrito']);
}

$total = 0;
$juegosCarrito = [];

if (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0) {
    $cantidadJuegos = array_count_values($_SESSION['carrito']);

    foreach ($cantidadJuegos as $idJuego => $cantidad) {
        $juegoInfo = $juego->obtenerJuegoPorId($idJuego);
        if ($juegoInfo) {
            $juegoInfo['cantidad'] = $cantidad;
            $total += $juegoInfo['precio'] * $cantidad;
            $juegosCarrito[] = $juegoInfo;
        }
    }
    $subtotal = number_format($total, 2);
    $envio = 0.00;
    $impuestos = number_format($total * IVA, 2);
    $totalCompra = number_format($total + $envio + $impuestos);

    $_SESSION['resumen_compra'] = [
        'juegos' => $juegosCarrito,
        'subtotal' => $subtotal,
        'envio' => $envio,
        'impuestos' => $impuestos,
        'total' => $totalCompra

    ];
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['finalizar_compra'])) {
    if (!isset($_SESSION['id_usuario'])) {
        $_SESSION['mensaje_error'] = "DEBE INICIAR SESION PARA FINALIZAR LA COMPRA.";
        $_SESSION['inicio_desde_carrito'] = true;
        header("Location: carrito.php");
        exit;
    } else {
        $nombre = $_POST['nombre_usuario'];
        header("Location: gestionPedido.php");
        exit;
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
    <title>Eurogames - Carrito de compras</title>
</head>
<?php include 'header.php'; ?>

<body class="bg-eurogames-blanco">
    <main class="mt-5">
        <div class="container-fluid w-75 mt-5 mb-5">
            <h1 class="mb-5 text-shadow-blue mt-5 ">Carrito de Compras</h1>

            <!-- Reformulado con CHATGPT -->
            <?php if (isset($_SESSION['mensaje_error'])) : ?>
                <div class="alert alert-warning w-50 mx-auto fs-5 text-center text-black" role="alert" id="error-message">
                    <?php echo $_SESSION['mensaje_error']; ?>
                </div>
                <?php unset($_SESSION['mensaje_error']); ?>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-8 ">
                    <?php if (isset($juegosCarrito) && count($juegosCarrito) > 0) : ?>
                        <div class="table-responsive register-grey">
                            <table class="table table-striped text-center align-middle">
                                <thead>
                                    <tr class="border-3">
                                        <th scope="col">Eurogame</th>
                                        <th scope="col">Nombre del Juego</th>
                                        <th scope="col">Precio</th>
                                        <th scope="col">Cantidad</th>
                                        <th scope="col">Subtotal</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($juegosCarrito as $juegoItem) : ?>
                                        <tr>
                                            <td class="align-middle">
                                                <img src="<?php echo $juegoItem['foto']; ?>" class="img-fluid" alt="<?php echo $juegoItem['nombre_juego']; ?>" style="max-width: 100px;">
                                            </td>
                                            <td class="align-middle">
                                                <h5><?php echo htmlspecialchars($juegoItem['nombre_juego']); ?></h5>
                                            </td>
                                            <td class="align-middle">
                                                €<?php echo number_format($juegoItem['precio'], 2); ?>
                                            </td>
                                            <td class="align-middle">
                                                <?php
                                                $stockDisponible = $juego->obtenerStockPorId($juegoItem['id_juego']);
                                                if ($stockDisponible == 0) {
                                                    echo '<span class="text-danger">Momentáneamente sin stock</span>';
                                                } else {
                                                ?>
                                                    <form action="carrito.php" method="post" class="d-inline">
                                                        <input type="hidden" name="id_juego" value="<?php echo $juegoItem['id_juego']; ?>">
                                                        <input type="hidden" name="accion" value="disminuir">
                                                        <button type="submit" class="btn btn-danger btn-sm" <?php echo $juegoItem['cantidad'] == 1 ? ' disabled' : ''; ?>>-</button>
                                                    </form>
                                                    <span><?php echo $juegoItem['cantidad']; ?></span>
                                                    <form action="carrito.php" method="post" class="d-inline">
                                                        <input type="hidden" name="id_juego" value="<?php echo $juegoItem['id_juego']; ?>">
                                                        <input type="hidden" name="accion" value="aumentar">
                                                        <button type="submit" class="btn btn-success btn-sm" <?php echo $juegoItem['cantidad'] >= $stockDisponible ? 'disabled' : ''; ?>>+</button>
                                                    </form>
                                                <?php } ?>
                                            </td>
                                            <td class="align-middle">
                                                €<?php echo number_format($juegoItem['precio'] * $juegoItem['cantidad'], 2); ?>
                                            </td>
                                            <td class="align-middle">
                                                <form action="carrito.php" method="post" class="d-inline" onsubmit="return confirmarEliminar()">
                                                    <input type="hidden" name="eliminar" value="<?php echo $juegoItem['id_juego']; ?>">
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                    <?php else : ?>
                        <div class=" alert alert-warning text-white text-center w-100" role="alert">
                            <h2 class="text-black">No hay juegos en el carrito.</h2>
                            <a href="index.php" class="btn btn-blue-orange btn-lg mt-3">SEGUIR COMPRANDO</a>
                        </div>
                        <div class=" d-flex flex-column align-items-center">

                        <?php endif; ?>
                        <?php if (isset($_SESSION['mensaje_stock'])) : ?>
                            <div class="alert alert-danger text-center" role="alert">
                                <?php echo $_SESSION['mensaje_stock']; ?>
                                <?php unset($_SESSION['mensaje_stock']); ?>
                            </div>
                        <?php endif; ?>
                        </div>

                        <?php if (isset($juegosCarrito) && count($juegosCarrito) > 0) : ?>
                            <div class="col-md-4 register-grey">
                                <table class="table">
                                    <tbody>
                                        <tr class="border-3">
                                            <td class="fw-bolder border-0">Resumen de la compra:</td>
                                            <td class="fw-bolder border-0 text-end"></td>
                                        </tr>
                                        <tr>
                                            <td class="border-0">Subtotal:</td>
                                            <td class="border-0 text-end">€<?php echo number_format($subtotal, 2); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="border-0">Envío:</td>
                                            <td class="border-0 text-end">€<?php echo number_format($envio, 2); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="border-0">Impuestos:</td>
                                            <td class="border-0 text-end">€<?php echo number_format($impuestos, 2); ?></td>
                                        </tr>
                                        <tr class="fw-bold border-3">
                                            <td class="border-0">Total:</td>
                                            <td class="border-0 text-end">€<?php echo number_format($totalCompra, 2); ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="border-0 text-center">
                                                <form action="carrito.php" method="post" class="d-inline">
                                                    <button type="submit" name="finalizar_compra" class="btn btn-blue-orange mt-2">FINALIZAR COMPRA</button>
                                                </form>
                                                <a href="index.php" class="btn btn-orange-blue mt-2 ms-2">SEGUIR COMPRANDO</a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                </div>
            </div>
    </main>
    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="../js/app.js"></script>
</body>

</html>