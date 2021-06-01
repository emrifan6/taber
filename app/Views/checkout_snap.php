<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<form id="payment-form" method="POST" action="<?= site_url() ?>/snap/finish">
  <input type="hidden" name="result_type" id="result-type" value=""></div>
  <input type="hidden" name="result_data" id="result-data" value=""></div>
</form>

<!-- <button id="pay-button">Pay!</button> -->
<form method="get" action="/taber/grup">
  <button type="submit" class="btn btn-info"> Kembali </button>
</form>

<div class="container mt-4">
  <form id="formbayar">
    <?= csrf_field(); ?>

    <div class="row mb-3">
      <label for="namagrup" class="col-sm-2 col-form-label">Nama Grup</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" id="namagrup" name="namagrup" value="<?= $namagrup ?>" disabled>
      </div>
    </div>

    <div class="row mb-3">
      <label for="nominal" class="col-sm-2 col-form-label">Nominal Menabung</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" id="nominal" name="nominal" value="<?= $nominal; ?>" autofocus>
      </div>
    </div>

    <div class="form-groupt row">
      <div class="col-sl-10">
        <button type="submit" class="btn btn-primary">Bayar</button>
      </div>
    </div>

  </form>
</div>


<script type="text/javascript">
  $(document).ready(function() {
    $("#formbayar").submit(function() {
      event.preventDefault();
      $(this).attr("disabled", "disabled");
      var nominal = document.getElementById('nominal').value;
      console.log(nominal);
      $.ajax({
        url: '<?= site_url() ?>/snap/token/' + nominal + '<?= '/' . $idgrup; ?>',
        cache: false,

        success: function(data) {
          //location = data;

          console.log('token = ' + data);

          var resultType = document.getElementById('result-type');
          var resultData = document.getElementById('result-data');

          function changeResult(type, data) {
            $("#result-type").val(type);
            $("#result-data").val(JSON.stringify(data));
            //resultType.innerHTML = type;
            //resultData.innerHTML = JSON.stringify(data);
          }

          snap.pay(data, {

            onSuccess: function(result) {
              changeResult('success', result);
              console.log(result.status_message);
              console.log(result);
              $("#payment-form").submit();
            },
            onPending: function(result) {
              changeResult('pending', result);
              console.log(result.status_message);
              $("#payment-form").submit();
            },
            onError: function(result) {
              changeResult('error', result);
              console.log(result.status_message);
              $("#payment-form").submit();
            }
          });
        }
      });
    });
  });
</script>
<?= $this->endSection('content'); ?>