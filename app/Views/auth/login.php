<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk CMS | BAMUSI</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        :root { --red: #a40000; --ink: #191919; }
        body { min-height: 100vh; display: grid; place-items: center; background: linear-gradient(135deg, #f7f2ed, #fff 55%, #f1e4d8); color: var(--ink); }
        .login-card { width: min(100% - 2rem, 440px); border: 0; border-radius: 1.25rem; box-shadow: 0 1.5rem 4rem rgba(65, 23, 12, .14); }
        .brand-mark { width: 4rem; height: 4rem; border-radius: 1rem; display: grid; place-items: center; background: var(--red); color: #fff; font-weight: 800; letter-spacing: .08em; }
        .btn-primary { --bs-btn-bg: var(--red); --bs-btn-border-color: var(--red); --bs-btn-hover-bg: #760000; --bs-btn-hover-border-color: #760000; }
    </style>
</head>
<body>
<main class="card login-card p-4 p-md-5">
    <div class="d-flex align-items-center gap-3 mb-4">
        <div class="brand-mark" aria-hidden="true">B</div>
        <div>
            <div class="text-uppercase small text-secondary fw-semibold">Panel Administrasi</div>
            <h1 class="h3 mb-0">CMS BAMUSI</h1>
        </div>
    </div>

    <?php if ($message = session()->getFlashdata('error')): ?>
        <div class="alert alert-danger" role="alert"><?= esc($message) ?></div>
    <?php endif; ?>
    <?php if ($message = session()->getFlashdata('success')): ?>
        <div class="alert alert-success" role="alert"><?= esc($message) ?></div>
    <?php endif; ?>

    <form method="post" action="<?= site_url('login') ?>" novalidate>
        <?= csrf_field() ?>
        <input type="hidden" name="redirect_to" value="<?= esc($redirectTo) ?>">
        <div class="mb-3">
            <label class="form-label" for="email">Email administrator</label>
            <input class="form-control form-control-lg" id="email" name="email" type="email" value="<?= esc(old('email')) ?>" autocomplete="username" required autofocus>
        </div>
        <div class="mb-4">
            <label class="form-label" for="password">Password</label>
            <input class="form-control form-control-lg" id="password" name="password" type="password" autocomplete="current-password" minlength="12" required>
            <div class="form-text">Gunakan password panjang dan unik. Jangan gunakan password bawaan dump SQL.</div>
        </div>
        <button class="btn btn-primary btn-lg w-100" type="submit">Masuk ke CMS</button>
    </form>
    <a class="d-block text-center mt-4 text-secondary text-decoration-none" href="<?= base_url('/') ?>">Kembali ke website</a>
</main>
</body>
</html>
