<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Restablecer Contraseña<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h2 class="card-title text-center mb-4">Restablecer Contraseña</h2>

                    <?php if (session('error')): ?>
                        <div class="alert alert-danger"><?= session('error') ?></div>
                    <?php endif; ?>

                    <form action="<?= base_url('procesar-reset-password') ?>" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="token" value="<?= $token ?>">

                        <div class="mb-3">
                            <label for="password" class="form-label">Nueva Contraseña</label>
                            <input type="password" class="form-control <?= session('errors.password') ? 'is-invalid' : '' ?>"
                                id="password" name="password" required>
                            <?php if (session('errors.password')): ?>
                                <div class="invalid-feedback"><?= session('errors.password') ?></div>
                            <?php endif; ?>
                            <small class="form-text text-muted">Mínimo 8 caracteres</small>
                        </div>

                        <div class="mb-3">
                            <label for="pass_confirm" class="form-label">Confirmar Contraseña</label>
                            <input type="password" class="form-control <?= session('errors.pass_confirm') ? 'is-invalid' : '' ?>"
                                id="pass_confirm" name="pass_confirm" required>
                            <?php if (session('errors.pass_confirm')): ?>
                                <div class="invalid-feedback"><?= session('errors.pass_confirm') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Restablecer Contraseña</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>