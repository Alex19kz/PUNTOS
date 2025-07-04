<?php
// --- CONFIGURACIÓN DE LA CONEXIÓN ---
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "s-puntos"; // cámbialo si tu base de datos tiene otro nombre

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("❌ Error de conexión: " . $conn->connect_error);
}

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $telefono = $_POST['telefono'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM uvm WHERE telefono = ?");
    $stmt->bind_param("s", $telefono);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $cliente = $resultado->fetch_assoc();

        // Comparación directa sin cifrado
        if ($password === $cliente['contraseña']) {
            session_start();
            $_SESSION['uvm_id'] = $uvm['id'] ?? null;
            $_SESSION['telefono'] = $uvm['telefono'];
            $mensaje = "<p class='exito'>✅ Login exitoso. ¡Bienvenido!</p>";
            header("Location: ../admin/inicio.php");
        } else {
            $mensaje = "<p class='error'>❌ Contraseña incorrecta.</p>";
        }
    } else {
        $mensaje = "<p class='error'>❌ Teléfono no registrado.</p>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login con Teléfono</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background:rgb(159, 200, 241);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background: white;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin: 10px 0 5px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        input[type="submit"] {
            background: #007BFF;
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background: #0056b3;
        }

        .error {
            color: #c0392b;
            text-align: center;
        }

        .exito {
            color: #27ae60;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Iniciar Sesión</h2>

        <?php echo $mensaje; ?>

        <form method="POST">
            <label for="telefono">Número de teléfono:</label>
            <input type="text" id="telefono" name="telefono" required>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="Ingresar">
        </form>
    </div>
</body>
</html>
