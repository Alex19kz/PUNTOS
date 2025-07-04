<?php

$conn = new mysqli("localhost", "root", "", "s-puntos");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Alta
if (isset($_POST['agregar'])) {
    $stmt = $conn->prepare("INSERT INTO cliente (telefono, nombre, apellidos, direccion, correo, estado, ciudad, puntos, contraseña) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssis", $_POST['telefono'], $_POST['nombre'], $_POST['apellidos'], $_POST['direccion'], $_POST['correo'], $_POST['estado'], $_POST['ciudad'], $_POST['puntos'], $_POST['contraseña']);
    $stmt->execute();
    $stmt->close();
}

// Baja
if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    $conn->query("DELETE FROM cliente WHERE id = $id");
}

// Actualizar
if (isset($_POST['editar'])) {
    $stmt = $conn->prepare("UPDATE cliente SET telefono=?, nombre=?, apellidos=?, direccion=?, correo=?, estado=?, ciudad=?, puntos=?, contraseña=? WHERE id=?");
    $stmt->bind_param("sssssssisi", $_POST['telefono'], $_POST['nombre'], $_POST['apellido'], $_POST['direccion'], $_POST['correo'], $_POST['estado'], $_POST['ciudad'], $_POST['puntos'], $_POST['contraseña'], $_POST['id']);
    $stmt->execute();
    $stmt->close();
}

// Obtener lista de clientes
$result = $conn->query("SELECT * FROM cliente");
$clientes = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Clientes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #111;
            color: white;
            margin: 0;
        }

        h1 {
            text-align: center;
            background-color: #ffb320;
            color: black;
            padding: 20px;
            margin: 0;
        }

        .contenedor {
            padding: 20px;
            max-width: 1000px;
            margin: auto;
        }

        form, table {
            margin-bottom: 30px;
            width: 100%;
        }

        input, select {
            padding: 6px;
            margin: 4px;
            width: calc(100% - 12px);
        }

        table {
            border-collapse: collapse;
            width: 100%;
            background-color: #222;
        }

        th, td {
            padding: 8px;
            border: 1px solid #444;
            text-align: center;
        }

        th {
            background-color: #333;
        }

        .acciones form {
            display: inline-block;
        }

        .btn {
            padding: 6px 12px;
            border: none;
            background-color: #ffb320;
            color: black;
            cursor: pointer;
            border-radius: 4px;
        }

        .btn:hover {
            background-color: #e0a200;
        }

        .editar-form {
            background-color: #1a1a1a;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<h1>Administrar Clientes</h1>

<div class="contenedor">

    <!-- Formulario Alta -->
    <h2>Agregar Cliente</h2>
    <form method="post">
        <input type="text" name="telefono" placeholder="Teléfono" required>
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="text" name="apellidos" placeholder="Apellido" required>
        <input type="text" name="direccion" placeholder="Dirección">
        <input type="email" name="correo" placeholder="Correo">
        <input type="text" name="estado" placeholder="Estado">
        <input type="text" name="ciudad" placeholder="Ciudad">
        <input type="number" name="puntos" placeholder="Puntos" value="0">
        <input type="text" name="contraseña" placeholder="Contraseña" required>
        <button class="btn" type="submit" name="agregar">Agregar</button>
    </form>

    <!-- Tabla Clientes -->
    <h2>Lista de Clientes</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Teléfono</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Correo</th>
            <th>Puntos</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($clientes as $cliente): ?>
            <tr>
                <td><?= $cliente['id'] ?></td>
                <td><?= htmlspecialchars($cliente['telefono']) ?></td>
                <td><?= htmlspecialchars($cliente['nombre']) ?></td>
                <td><?= htmlspecialchars($cliente['apellidos']) ?></td>
                <td><?= htmlspecialchars($cliente['correo']) ?></td>
                <td><?= $cliente['puntos'] ?></td>
                <td class="acciones">
                    <!-- Editar -->
                    <form method="post" class="editar-form">
                        <input type="hidden" name="id" value="<?= $cliente['id'] ?>">
                        <input type="text" name="telefono" value="<?= htmlspecialchars($cliente['telefono']) ?>" required>
                        <input type="text" name="nombre" value="<?= htmlspecialchars($cliente['nombre']) ?>" required>
                        <input type="text" name="apellido" value="<?= htmlspecialchars($cliente['apellidos']) ?>" required>
                        <input type="text" name="direccion" value="<?= htmlspecialchars($cliente['direccion']) ?>">
                        <input type="email" name="correo" value="<?= htmlspecialchars($cliente['correo']) ?>">
                        <input type="text" name="estado" value="<?= htmlspecialchars($cliente['estado']) ?>">
                        <input type="text" name="ciudad" value="<?= htmlspecialchars($cliente['ciudad']) ?>">
                        <input type="number" name="puntos" value="<?= $cliente['puntos'] ?>">
                        <input type="text" name="contraseña" value="<?= htmlspecialchars($cliente['contraseña']) ?>" required>
                        <button class="btn" type="submit" name="editar">Actualizar</button>
                    </form>
                    <!-- Eliminar -->
                    <form method="get" onsubmit="return confirm('¿Eliminar este cliente?')">
                        <input type="hidden" name="eliminar" value="<?= $cliente['id'] ?>">
                        <button class="btn" type="submit">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

</body>
</html>
