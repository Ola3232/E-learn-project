<?php
$successMessage = '';
if (isset($_GET['success'])) {
    switch ($_GET['success']) {
        case 'ajout':
            $successMessage = "Le cours a été ajouté avec succès.";
            break;
        case 'suppression':
            $successMessage = "Le cours a été supprimé avec succès.";
            break;
        case 'inscription':
            $successMessage = "Inscription réussie. Bienvenue !";
            break;
        case 'connexion':
            $successMessage = "Connexion réussie. Bienvenue !";
            break;
        case 'deconnexion':
            $successMessage = "Déconnexion réussie. À bientôt !";
            break;
    }
}

if ($successMessage): ?>
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-success shadow">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="successModalLabel">Succès</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body text-center">
        <?= htmlspecialchars($successMessage) ?>
      </div>
    </div>
  </div>
</div>

<script>
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
    window.addEventListener('load', () => {
        successModal.show();
    });
</script>
<?php endif; ?>
