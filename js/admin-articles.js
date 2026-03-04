document.addEventListener('DOMContentLoaded', function () {
    // --- PARTIE SUPPRESSION ---
    const deleteModalElement = document.getElementById('deleteModal');

    if (deleteModalElement) {
        const bootstrapModal = new bootstrap.Modal(deleteModalElement);
        const modalInputId = document.getElementById('modal-article-id');
        const modalTextName = document.getElementById('modal-article-name');

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

    // --- PARTIE CHANGEMENT DE STATUT ---
    const statusModalElement = document.getElementById('statusModal');

    if (statusModalElement) {
        const bootstrapStatusModal = new bootstrap.Modal(statusModalElement);
        const modalHeader = document.getElementById('modal-status-header');
        const modalTitle = document.getElementById('modal-status-title');
        const modalQuestion = document.getElementById('modal-status-question');
        const modalArticleName = document.getElementById('modal-status-article-name');
        const modalConfirmBtn = document.getElementById('modal-status-confirm');

        document.querySelectorAll('.btn-trigger-status').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const currentStatus = this.getAttribute('data-status');
                const newStatus = this.getAttribute('data-new-status');

                // Configuration selon le nouveau statut
                if (newStatus === 'published') {
                    modalHeader.className = 'modal-header bg-success text-white';
                    modalTitle.innerHTML = '🚀 Publier l\'article';
                    modalQuestion.textContent = 'Voulez-vous publier l\'article :';
                    modalConfirmBtn.className = 'btn btn-success';
                    modalConfirmBtn.innerHTML = '🚀 Publier';
                } else {
                    modalHeader.className = 'modal-header bg-warning';
                    modalTitle.innerHTML = '📝 Mettre en brouillon';
                    modalQuestion.textContent = 'Voulez-vous mettre en brouillon l\'article :';
                    modalConfirmBtn.className = 'btn btn-warning';
                    modalConfirmBtn.innerHTML = '📝 Mettre en brouillon';
                }

                modalArticleName.textContent = name;
                modalConfirmBtn.href = '?action=toggle_status&id=' + id;

                bootstrapStatusModal.show();
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