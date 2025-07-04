<?php
session_start();

if (!isset($_SESSION['telefono'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "s-puntos");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$telefono = $_SESSION['telefono'];

// Obtener datos del cliente
$stmt = $conn->prepare("SELECT * FROM cliente WHERE telefono = ?");
$stmt->bind_param("s", $telefono);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 1) {
    $cliente = $res->fetch_assoc();
    $puntos = $cliente['puntos'];
} else {
    $puntos = 0;
}

// Procesar canje de premio
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['canjear_premio'])) {
    $premio_id = (int)$_POST['premio_id'];
    $premio_query = $conn->prepare("SELECT * FROM Premios WHERE id = ?");
    $premio_query->bind_param("i", $premio_id);
    $premio_query->execute();
    $resultado = $premio_query->get_result();

    if ($resultado->num_rows === 1) {
        $premio = $resultado->fetch_assoc();
        $nombre = $premio['nombre'];
        $costo = $premio['puntos'];

        if ($puntos >= $costo) {
            $nuevo = $puntos - $costo;
            $update = $conn->prepare("UPDATE cliente SET puntos = ? WHERE telefono = ?");
            $update->bind_param("is", $nuevo, $telefono);
            $update->execute();
            $puntos = $nuevo;
            $mensaje = "¡Canjeaste el premio '$nombre' con éxito!";
        } else {
            $mensaje = "No tienes suficientes puntos para canjear el premio '$nombre'.";
        }
    }
}

// Procesar canje de beneficio
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['canjear_beneficio'])) {
    $beneficio_id = (int)$_POST['beneficio_id'];
    $benef_query = $conn->prepare("SELECT * FROM beneficios WHERE id = ?");
    $benef_query->bind_param("i", $beneficio_id);
    $benef_query->execute();
    $benef_res = $benef_query->get_result();

    if ($benef_res->num_rows === 1) {
        $beneficio = $benef_res->fetch_assoc();
        $nombre = $beneficio['nombre'];
        $costo = $beneficio['puntos'];

        if ($puntos >= $costo) {
            $nuevo = $puntos - $costo;
            $update = $conn->prepare("UPDATE cliente SET puntos = ? WHERE telefono = ?");
            $update->bind_param("is", $nuevo, $telefono);
            $update->execute();
            $puntos = $nuevo;
            $mensaje = "¡Canjeaste el beneficio '$nombre' con éxito!";
        } else {
            $mensaje = "No tienes suficientes puntos para canjear el beneficio '$nombre'.";
        }
    }
}

// Cargar premios y beneficios
$premios = $conn->query("SELECT * FROM Premios")->fetch_all(MYSQLI_ASSOC);
$beneficios = $conn->query("SELECT * FROM beneficios")->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi cuenta - Megacard</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            background-color: #111;
            color: white;
        }

        .header {
            background-color: #ffb320;
            padding: 20px;
            text-align: center;
            border-bottom-left-radius: 20px;
            border-bottom-right-radius: 20px;
            color: black;
        }

        .tarjeta-visual {
            background-color: #2b2b2b;
            border-radius: 10px;
            padding: 20px;
            margin: 20px auto;
            max-width: 350px;
            text-align: center;
            background-image: url('https://st2.depositphotos.com/41691762/46291/i/450/depositphotos_462915356-stock-photo-carbon-fiber-texture-wallpapers-background.jpg');
            background-size: cover;
            height: 180px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            color: white;
        }

        .puntos {
            text-align: center;
            margin: 10px 0 30px 0;
        }

        .puntos .valor {
            font-size: 30px;
            color: #ffb320;
            font-weight: bold;
        }

        .mensaje {
            text-align: center;
            margin: 10px;
            font-weight: bold;
            color: #00ffcc;
        }

        .section {
            padding: 10px 20px;
        }

        .section h2 {
            color: #ffb320;
            margin-bottom: 10px;
        }

        .items {
            display: flex;
            gap: 10px;
            overflow-x: auto;
        }

        .card {
            background: white;
            color: black;
            border-radius: 10px;
            padding: 10px;
            min-width: 200px;
            text-align: center;
            flex-shrink: 0;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .card img {
            width: 100px;
            height: auto;
        }

        .card h3 {
            font-size: 14px;
            margin: 10px 0 5px 0;
        }

        .card .pts {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .card form button {
            background-color: #ffb320;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .card form button:hover {
            background-color: #e09e00;
        }
    </style>
    <script>
        function confirmarCanje(nombre) {
            return confirm("¿Estás seguro de que quieres canjear: " + nombre + "?");
        }
    </script>
</head>
<body>

    <div class="header">
        <h2>Mi cuenta</h2>
        <div>Tel: <?= htmlspecialchars($telefono) ?></div>
    </div>

    <div class="tarjeta-visual">
        <h3>MEGACARD CLUB</h3>
    </div>

    <div class="puntos">
        <div class="titulo">Puntos bonificados</div>
        <div class="valor"><?= $puntos ?></div>
    </div>

    <?php if (isset($mensaje)): ?>
        <div class="mensaje"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <!-- Premios -->
    <div class="section">
        <h2>Premios disponibles</h2>
        <div class="items">
            <?php foreach ($premios as $item): ?>
                <div class="card">
                    <img src="<?= htmlspecialchars($item['imagen']) ?>" alt="<?= htmlspecialchars($item['nombre']) ?>">
                    <h3><?= htmlspecialchars($item['nombre']) ?></h3>
                    <div class="pts"><?= $item['puntos'] ?> pts</div>
                    <form method="post" onsubmit="return confirmarCanje('<?= htmlspecialchars($item['nombre']) ?>')">
                        <input type="hidden" name="premio_id" value="<?= $item['id'] ?>">
                        <button type="submit" name="canjear_premio">Canjear</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Beneficios -->
    <div class="section">
        <h2>Beneficios disponibles</h2>
        <div class="items">
            <?php foreach ($beneficios as $item): ?>
                <div class="card">
                    <img src="<?= htmlspecialchars($item['imagen']) ?>" alt="<?= htmlspecialchars($item['nombre']) ?>">
                    <h3><?= htmlspecialchars($item['nombre']) ?></h3>
                    <div class="pts"><?= $item['puntos'] ?> pts</div>
                    <form method="post" onsubmit="return confirmarCanje('<?= htmlspecialchars($item['nombre']) ?>')">
                        <input type="hidden" name="beneficio_id" value="<?= $item['id'] ?>">
                        <button type="submit" name="canjear_beneficio">Canjear</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</body>
</html>
