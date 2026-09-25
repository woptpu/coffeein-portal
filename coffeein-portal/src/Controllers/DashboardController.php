<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Auth;
use App\Models\Supplier;
use App\View;

final class DashboardController
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
        $this->view->render('dashboard', [
            'title' => 'Личный кабинет',
            'auth' => $this->auth,
            'supplierCount' => $this->suppliers->countForUser($this->auth->id()),
        ]);
    }
}

