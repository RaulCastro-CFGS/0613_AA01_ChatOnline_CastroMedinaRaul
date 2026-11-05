<?php
session_start();
if (($_SESSION['rol'] ?? '') !== 'admin') {
    header("Location: principal.php");         
}
// Solo se acepta el envío del formulario por un usuario con sesión
if (!isset($_SESSION['id_usuario']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../view/login.php");
    exit();
}

require_once '../includes/conexion.php';


$id_usuario = $_SESSION['id_usuario'];
$id_usuario = 0;

if (isset($_POST['id_usuario']) && is_numeric($_POST['id_usuario'])) {
    $id_usuario = (int)$_POST['id_usuario'];
}

if ($id_usuario > 0) {
    try {
        // borra solo el usuario escogido
        $stmt = $pdo->prepare("DELETE FROM tbl_usuario WHERE id_usuario = ?");
       
        $stmt->execute();
        
        $_SESSION['mensaje_usuario'] = "usuario eliminado.";
    } catch (PDOException $e) {
        $_SESSION['mensaje_usuario'] = "Error al eliminar al usuario.";
        
    }
}

header("Location: ../view/panel_admin.php");
exit();
