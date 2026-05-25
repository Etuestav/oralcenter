<table class="pdf-header-table" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td width="22%" class="pdf-header-logo">
            <?php
                $logo = $clinicas->photo ?? session()->get('foto');
                $logoPath = !empty($logo) ? FCPATH . 'vendor/uploads/logo/' . $logo : '';
            ?>
            <?php if (!empty($logoPath) && is_file($logoPath)): ?>
                <img src="<?= esc($logoPath) ?>" style="max-width: 95px; max-height: 55px;">
            <?php endif; ?>
        </td>
        <td width="48%" class="pdf-header-center">
            <div class="pdf-clinic-title">CLINICA DENTAL</div>
            <div class="pdf-clinic-name"><?= esc($clinicas->nomb_clin ?? '') ?></div>
            <div>Dirección: <?= esc($clinicas->direc_clin ?? '') ?></div>
            <div>Email: <?= esc($clinicas->email_clin ?? '') ?></div>
            <div>Teléfono: <?= esc($clinicas->telf_clin ?? '') ?></div>
        </td>
        <td width="30%" class="pdf-header-report">
            <div><strong>Reporte de tratamientos</strong></div>
            <div>
                <?= !empty($desde) ? esc(date('Y/m/d', strtotime($desde))) : '' ?>
                -
                <?= !empty($hasta) ? esc(date('Y/m/d', strtotime($hasta))) : '' ?>
            </div>
            <div>Estado: <?= esc($estado ?? 'Todos') ?></div>
        </td>
    </tr>
</table>
