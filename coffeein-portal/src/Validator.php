<?php
declare(strict_types=1);

namespace App;

final class Validator
{
    public static function registration(array $data): array
    {
        $errors = [];
        if (trim((string) ($data['name'] ?? '')) === '') {
            $errors['name'] = 'Укажите имя.';
        }
        if (!filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Укажите корректный email.';
        }
        if (mb_strlen((string) ($data['password'] ?? '')) < 6) {
            $errors['password'] = 'Пароль должен содержать не менее 6 символов.';
        }
        return $errors;
    }

    public static function supplier(array $data): array
    {
        $errors = [];
        if (trim((string) ($data['name'] ?? '')) === '') {
            $errors['name'] = 'Название компании обязательно.';
        }
        if (mb_strlen((string) ($data['name'] ?? '')) > 160) {
            $errors['name'] = 'Название не должно превышать 160 символов.';
        }
        if (mb_strlen((string) ($data['contact_person'] ?? '')) > 120) {
            $errors['contact_person'] = 'Имя контакта не должно превышать 120 символов.';
        }
        if (mb_strlen((string) ($data['phone'] ?? '')) > 40) {
            $errors['phone'] = 'Телефон не должен превышать 40 символов.';
        }
        return $errors;
    }
}

