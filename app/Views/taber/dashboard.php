<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<?php if (session()->getFlashdata('pesan')) : ?>
    <div class="alert alert-success" role="alert">
        <?= session()->getFlashdata('pesan'); ?>
    </div>
<?php endif ?>
<div class="container mt-4">
<!-- AWAL MENU -->
    <div class="container">
        <div class="card-deck row">

            <div class="col-xs-12 col-sm-6 col-md-4">
                <div class="card">
                    <div class="view overlay">
                    <img class="card-img-top" src="https://img.freepik.com/free-vector/group-people-illustration-set_52683-33806.jpg" alt="Card image cap">
                    <a href="#!">
                        <div class="mask rgba-white-slight"></div>
                    </a>
                    </div>
                    <div class="card-body">
                    <a href=" <?= base_url('/taber/grup'); ?>" class="btn btn-primary">GRUP MENABUNG</a>
                    </div>
                </div>
            </div>  

            <div class="col-xs-12 col-sm-6 col-md-4">
                <div class="card">
                    <div class="view overlay">
                    <img class="card-img-top" src="https://img.freepik.com/free-vector/group-people-illustration-set_52683-33806.jpg" alt="Card image cap">
                    <a href="#!">
                        <div class="mask rgba-white-slight"></div>
                    </a>
                    </div>
                    <div class="card-body">
                    <a href=" <?= base_url('/taber/saldo'); ?>" class="btn btn-primary">SALDO TABUNGAN</a>
                    </div>
                </div>
            </div>  

            <div class="col-xs-12 col-sm-6 col-md-4">
                <div class="card">
                    <div class="view overlay">
                    <img class="card-img-top" src="https://img.freepik.com/free-vector/group-people-illustration-set_52683-33806.jpg" alt="Card image cap">
                    <a href="#!">
                        <div class="mask rgba-white-slight"></div>
                    </a>
                    </div>
                    <div class="card-body">
                    <a href=" <?= base_url('/taber/payout'); ?>" class="btn btn-primary">TARIK UANG TABUNGAN</a>
                    </div>
                </div>
            </div>  

        </div>  
    </div>
    <!-- AKHIR MENU -->

    <div class="row">
        <!-- AWAL CEK PERMINTAAN GABUNG GRUP -->
        <div class="col">
            <?php if (!empty($join_req)) : ?>
                <?php $join_req = json_decode(json_encode($join_req), true); ?>
                <h3>DAFTAR PERMINTAAN GABUNG GRUP</h3>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">User</th>
                                <th scope="col">Grup</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach ($join_req as $k) : ?>
                                <tr>
                                    <th scope="row"><?= $i++; ?></th>
                                    <td><?= $k['username']; ?></td>
                                    <td><?= $k['nama_grup']; ?></td>
                                    <td>
                                        <a href="/taber/terima/<?= $k['id']; ?>" class="btn btn-success">TERIMA</a>
                                        <a href="/taber/tolak/<?= $k['id']; ?>" class="btn btn-warning">TOLAK</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif ?>
        </div>
        <!-- AKHIR CEK PERMINTAAN GABUNG GRUP -->
    </div>

    <!-- AWAL CEK TAGIHAN -->
    <div class="row mt-4">
        <?php if (!empty($tagihan)) : ?>
            <?php $join_req = json_decode(json_encode($tagihan), true); ?>
            <h3>DAFTAR TAGIHAN</h3>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Tagihan</th>
                            <th scope="col">Grup</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <?php foreach ($tagihan as $t) : ?>
                            <tr>
                                <th scope="row"><?= $i++; ?></th>
                                <td><?= 'Rp. ' . number_format(abs($t['tagihan']), 0, ',', '.'); ?></td>
                                <td><?= $t['nama_grup']; ?></td>
                                <td>
                                    <a href="/taber/bayar/<?= abs($t['tagihan']) . '/' . $t['id_grup']; ?>" class="btn btn-success">BAYAR</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif ?>
    </div>
    <!-- AKHIR CEK TAGIHAN -->

    <!-- CEK TRANSAKSI -->
    <div class="row">
        <?php if (!empty($transaksi)) : ?>
            <h3>DAFTAR TRANSAKSI</h3>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Nominal</th>
                            <th scope="col">Grup</th>
                            <th scope="col">Status</th>
                            <th scope="col">Bank</th>
                            <th scope="col">VA</th>
                            <th scope="col">Metode</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <?php foreach ($transaksi as $r) : ?>
                            <tr>
                                <th scope="row"><?= $i++; ?></th>
                                <td class="text-nowrap"  ><?= 'Rp. ' . number_format($r['gross_amount'], 0, ',', '.'); ?></td>
                                <td><?= $r['id_grup']; ?></td>
                                <td><?= $r['transaction_status']; ?></td>
                                <td><?= strtoupper($r['bank']); ?></td>
                                <td><?= $r['va_number']; ?></td>
                                <td><?= $r['payment_type']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif ?>
    </div>
    <!-- AKHIR CEK TRANSAKSI -->
</div>

<?= $this->endSection('content'); ?>