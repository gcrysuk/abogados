<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Iniciar Sesión<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h2 class="card-title text-center mb-4">Iniciar Sesión</h2>

                    <?php if (session('error')): ?>
                        <div class="alert alert-danger"><?= session('error') ?></div>
                    <?php endif; ?>

                    <?php if (session('success')): ?>
                        <div class="alert alert-success"><?= session('success') ?></div>
                    <?php endif; ?>

                    <form action="<?= base_url('procesar-login') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="username" class="form-label">Usuario</label>
                            <input type="text" class="form-control <?= session('errors.username') ? 'is-invalid' : '' ?>"
                                id="username" name="username" value="<?= old('username') ?>" required>
                            <?php if (session('errors.username')): ?>
                                <div class="invalid-feedback"><?= session('errors.username') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control <?= session('errors.password') ? 'is-invalid' : '' ?>"
                                id="password" name="password" required>
                            <?php if (session('errors.password')): ?>
                                <div class="invalid-feedback"><?= session('errors.password') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary">Ingresar</button>
                        </div>

                        <div class="text-center">
                            <a href="<?= base_url('forgot-password') ?>">¿Olvidaste tu contraseña?</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>