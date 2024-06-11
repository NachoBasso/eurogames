<?php

require_once 'Conexion.php';

class Juego
{
    protected $conexion = null;
    private $idJuego;
    private $nombre;
    private $precio;
    private $descripcion;
    private $stock;
    private $editor;
    private $anioEdicion;
    private $cantidadJugadores;
    private $foto;
    private $edadMinima;
    private $duracion;
    private $idCategoria;

    public function __construct()
    {
        $this->conexion = new Conexion();
    }

    public function getIdJuego()
    {
        return $this->idJuego;
    }

    public function setIdJuego($idJuego)
    {
        $this->idJuego = $idJuego;
    }
    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function getPrecio()
    {
        return $this->precio;
    }

    public function setPrecio($precio)
    {
        $this->precio = $precio;
    }
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }
    public function getStock()
    {
        return $this->stock;
    }

    public function setStock($stock)
    {
        $this->stock = $stock;
    }
    public function getEditor()
    {
        return $this->editor;
    }

    public function setEditor($editor)
    {
        $this->editor = $editor;
    }
    public function getAnioEdicion()
    {
        return $this->anioEdicion;
    }

    public function setAnioEdicion($anioEdicion)
    {
        $this->anioEdicion = $anioEdicion;
    }
    public function getCantidadJugadores()
    {
        return $this->cantidadJugadores;
    }

    public function setCantidadJugadores($cantidadJugadores)
    {
        $this->cantidadJugadores = $cantidadJugadores;
    }
    public function getFoto()
    {
        return $this->foto;
    }

    public function setFoto($foto)
    {
        $this->foto = $foto;
    }
    public function getEdadMinima()
    {
        return $this->edadMinima;
    }

    public function setEdadMinima($edadMinima)
    {
        $this->edadMinima = $edadMinima;
    }

    public function getDuracion()
    {
        return $this->duracion;
    }

    public function setDuracion($duracion)
    {
        $this->duracion = $duracion;
    }

    public function getIdCategoria()
    {
        return $this->idCategoria;
    }

    public function setIdCategoria($idCategoria)
    {
        $this->idCategoria = $idCategoria;
    }


    public function ejecutarConsulta($query)
    {
        try {
            $resultado = $this->conexion->getConBD()->query($query);
            $resultado->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (PDOException $e) {
            die("¡Error al ejecutar la consulta!: " . $e->getMessage() . "<br/>");
        }
    }

    public function listarJuegos()
    {
        try {
            $query = "SELECT  id_juego, nombre_juego, precio, descripcion, stock, editor, anio_edicion, cantidad_jugadores, foto, 
                              edad_minima, duracion_minutos, id_categoria FROM juego";
            $resultado = $this->conexion->getConBD()->query($query);
            $resultado->setFetchMode(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (PDOException $e) {
            die("¡Error al listar juegos!: " . $e->getMessage() . "<br/>");
        }
    }

    public function listarJuegosConStock()
    {
        try {
            $query = "SELECT  id_juego, nombre_juego, precio, descripcion, stock, editor, anio_edicion, cantidad_jugadores, foto, 
                              edad_minima, duracion_minutos, id_categoria FROM juego WHERE stock > 0";
            $resultado = $this->conexion->getConBD()->query($query);
            $resultado->setFetchMode(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (PDOException $e) {
            die("¡Error al listar juegos!: " . $e->getMessage() . "<br/>");
        }
    }

    public function listarJuegosPorCategoria($categoria)
    {
        try {
            $query = "SELECT j.nombre_juego, j.precio, j.descripcion, j.editor, j.anio_edicion, j.cantidad_jugadores, j.foto, 
                            j.duracion_minutos, j.edad_minima, j.id_juego
                      FROM juego j
                      JOIN categoria c ON j.id_categoria = c.id_categoria
                      WHERE c.nombre_categoria LIKE :categoria";

            $stmt = $this->conexion->getConBD()->prepare($query);
            $categoriaParam = '%' . $categoria . '%';
            $stmt->bindParam(':categoria', $categoriaParam, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            return $stmt;
        } catch (PDOException $e) {
            die("¡Error al listar los juegos por categoria!: " . $e->getMessage() . "<br/>");
        }
    }

    public function listarJuegosPorCategoriaConStock($categoria)
    {
        try {
            $query = "SELECT j.nombre_juego, j.precio, j.descripcion, j.editor, j.anio_edicion, j.cantidad_jugadores, j.foto, 
                            j.duracion_minutos, j.edad_minima, j.id_juego, j.stock
                      FROM juego j
                      JOIN categoria c ON j.id_categoria = c.id_categoria
                      WHERE c.nombre_categoria LIKE :categoria AND stock > 0";

            $stmt = $this->conexion->getConBD()->prepare($query);
            $categoriaParam = '%' . $categoria . '%';
            $stmt->bindParam(':categoria', $categoriaParam, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            return $stmt;
        } catch (PDOException $e) {
            die("¡Error al listar los juegos por categoria!: " . $e->getMessage() . "<br/>");
        }
    }


    public function listarJuegosCrud(){
        try {
            $query = "SELECT j.id_juego, j.nombre_juego, j.precio, j.descripcion, j.stock, j.editor, j.anio_edicion, 
            j.cantidad_jugadores, j.foto, j.edad_minima, j.duracion_minutos, c.nombre_categoria 
            FROM juego j
            JOIN categoria c ON j.id_categoria = c.id_categoria";
            $resultado = $this->conexion->getConBD()->query($query);
            $resultado->setFetchMode(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (PDOException $e) {
            die("¡Error al listar los juegos!: " . $e->getMessage() . "<br/>");
        }
    }

    public function borrarJuego($idJuego)
{
    try {
        $datos = array(':idJuego' => $idJuego);
        $resultado = $this->conexion->getConBD()->prepare("DELETE FROM juego WHERE id_juego = :idJuego");
        $resultado->execute($datos);

        if ($resultado->rowCount() == 0) {
            throw new Exception("El id $idJuego es incorrecto. No es posible borrar el juego.");
        } else {
            return "Borrado con éxito";
        }
    } catch (PDOException $e) {
        die("¡Error al borrar el juego!: " . $e->getMessage() . "<br/>");
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

    public function crearJuego($nombre, $precio, $descripcion, $stock, $editor, $anio_edicion, $cantidad_jugadores, $rutaImagen, $edad_minima, $duracion, $id_categoria)
    {
        try {
            $query = "INSERT INTO juego (nombre_juego, precio, descripcion, stock, editor, anio_edicion, cantidad_jugadores, foto, edad_minima, duracion_minutos, id_categoria) 
                    VALUES (:nombre, :precio, :descripcion, :stock, :editor, :anio_edicion, :cantidad_jugadores, :foto, :edad_minima, :duracion_minutos, :id_categoria)";
            $stmt = $this->conexion->getConBD()->prepare($query);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':precio', $precio);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':stock', $stock);
            $stmt->bindParam(':editor', $editor);
            $stmt->bindParam(':anio_edicion', $anio_edicion);
            $stmt->bindParam(':cantidad_jugadores', $cantidad_jugadores);
            $stmt->bindParam(':foto', $rutaImagen);
            $stmt->bindParam(':edad_minima', $edad_minima);
            $stmt->bindParam(':duracion_minutos', $duracion);
            $stmt->bindParam(':id_categoria', $id_categoria);
            $stmt->execute();
        } catch (PDOException $e) {
            die("¡Error al ingresar el juego!: " . $e->getMessage() . "<br/>");
        }
    }

    public function obtenerJuegoPorId($idJuego)
    {
        try {
            $query = "SELECT id_juego, nombre_juego, precio, descripcion, stock, editor, anio_edicion, cantidad_jugadores, foto, edad_minima, duracion_minutos, id_categoria FROM juego WHERE id_juego = :idJuego";
            $stmt = $this->conexion->getConBD()->prepare($query);
            $stmt->bindParam(':idJuego', $idJuego, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Error al obtener el juego por el id: ' . $e->getMessage());
        }
    }

    public function obtenerStockPorId($idJuego)
    {
        try {
            $query = "SELECT stock FROM juego WHERE id_juego = :id_juego";
            $stmt = $this->conexion->getConBD()->prepare($query);
            $stmt->bindParam(':id_juego', $idJuego, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            die('Error al obtener el stock: ' . $e->getMessage());
        }
    }

    public function ordenarJuegos($juegos, $orden)
    {
        switch ($orden) {
            case 'precio_asc':
                usort($juegos, function($juego1, $juego2) {
                    return $juego1['precio'] <=> $juego2['precio'];
                });
                break;
            case 'precio_desc':
                usort($juegos, function($juego1, $juego2) {
                    return $juego2['precio'] <=> $juego1['precio'];
                });
                break;
            case 'nombre_asc':
                usort($juegos, function($juego1, $juego2) {
                    return strcmp($juego1['nombre_juego'], $juego2['nombre_juego']);
                });
                break;
            case 'nombre_desc':
                usort($juegos, function($juego1, $juego2) {
                    return strcmp($juego2['nombre_juego'], $juego1['nombre_juego']);
                });
                break;
            default:
                break;
        }
        return $juegos;
    }

    public function buscarJuego($busqueda, $orden = null)
    {
        try {
            $query = "SELECT j.id_juego, j.nombre_juego, j.stock, j.precio, j.descripcion, j.editor, j.anio_edicion, j.cantidad_jugadores, j.foto, j.duracion_minutos, j.edad_minima, c.nombre_categoria
                      FROM juego j
                      JOIN categoria c ON j.id_categoria = c.id_categoria
                      WHERE j.nombre_juego LIKE :busqueda OR c.nombre_categoria LIKE :busqueda OR j.edad_minima LIKE :busqueda OR j.editor LIKE :busqueda OR j.anio_edicion LIKE :busqueda";
    
            switch ($orden) {
                case 'precio_asc':
                    $query .= " ORDER BY j.precio ASC";
                    break;
                case 'precio_desc':
                    $query .= " ORDER BY j.precio DESC";
                    break;
                case 'nombre_asc':
                    $query .= " ORDER BY j.nombre_juego ASC";
                    break;
                case 'nombre_desc':
                    $query .= " ORDER BY j.nombre_juego DESC";
                    break;
                default:
                    break;
            }
    
            $stmt = $this->conexion->getConBD()->prepare($query);
            $busqueda = "%$busqueda%";
            $stmt->bindParam(':busqueda', $busqueda, PDO::PARAM_STR);
            $stmt->execute();
            $juegos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $juegos = $this->ordenarJuegos($juegos, $orden);

            return $juegos;
        } catch (PDOException $e) {
            die('Error al buscar el juego: ' . $e->getMessage());
        }
    }
    

    public function filtrarPorEditor($editor)
    {
        try {
            $sql = "SELECT nombre_juego, precio, descripcion, editor, anio_edicion, cantidad_jugadores, foto, edad_minima, duracion_minutos FROM juego WHERE editor = :editor";
            $stmt = $this->conexion->getConBD()->prepare($sql);
            $stmt->bindValue(':editor', $editor);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            die('Error al filtrar por editor: ' . $e->getMessage());
        }
    }

    public function filtrarPorPrecio($precio)
    {
        try {
            if ($precio <= 51) {
                $sql = "SELECT nombre_juego, precio, descripcion, editor, anio_edicion, cantidad_jugadores, foto, duracion_minutos, edad_minima FROM juego WHERE precio <= :precio";
            } else {
                $sql = "SELECT nombre_juego, precio, descripcion, editor, anio_edicion, cantidad_jugadores, foto, duracion_minutos, edad_minima FROM juego WHERE precio > 50";
            }
            $stmt = $this->conexion->getConBD()->prepare($sql);
            $stmt->bindValue(':precio', $precio);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            die("¡Error al filtrar por precio!: " . $e->getMessage() . "<br/>");
        }
    }
    

    public function filtrarPorDuracion($duracion)
    {
        try {
            if ($duracion <= 90) {
                $sql = "SELECT nombre_juego, precio, descripcion, editor, anio_edicion, cantidad_jugadores, foto, duracion_minutos, edad_minima FROM juego WHERE duracion_minutos <= :duracion_minutos";
            } else {
                $sql = "SELECT nombre_juego, precio, descripcion, editor, anio_edicion, cantidad_jugadores, foto, duracion_minutos, edad_minima FROM juego WHERE duracion_minutos > 90";
            }
            $stmt = $this->conexion->getConBD()->prepare($sql);
            $stmt->bindValue(':duracion_minutos', $duracion);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            die('Error al filtrar por la duración del juego: ' . $e->getMessage());
        }
    }

    public function filtrarPorEdadMinima($edadMinima)
    {
        
            try {
                if ($edadMinima <= 15) {
                    $sql = "SELECT nombre_juego, precio, descripcion, editor, anio_edicion, cantidad_jugadores, foto, duracion_minutos, edad_minima FROM juego WHERE edad_minima <= :edad_minima";
                } else {
                    $sql = "SELECT nombre_juego, precio, descripcion, editor, anio_edicion, cantidad_jugadores, foto, duracion_minutos, edad_minima FROM juego WHERE edad_minima > 15";
                }            $stmt = $this->conexion->getConBD()->prepare($sql);
            $stmt->bindValue(':edad_minima', $edadMinima);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            die('Error al filtrar por el campo edad mínima: ' . $e->getMessage());
        }
    }

    public function obtenerEditores()
    {
        try {
            $query = "SELECT DISTINCT editor FROM juego";
            $stmt = $this->conexion->getConBD()->query($query);
            $editores = $stmt->fetchAll(PDO::FETCH_COLUMN);
            return $editores;
        } catch (PDOException $e) {
            die("¡Error al obtener los editores!: " . $e->getMessage() . "<br/>");
        }
    }
    

    public function seleccionarJuegosRandom($limit)
    {
        try {
            $query = "SELECT id_juego, nombre_juego, precio, descripcion, stock, editor, anio_edicion, cantidad_jugadores, foto, edad_minima,
             duracion_minutos, id_categoria FROM juego WHERE stock > 0 ORDER BY RAND() LIMIT $limit";

            $stmt = $this->conexion->getConBD()->query($query);
            $juegosRandom = $stmt->fetchAll(PDO::FETCH_ASSOC);          
            return $juegosRandom;
        } catch (PDOException $e) {
            die("¡Error al seleccionar juegos random!: " . $e->getMessage() . "<br/>");
        }
    }

    public function obtenerCategorias()
    {
        try {
            $query = "SELECT id_categoria, nombre_categoria FROM categoria";
            $stmt = $this->conexion->getConBD()->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("¡Error al obtener las categorías!: " . $e->getMessage() . "<br/>");
        }
    }



    public function listarJuegosFiltrados($editor = null, $categoria = null, $orden = null, $edad = null, $duracion = null, $precio = null)
    {
        try {
            $query = "SELECT j.id_juego, j.stock, j.nombre_juego, j.precio, j.descripcion, j.editor, j.anio_edicion, j.cantidad_jugadores, j.foto, j.duracion_minutos, j.edad_minima, c.nombre_categoria
                    FROM juego j
                    JOIN categoria c ON j.id_categoria = c.id_categoria
                   ";
    
            if ($editor !== null) {
                $query .= " AND j.editor = :editor";
            }
            if ($categoria !== null) {
                $query .= " AND c.nombre_categoria LIKE :categoria";
            }
            if ($edad !== null) {
                switch ($edad) {
                    case '6':
                        $query .= " AND j.edad_minima BETWEEN 0 AND 6";
                        break;
                    case '10':
                        $query .= " AND j.edad_minima BETWEEN 6 AND 10";
                        break;
                        case '14':
                            $query .= " AND j.edad_minima BETWEEN 10 AND 14";
                            break;
                    case '15':
                        $query .= " AND j.edad_minima > 14";
                        break;
                    default:
                        break;
                }                
            }
            if ($duracion !== null) {
                switch ($duracion) {
                    case '30':
                        $query .= " AND j.duracion_minutos BETWEEN 0 AND 30";
                        break;
                    case '60':
                        $query .= " AND j.duracion_minutos BETWEEN 30 AND 60";
                        break;
                        case '90':
                            $query .= " AND j.duracion_minutos BETWEEN 60 AND 90";
                            break;
                    case '91':
                        $query .= " AND j.duracion_minutos > 90";
                        break;
                    default:
                        break;
                }
            }
            if ($precio !== null) {
                switch ($precio) {
                    case '20':
                        $query .= " AND j.precio BETWEEN 5 AND 20";
                        break;
                    case '50':
                        $query .= " AND j.precio BETWEEN 20 AND 50";
                        break;
                    case '51':
                        $query .= " AND j.precio > 50";
                        break;
                    default:
                        break;
                }
            }
    
            switch ($orden) {
                case 'precio_asc':
                    $query .= " ORDER BY j.precio ASC";
                    break;
                case 'precio_desc':
                    $query .= " ORDER BY j.precio DESC";
                    break;
                case 'nombre_asc':
                    $query .= " ORDER BY j.nombre_juego ASC";
                    break;
                case 'nombre_desc':
                    $query .= " ORDER BY j.nombre_juego DESC";
                    break;
                default:
                    break;
            }
    
            $stmt = $this->conexion->getConBD()->prepare($query);
    
            if ($editor !== null) {
                $stmt->bindParam(':editor', $editor);
            }
            
            if ($categoria !== null) {
                $categoriaParam = '%' . $categoria . '%';
                $stmt->bindParam(':categoria', $categoriaParam);
            }
           
    
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            return $stmt;
        } catch (PDOException $e) {
            die("¡Error al filtrar los juegos!: " . $e->getMessage() . "<br/>");
        }
    }
    
    
    

    public function editarJuego($nombre, $precio, $descripcion, $stock, $editor, $anioEdicion, $cantidadJugadores, $foto, $edadMinima, $duracion, $idCategoria, $idJuego)
    {
        try {
            $query = "UPDATE juego SET nombre_juego = :nombre, precio = :precio, descripcion = :descripcion, 
                  stock = :stock, editor = :editor, anio_edicion = :anioEdicion, cantidad_jugadores = :cantidadJugadores,
                  foto = :foto, edad_minima = :edadMinima, duracion_minutos = :duracionMinutos, id_categoria = :idCategoria
                  WHERE id_juego = :idJuego";
            $stmt = $this->conexion->getConBD()->prepare($query);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':precio', $precio);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':stock', $stock);
            $stmt->bindParam(':editor', $editor);
            $stmt->bindParam(':anioEdicion', $anioEdicion);
            $stmt->bindParam(':cantidadJugadores', $cantidadJugadores);
            $stmt->bindParam(':foto', $foto);
            $stmt->bindParam(':edadMinima', $edadMinima);
            $stmt->bindParam(':duracionMinutos', $duracion);
            $stmt->bindParam(':idCategoria', $idCategoria);
            $stmt->bindParam(':idJuego', $idJuego);

            return $stmt->execute();
        } catch (PDOException $e) {
            die("¡Error al editar los juegos!: " . $e->getMessage() . "<br/>");
        }
    }

    public function __destruct()
    {
        return $this->conexion = null;
    }
}
