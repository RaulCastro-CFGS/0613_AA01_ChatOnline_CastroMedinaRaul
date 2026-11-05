<?php
require_once "../includes/sesion.php";
require_once '../includes/conexion.php';
require_once '../includes/contador_solicitudes.php';


if (($_SESSION['rol'] ?? '') !== 'admin') {
    header("Location: principal.php");         
}
// Prepara la lista de usuarios y el aviso para posibles errores.
$id_usuario = $_SESSION['id_usuario'];
$usuarios = [];
$error = "";



try {
    // Busca solicitudes aceptadas en las que participe el usuario actual.
    // La comparación en ambos sentidos permite encontrar al otro usuario sin importar quién la inició.
    $stmt = $pdo->prepare("SELECT * FROM tbl_usuario WHERE rol != 'admin'");

    
    $stmt->execute();   
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // un mensaje sencillo si no se puede cargar la lista.
    $error = "Error al cargar la lista de usuarios.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios - ChatOnline</title>
    <link rel="stylesheet" href="../css/principal.css">
    <link rel="stylesheet" href="../css/amigos.css">
</head>
<body>

    <!-- Mantiene los enlaces de navegación de la aplicación -->
    <header class="bar">
        <span class="brand">WhatsUp</span>
        <div class="row">
            
            <!-- Saluda al usuario; htmlspecialchars evita interpretar texto como HTML. -->
            <span class="user-greeting">Hola, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
            <a href="amigos.php" class="ghost">Mis Amigos</a>
            <a href="buscar_usuarios.php" class="ghost">Buscar Usuarios</a>
            <a href="solicitudes.php" class="ghost">Ver Solicitudes<?php if ($num_solicitudes > 0) { echo " (" . $num_solicitudes . ")"; } ?></a>
            <a href="../procesos/logout.php" class="ghost">Cerrar sesión</a>
        </div>
        </div>
    </header>

    <!-- Muestra los usuarios encontrados o el estado vacío. -->
    <main class="container-amigos">
        <h2>Lista de Usuarios</h2>

      

        <!-- Informa si la consulta de amigos ha fallado. -->
        <?php if ($error !== "") { ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php } ?>

        <!-- Si hay usuarios, crea una fila con acceso al chat para cada una. -->
        <?php if (empty($usuarios)) { ?>
            <p class="vacio">No se han encontrado usuarios.</p>
        <?php } else { ?>
            <ul class="lista-amigos">
                <?php foreach ($usuarios as $usuario) { ?>
                    <li class="amigo-item">
                        <span><strong><?php echo htmlspecialchars($usuario['username']); ?></strong></span>
                        <div class="amigo-acciones">
                            <a href="../procesos/modificar_usuario.php?id=<?php echo $usuario['id_usuario']; ?>" class="btn-chat">Modificar</a>
                            <form action="../procesos/eliminar_usuario.php" method="POST">
                                <input type="hidden" name="id_amigo" value="<?php echo $usuario['id_usuario']; ?>">
                                <button type="submit" class="btn-eliminar">Eliminar</button>
                            </form>
                        </div>
                    </li>
                <?php } ?>
            </ul>
        <?php } ?>
    </main>

</body>
</html>