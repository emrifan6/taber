<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container " style="border: 5px #a80505">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-link active" aria-current="page" href=" <?= base_url('/'); ?>">Home</a>
                    <a class="nav-link" href=" <?= base_url('/taber'); ?> ">Tabungan</a>
                    <a class="nav-link" href=" <?= base_url('/contact'); ?> ">Contact</a>
                </div>
                <?php if (logged_in()) : ?>
                    <a class="nav-link" href=" <?= base_url('/logout'); ?> ">Logout</a>
                <?php else : ?>
                    <a class="nav-link" href=" <?= base_url('/login'); ?> ">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>