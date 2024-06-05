<?php
require_once 'conexion.php';
require_once '../classes/Juego.php';
require_once 'functions.php';

session_start();
$nombreUsuario = $_SESSION['nombre_usuario'];
$juego = new Juego();
$datosJuego = $juego->listarJuegosCrud();

if (isset($_POST['eliminar'])) {
    $idJuego = $_POST['eliminar'];
    $juego->borrarJuego($idJuego);
    header('Location: administrador.php');
}


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Ignacio Basso">
    <title>Eurogames - Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/probando.css">
</head>

<body class="bg-eurogames-blanco">
    <?php require 'header.php'; ?>

    <div class="container-fluid">
        <div class="col-md-12  text-black roboto-mono  mt-4  ">
            <h1 class="text-center text-shadow-blue p-5 mt-5">¡HOLA,
                <?php echo htmlspecialchars($nombreUsuario); ?>!</h1>
        </div>
        <div class=" col-md-12 p-5 text-black roboto-mono mt-4 register-grey">
            <h2 class="roboto-mono text-black  mb-5">Administrar Juegos</h2>
            <a href="agregarJuego.php" class="btn btn-orange-black fw-bold mt-2 mb-5">AGREGAR JUEGO</a>
            <table class="table table-sm table-responsive-sm table-striped table-bordered text-center
                     font-weight-bold border-dark  align-middle mb-4">
                <thead class="mt-5 text-center align-middle">
                    <tr>
                        <th class="align-middle">ID</th>
                        <th class="align-middle">Nombre del Juego</th>
                        <th class="align-middle">Precio</th>
                        <th class="align-middle">Descripción</th>
                        <th class="align-middle">Stock</th>
                        <th class="align-middle">Editor</th>
                        <th class="align-middle">Año de Edición</th>
                        <th class="align-middle">Cantidad de Jugadores</th>
                        <th class="align-middle">Foto</th>
                        <th class="align-middle">Edad Mínima</th>
                        <th class="align-middle">Duración en minutos</th>
                        <th class="align-middle">Categoria</th>
                        <th class="align-middle">Acciones</th>
                    </tr>
                </thead>
                <tbody>
    <?php foreach ($datosJuego as $juego) : ?>
        <tr>
            <td><?= $juego['id_juego'] ?></td>
            <td><?= $juego['nombre_juego'] ?></td>
            <td><?= "€" . $juego['precio'] ?></td>
            <td class="align-middle"><div class="d-flex align-items-center justify-content-center">
                    <?= substr($juego['descripcion'], 0, 20) ?>... <button class="btn btn-link ver-mas" data-bs-toggle="modal" data-bs-target="#modalJuego<?= $juego['id_juego'] ?>">Ver más</button>
                </div>
            </td>
            <td><?= $juego['stock'] ?></td>
            <td><?= $juego['editor'] ?></td>
            <td><?= $juego['anio_edicion'] ?></td>
            <td><?= $juego['cantidad_jugadores'] ?></td>
            <td><img src="<?= $juego['foto'] ?>" alt="<?= $juego['nombre_juego'] ?>" width="50"></td>
            <td><?= $juego['edad_minima'] ?></td>
            <td><?= $juego['duracion_minutos'] ?></td>
            <td><?= $juego['nombre_categoria'] ?></td>
            <td>
                <div class="d-flex justify-content-center text-center mt-3 mb-3 ">
                    <form action="editarJuego.php?idJuego=<?= $juego['id_juego'] ?>" method="post" class="d-inline p-1">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-pencil-fill "></i>
                        </button>
                    </form>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="d-inline p-1" onsubmit="return confirmarEliminar()">
                        <input type="hidden" name="eliminar" value="<?php echo $juego['id_juego']; ?>">
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
        <div class="modal fade" id="modalJuego<?= $juego['id_juego'] ?>" tabindex="-1" aria-labelledby="modalJuego<?= $juego['id_juego'] ?>Label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalJuego<?= $juego['id_juego'] ?>Label"><?php echo $juego['nombre_juego']; ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body bg-eurogames-blanco">
                        <img src="<?php echo $juego['foto']; ?>" class="d-block w-50 h-50 mx-auto mb-3" alt="<?php echo $juego['nombre_juego']; ?>">
                        <hr>
                        <p class="text-black mt-5"><strong>Descripción:</strong> <?php echo $juego['descripcion']; ?></p>
                        <hr>
                        <p class="text-black"><strong>Precio:</strong> <?php echo "€". $juego['precio']; ?></p>
                        <p class="text-black"><strong>Editor:</strong> <?php echo $juego['editor']; ?></p>
                        <p class="text-black"><strong>Año de Edición:</strong> <?php echo $juego['anio_edicion']; ?></p>
                        <p class="text-black"><strong>Cantidad de Jugadores:</strong> <?php echo $juego['cantidad_jugadores']; ?></p>
                        <p class="text-black"><strong>Duración:</strong> <?php echo $juego['duracion_minutos']; ?> minutos</p>
                        <p class="text-black"><strong>Edad Mínima:</strong> <?php echo $juego['edad_minima']; ?> años</p>
                    </div>
                    <div class="modal-footer">
                        <form action="editarJuego.php?idJuego=<?= $juego['id_juego'] ?>" method="post" class="d-inline p-1">
                            <button type="submit" class="btn btn-primary">Editar</button>
                        </form>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="d-inline p-1" onsubmit="return confirmarEliminar()">
                            <input type="hidden" name="eliminar" value="<?php echo $juego['id_juego']; ?>">
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</tbody>

            </table>
        </div>
    </div>
    </div>
    <?php require 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="../js/app.js"></script>
</body>

</html>