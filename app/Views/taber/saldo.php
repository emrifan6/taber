<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<form class="mt-2 ml-2" method="get" action="/taber">
    <button type="submit" class="btn btn-info"> Kembali </button>
</form>
<h3>SALDO TABUNGAN</h3>

<!-- AWAL CEK TAGIHAN -->
<div class="row ml-3 ">
    <div class="col col-lg-4">
        <?php if (!empty($saldo)) : ?>
            <h3>DAFTAR TAGIHAN</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Grup</th>
                        <th scope="col">Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php $sld = json_decode(json_encode($saldo), true); ?>
                    <?php if ($sld['grup1'] != null) : ?>
                        <tr>
                            <th scope="row"><?= $i++; ?></th>
                            <td><?= $sld['grup1']; ?></td>
                            <td><?= 'Rp. ' . number_format($sld['saldo_grup1'], 0, ',', '.'); ?></td>
                        </tr>
                    <?php endif ?>
                    <?php if ($sld['grup2'] != null) : ?>
                        <tr>
                            <th scope="row"><?= $i++; ?></th>
                            <td><?= $sld['grup2']; ?></td>
                            <td><?= 'Rp. ' . number_format($sld['saldo_grup2'], 0, ',', '.'); ?></td>
                        </tr>
                    <?php endif ?>
                    <?php if ($sld['grup3'] != null) : ?>
                        <tr>
                            <th scope="row"><?= $i++; ?></th>
                            <td><?= $sld['grup3']; ?></td>
                            <td><?= 'Rp. ' . number_format($sld['saldo_grup3'], 0, ',', '.'); ?></td>
                        </tr>
                    <?php endif ?>
                </tbody>
            </table>
        <?php endif ?>
    </div>
    <!-- AKHIR CEK TAGIHAN -->

    <!-- TOTAL SALDO -->
    <div class="col col-lg-4">
        <h3>SALDO DILUAR GRUP</h3>
        <h5>Saldo = Rp. <?php echo number_format($sld['saldo'], 0, ',', '.'); ?></h5>
    </div>

    <!-- TOTAL SALDO -->
    <div class="col col-lg-4">
        <h3>TOTAL SALDO</h3>
        <?php $saldo_total = 0 + $sld['saldo_grup1'] + $sld['saldo_grup2'] + $sld['saldo_grup3'] + $sld['saldo']; ?>
        <h5>Total saldo = Rp. <?php echo number_format($saldo_total, 0, ',', '.'); ?></h5>
    </div>

</div>


<?= $this->endSection('content'); ?>