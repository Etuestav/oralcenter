<?php
$logoName = basename((string) (session()->get('foto') ?: 'logo.png'));
$logoPath = ROOTPATH . 'vendor/uploads/logo/' . $logoName;

if (!is_file($logoPath)) {
    $logoPath = ROOTPATH . 'vendor/img/logo_dental.png';
}

$nombreClinica = trim((string) ($configFacturacion->nombre_comercial ?? $clinicas->nomb_clin ?? 'ORAL CENTER'));
$razonEmisor = trim((string) ($configFacturacion->razon_social ?? $clinicas->nomb_clin ?? $nombreClinica));
$rucEmisor = preg_replace('/\D+/', '', (string) ($configFacturacion->ruc_emisor ?? $clinicas->ruc_clin ?? ''));
$direccionEmisor = trim((string) ($configFacturacion->direccion ?? $clinicas->direc_clin ?? ''));
$emailEmisor = trim((string) ($clinicas->email_clin ?? ''));
$tipoSunat = (string) ($facturacion->tipo_comprobante ?? '');
$tipoDocumento = $tipoSunat === '01'
    ? 'FACTURA ELECTRONICA'
    : ($tipoSunat === '03' ? 'BOLETA ELECTRONICA' : strtoupper((string) ($comprobante->descripcion ?? 'COMPROBANTE')));
$serie = $facturacion
    ? (string) $facturacion->serie
    : (string) ($comprobante->serie_com ?? '');
$numero = $facturacion
    ? str_pad((string) $facturacion->correlativo, 8, '0', STR_PAD_LEFT)
    : (string) ($comprobante->secuencia_com ?? '');
$serieNumero = trim($serie . '-' . $numero, '-');
$cliente = trim((string) (($comprobante->nomb_pac ?? '') . ' ' . ($comprobante->apel_pac ?? '')));
$documentoCliente = trim((string) (($comprobante->ruc_pac ?? '') ?: ($comprobante->dni_pac ?? '')));
$documentoClienteLabel = strlen(preg_replace('/\D+/', '', $documentoCliente)) === 11 ? 'R.U.C :' : 'DNI :';
$direccionCliente = trim((string) ($comprobante->dire_pac ?? ''));
$moneda = 'SOLES';
$total = round((float) ($comprobante->total_comp ?? 0), 2);
$igv = round((float) ($comprobante->igv_com ?? 0), 2);
$opGravada = round($total - $igv, 2);
$exonerada = 0.00;
$items = [];
$procedimientos = $comprobante->procedimientos ?? [];
$totalProcedimientos = 0.0;

foreach ($procedimientos as $p) {
    $totalProcedimientos += (float) ($p->subtotal_tradet ?? 0);
}

if (!empty($procedimientos) && abs(round($totalProcedimientos, 2) - $total) <= 0.02) {
    foreach ($procedimientos as $p) {
        $cantidad = (float) ($p->cant_tradet ?? 1);
        $importe = round((float) ($p->subtotal_tradet ?? 0), 2);
        $items[] = [
            'codigo' => (string) ($p->id_procedimiento ?? ''),
            'descripcion' => (string) ($p->nombre ?? 'Servicio odontologico'),
            'medida' => 'UND',
            'cantidad' => $cantidad,
            'precio' => $cantidad > 0 ? round($importe / $cantidad, 2) : $importe,
            'importe' => $importe,
        ];
    }
} else {
    $items[] = [
        'codigo' => 'TRAT-' . ($comprobante->codi_tra ?? $comprobante->id_com ?? ''),
        'descripcion' => trim('Pago de tratamiento: ' . (string) ($comprobante->asunto_tra ?? '')),
        'medida' => 'UND',
        'cantidad' => 1,
        'precio' => $total,
        'importe' => $total,
    ];
}

$hash = (string) ($facturacion->xml_hash ?? '');
$qrSeed = sha1($rucEmisor . '|' . $serieNumero . '|' . $total . '|' . $documentoCliente . '|' . $hash);
$qrCode = implode('|', [
    $rucEmisor,
    $tipoSunat !== '' ? $tipoSunat : (string) ($comprobante->cod_tipodocumento ?? ''),
    $serie,
    $numero,
    number_format($igv, 2, '.', ''),
    number_format($total, 2, '.', ''),
    (string) ($comprobante->fecha_com ?? date('Y-m-d')),
    strlen(preg_replace('/\D+/', '', $documentoCliente)) === 11 ? '6' : '1',
    $documentoCliente,
    $hash !== '' ? $hash : $qrSeed,
]);
?>

<div class="invoice-page">
  <table class="invoice-header">
    <tr>
      <td style="width: 28%; vertical-align: middle;">
        <img src="<?= esc($logoPath) ?>" class="brand-logo">
      </td>
      <td style="width: 38%; vertical-align: middle;">
        <div class="issuer-name"><?= esc(strtoupper($nombreClinica)) ?></div>
        <div class="issuer-meta">
          <?= esc(strtoupper($direccionEmisor)) ?><br>
          <?= $emailEmisor !== '' ? 'CONTACTO@ ' . esc(strtoupper($emailEmisor)) : '' ?>
        </div>
      </td>
      <td style="width: 34%; vertical-align: middle; text-align: right;">
        <table class="document-box-table" align="right">
          <tr><td class="document-ruc">R.U.C <?= esc($rucEmisor) ?></td></tr>
          <tr><td class="document-title"><?= esc($tipoDocumento) ?></td></tr>
          <tr><td class="document-number"><?= esc($serieNumero) ?></td></tr>
        </table>
      </td>
    </tr>
  </table>

  <br>

  <table class="client-table">
    <tr>
      <td class="label-cell">CLIENTE</td>
      <td class="value-cell" colspan="3"><?= esc(strtoupper($cliente)) ?></td>
      <td class="label-cell"><?= esc($documentoClienteLabel) ?></td>
      <td class="value-cell"><?= esc($documentoCliente) ?></td>
    </tr>
    <tr>
      <td class="label-cell">DIRECCION</td>
      <td class="value-cell" colspan="3"><?= esc(strtoupper($direccionCliente)) ?></td>
      <td class="label-cell">F. EMISION</td>
      <td class="value-cell"><?= esc($comprobante->fecha_com ?? date('Y-m-d')) ?></td>
    </tr>
    <tr>
      <td class="label-cell">TIPO MONEDA</td>
      <td class="value-cell"><?= esc($moneda) ?></td>
      <td class="label-cell">VENDEDOR</td>
      <td class="value-cell"><?= esc(strtoupper(trim(($comprobante->nomb_med ?? '') . ' ' . ($comprobante->apel_med ?? '')))) ?></td>
      <td class="label-cell">CONDICION</td>
      <td class="value-cell"><?= ((int) ($comprobante->cod_tipopago ?? 0) === 1) ? 'CREDITO' : 'CONTADO' ?></td>
    </tr>
  </table>

  <br>

  <table class="items-table">
    <thead>
      <tr>
        <th style="width: 13%;">CODIGO.</th>
        <th style="width: 50%;">DESCRIPCION:</th>
        <th style="width: 8%;">MEDIDA</th>
        <th style="width: 8%;">CANT.</th>
        <th style="width: 10%;">PRECIO</th>
        <th style="width: 11%;">IMPORTE</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($items as $item): ?>
        <tr>
          <td class="center"><?= esc($item['codigo']) ?></td>
          <td><?= esc(strtoupper($item['descripcion'])) ?></td>
          <td class="center"><?= esc($item['medida']) ?></td>
          <td class="num"><?= esc(number_format((float) $item['cantidad'], 2)) ?></td>
          <td class="num"><?= esc(number_format((float) $item['precio'], 2)) ?></td>
          <td class="num"><?= esc(number_format((float) $item['importe'], 2)) ?></td>
        </tr>
      <?php endforeach; ?>
      <tr class="items-filler">
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
    </tbody>
  </table>

  <table style="width:100%; border-collapse: collapse;">
    <tr>
      <td style="width: 79%;"></td>
      <td class="amount-label" style="width: 10%;">OP. GRAV:</td>
      <td class="amount-value" style="width: 11%;">S/ <?= esc(number_format($opGravada, 2)) ?></td>
    </tr>
    <tr>
      <td></td>
      <td class="amount-label">IGV 18%:</td>
      <td class="amount-value">S/ <?= esc(number_format($igv, 2)) ?></td>
    </tr>
    <tr>
      <td></td>
      <td class="amount-label">EXON:</td>
      <td class="amount-value">S/ <?= esc(number_format($exonerada, 2)) ?></td>
    </tr>
    <tr>
      <td></td>
      <td class="amount-total-label">TOTAL:</td>
      <td class="amount-total-value">S/ <?= esc(number_format($total, 2)) ?></td>
    </tr>
  </table>

  <div class="son">SON: <?= esc(strtoupper(number_format($total, 2))) ?> SOLES</div>

  <br>

  <table class="footer-table">
    <tr>
      <td class="legal" style="width: 84%; vertical-align: bottom;">
        Representacion impresa de la <?= esc($tipoDocumento) ?>.<br>
        Consulte el comprobante electronico en SUNAT: www.sunat.gob.pe<br>
        HASH: <?= esc($hash !== '' ? $hash : substr($qrSeed, 0, 28)) ?>
      </td>
      <td style="width: 16%; text-align: right;">
        <barcode code="<?= esc($qrCode, 'attr') ?>" type="QR" size="0.8" error="M" disableborder="1" />
      </td>
    </tr>
  </table>
</div>
