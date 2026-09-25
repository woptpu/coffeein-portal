<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Auth;
use App\Csrf;
use App\Models\User;
use App\Validator;
use App\View;

final class AuthController
{
    public function __construct(
        private readonly View $view,
        private readonly Auth $auth,
        private readonly User $users
    ) {
    }

    public function showRegister(): void
    {
        if ($this->auth->check()) {
            $this->redirect('/dashboard');
        }
        $this->view->render('auth/register', [
            'title' => 'Регистрация',
            'errors' => [],
            'old' => [],
            'auth' => $this->auth,
        ]);
    }

    public function register(): void
    {
        Csrf::verify($_POST['_token'] ?? null);
        $old = [
            'name' => trim((string) ($_POST['name'] ?? '')),
            'email' => trim((string) ($_POST['email'] ?? '')),
        ];
        $errors = Validator::registration($_POST);

        if ($errors !== []) {
            $this->view->render('auth/register', compact('errors', 'old') + [
                'title' => 'Регистрация',
                'auth' => $this->auth,
            ]);
            return;
        }

        try {
            $user = $this->users->create($old['name'], $old['email'], (string) $_POST['password']);
        } catch (\DomainException $exception) {
            $errors['email'] = $exception->getMessage();
            $this->view->render('auth/register', compact('errors', 'old') + [
                'title' => 'Регистрация',
                'auth' => $this->auth,
            ]);
            return;
        }

        $this->auth->loginUser($user);
        $_SESSION['flash_success'] = 'Аккаунт создан. Добро пожаловать!';
        $this->redirect('/dashboard');
    }

    public function showLogin(): void
    {
        if ($this->auth->check()) {
            $this->redirect('/dashboard');
        }
        $this->view->render('auth/login', [
            'title' => 'Вход',
            'error' => null,
            'email' => '',
            'auth' => $this->auth,
        ]);
    }

    public function login(): void
    {
        Csrf::verify($_POST['_token'] ?? null);
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !$this->auth->attempt($email, $password)) {
            $this->view->render('auth/login', [
                'title' => 'Вход',
                'error' => 'Неверный email или пароль.',
                'email' => $email,
                'auth' => $this->auth,
            ]);
            return;
        }

        $_SESSION['flash_success'] = 'Вы вошли в систему.';
        $this->redirect('/dashboard');
    }

    public function logout(): void
    {
        Csrf::verify($_POST['_token'] ?? null);
        $this->auth->logout();
        header('Location: /login');
        exit;
    }

    private function redirect(string $path): never
    {
        header('Location: ' . $path);
        exit;
    }
}

