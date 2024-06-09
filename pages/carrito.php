<?php
session_start();

require_once '../classes/Conexion.php';
require_once '../classes/Juego.php';
require_once 'functions.php';

$juego = new Juego();

const IVA = 0.21;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    if (isset($_POST['eliminar'])) {
        $idJuego = $_POST['eliminar'];
        $_SESSION['carrito'] = array_diff($_SESSION['carrito'], array($idJuego));
        $_SESSION['carrito'] = array_values($_SESSION['carrito']);
    }

    if (isset($_POST['finalizar_compra'])) {
        if (isset($_SESSION['id_usuario'])) {
            if ($juegosConStock) {
                // Eliminar juegos sin stock del carrito al finalizar la compra
                foreach ($juegosSinStock as $juegoSinStock) {
                    $indice = array_search($juegoSinStock['id_juego'], $_SESSION['carrito']);
                    if ($indice !== false) {
                        unset($_SESSION['carrito'][$indice]);
                    }
                }
                $_SESSION['carrito'] = array_values($_SESSION['carrito']); // Reindexar el array
                $nombre = $_POST['nombre_usuario'];
                header("Location: gestionPedido.php");
                exit;
            } else {
                $_SESSION['mensaje_error'] = "No puedes finalizar la compra porque no hay juegos con stock en el carrito.";
                $_SESSION['inicio_desde_carrito'] = true;
                header("Location: carrito.php");
                exit;
            }
        } else {
            $_SESSION['mensaje_error'] = "DEBE INICIAR SESION PARA FINALIZAR LA COMPRA.";
            $_SESSION['inicio_desde_carrito'] = true;
            header("Location: carrito.php");
            exit;
        }
    }
}

$total = 0;
$juegosCarrito = [];
$juegosConStock = false;
$juegosSinStock = [];

if (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0) {
    $cantidadJuegos = array_count_values($_SESSION['carrito']);

    foreach ($cantidadJuegos as $idJuego => $cantidad) {
        $juegoInfo = $juego->obtenerJuegoPorId($idJuego);
        if ($juegoInfo) {
            $stockDisponible = $juego->obtenerStockPorId($idJuego);
            $juegoInfo['cantidad'] = $cantidad;
            if ($stockDisponible > 0) {
                $juegosConStock = true;
                $juegoInfo['subtotal'] = $juegoInfo['precio'] * $cantidad;
                $total += $juegoInfo['subtotal'];
            } else {
                $juegoInfo['subtotal'] = 0; 
                $juegosSinStock[] = $juegoInfo; 
                $indice = array_search($juegoInfo['id_juego'], $_SESSION['carrito']);
                if ($indice !== false) {
                    unset($_SESSION['carrito'][$indice]);
                }
            }
            $juegosCarrito[] = $juegoInfo;
        }
    }

    // Cálculos del resumen de compra
    $subtotal = number_format($total, 2);
    $impuestos = number_format($total * IVA, 2);
    $subtotalResumen = number_format($total - ($total * IVA), 2);
    $totalCompra = number_format($total, 2);

    $_SESSION['resumen_compra'] = [
        'juegos' => $juegosCarrito,
        'subtotal' => $subtotalResumen,
        'envio' => 0.00,
        'impuestos' => $impuestos,
        'total' => $totalCompra
    ];
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
    <title>Eurogames - Carrito de compras</title>
</head>
<?php include 'header.php'; ?>

<body class="bg-eurogames-blanco">
    <main class="mt-5">
        <div class="container mt-5 mb-5">
            <h1 class="mb-5 text-shadow-blue mt-5 text-center-mobile">Carrito de Compras</h1>

            <?php if (isset($_SESSION['mensaje_error'])) : ?>
                <div class="alert alert-warning w-50 mx-auto fs-5 text-center text-black" role="alert" id="error-message">
                    <?php echo $_SESSION['mensaje_error']; ?>
                </div>
                <?php unset($_SESSION['mensaje_error']); ?>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-8">
                    <?php if (isset($juegosCarrito) && count($juegosCarrito) > 0) : ?>
                        <div class="table register-grey">
                            <table class="table table-striped text-center align-middle register-grey">
                                <thead>
                                    <tr class="border-3">
                                        <th scope="col">Eurogame</th>
                                        <th scope="col">Precio</th>
                                        <th scope="col">Cantidad</th>
                                        <th scope="col">Total</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($juegosCarrito as $juegoItem) : ?>
                                        <tr>
                                            <td class="align-middle">
                                                <img src="<?php echo $juegoItem['foto']; ?>" class="w-50" alt="<?php echo $juegoItem['nombre_juego']; ?>" style="max-width: 90px;">
                                                <p><?php echo htmlspecialchars($juegoItem['nombre_juego']); ?></p>
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
                                                        <button type="submit" class="btn btn-danger btn-mobile" <?php echo $juegoItem['cantidad'] == 1 ? ' disabled' : ''; ?>>-</button>
                                                    </form>
                                                    <span><?php echo $juegoItem['cantidad']; ?></span>
                                                    <form action="carrito.php" method="post" class="d-inline">
                                                        <input type="hidden" name="id_juego" value="<?php echo $juegoItem['id_juego']; ?>">
                                                        <input type="hidden" name="accion" value="aumentar">
                                                        <button type="submit" class="btn btn-success btn-mobile" <?php echo $juegoItem['cantidad'] >= $stockDisponible ? 'disabled' : ''; ?>>+</button>
                                                    </form>
                                                <?php } ?>
                                            </td>
                                            <td class="align-middle">
                                                <?php echo '€' . number_format($juegoItem['subtotal'], 2); ?>
                                            </td>
                                            <td class="align-middle">
                                                <?php if ($juegoItem['subtotal'] > 0) : ?>
                                                    <form action="carrito.php" method="post" class="d-inline" onsubmit="return confirmarEliminar()">
                                                        <input type="hidden" name="eliminar" value="<?php echo $juegoItem['id_juego']; ?>">
                                                        <button type="submit" class="btn btn-danger btn-mobile">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else : ?>
                        <div class="alert alert-warning text-white text-center w-100" role="alert">
                            <h2 class="text-black">No hay juegos en el carrito.</h2>
                            <a href="index.php" class="btn btn-blue-orange btn-lg mt-3">SEGUIR COMPRANDO</a>
                        </div>
                        <div class="d-flex flex-column align-items-center">
                    <?php endif; ?>
                    <?php if (isset($_SESSION['mensaje_stock'])) : ?>
                        <div class="alert alert-danger text-center" role="alert">
                            <?php echo $_SESSION['mensaje_stock']; ?>
                            <?php unset($_SESSION['mensaje_stock']); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (isset($juegosCarrito) && count($juegosCarrito) > 0) : ?>
                    <div class="col-md-4">
                        <div class="container-fluis register-grey">
                            <table class="table">
                                <tbody>
                                    <tr class="border-3">
                                        <td class="fw-bolder border-0">Resumen de la compra:</td>
                                        <td class="fw-bolder border-0 text-end"></td>
                                    </tr>
                                    <tr>
                                        <td class="border-0">Subtotal:</td>
                                        <td class="border-0 text-end">€<?php echo number_format($subtotalResumen, 2); ?></td>
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
                                            <?php if ($juegosConStock) : ?>
                                                <form action="carrito.php" method="post" class="d-inline">
                                                    <button type="submit" name="finalizar_compra" class="btn btn-blue-orange mt-2">FINALIZAR COMPRA</button>
                                                </form>
                                            <?php else : ?>
                                                <button type="button" class="btn btn-blue-orange mt-2" disabled>FINALIZAR COMPRA</button>
                                            <?php endif; ?>
                                            <a href="index.php" class="btn btn-orange-blue mt-2 ms-2">SEGUIR COMPRANDO</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </main>
    <?php include 'footer.php'; ?>
    <script src="https://kit.fontawesome.com/0163624b84.js" crossorigin="anonymous"></script> 
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="../js/app.js"></script>
</body>

</html>
