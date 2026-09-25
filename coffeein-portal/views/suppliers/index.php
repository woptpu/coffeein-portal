<section class="page-hero compact">
    <div>
        <span class="eyebrow">Справочник</span>
        <h1>Поставщики</h1>
        <p>Компании и контактные лица, добавленные текущим пользователем.</p>
    </div>
    <a class="btn btn-coffee" href="/suppliers/create">+ Добавить поставщика</a>
</section>

<section class="data-card">
    <?php if ($suppliers === []): ?>
        <div class="empty-state">
            <div class="empty-icon">＋</div>
            <h2>Список пока пуст</h2>
            <p>Добавьте первого поставщика, чтобы начать работу.</p>
            <a class="btn btn-coffee" href="/suppliers/create">Добавить</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table align-middle supplier-table mb-0">
                <thead>
                <tr>
                    <th>Компания</th>
                    <th>Контактное лицо</th>
                    <th>Телефон</th>
                    <th class="text-end">Действия</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($suppliers as $supplier): ?>
                    <tr id="supplier-<?= (int) $supplier['id'] ?>">
                        <td><strong><?= e($supplier['name']) ?></strong></td>
                        <td><?= e($supplier['contact_person'] ?: '—') ?></td>
                        <td><?= e($supplier['phone'] ?: '—') ?></td>
                        <td class="text-end text-nowrap">
                            <a class="btn btn-sm btn-outline-coffee" href="/suppliers/edit/<?= (int) $supplier['id'] ?>">Редактировать</a>
                            <button class="btn btn-sm btn-outline-danger js-delete-supplier" data-id="<?= (int) $supplier['id'] ?>" data-name="<?= e($supplier['name']) ?>" type="button">Удалить</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

