<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand ml-1" href="<?= base_url('/'); ?>">Taber</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
    <ul class="navbar-nav  ml-2">
      <li class="nav-item active">
        <a class="nav-link" href=" <?= base_url('/'); ?>">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?= base_url('/taber'); ?>">Tabungan</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href=" <?= base_url('/contact'); ?> ">Contact</a>
      </li>
      <li class="nav-item">
            <?php if (logged_in()) : ?>
                    <a class="nav-link" href=" <?= base_url('/logout'); ?> ">Logout</a>
                <?php else : ?>
                    <a class="nav-link" href=" <?= base_url('/login'); ?> ">Login</a>
            <?php endif; ?>
      </li>
    </ul>
  </div>
</nav>