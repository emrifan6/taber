<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<form class="mt-2 ml-2" method="get" action="/taber/grup">
    <button type="submit" class="btn btn-info"> Kembali </button>
</form>
<div class="container mt-4">
    <form action="/taber/savegroup" method="post" enctype="multipart/form-data">
        <?= csrf_field(); ?>

        <div class="row mb-3">
            <label for="namagrup" class="col-sm-2 col-form-label">Nama Grup</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="namagrup" name="namagrup" autofocus>
            </div>
        </div>
        <div class="row mb-3">
            <label for="tujuangrup" class="col-sm-2 col-form-label">Tujuan Grup</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="tujuangrup" name="tujuangrup">
            </div>
        </div>
        <div class="row mb-3">
            <label for="targettabungan" class="col-sm-2 col-form-label">Target Tabungan (per orang)</label>
            <div class="col-sm-10">
                <input type="number" class="form-control" id="targettabungan" name="targettabungan">
            </div>
        </div>
        <div class="row mb-3">
            <label for="jmlsetoran" class="col-sm-2 col-form-label">Jumlah Setoran</label>
            <div class="col-sm-10">
                <input type="number" class="form-control" id="jmlsetoran" name="jmlsetoran">
            </div>
        </div>
        <div class="row mb-3">
            <label for="periodesetoran" class="col-sm-2 col-form-label">Periode Setoran</label>
            <div class="col-sm-10">
                <input type="number" class="form-control" id="periodesetoran" name="periodesetoran">
            </div>
        </div>
        <div class="row mb-3">
            <label for="mulaimenabung" class="col-sm-2 col-form-label">Mulai Menabung</label>
            <div class="col-sm-10">
                <input type="date" class="form-control" id="mulaimenabung" name="mulaimenabung">
            </div>
        </div>


        <div class="form-groupt row justify-content-center mb-4 mt-2">
            <div class="col-sl-10">
                <button type="submit" class="btn btn-primary">Tambah Data</button>
            </div>
        </div>

    </form>
    <?= $this->endSection('content'); ?>