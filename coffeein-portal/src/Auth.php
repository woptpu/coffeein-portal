<?php
declare(strict_types=1);

namespace App;

use App\Models\User;

final class Auth
{
    private User $users;

    public function __construct(Database $database)
    {
        $this->users = new User($database);
    }

    public function attempt(string $email, string $password): bool
    {
        $user = $this->users->findByEmail($email);
        if ($user === null || !password_verify($password, $user['password_hash'])) {
            return false;
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['user_name'] = $user['name'];
        return true;
    }

    public function loginUser(array $user): void
    {
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['user_name'] = $user['name'];
    }

    public function check(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public function id(): int
    {
        return (int) ($_SESSION['user_id'] ?? 0);
    }

    public function name(): string
    {
        return (string) ($_SESSION['user_name'] ?? '');
    }

    public function requireLogin(): void
    {
        if (!$this->check()) {
            $_SESSION['flash_error'] = 'Войдите, чтобы открыть личный кабинет.';
            header('Location: /login');
            exit;
        }
    }

    public function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
    }
}

