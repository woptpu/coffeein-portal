<div class="auth-grid">
    <section class="auth-intro">
        <span class="eyebrow">Новый администратор</span>
        <h1>Создайте рабочий аккаунт</h1>
        <p>После регистрации вы сразу попадете в личный кабинет и сможете создать собственный список поставщиков.</p>
        <div class="feature-list">
            <div><span>01</span> Пароль хранится в виде безопасного хеша</div>
            <div><span>02</span> Email проверяется на уникальность</div>
            <div><span>03</span> Чужие записи недоступны</div>
        </div>
    </section>
    <section class="auth-card">
        <div class="card-body p-4 p-lg-5">
            <h2>Регистрация</h2>
            <p class="text-secondary mb-4">Заполните три поля для создания аккаунта.</p>
            <form action="/register" method="post" class="needs-validation" novalidate>
                <input type="hidden" name="_token" value="<?= e(\App\Csrf::token()) ?>">
                <div class="mb-3">
                    <label class="form-label" for="name">Имя</label>
                    <input class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" id="name" name="name" value="<?= e($old['name'] ?? '') ?>" placeholder="Иван Иванов" required>
                    <div class="invalid-feedback"><?= e($errors['name'] ?? 'Укажите имя.') ?></div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="email">Email</label>
                    <input class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" id="email" type="email" name="email" value="<?= e($old['email'] ?? '') ?>" placeholder="admin@coffeein.ru" required>
                    <div class="invalid-feedback"><?= e($errors['email'] ?? 'Введите корректный email.') ?></div>
                </div>
                <div class="mb-4">
                    <label class="form-label" for="password">Пароль</label>
                    <input class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" id="password" type="password" name="password" minlength="6" placeholder="Минимум 6 символов" required>
                    <div class="invalid-feedback"><?= e($errors['password'] ?? 'Минимум 6 символов.') ?></div>
                </div>
                <button class="btn btn-coffee w-100" type="submit">Создать аккаунт</button>
            </form>
            <p class="auth-switch">Уже зарегистрированы? <a href="/login">Войти</a></p>
        </div>
    </section>
</div>

