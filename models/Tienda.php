<?php

class Tienda
{
    private PDO $db;

    private ?string $id_tienda     = null;
    private ?string $nombre_tienda = null;
    private ?string $direccion     = null;
    private ?string $ciudad        = null;
    private ?string $provincia     = null;
    private ?string $codigo_postal = null;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // Limpia los datos recibidos y los guarda en las propiedades privadas
    private function asignar(array $datos): void
    {
        $this->id_tienda     = strtoupper(trim($datos['id_tienda'] ?? ''));
        $this->nombre_tienda = trim($datos['nombre_tienda'] ?? '');
        $this->direccion     = $this->nullSiVacio($datos['direccion'] ?? '');
        $this->ciudad        = $this->nullSiVacio($datos['ciudad'] ?? '');
        $this->provincia     = $this->nullSiVacio($datos['provincia'] ?? '');
        $this->codigo_postal = $this->nullSiVacio($datos['codigo_postal'] ?? '');
    }

    private function nullSiVacio(string $valor): ?string
    {
        $valor = trim($valor);
        return $valor === '' ? null : $valor;
    }

    // Devuelve un arreglo con los errores encontrados (vacio si todo esta bien)
    public function validar(array $datos, bool $esNuevo = true): array
    {
        $errores = [];

        $id     = strtoupper(trim($datos['id_tienda'] ?? ''));
        $nombre = trim($datos['nombre_tienda'] ?? '');
        $cp     = trim($datos['codigo_postal'] ?? '');

        if ($esNuevo) {
            if ($id === '' || strlen($id) !== 4) {
                $errores[] = 'El ID de la tienda es obligatorio y debe tener exactamente 4 caracteres.';
            } elseif ($this->existe($id)) {
                $errores[] = 'Ya existe una tienda con ese ID.';
            }
        }

        if ($nombre === '') {
            $errores[] = 'El nombre de la tienda no puede estar vacio.';
        } elseif (mb_strlen($nombre) > 40) {
            $errores[] = 'El nombre de la tienda no puede superar los 40 caracteres.';
        }

        if ($cp !== '' && !preg_match('/^\d{5}$/', $cp)) {
            $errores[] = 'El codigo postal debe tener 5 digitos.';
        }

        return $errores;
    }

    public function existe(string $id): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM tiendas WHERE id_tienda = :id');
        $stmt->execute([':id' => $id]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function getAll(): array
    {
        $stmt = $this->db->prepare('SELECT * FROM tiendas ORDER BY nombre_tienda');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById(string $id): array|false
    {
        $stmt = $this->db->prepare('SELECT * FROM tiendas WHERE id_tienda = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function create(array $datos): bool
    {
        $this->asignar($datos);

        $sql = 'INSERT INTO tiendas
                    (id_tienda, nombre_tienda, direccion, ciudad, provincia, codigo_postal)
                VALUES
                    (:id_tienda, :nombre_tienda, :direccion, :ciudad, :provincia, :codigo_postal)';

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id_tienda'     => $this->id_tienda,
            ':nombre_tienda' => $this->nombre_tienda,
            ':direccion'     => $this->direccion,
            ':ciudad'        => $this->ciudad,
            ':provincia'     => $this->provincia,
            ':codigo_postal' => $this->codigo_postal,
        ]);
    }

    public function update(string $id, array $datos): bool
    {
        $this->asignar($datos);

        $sql = 'UPDATE tiendas SET
                    nombre_tienda = :nombre_tienda,
                    direccion     = :direccion,
                    ciudad        = :ciudad,
                    provincia     = :provincia,
                    codigo_postal = :codigo_postal
                WHERE id_tienda = :id';

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nombre_tienda' => $this->nombre_tienda,
            ':direccion'     => $this->direccion,
            ':ciudad'        => $this->ciudad,
            ':provincia'     => $this->provincia,
            ':codigo_postal' => $this->codigo_postal,
            ':id'            => $id,
        ]);
    }

    public function delete(string $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM tiendas WHERE id_tienda = :id');
        return $stmt->execute([':id' => $id]);
    }
}