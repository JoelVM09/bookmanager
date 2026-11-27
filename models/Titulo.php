<?php

class Titulo
{
    private PDO $db;

    private ?string $id_titulo    = null;
    private ?string $titulo       = null;
    private ?string $tipo         = null;
    private ?string $id_pub       = null;
    private ?string $precio       = null;
    private ?string $avance       = null;
    private ?int    $total_ventas = null;
    private ?string $notas        = null;
    private ?string $fecha_pub    = null;
    private ?string $contrato     = null;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // Tipos que usa la base de datos
    public static function getTipos(): array
    {
        return ['business', 'mod_cook', 'popular_comp', 'psychology', 'trad_cook', 'UNDECIDED'];
    }

    private function nullSiVacio(string $valor): ?string
    {
        $valor = trim($valor);
        return $valor === '' ? null : $valor;
    }

    // Limpia los datos y los guarda en las propiedades privadas.
    // precio, avance y total_ventas aceptan NULL; notas no (se guarda '').
    private function asignar(array $datos): void
    {
        $totalVentas = $this->nullSiVacio($datos['total_ventas'] ?? '');

        $this->id_titulo    = strtoupper(trim($datos['id_titulo'] ?? ''));
        $this->titulo       = trim($datos['titulo'] ?? '');
        $this->tipo         = trim($datos['tipo'] ?? '');
        $this->id_pub       = trim($datos['id_pub'] ?? '');
        $this->precio       = $this->nullSiVacio($datos['precio'] ?? '');
        $this->avance       = $this->nullSiVacio($datos['avance'] ?? '');
        $this->total_ventas = $totalVentas === null ? null : (int) $totalVentas;
        $this->notas        = trim($datos['notas'] ?? '');
        $this->fecha_pub    = trim($datos['fecha_pub'] ?? '') . ' 00:00:00';
        $this->contrato     = trim($datos['contrato'] ?? '');
    }

    public function validar(array $datos, bool $esNuevo = true): array
    {
        $errores = [];

        $id     = strtoupper(trim($datos['id_titulo'] ?? ''));
        $titulo = trim($datos['titulo'] ?? '');
        $tipo   = trim($datos['tipo'] ?? '');
        $idPub  = trim($datos['id_pub'] ?? '');
        $precio = trim($datos['precio'] ?? '');
        $avance = trim($datos['avance'] ?? '');
        $ventas = trim($datos['total_ventas'] ?? '');
        $notas  = trim($datos['notas'] ?? '');
        $fecha  = trim($datos['fecha_pub'] ?? '');
        $contr  = trim($datos['contrato'] ?? '');

        if ($esNuevo) {
            if ($id === '' || strlen($id) > 6) {
                $errores[] = 'El ID del titulo es obligatorio y puede tener hasta 6 caracteres.';
            } elseif ($this->existe($id)) {
                $errores[] = 'Ya existe un titulo con ese ID.';
            }
        }

        if ($titulo === '') {
            $errores[] = 'El titulo no puede estar vacio.';
        } elseif (mb_strlen($titulo) > 60) {
            $errores[] = 'El titulo no puede superar los 60 caracteres.';
        }

        if (!in_array($tipo, self::getTipos(), true)) {
            $errores[] = 'Debes seleccionar un tipo valido.';
        }

        if ($idPub === '') {
            $errores[] = 'Debes seleccionar una editorial.';
        } elseif (!$this->editorialExiste($idPub)) {
            $errores[] = 'La editorial seleccionada no es valida.';
        }

        if ($precio !== '') {
            if (!is_numeric($precio)) {
                $errores[] = 'El precio debe ser un numero.';
            } elseif ((float) $precio < 0) {
                $errores[] = 'El precio no puede ser negativo.';
            } elseif ((float) $precio > 999999.99) {
                $errores[] = 'El precio es demasiado alto.';
            }
        }

        if ($avance !== '') {
            if (!is_numeric($avance)) {
                $errores[] = 'El avance debe ser un numero.';
            } elseif ((float) $avance < 0) {
                $errores[] = 'El avance no puede ser negativo.';
            }
        }

        if ($ventas !== '') {
            if (!ctype_digit($ventas)) {
                $errores[] = 'El total de ventas debe ser un numero entero no negativo.';
            } elseif ((int) $ventas > 2000000000) {
                $errores[] = 'El total de ventas es demasiado alto.';
            }
        }

        if (mb_strlen($notas) > 255) {
            $errores[] = 'Las notas no pueden superar los 255 caracteres.';
        }

        if ($fecha === '') {
            $errores[] = 'La fecha de publicacion es obligatoria.';
        } else {
            $d = DateTime::createFromFormat('Y-m-d', $fecha);
            if (!$d || $d->format('Y-m-d') !== $fecha) {
                $errores[] = 'La fecha de publicacion no es valida.';
            }
        }

        if (!in_array($contr, ['0', '1'], true)) {
            $errores[] = 'Debes indicar si el titulo tiene contrato.';
        }

        return $errores;
    }

    public function existe(string $id): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM titulos WHERE id_titulo = :id');
        $stmt->execute([':id' => $id]);
        return (int) $stmt->fetchColumn() > 0;
    }

    private function editorialExiste(string $idPub): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM publicadores WHERE id_pub = :id');
        $stmt->execute([':id' => $idPub]);
        return (int) $stmt->fetchColumn() > 0;
    }

    // La base no tiene llaves foraneas: se revisa a mano si el titulo
    // tiene ventas (detalle_venta) o autores (titulo_autor) asociados.
    public function tieneRelaciones(string $id): bool
    {
        $stmt = $this->db->prepare(
            'SELECT (SELECT COUNT(*) FROM detalle_venta WHERE id_titulo = :id1)
                  + (SELECT COUNT(*) FROM titulo_autor  WHERE id_titulo = :id2)'
        );
        $stmt->execute([':id1' => $id, ':id2' => $id]);
        return (int) $stmt->fetchColumn() > 0;
    }

    // Lista los titulos junto al nombre de su editorial
    public function getAll(): array
    {
        $sql = 'SELECT t.*, p.nombre_pub
                FROM titulos t
                LEFT JOIN publicadores p ON p.id_pub = t.id_pub
                ORDER BY t.titulo';
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById(string $id): array|false
    {
        $stmt = $this->db->prepare('SELECT * FROM titulos WHERE id_titulo = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Alimenta el <select> de editoriales
    public function getPublicadores(): array
    {
        $stmt = $this->db->prepare('SELECT id_pub, nombre_pub FROM publicadores ORDER BY nombre_pub');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function count(): int
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM titulos');
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function create(array $datos): bool
    {
        $this->asignar($datos);

        $sql = 'INSERT INTO titulos
                    (id_titulo, titulo, tipo, id_pub, precio, avance, total_ventas, notas, fecha_pub, contrato)
                VALUES
                    (:id_titulo, :titulo, :tipo, :id_pub, :precio, :avance, :total_ventas, :notas, :fecha_pub, :contrato)';

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id_titulo'    => $this->id_titulo,
            ':titulo'       => $this->titulo,
            ':tipo'         => $this->tipo,
            ':id_pub'       => $this->id_pub,
            ':precio'       => $this->precio,
            ':avance'       => $this->avance,
            ':total_ventas' => $this->total_ventas,
            ':notas'        => $this->notas,
            ':fecha_pub'    => $this->fecha_pub,
            ':contrato'     => $this->contrato,
        ]);
    }

    public function update(string $id, array $datos): bool
    {
        $this->asignar($datos);

        $sql = 'UPDATE titulos SET
                    titulo       = :titulo,
                    tipo         = :tipo,
                    id_pub       = :id_pub,
                    precio       = :precio,
                    avance       = :avance,
                    total_ventas = :total_ventas,
                    notas        = :notas,
                    fecha_pub    = :fecha_pub,
                    contrato     = :contrato
                WHERE id_titulo = :id';

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':titulo'       => $this->titulo,
            ':tipo'         => $this->tipo,
            ':id_pub'       => $this->id_pub,
            ':precio'       => $this->precio,
            ':avance'       => $this->avance,
            ':total_ventas' => $this->total_ventas,
            ':notas'        => $this->notas,
            ':fecha_pub'    => $this->fecha_pub,
            ':contrato'     => $this->contrato,
            ':id'           => $id,
        ]);
    }

    public function delete(string $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM titulos WHERE id_titulo = :id');
        return $stmt->execute([':id' => $id]);
    }
}