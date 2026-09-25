<?php
declare(strict_types=1);

namespace App;

final class View
{
    public function __construct(private readonly string $basePath)
    {
    }

    public function render(string $template, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        $contentTemplate = $this->basePath . '/' . $template . '.php';
        if (!is_file($contentTemplate)) {
            throw new \RuntimeException('Шаблон не найден: ' . $template);
        }
        require $this->basePath . '/layout.php';
    }
}

