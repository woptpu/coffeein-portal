<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Auth;
use App\Csrf;
use App\Models\Supplier;
use App\Validator;
use App\View;

final class SupplierController
{
    public function __construct(
        private readonly View $view,
        private readonly Auth $auth,
        private readonly Supplier $suppliers
    ) {
    }

    public function index(): void
    {
        $this->auth->requireLogin();
        $this->view->render('suppliers/index', [
            'title' => 'Поставщики',
            'auth' => $this->auth,
            'suppliers' => $this->suppliers->allForUser($this->auth->id()),
        ]);
    }

    public function create(): void
    {
        $this->auth->requireLogin();
        $this->renderForm('Добавить поставщика', '/suppliers/create', [], []);
    }

    public function store(): void
    {
        $this->auth->requireLogin();
        Csrf::verify($_POST['_token'] ?? null);
        $data = $this->supplierData();
        $errors = Validator::supplier($data);

        if ($errors !== []) {
            $this->renderForm('Добавить поставщика', '/suppliers/create', $data, $errors);
            return;
        }

        $this->suppliers->create($data, $this->auth->id());
        $_SESSION['flash_success'] = 'Поставщик добавлен.';
        $this->redirect('/suppliers');
    }

    public function edit(int $id): void
    {
        $this->auth->requireLogin();
        $supplier = $this->ownedOrFail($id);
        $this->renderForm('Редактировать поставщика', '/suppliers/edit/' . $id, $supplier, []);
    }

    public function update(int $id): void
    {
        $this->auth->requireLogin();
        Csrf::verify($_POST['_token'] ?? null);
        $this->ownedOrFail($id);
        $data = $this->supplierData();
        $errors = Validator::supplier($data);

        if ($errors !== []) {
            $this->renderForm('Редактировать поставщика', '/suppliers/edit/' . $id, $data, $errors);
            return;
        }

        $this->suppliers->updateOwned($id, $data, $this->auth->id());
        $_SESSION['flash_success'] = 'Данные поставщика обновлены.';
        $this->redirect('/suppliers');
    }

    public function destroy(int $id): void
    {
        $this->auth->requireLogin();
        $payload = json_decode(file_get_contents('php://input') ?: '{}', true);
        Csrf::verify(is_array($payload) ? ($payload['_token'] ?? null) : null);
        $deleted = $this->suppliers->deleteOwned($id, $this->auth->id());

        header('Content-Type: application/json; charset=utf-8');
        if (!$deleted) {
            http_response_code(404);
            echo json_encode(['message' => 'Поставщик не найден.'], JSON_UNESCAPED_UNICODE);
            return;
        }
        echo json_encode(['message' => 'Поставщик удален.'], JSON_UNESCAPED_UNICODE);
    }

    private function ownedOrFail(int $id): array
    {
        $supplier = $this->suppliers->findOwned($id, $this->auth->id());
        if ($supplier === null) {
            http_response_code(404);
            exit('Поставщик не найден или принадлежит другому пользователю.');
        }
        return $supplier;
    }

    private function supplierData(): array
    {
        return [
            'name' => trim((string) ($_POST['name'] ?? '')),
            'contact_person' => trim((string) ($_POST['contact_person'] ?? '')),
            'phone' => trim((string) ($_POST['phone'] ?? '')),
        ];
    }

    private function renderForm(string $title, string $action, array $supplier, array $errors): void
    {
        $this->view->render('suppliers/form', compact('title', 'action', 'supplier', 'errors') + [
            'auth' => $this->auth,
        ]);
    }

    private function redirect(string $path): never
    {
        header('Location: ' . $path);
        exit;
    }
}

