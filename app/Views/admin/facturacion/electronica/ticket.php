<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <?php $serieTicket = strtoupper((string) $comprobante->serie); $serieTicket = $comprobante->tipo_comprobante === '03' && !str_starts_with($serieTicket, 'B') ? 'B' . str_pad(preg_replace('/\\D+/', '', $serieTicket), 3, '0', STR_PAD_LEFT) : ($comprobante->tipo_comprobante === '01' && !str_starts_with($serieTicket, 'F') ? 'F' . str_pad(preg_replace('/\\D+/', '', $serieTicket), 3, '0', STR_PAD_LEFT) : $serieTicket); ?>
  <title>Ticket <?= esc($serieTicket . '-' . str_pad((string) $comprobante->correlativo, 8, '0', STR_PAD_LEFT)) ?></title>
  <style>
    @page { size: 80mm auto; margin: 4mm; }
    * { box-sizing: border-box; }
    body { margin: 0; font-family: Arial, Helvetica, sans-serif; color: #111; font-size: 11px; }
    .ticket { width: 72mm; margin: 0 auto; }
    .center { text-align: center; }
    .bold { font-weight: 700; }
    .muted { color: #444; }
    .line { border-top: 1px dashed #333; margin: 8px 0; }
    .row { display: flex; justify-content: space-between; gap: 8px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 3px 0; vertical-align: top; }
    th { border-bottom: 1px dashed #333; text-align: left; }
    .num { text-align: right; white-space: nowrap; }
    .totals td { padding-top: 4px; }
    @media print { .no-print { display: none; } }
  </style>
</head>
<body>
  <div class="ticket">
    <div class="center">
      <div class="bold"><?= esc($config->razon_social ?? 'ORAL CENTER') ?></div>
      <?php if (!empty($config->nombre_comercial)): ?><div><?= esc($config->nombre_comercial) ?></div><?php endif; ?>
      <div>RUC: <?= esc($config->ruc_emisor ?? '') ?></div>
      <?php if (!empty($config->direccion)): ?><div><?= esc($config->direccion) ?></div><?php endif; ?>
    </div>

    <div class="line"></div>
    <div class="center bold"><?= $comprobante->tipo_comprobante === '01' ? 'FACTURA ELECTRONICA' : 'BOLETA ELECTRONICA' ?></div>
    <div class="center bold"><?= esc($serieTicket . '-' . str_pad((string) $comprobante->correlativo, 8, '0', STR_PAD_LEFT)) ?></div>
    <div class="line"></div>

    <div class="row"><span>Fecha:</span><span><?= esc($comprobante->fecha_emision . ' ' . ($comprobante->hora_emision ?? '')) ?></span></div>
    <div>Cliente: <?= esc($comprobante->razon_social_cliente) ?></div>
    <div>Doc.: <?= esc($comprobante->numero_documento_cliente) ?></div>
    <?php if (!empty($comprobante->direccion_cliente)): ?><div>Dir.: <?= esc($comprobante->direccion_cliente) ?></div><?php endif; ?>

    <div class="line"></div>
    <table>
      <thead>
        <tr>
          <th>Detalle</th>
          <th class="num">Importe</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach (($items ?? $comprobante->detalle) as $item): ?>
          <tr>
            <td>
              <?= esc($item->descripcion) ?><br>
              <span class="muted"><?= esc(number_format((float) $item->cantidad, 2)) ?> x S/ <?= esc(number_format((float) $item->precio_unitario, 2)) ?></span>
            </td>
            <td class="num">S/ <?= esc(number_format((float) $item->total, 2)) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot class="totals">
        <tr><td>Op. gravada</td><td class="num">S/ <?= esc(number_format((float) $comprobante->op_gravada, 2)) ?></td></tr>
        <tr><td>IGV</td><td class="num">S/ <?= esc(number_format((float) $comprobante->igv, 2)) ?></td></tr>
        <tr><td class="bold">Total</td><td class="num bold">S/ <?= esc(number_format((float) $comprobante->total, 2)) ?></td></tr>
      </tfoot>
    </table>

    <div class="line"></div>
    <div>Estado SUNAT: <?= esc($comprobante->sunat_estado ?? 'pendiente') ?></div>
    <?php if (!empty($comprobante->sunat_mensaje)): ?><div><?= esc($comprobante->sunat_mensaje) ?></div><?php endif; ?>
    <div class="center muted" style="margin-top: 10px;">Gracias por su preferencia</div>
    <div class="center no-print" style="margin-top: 12px;">
      <button onclick="window.print()">Imprimir</button>
      <button onclick="window.close()">Cerrar</button>
    </div>
  </div>
  <script>
    window.addEventListener('load', function () { window.print(); });
  </script>
</body>
</html>