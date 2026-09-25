<section class="empty-state standalone">
    <div class="empty-icon">404</div>
    <h1>Страница не найдена</h1>
    <p>Проверьте адрес или вернитесь в личный кабинет.</p>
    <a class="btn btn-coffee" href="<?= $auth->check() ? '/dashboard' : '/login' ?>">На главную</a>
</section>

