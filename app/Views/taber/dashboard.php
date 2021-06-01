<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<?php if (session()->getFlashdata('pesan')) : ?>
    <div class="alert alert-success" role="alert">
        <?= session()->getFlashdata('pesan'); ?>
    </div>
<?php endif ?>
<div class="container mt-4">
<div class="row">
<div class="col">
<div class="card" style="width:400px">
        <img class="card-img-top" src="https://img.freepik.com/free-vector/group-people-illustration-set_52683-33806.jpg" alt="Card image" style="width:100%">
        <div class="card-body">
            <a href=" <?= base_url('/taber/grup'); ?>" class="btn btn-primary">GRUP MENABUNG</a>
        </div>
    </div>
</div>
<!-- AWAL CEK PERMINTAAN GABUNG GRUP -->
<div class="col">
<?php if (!empty($join_req)) : ?>
<?php $join_req = json_decode(json_encode($join_req), true); ?>
    <h3>DAFTAR PERMINTAAN GABUNG GRUP</h3>
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
    <?php endif ?>
</div>
<!-- AKHIR CEK PERMINTAAN GABUNG GRUP -->
</div>
<!-- AWAL CEK TAGIHAN -->
<div class="row">
<?php if (!empty($tagihan)) : ?>
    <?php $join_req = json_decode(json_encode($tagihan), true); ?>
    <h3>DAFTAR TAGIHAN</h3>
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
                           <td><?= abs($t['tagihan']); ?></td>
                           <td><?= $t['nama_grup']; ?></td>
                           <td>
                            <a href="/taber/bayar/<?= abs($t['tagihan']).'/'.$t['id_grup']; ?>" class="btn btn-success">BAYAR</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
<?php endif ?>
</div>

<!-- AKHIR CEK TAGIHAN -->


    
    

    <?= $this->endSection('content'); ?>