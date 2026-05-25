<?php foreach ($tratamientos as $t): ?>
    <table class="pdf-treatment-table" width="100%" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th width="10%" class="pdf-head-dark">Código</th>
                <th width="13%" class="pdf-head-dark">Fecha</th>
                <th width="24%" class="pdf-head-dark">Paciente</th>
                <th width="33%" class="pdf-head-dark">Médico</th>
                <th width="20%" class="pdf-head-dark">Asunto</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= esc($t->codi_tra) ?></td>
                <td><?= esc($t->fecha_tra) ?></td>
                <td><?= esc($t->NombresApellidos) ?></td>
                <td><?= esc(trim($t->nomb_med . ' ' . $t->apel_med . ' - ' . $t->nombre_especialidad)) ?></td>
                <td><?= esc($t->asunto_tra) ?></td>
            </tr>
            <tr>
                <th width="48%" class="pdf-head-blue">Procedimiento</th>
                <th width="12%" class="pdf-head-blue text-right">Cantidad</th>
                <th width="14%" class="pdf-head-blue text-right">Precio</th>
                <th width="12%" class="pdf-head-blue text-right">Descuento</th>
                <th width="14%" class="pdf-head-blue text-right">Subtotal</th>
            </tr>
            <?php foreach ($t->procedimientos as $p): ?>
                <tr>
                    <td><?= esc($p->nombre) ?></td>
                    <td class="text-right"><?= esc(number_format((float) $p->cant_tradet, 2)) ?></td>
                    <td class="text-right"><?= esc(number_format((float) $p->preciounit_tradet, 2)) ?></td>
                    <td class="text-right"><?= esc(number_format((float) $p->descuento_tradet, 2)) ?>%</td>
                    <td class="text-right"><?= esc(number_format((float) $p->subtotal_tradet, 2)) ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3" class="pdf-total-empty"></td>
                <th class="pdf-head-dark text-right">Total</th>
                <th class="text-right"><?= esc(number_format((float) $t->total_tra, 2)) ?></th>
            </tr>
        </tbody>
    </table>
<?php endforeach; ?>
