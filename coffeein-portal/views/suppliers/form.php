<section class="page-hero compact">
    <div>
        <span class="eyebrow">Поставщики</span>
        <h1><?= e($title) ?></h1>
        <p>Название компании обязательно, остальные поля можно заполнить позже.</p>
    </div>
    <a class="btn btn-outline-coffee" href="/suppliers">← К списку</a>
</section>

<section class="form-card">
    <form action="<?= e($action) ?>" method="post" class="needs-validation" novalidate>
        <input type="hidden" name="_token" value="<?= e(\App\Csrf::token()) ?>">
        <div class="mb-4">
            <label class="form-label" for="name">Название компании <span class="required">*</span></label>
            <input class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" id="name" name="name" maxlength="160" value="<?= e($supplier['name'] ?? '') ?>" placeholder="Например, Обжарка Про" required>
            <div class="invalid-feedback"><?= e($errors['name'] ?? 'Название компании обязательно.') ?></div>
        </div>
        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label" for="contact_person">Контактное лицо</label>
                <input class="form-control <?= isset($errors['contact_person']) ? 'is-invalid' : '' ?>" id="contact_person" name="contact_person" maxlength="120" value="<?= e($supplier['contact_person'] ?? '') ?>" placeholder="Анна Смирнова">
                <div class="invalid-feedback"><?= e($errors['contact_person'] ?? '') ?></div>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="phone">Телефон</label>
                <input class="form-control <?= isset($errors['phone']) ? 'is-invalid' : '' ?>" id="phone" type="tel" name="phone" maxlength="40" value="<?= e($supplier['phone'] ?? '') ?>" placeholder="+7 (900) 000-00-00">
                <div class="invalid-feedback"><?= e($errors['phone'] ?? '') ?></div>
            </div>
        </div>
        <div class="form-actions">
            <button class="btn btn-coffee" type="submit">Сохранить</button>
            <a class="btn btn-light" href="/suppliers">Отмена</a>
        </div>
    </form>
</section>

