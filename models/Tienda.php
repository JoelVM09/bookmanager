<?php

class Tienda
{
    private PDO $db;

    private ?string $id_tienda     = null;
    private ?string $nombre_tienda = null;
    private ?string $direcc_tienda = null;
    private ?string $ciudad        = null;
    private ?string $estado        = null;
    private ?string $pais          = null;
    private ?string $cod_postal    = null;
    private ?string $terminos      = null;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // Opciones validas para el campo terminos
    public static function getTerminos(): array
    {
        return ['Net 30', 'Net 60'];
    }

    // Limpia los datos recibidos y los guarda en las propiedades privadas.
    // La base exige NOT NULL, por eso los campos vacios se guardan como ''.
    private function asignar(array $datos): void
    {
        $this->id_tienda     = strtoupper(trim($datos['id_tienda'] ?? ''));
        $this->nombre_tienda = trim($datos['nombre_tienda'] ?? '');
        $this->direcc_tienda = trim($datos['direcc_tienda'] ?? '');
        $this->ciudad        = trim($datos['ciudad'] ?? '');
        $this->estado        = strtoupper(trim($datos['estado'] ?? ''));
        $this->pais          = strtoupper(trim($datos['pais'] ?? ''));
        $this->cod_postal    = trim($datos['cod_postal'] ?? '');
        $this->terminos      = trim($datos['terminos'] ?? '');
    }

    // Devuelve un arreglo con los errores encontrados (vacio si todo esta bien)
    public function validar(array $datos, bool $esNuevo = true): array
    {
        $errores = [];

        $id        = strtoupper(trim($datos['id_tienda'] ?? ''));
        $nombre    = trim($datos['nombre_tienda'] ?? '');
        $direccion = trim($datos['direcc_tienda'] ?? '');
        $ciudad    = trim($datos['ciudad'] ?? '');
        $estado    = trim($datos['estado'] ?? '');
        $pais      = trim($datos['pais'] ?? '');
        $cp        = trim($datos['cod_postal'] ?? '');
        $terminos  = trim($datos['terminos'] ?? '');

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

        if (mb_strlen($direccion) > 40) {
            $errores[] = 'La direccion no puede superar los 40 caracteres.';
        }

        if (mb_strlen($ciudad) > 15) {
            $errores[] = 'La ciudad no puede superar los 15 caracteres.';
        }

        if ($estado !== '' && !preg_match('/^[A-Za-z]{2}$/', $estado)) {
            $errores[] = 'El estado debe tener exactamente 2 letras (ej. CA).';
        }

        if ($pais !== '' && !preg_match('/^[A-Za-z]{3}$/', $pais)) {
            $errores[] = 'El pais debe tener exactamente 3 letras (ej. USA).';
        }

        if ($cp !== '' && !preg_match('/^\d{5}$/', $cp)) {
            $errores[] = 'El codigo postal debe tener 5 digitos.';
        }

        if (!in_array($terminos, self::getTerminos(), true)) {
            $errores[] = 'Debes seleccionar unos terminos validos.';
        }

        return $errores;
    }

    public function existe(string $id): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM tiendas WHERE id_tienda = :id');
        $stmt->execute([':id' => $id]);
        return (int) $stmt->fetchColumn() > 0;
    }

    // La base no tiene llaves foraneas, asi que verificamos a mano
    // si la tienda ya tiene ventas registradas antes de permitir borrarla.
    public function tieneVentas(string $id): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM ventas WHERE id_tienda = :id');
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
                    (id_tienda, nombre_tienda, direcc_tienda, ciudad, estado, pais, cod_postal, terminos)
                VALUES
                    (:id_tienda, :nombre_tienda, :direcc_tienda, :ciudad, :estado, :pais, :cod_postal, :terminos)';

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id_tienda'     => $this->id_tienda,
            ':nombre_tienda' => $this->nombre_tienda,
            ':direcc_tienda' => $this->direcc_tienda,
            ':ciudad'        => $this->ciudad,
            ':estado'        => $this->estado,
            ':pais'          => $this->pais,
            ':cod_postal'    => $this->cod_postal,
            ':terminos'      => $this->terminos,
        ]);
    }

    public function update(string $id, array $datos): bool
    {
        $this->asignar($datos);

        $sql = 'UPDATE tiendas SET
                    nombre_tienda = :nombre_tienda,
                    direcc_tienda = :direcc_tienda,
                    ciudad        = :ciudad,
                    estado        = :estado,
                    pais          = :pais,
                    cod_postal    = :cod_postal,
                    terminos      = :terminos
                WHERE id_tienda = :id';

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nombre_tienda' => $this->nombre_tienda,
            ':direcc_tienda' => $this->direcc_tienda,
            ':ciudad'        => $this->ciudad,
            ':estado'        => $this->estado,
            ':pais'          => $this->pais,
            ':cod_postal'    => $this->cod_postal,
            ':terminos'      => $this->terminos,
            ':id'            => $id,
        ]);
    }

    public function delete(string $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM tiendas WHERE id_tienda = :id');
        return $stmt->execute([':id' => $id]);
    }
}