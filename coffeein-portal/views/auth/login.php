<div class="auth-grid">
    <section class="auth-intro">
        <span class="eyebrow">Панель администратора</span>
        <h1>Все поставщики кофеен в одном месте</h1>
        <p>Войдите, чтобы просматривать контакты, добавлять партнеров и поддерживать данные в актуальном состоянии.</p>
        <div class="feature-list">
            <div><span>01</span> Защищенный личный кабинет</div>
            <div><span>02</span> Данные только вашего аккаунта</div>
            <div><span>03</span> Быстрое управление поставщиками</div>
        </div>
    </section>
    <section class="auth-card">
        <div class="card-body p-4 p-lg-5">
            <h2>Вход</h2>
            <p class="text-secondary mb-4">Используйте email и пароль администратора.</p>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= e($error) ?></div>
            <?php endif; ?>
            <form action="/login" method="post" class="needs-validation" novalidate>
                <input type="hidden" name="_token" value="<?= e(\App\Csrf::token()) ?>">
                <div class="mb-3">
                    <label class="form-label" for="email">Email</label>
                    <input class="form-control" id="email" type="email" name="email" value="<?= e($email) ?>" placeholder="admin@coffeein.ru" required autocomplete="email">
                    <div class="invalid-feedback">Введите корректный email.</div>
                </div>
                <div class="mb-4">
                    <label class="form-label" for="password">Пароль</label>
                    <input class="form-control" id="password" type="password" name="password" placeholder="Не менее 6 символов" required autocomplete="current-password">
                    <div class="invalid-feedback">Введите пароль.</div>
                </div>
                <button class="btn btn-coffee w-100" type="submit">Войти</button>
            </form>
            <p class="auth-switch">Нет аккаунта? <a href="/register">Зарегистрироваться</a></p>
        </div>
    </section>
</div>

