# Глава 3 Исходный код проекта

## 3.1 Структура приложения

Проект построен по простой схеме, близкой к MVC. Контроллеры принимают запросы, модели работают с базой данных, а файлы из каталога `views` формируют HTML-страницы.

- `public/index.php` содержит маршруты приложения;
- `src/Controllers` содержит обработчики страниц;
- `src/Models` содержит запросы к базе данных;
- `views` содержит шаблоны интерфейса;
- `public/assets` содержит CSS и JavaScript;
- `database/schema.sql` создает базу данных.

## 3.2 Подключение к базе данных

Класс `Database` создает PDO-соединение. Отключение эмуляции подготовленных запросов повышает безопасность работы с MySQL.

```php
$this->pdo = new PDO($this->dsn, $this->username, $this->password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
]);
```

## 3.3 Регистрация пользователя

Перед сохранением проверяются имя, email и длина пароля. Пароль не записывается в открытом виде: функция `password_hash` создает безопасный хеш.

```php
$statement = $pdo->prepare(
    'INSERT INTO users (name, email, password_hash)
     VALUES (:name, :email, :password_hash)'
);

$statement->execute([
    'name' => trim($name),
    'email' => mb_strtolower(trim($email)),
    'password_hash' => password_hash($password, PASSWORD_DEFAULT),
]);
```

## 3.4 Вход и защита личного кабинета

При входе приложение находит пользователя по email и проверяет пароль через `password_verify`. Закрытые страницы вызывают метод `requireLogin`. Если пользователь не вошел, он перенаправляется на `/login`.

```php
public function requireLogin(): void
{
    if (!$this->check()) {
        $_SESSION['flash_error'] = 'Войдите, чтобы открыть личный кабинет.';
        header('Location: /login');
        exit;
    }
}
```

## 3.5 CRUD поставщиков

Все запросы ограничены идентификатором текущего пользователя. Поэтому один пользователь не может изменить или удалить поставщика другого пользователя.

```php
$statement = $this->database->connection()->prepare(
    'UPDATE suppliers
     SET name = :name, contact_person = :contact_person, phone = :phone
     WHERE id = :id AND user_id = :user_id'
);
```

Удаление использует такую же проверку:

```php
$statement = $this->database->connection()->prepare(
    'DELETE FROM suppliers WHERE id = :id AND user_id = :user_id'
);
```

## 3.6 Удаление через Fetch API

Перед удалением JavaScript показывает стандартное окно подтверждения. После согласия браузер отправляет DELETE-запрос с CSRF-токеном.

```javascript
if (!window.confirm(`Точно удалить «${name}»?`)) {
    return;
}

const response = await fetch(`/api/suppliers/${button.dataset.id}`, {
    method: 'DELETE',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({_token: csrf}),
});
```

## 3.7 Проверка введенных данных

На сервере проверяются корректность email, минимальная длина пароля и обязательность названия поставщика. В HTML используются атрибуты `required`, `type="email"`, `minlength` и `maxlength`. Такая двойная проверка помогает пользователю быстрее исправить ошибку и не позволяет сохранить некорректные данные через прямой запрос.

## 3.8 Результат

В приложении реализованы регистрация, вход, выход, защищенный личный кабинет и полный набор CRUD-операций. Использование PDO, CSRF-токена, экранирования HTML и проверки владельца записи закрывает основные риски учебного проекта.
