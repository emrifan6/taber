<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<form class="mt-2 ml-2" method="get" action="/taber/grup">
    <button type="submit" class="btn btn-info"> Kembali </button>
</form>
<div class="row">
    <div class="col">
        <form>
            <div class="row">
                <div class="col-sm">
                    <div class="form-group row">
                        <label for="staticEmail" class="col col-form-label">Tabungan</label>
                        <div class="col-sm-5">
                            <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?= $datagrup['nama_grup'] ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="staticEmail" class="col col-form-label">Tujuan</label>
                        <div class="col">
                            <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?= $datagrup['tujuan'] ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="staticEmail" class="col col-form-label">Anggota</label>
                        <div class="col">
                            <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?= count($saldoanggota) . ' Anggota' ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="staticEmail" class="col col-form-label">Target Tabungan</label>
                        <div class="col">
                            <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?= 'Rp. ' . number_format($datagrup['target_tabungan'], 0, ',', '.'); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="staticEmail" class="col col-form-label">Setoran Menabung</label>
                        <div class="col">
                            <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?= 'Rp. ' . number_format($datagrup['jumlah_setoran'], 0, ',', '.'); ?>">
                        </div>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="form-group row">
                        <label for="staticEmail" class="col col-form-label">Periode menabung</label>
                        <div class="col">
                            <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?= $datagrup['periode_setoran'] . ' Hari Sekali' ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="staticEmail" class="col col-form-label">Jangka Waktu</label>
                        <div class="col">
                            <?php
                            $cicilan = $datagrup['jangka_waktu'] / $datagrup['periode_setoran'];
                            ?>
                            <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?= $cicilan . 'X (' . $datagrup['jangka_waktu'] . ' Hari)' ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="staticEmail" class="col col-form-label">Awal Menabung</label>
                        <div class="col">
                            <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?= $datagrup['awal_menabung'] ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="staticEmail" class="col col-form-label">Akhir Menabung</label>
                        <div class="col">
                            <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?= $datagrup['akhir_menabung'] ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="staticEmail" class="col col-form-label">Progres Tabungan Grup</label>
                        <div class="col">
                            <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?= $progresgrup * 100 . '%' ?>">
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group row">
                        <label for="staticEmail" class="col col-form-label">Kode Grup</label>
                        <div class="col">
                            <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?= $datagrup['kode_grup'] ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="staticEmail" class="col col-form-label">Ketua Grup</label>
                        <div class="col">
                            <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?= $namaketua['username'] ?>">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="col col-lg-3">
        <form method="POST" action="/taber/keluargrup">
            <button type="submit" class="btn btn-danger" name="id_grup" id="id_grup" value='<?= $datagrup['id'] ?>' onclick="return confirm('Apakah Anda Yakin Keluar dari Anggota grup <?= $datagrup['nama_grup'] ?>')"> KELUAR DARI ANGGOTA GRUP </button>
        </form>
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
                                        $progres = ($k['saldoingrup']/ $datagrup['target_tabungan'])*100;
                                        ?>
                                            <div class="progress">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: <?= $progres; ?>%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><?= $progres; ?>%</div>
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