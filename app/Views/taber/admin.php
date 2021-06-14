<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container">
    <h3>ADMIN</h3>
    <!-- CEK TRANSAKSI PAYOUT-->
    <div class="row mt-5">
        <?php if (!empty($transaksi)) : ?>
            <h3>DAFTAR PERMINTAAN TRANSAKSI TARIK TABUNGAN</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Status</th>
                        <th scope="col">Nominal</th>
                        <th scope="col">Bank</th>
                        <th scope="col">Rekening</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($transaksi as $r) : ?>
                        <tr>
                            <th scope="row"><?= $i++; ?></th>
                            <td><?= $r['status']; ?></td>
                            <td><?= $r['nominal']; ?></td>
                            <td><?= $r['bank_code']; ?></td>
                            <td><?= $r['rekening']; ?></td>
                            <td><?= $r['nama']; ?></td>
                            <td>
                                <a href="/taber/payout/bayar/<?= $r['id']; ?>" class="btn btn-success">BAYAR</a>
                                <a href="/taber/payout/tolak/<?= $r['id']; ?>" class="btn btn-warning">TOLAK</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif ?>
    </div>
    <!-- AKHIR CEK TRANSAKSI PAYOUT-->
</div>
<?= $this->endSection('content'); ?>