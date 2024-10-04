<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Personas por Tipo de Impuesto</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Incluir la barra de navegación -->
    <?php include 'navbar.php'; ?>

    <section class="container mt-5">
        <h2 class="text-center">Lista de Personas por Tipo de Impuesto</h2>
        <?php
        // Conectar a la base de datos
        require 'db.php';

        // Consulta para obtener la lista de personas y sus propiedades por tipo de impuesto
        $stmt = $conn->prepare("
            SELECT 
                p.ci,
                p.nombre,
                p.apellido,
                MAX(CASE WHEN prop.tipo_impuesto = 'Alto' THEN prop.lugar ELSE NULL END) AS impuesto_alto,
                MAX(CASE WHEN prop.tipo_impuesto = 'Medio' THEN prop.lugar ELSE NULL END) AS impuesto_medio,
                MAX(CASE WHEN prop.tipo_impuesto = 'Bajo' THEN prop.lugar ELSE NULL END) AS impuesto_bajo
            FROM Persona p
            LEFT JOIN Persona_propiedad pp ON p.ci = pp.ci
            LEFT JOIN Propiedad prop ON pp.id_propiedad = prop.id_propiedad
            GROUP BY p.ci, p.nombre, p.apellido;
        ");
        $stmt->execute();
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <!-- Mostrar los resultados en una tabla -->
        <table class="table table-bordered table-hover mt-3">
            <thead class="table-primary">
                <tr>
                    <th>CI</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Impuesto Alto</th>
                    <th>Impuesto Medio</th>
                    <th>Impuesto Bajo</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resultados as $fila): ?>
                    <tr>
                        <td><?php echo $fila['ci']; ?></td>
                        <td><?php echo $fila['nombre']; ?></td>
                        <td><?php echo $fila['apellido']; ?></td>
                        <td><?php echo $fila['impuesto_alto'] ?: 'N/A'; ?></td>
                        <td><?php echo $fila['impuesto_medio'] ?: 'N/A'; ?></td>
                        <td><?php echo $fila['impuesto_bajo'] ?: 'N/A'; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
