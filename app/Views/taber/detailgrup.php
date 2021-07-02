<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<form class="mt-2 ml-2" method="get" action="/taber/grup">
    <button type="submit" class="btn btn-info"> Kembali </button>
</form>

<div class="row mt-2 mb-2">
    <div class="col-sm-12">
        <div class="panel panel-primary">
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-8">
                        <table id="tabel_tagihan" class="table table-hover" cellspacing="0" width="100%">
                            <tbody>
                                <tr>
                                    <td>Nama Grup</td>
                                    <td>: <b><?= $datagrup['nama_grup']; ?></b></td>
                                </tr>
                                <tr>
                                    <td>Tujuan Grup</td>
                                    <td>: <b><?= $datagrup['tujuan']; ?></b></td>
                                </tr>
                                <tr>
                                    <td>Anggota</td>
                                    <td>: <b><?= count($saldoanggota); ?></b> </td>
                                </tr>
                                <tr>
                                    <td>Target Tabungan</td>
                                    <td>: <b><?= 'Rp. ' . number_format($datagrup['target_tabungan'], 0, ',', '.'); ?></b></td>
                                </tr>
                                <tr>
                                    <td>Setoran Menabung</td>
                                    <td>: <b><?= 'Rp. ' . number_format($datagrup['jumlah_setoran'], 0, ',', '.'); ?></b></td>
                                </tr>
                                <tr>
                                    <td>Periode Menabung</td>
                                    <?php
                                    $cicilan = $datagrup['jangka_waktu'] / $datagrup['periode_setoran'];
                                    ?>
                                    <td>: <b><?= $cicilan . 'X (' . $datagrup['jangka_waktu'] . ' Hari)' ?></b></td>
                                </tr>
                                <tr>
                                    <td>Awal Menabung</td>
                                    <td>: <b><?= $datagrup['awal_menabung'] ?></b></td>
                                </tr>
                                <tr>
                                    <td>Akhir Menabung</td>
                                    <td>: <b><?= $datagrup['awal_menabung'] ?></b></td>
                                </tr>
                                <tr>
                                    <td>Awal Menabung</td>
                                    <td>: <b><?= $datagrup['akhir_menabung'] ?></b></td>
                                </tr>
                                <tr>
                                    <td>Progres Tabungan</td>
                                    <td>: <b><?= $progresgrup * 100 . '%' ?></b></td>
                                </tr>
                                <tr>
                                    <td>Kode Grup</td>
                                    <td>: <b><?= $datagrup['kode_grup'] ?></b></td>
                                </tr>
                                <tr>
                                    <td>Ketua Grup</td>
                                    <td>: <b><?= $namaketua['username'] ?></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="col col-lg-3">
                            <form method="POST" action="/taber/keluargrup">
                                <button type="submit" class="btn btn-danger" name="id_grup" id="id_grup" value='<?= $datagrup['id'] ?>' onclick="return confirm('Apakah Anda Yakin Keluar dari Anggota grup <?= $datagrup['nama_grup'] ?>')"> KELUAR DARI ANGGOTA GRUP </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





<!-- AWAL PROGRES TABUNGAN ANGGOTA -->
<div class="page-content page-container" id="page-content">
    <div class="padding">
        <div class="row container d-flex justify-content-center">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Progres Tabungan Anggota</h4>
                        <!-- <p class="card-description"> Basic stripped table example </p> -->
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th> No </th>
                                        <th> Nama</th>
                                        <th> Progres </th>
                                        <th> Saldo </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    <?php foreach ($saldoanggota as $k) : ?>
                                        <tr>
                                            <th scope="row"><?= $i++; ?></th>
                                            <td><?= $k['username']; ?></td>
                                            <td>
                                                <?php
                                                $progres = ($k['saldoingrup'] / $datagrup['target_tabungan']) * 100;
                                                ?>
                                                <div class="progress">
                                                    <div class="progress-bar bg-success" role="progressbar" style="color :black; width: <?= $progres; ?>%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><?= $progres; ?>%</div>
                                                </div>
                                            </td>
                                            <td><?= 'Rp. ' . number_format($k['saldoingrup'], 0, ',', '.'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- AKHIR PROGRES TABUNGAN ANGGOTA -->

<?= $this->endSection('content'); ?>