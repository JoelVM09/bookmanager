<?php

class Titulo
{
    private PDO $db;

    private ?string $id_titulo = null;
    private ?string $titulo    = null;
    private ?string $tipo      = null;
    private ?string $id_pub    = null;
    private ?string $precio    = null;
    private ?string $notas     = null;
    private ?string $fecha_pub = null;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    private function asignar(array $datos): void
    {
        $this->id_titulo = strtoupper(trim($datos['id_titulo'] ?? ''));
        $this->titulo    = trim($datos['titulo'] ?? '');
        $this->tipo      = trim($datos['tipo'] ?? '') ?: 'indefinido';
        $this->id_pub    = $this->nullSiVacio($datos['id_pub'] ?? '');
        $this->precio    = $this->nullSiVacio($datos['precio'] ?? '');
        $this->notas     = $this->nullSiVacio($datos['notas'] ?? '');
        $this->fecha_pub = $this->nullSiVacio($datos['fecha_pub'] ?? '');
    }

    private function nullSiVacio(string $valor): ?string
    {
        $valor = trim($valor);
        return $valor === '' ? null : $valor;
    }

    public function validar(array $datos, bool $esNuevo = true): array
    {
        $errores = [];

        $id     = strtoupper(trim($datos['id_titulo'] ?? ''));
        $titulo = trim($datos['titulo'] ?? '');
        $tipo   = trim($datos['tipo'] ?? '');
        $idPub  = trim($datos['id_pub'] ?? '');
        $precio = trim($datos['precio'] ?? '');
        $fecha  = trim($datos['fecha_pub'] ?? '');

        if ($esNuevo) {
            if ($id === '' || strlen($id) > 6) {
                $errores[] = 'El ID del titulo es obligatorio y puede tener hasta 6 caracteres.';
            } elseif ($this->existe($id)) {
                $errores[] = 'Ya existe un titulo con ese ID.';
            }
        }

        if ($titulo === '') {
            $errores[] = 'El titulo no puede estar vacio.';
        } elseif (mb_strlen($titulo) > 80) {
            $errores[] = 'El titulo no puede superar los 80 caracteres.';
        }

        if (mb_strlen($tipo) > 12) {
            $errores[] = 'El tipo no puede superar los 12 caracteres.';
        }

        if ($idPub !== '' && !$this->editorialExiste($idPub)) {
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

        if ($fecha !== '') {
            $d = DateTime::createFromFormat('Y-m-d', $fecha);
            if (!$d || $d->format('Y-m-d') !== $fecha) {
                $errores[] = 'La fecha de publicacion no es valida.';
            }
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
                    (id_titulo, titulo, tipo, id_pub, precio, notas, fecha_pub)
                VALUES
                    (:id_titulo, :titulo, :tipo, :id_pub, :precio, :notas, :fecha_pub)';

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id_titulo' => $this->id_titulo,
            ':titulo'    => $this->titulo,
            ':tipo'      => $this->tipo,
            ':id_pub'    => $this->id_pub,
            ':precio'    => $this->precio,
            ':notas'     => $this->notas,
            ':fecha_pub' => $this->fecha_pub,
        ]);
    }

    public function update(string $id, array $datos): bool
    {
        $this->asignar($datos);

        $sql = 'UPDATE titulos SET
                    titulo    = :titulo,
                    tipo      = :tipo,
                    id_pub    = :id_pub,
                    precio    = :precio,
                    notas     = :notas,
                    fecha_pub = :fecha_pub
                WHERE id_titulo = :id';

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':titulo'    => $this->titulo,
            ':tipo'      => $this->tipo,
            ':id_pub'    => $this->id_pub,
            ':precio'    => $this->precio,
            ':notas'     => $this->notas,
            ':fecha_pub' => $this->fecha_pub,
            ':id'        => $id,
        ]);
    }

    public function delete(string $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM titulos WHERE id_titulo = :id');
        return $stmt->execute([':id' => $id]);
    }
}