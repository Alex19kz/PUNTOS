<?php

$conn = new mysqli("localhost", "root", "", "s-puntos");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Alta
if (isset($_POST['agregar'])) {
    $stmt = $conn->prepare("INSERT INTO premios (nombre, puntos, imagen) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $_POST['nombre'], $_POST['puntos'], $_POST['imagen']);
    $stmt->execute();
    $stmt->close();
}

// Baja
if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    $conn->query("DELETE FROM premios WHERE id = $id");
}

// Actualizar
if (isset($_POST['editar'])) {
    $stmt = $conn->prepare("UPDATE premios SET nombre=?, puntos=?, imagen=? WHERE id=?");
    $stmt->bind_param("sisi", $_POST['nombre'], $_POST['puntos'], $_POST['imagen'], $_POST['id']);
    $stmt->execute();
    $stmt->close();
}

// Obtener lista de premios
$result = $conn->query("SELECT * FROM premios");
$premios = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Premios</title>
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
            max-width: 900px;
            margin: auto;
        }

        form, table {
            margin-bottom: 30px;
            width: 100%;
        }

        input {
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
            margin-bottom: 10px;
        }

        img {
            max-height: 60px;
            max-width: 100px;
        }
    </style>
</head>
<body>

<h1>Administrar Premios</h1>

<div class="contenedor">

    <!-- Formulario Alta -->
    <h2>Agregar Premio</h2>
    <form method="post">
        <input type="text" name="nombre" placeholder="Nombre del premio" required>
        <input type="number" name="puntos" placeholder="Puntos requeridos" required>
        <input type="text" name="imagen" placeholder="URL de la imagen" required>
        <button class="btn" type="submit" name="agregar">Agregar</button>
    </form>

    <!-- Tabla Premios -->
    <h2>Lista de Premios</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Puntos</th>
            <th>Imagen</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($premios as $premio): ?>
            <tr>
                <td><?= $premio['id'] ?></td>
                <td><?= htmlspecialchars($premio['nombre']) ?></td>
                <td><?= $premio['puntos'] ?></td>
                <td><img src="<?= htmlspecialchars($premio['imagen']) ?>" alt="premio"></td>
                <td class="acciones">
                    <!-- Editar -->
                    <form method="post" class="editar-form">
                        <input type="hidden" name="id" value="<?= $premio['id'] ?>">
                        <input type="text" name="nombre" value="<?= htmlspecialchars($premio['nombre']) ?>" required>
                        <input type="number" name="puntos" value="<?= $premio['puntos'] ?>" required>
                        <input type="text" name="imagen" value="<?= htmlspecialchars($premio['imagen']) ?>" required>
                        <button class="btn" type="submit" name="editar">Actualizar</button>
                    </form>
                    <!-- Eliminar -->
                    <form method="get" onsubmit="return confirm('¿Eliminar este premio?')">
                        <input type="hidden" name="eliminar" value="<?= $premio['id'] ?>">
                        <button class="btn" type="submit">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

</body>
</html>
