<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<form class="mt-2 ml-2" method="get" action="/taber">
    <button type="submit" class="btn btn-info"> Kembali </button>
</form>
<h3 class="text-center" >SALDO TABUNGAN</h3>

<!-- AWAL CEK TAGIHAN -->
<div class="row ml-3 ">
    <div class="col col-lg-4">
        <?php if (!empty($saldo)) : ?>
            <h3 class="text-center" >DAFTAR TABUNGAN GRUP</h3>
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
</div>
<!-- AWAL SALDO DILUAR GRUP -->
<div class="col-xl-6 col-md-12 mt-2 mb-2">
 <div class="card">
  <div class="card-content">
   <div class="card-body cleartfix">
    <div class="media align-items-stretch">
     <div class="align-self-center">
      <h1 class="mr-2"><?= 'Rp. ' . number_format($sld['saldo'], 0, ',', '.'); ?></h1>
     </div>
     <div class="media-body">
      <h4>Saldo</h4>
      <span>Saldo di luar grup</span>
     </div>
     <div class="align-self-center">
        <span style="font-size: 48px; color: tomato;">
         <i class="fas fa-wallet"></i>
        </span>
     </div>
    </div>
   </div>
  </div>
 </div>
</div>
<!-- AKHIR SALDO DILUAR GRUP -->

<!-- AWAL SALDO TOTAL -->
<div class="col-xl-6 col-md-12 mt-2 mb-2">
 <div class="card">
  <div class="card-content">
   <div class="card-body cleartfix">
    <div class="media align-items-stretch">
     <div class="align-self-center">
     <?php $saldo_total = 0 + $sld['saldo_grup1'] + $sld['saldo_grup2'] + $sld['saldo_grup3'] + $sld['saldo']; ?>
      <h1 class="mr-2"><?= 'Rp. ' . number_format($saldo_total, 0, ',', '.'); ?></h1>
     </div>
     <div class="media-body">
      <h4>Saldo</h4>
      <span>Total saldo</span>
     </div>
     <div class="align-self-center">
        <span style="font-size: 48px; color: Dodgerblue;">
             <i class="fas fa-wallet"></i>
        </span>
     </div>
    </div>
   </div>
  </div>
 </div>
</div>
<!-- AKHIR SALDO TOTAL -->

<?= $this->endSection('content'); ?>