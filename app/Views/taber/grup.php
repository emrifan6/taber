<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<?php if (session()->getFlashdata('pesan')) : ?>
    <div class="alert alert-success" role="alert">
        <?= session()->getFlashdata('pesan'); ?>
    </div>
<?php endif ?>
<div class="container mt-4 row">
    <div class="card col" style="width:200px">
        <img class="card-img-top" src="https://img.freepik.com/free-vector/group-people-illustration-set_52683-33806.jpg" alt="Card image" style="width:100%">
        <div class="card-body">
            <form action="/taber/join" method="POST" enctype="multipart/form-data">
                <div class=" row mb-3">
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="kodegrup" name="kodegrup" placeholder="Kode Grup">
                    </div>
                </div>
                <div class=" form-groupt row">
                    <div class="col-sl-10">
                        <button type="submit" class="btn btn-primary">GABUNG GRUP</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card col" style="width:200px">
        <img class="card-img-top" src="https://img.freepik.com/free-vector/group-people-illustration-set_52683-33806.jpg" alt="Card image" style="width:100%">
        <div class="card-body">
            <a href=" <?= base_url('/taber/grup/create'); ?>" class="btn btn-primary">BUAT GRUP MENABUNG</a>
        </div>
    </div>
    <?php $i = 1; ?>
    <?php $datagrup = json_decode(json_encode($grups), true); ?>
    <?php if ($datagrup !== null) : ?>
        <?php foreach ($datagrup as $k) : ?>
            <div class="card col" style="width:200px">
                <img class="card-img-top" src="https://img.freepik.com/free-vector/group-people-illustration-set_52683-33806.jpg" alt="Card image" style="width:100%">
                <div class="card-body">
                    <a href="/taber/grup/<?= $k['kode_grup']; ?>" class="btn btn-primary"><?php echo 'GRUP ' . $k['nama_grup'] ?></a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif ?>


    <?= $this->endSection('content'); ?>