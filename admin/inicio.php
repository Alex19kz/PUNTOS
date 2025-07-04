
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
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
            color: black;
            border-bottom-left-radius: 20px;
            border-bottom-right-radius: 20px;
        }

        .contenedor {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
        }

        .card-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .card {
            background-color: #1f1f1f;
            border: 1px solid #333;
            border-radius: 10px;
            width: 220px;
            text-align: center;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.4);
            transition: transform 0.2s ease;
        }

        .card:hover {
            transform: scale(1.03);
        }

        .card h3 {
            margin: 10px 0;
            color: #ffb320;
        }

        .card a {
            display: inline-block;
            margin-top: 10px;
            background-color: #ffb320;
            color: black;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }

        .card a:hover {
            background-color: #e0a800;
        }

        .footer {
            text-align: center;
            margin-top: 50px;
            font-size: 14px;
            color: #aaa;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Panel de Administración</h1>
        <p>Bienvenido, 👋</p>
    </div>

    <div class="contenedor">
        <div class="card-grid">
            <div class="card">
                <h3>Clientes</h3>
                <p>Gestiona todos los clientes registrados.</p>
                <a href="clientes.php">Administrar</a>
            </div>

            <div class="card">
                <h3>Premios</h3>
                <p>Alta, baja y edición de premios disponibles.</p>
                <a href="premios.php">Administrar</a>
            </div>

            <div class="card">
                <h3>Beneficios</h3>
                <p>Control total sobre los beneficios del sistema.</p>
                <a href="beneficios.php">Administrar</a>
            </div>
        </div>
    </div>

    

</body>
</html>
