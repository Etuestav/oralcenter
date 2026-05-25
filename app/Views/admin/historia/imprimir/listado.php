<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: sans-serif;
            font-size: 10px;
        }

        h2, h4 {
            margin: 0;
            text-align: center;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 12px;
        }

        th, td {
            border: 1px solid #333;
            padding: 5px;
        }

        th {
            background: #3c8dbc;
            color: #fff;
            text-align: center;
        }

        .meta {
            margin-top: 10px;
            font-size: 10px;
        }

        .center {
            text-align: center;
        }
    </style>
</head>
<body>
    <h2>Listado de Historias Clínicas</h2>
    <h4><?= esc(session()->get('clinica') ?? '') ?></h4>

    <div class="meta">
        <strong>Desde:</strong> <?= esc($desde ?: 'Todos') ?>
        &nbsp;&nbsp;
        <strong>Hasta:</strong> <?= esc($hasta ?: 'Todos') ?>
        &nbsp;&nbsp;
        <strong>Paciente:</strong> <?= esc($nombresApellidos ?: 'Todos') ?>
        &nbsp;&nbsp;
        <strong>Fecha impresión:</strong> <?= date('d/m/Y H:i:s') ?>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 8%;">Historia</th>
                <th>Paciente</th>
                <th style="width: 8%;">Edad</th>
                <th style="width: 12%;">DNI</th>
                <th>Dirección</th>
                <th style="width: 12%;">Fecha cita</th>
                <th style="width: 10%;">Hora</th>
                <th style="width: 10%;">Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($historias)): ?>
                <tr>
                    <td colspan="8" class="center">No se encontraron registros.</td>
                </tr>
            <?php endif; ?>

            <?php foreach ($historias as $historia): ?>
                <tr>
                    <td class="center"><?= esc($historia->codi_pac) ?></td>
                    <td><?= esc($historia->paciente) ?></td>
                    <td class="center"><?= esc($historia->edad_pac) ?></td>
                    <td class="center"><?= esc($historia->dni_pac) ?></td>
                    <td><?= esc($historia->dire_pac) ?></td>
                    <td class="center"><?= esc($historia->fecha_cita) ?></td>
                    <td class="center"><?= esc($historia->hora_cita) ?></td>
                    <td class="center"><?= ($historia->esta_pac === 'S') ? 'Activo' : 'Inactivo' ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
