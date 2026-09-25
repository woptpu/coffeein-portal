<?php
declare(strict_types=1);

namespace App\Models;

use App\Database;
use PDOException;

final class User
{
    public function __construct(private readonly Database $database)
    {
    }

    public function findByEmail(string $email): ?array
    {
        $statement = $this->database->connection()->prepare(
            'SELECT id, name, email, password_hash FROM users WHERE email = :email LIMIT 1'
        );
        $statement->execute(['email' => mb_strtolower(trim($email))]);
        $user = $statement->fetch();
        return $user === false ? null : $user;
    }

    public function create(string $name, string $email, string $password): array
    {
        $pdo = $this->database->connection();
        $statement = $pdo->prepare(
            'INSERT INTO users (name, email, password_hash) VALUES (:name, :email, :password_hash)'
        );
        try {
            $statement->execute([
                'name' => trim($name),
                'email' => mb_strtolower(trim($email)),
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            ]);
        } catch (PDOException $exception) {
            if ((string) $exception->getCode() === '23000') {
                throw new \DomainException('Пользователь с таким email уже зарегистрирован.');
            }
            throw $exception;
        }

        return [
            'id' => (int) $pdo->lastInsertId(),
            'name' => trim($name),
        ];
    }
}

