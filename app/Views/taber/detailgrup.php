<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<form method="get" action="/taber/grup">
    <button type="submit" class="btn btn-info"> Kembali </button>
</form>
<form>
    <div class="row ml-4 mt-2">
        <div class="col-sm">
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-3 col-form-label">Tabungan</label>
                <div class="col-sm-5">
                    <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?= $datagrup['nama_grup'] ?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-3 col-form-label">Tujuan</label>
                <div class="col-sm-3">
                    <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?= $datagrup['tujuan'] ?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-3 col-form-label">Anggota</label>
                <div class="col-sm-3">
                    <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?= count($saldoanggota) . ' Anggota' ?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-3 col-form-label">Target Tabungan</label>
                <div class="col-sm-3">
                    <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?= 'Rp. ' . $datagrup['target_tabungan'] ?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-3 col-form-label">Setoran Menabung</label>
                <div class="col-sm-3">
                    <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?= 'Rp. ' . $datagrup['jumlah_setoran'] ?>">
                </div>
            </div>
        </div>
        <div class="col-sm">
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-3 col-form-label">Periode menabung</label>
                <div class="col-sm-3">
                    <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?= $datagrup['periode_setoran'] . ' Hari Sekali' ?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-3 col-form-label">Jangka Waktu</label>
                <div class="col-sm-3">
                    <?php
                    $cicilan = $datagrup['jangka_waktu'] / $datagrup['periode_setoran'];
                    ?>
                    <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?= $cicilan . 'X (' . $datagrup['jangka_waktu'] . ' Hari)' ?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-3 col-form-label">Awal Menabung</label>
                <div class="col-sm-3">
                    <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?= $datagrup['awal_menabung'] ?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-3 col-form-label">Akhir Menabung</label>
                <div class="col-sm-3">
                    <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?= $datagrup['akhir_menabung'] ?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-3 col-form-label">Progres Tabungan Grup</label>
                <div class="col-sm-3">
                    <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?= $progresgrup * 100 . '%' ?>">
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-3 col-form-label">Kode Grup</label>
                <div class="col-sm-3">
                    <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?= $datagrup['kode_grup'] ?>">
                </div>
            </div>
        </div>
    </div>
</form>

<div>
    <table class="table w-25">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Nama</th>
                <th scope="col">Saldo</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; ?>
            <?php foreach ($saldoanggota as $k) : ?>
                <tr>
                    <th scope="row"><?= $i++; ?></th>
                    <td><?= $k['username']; ?></td>
                    <td><?= 'Rp. ' . $k['saldoingrup']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection('content'); ?>