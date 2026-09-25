<section class="page-hero">
    <div>
        <span class="eyebrow">Личный кабинет</span>
        <h1>Здравствуйте, <?= e($auth->name()) ?>!</h1>
        <p>Управляйте базой поставщиков и быстро находите нужные контакты.</p>
    </div>
    <a class="btn btn-coffee" href="/suppliers/create">+ Добавить поставщика</a>
</section>

<div class="dashboard-grid">
    <article class="metric-card">
        <span class="metric-label">Мои поставщики</span>
        <strong><?= (int) $supplierCount ?></strong>
        <a href="/suppliers">Открыть список →</a>
    </article>
    <article class="info-card">
        <h2>Работа с данными</h2>
        <p>Каждая запись связана с вашим аккаунтом. Просмотр, изменение и удаление выполняются только после проверки владельца.</p>
    </article>
</div>

