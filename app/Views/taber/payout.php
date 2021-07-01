<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<?php if (session()->getFlashdata('pesan')) : ?>
    <div class="alert alert-success" role="alert">
        <?= session()->getFlashdata('pesan'); ?>
    </div>
<?php endif ?>
<form class="mt-2 ml-2" method="get" action="/taber">
    <button type="submit" class="btn btn-info"> Kembali </button>
</form>
<div class="container mt-4">
    <form action="/taber/payout/request" method="post" enctype="multipart/form-data">
        <?= csrf_field(); ?>

        <div class="row mb-3">
            <label for="payout_nominal" class="col-sm-2 col-form-label">Nominal</label>
            <div class="col-sm-10">
                <input type="number" class="form-control" id="payout_nominal" name="payout_nominal" value="<?= $saldo ?>" max="<?= $saldo ?>" autofocus>
            </div>
        </div>
        <div class=" row mb-3">
            <label for="bank_name" class="col-sm-2 col-form-label">Tujuan Bank</label>
            <div class="col-sm-10">
                <select class="form-control" id="bank_name" name="bank_name">
                    <option></option>
                    <?php foreach ($bank as $b) : ?>
                        <option> <?php echo $b['bank_name'] ?> </option>
                    <?php endforeach ?>
                </select>
            </div>
        </div>
        <div class="row mb-3">
            <label for="nomor_rekening" class="col-sm-2 col-form-label">Nomor Rekening</label>
            <div class="col-sm-10">
                <input type="number" class="form-control" id="nomor_rekening" name="nomor_rekening">
            </div>
        </div>
        <div class="row mb-3">
            <label for="nama_pemilik_rekening" class="col-sm-2 col-form-label">Nama Pemilik Rekening</label>
            <div class="col-sm-10">
                <input type="text" style="text-transform: uppercase" class="form-control" id="nama_pemilik_rekening" name="nama_pemilik_rekening">
            </div>
        </div>

        <div class="form-groupt row">
            <div class="col-sl-10">
                <button type="submit" class="btn btn-primary">Kirim</button>
            </div>
        </div>

    </form>


    <!-- CEK TRANSAKSI PAYOUT-->
    <div class="row mt-5">
        <?php if (!empty($transaksi)) : ?>
            <h3>DAFTAR TRANSAKSI TARIK TABUNGAN</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Status</th>
                        <th scope="col">Nominal</th>
                        <th scope="col">Bank</th>
                        <th scope="col">Rekening</th>
                        <th scope="col">Nama</th>
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
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif ?>
    </div>
    <!-- AKHIR CEK TRANSAKSI PAYOUT-->

    <?= $this->endSection('content'); ?>