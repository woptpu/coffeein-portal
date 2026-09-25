(() => {
    'use strict';

    document.querySelectorAll('.needs-validation').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });

    const csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    document.querySelectorAll('.js-delete-supplier').forEach((button) => {
        button.addEventListener('click', async () => {
            const name = button.dataset.name || 'этого поставщика';
            if (!window.confirm(`Точно удалить «${name}»?`)) {
                return;
            }

            button.disabled = true;
            try {
                const response = await fetch(`/api/suppliers/${button.dataset.id}`, {
                    method: 'DELETE',
                    headers: {'Content-Type': 'application/json', 'Accept': 'application/json'},
                    body: JSON.stringify({_token: csrf}),
                });
                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.message || 'Не удалось удалить запись.');
                }
                document.getElementById(`supplier-${button.dataset.id}`)?.remove();
            } catch (error) {
                window.alert(error.message);
                button.disabled = false;
            }
        });
    });
})();

