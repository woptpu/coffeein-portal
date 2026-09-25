<?php
declare(strict_types=1);

namespace App\Models;

use App\Database;

final class Supplier
{
    public function __construct(private readonly Database $database)
    {
    }

    public function allForUser(int $userId): array
    {
        $statement = $this->database->connection()->prepare(
            'SELECT id, name, contact_person, phone, created_at FROM suppliers WHERE user_id = :user_id ORDER BY id DESC'
        );
        $statement->execute(['user_id' => $userId]);
        return $statement->fetchAll();
    }

    public function findOwned(int $id, int $userId): ?array
    {
        $statement = $this->database->connection()->prepare(
            'SELECT id, name, contact_person, phone FROM suppliers WHERE id = :id AND user_id = :user_id LIMIT 1'
        );
        $statement->execute(['id' => $id, 'user_id' => $userId]);
        $supplier = $statement->fetch();
        return $supplier === false ? null : $supplier;
    }

    public function create(array $data, int $userId): void
    {
        $statement = $this->database->connection()->prepare(
            'INSERT INTO suppliers (name, contact_person, phone, user_id) VALUES (:name, :contact_person, :phone, :user_id)'
        );
        $statement->execute([
            'name' => trim($data['name']),
            'contact_person' => self::nullable($data['contact_person'] ?? ''),
            'phone' => self::nullable($data['phone'] ?? ''),
            'user_id' => $userId,
        ]);
    }

    public function updateOwned(int $id, array $data, int $userId): bool
    {
        $statement = $this->database->connection()->prepare(
            'UPDATE suppliers SET name = :name, contact_person = :contact_person, phone = :phone WHERE id = :id AND user_id = :user_id'
        );
        $statement->execute([
            'name' => trim($data['name']),
            'contact_person' => self::nullable($data['contact_person'] ?? ''),
            'phone' => self::nullable($data['phone'] ?? ''),
            'id' => $id,
            'user_id' => $userId,
        ]);
        return $statement->rowCount() > 0;
    }

    public function deleteOwned(int $id, int $userId): bool
    {
        $statement = $this->database->connection()->prepare(
            'DELETE FROM suppliers WHERE id = :id AND user_id = :user_id'
        );
        $statement->execute(['id' => $id, 'user_id' => $userId]);
        return $statement->rowCount() > 0;
    }

    public function countForUser(int $userId): int
    {
        $statement = $this->database->connection()->prepare(
            'SELECT COUNT(*) FROM suppliers WHERE user_id = :user_id'
        );
        $statement->execute(['user_id' => $userId]);
        return (int) $statement->fetchColumn();
    }

    private static function nullable(string $value): ?string
    {
        $value = trim($value);
        return $value === '' ? null : $value;
    }
}

