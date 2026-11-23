<?php

$config = require __DIR__ . '/../config/config.local.php';
define('BASE_URL', rtrim($config['base_url'] ?? '', '/'));

require_once __DIR__ . '/../config/database.php';

session_start();

// Escapa texto para mostrarlo de forma segura (evita XSS)
function e(?string $texto): string
{
    return htmlspecialchars($texto ?? '', ENT_QUOTES, 'UTF-8');
}

// Guarda un mensaje para mostrarlo en la siguiente pagina
function setFlash(string $tipo, string $mensaje): void
{
    $_SESSION['flash'] = ['tipo' => $tipo, 'mensaje' => $mensaje];
}

// Muestra y borra el mensaje guardado
function mostrarFlash(): void
{
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        echo '<div class="alert alert-' . e($f['tipo']) . ' alert-dismissible fade show" role="alert">'
           . e($f['mensaje'])
           . '<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>'
           . '</div>';
    }
}