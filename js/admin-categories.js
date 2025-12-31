document.addEventListener('DOMContentLoaded', function () {
    // --- PARTIE SUPPRESSION ---
    const deleteModalElement = document.getElementById('deleteModal');

    if (deleteModalElement) {
        const bootstrapModal = new bootstrap.Modal(deleteModalElement);
        const modalInputId = document.getElementById('modal-category-id');
        const modalTextName = document.getElementById('modal-category-name');

        document.querySelectorAll('.btn-trigger-delete').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');

                modalInputId.value = id;
                modalTextName.textContent = name;

                bootstrapModal.show();
            });
        });
    }

    // --- PARTIE AUTO-SLUG ---
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');

    if (nameInput && slugInput) {
        nameInput.addEventListener('input', function () {
            // On ne génère le slug que si on est en mode "Ajout" (pas en modification)
            const isAddMode = !window.location.search.includes('edit=');
            if (isAddMode) {
                slugInput.value = this.value
                    .toLowerCase()
                    .normalize("NFD").replace(/[\u0300-\u036f]/g, "") // Enlève les accents
                    .replace(/[^a-z0-9]/g, '-')
                    .replace(/-+/g, '-')
                    .replace(/^-|-$/g, '');
            }
        });
    }
});