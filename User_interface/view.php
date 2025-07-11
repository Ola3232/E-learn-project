<?php
// Sécurité et lecture du nom du fichier PDF
$file = isset($_GET['file']) ? htmlspecialchars($_GET['file']) : '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecture du document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
    <!-- Titre -->
    <h1 class="mb-4">Lecture du document</h1>

    <!-- Section du document PDF réel -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white d-flex align-items-center">
            <i class="bi bi-file-earmark-text-fill me-2"></i>
            <h5 class="mb-0">Nom du document : <strong><?php echo basename($file); ?></strong></h5>
        </div>
        <div class="card-body p-0">
            <?php if ($file && file_exists("uploads/" . $file)): ?>
                <iframe src="<?php echo 'uploads/' . $file; ?>" width="100%" height="600px" style="border: none;"></iframe>
            <?php else: ?>
                <div class="p-4 text-danger">Fichier introuvable ou non spécifié.</div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Section Commentaires -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>Commentaires</h5>
        </div>
        <div class="card-body">
            <!-- Formulaire d'ajout de commentaire -->
            <form class="mb-3" method="POST">
                <div class="mb-3">
                    <label for="commentaire" class="form-label">Ajouter un commentaire :</label>
                    <textarea class="form-control" name="commentaire" id="commentaire" rows="3" placeholder="Écris ton commentaire ici..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Publier</button>
            </form>

            <!-- Liste des commentaires (à intégrer dynamiquement avec PHP) -->
            <div class="list-group">
                <!-- Exemple statique -->
                <div class="list-group-item">
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">Jean Dupont</h6>
                        <small>il y a 10 minutes</small>
                    </div>
                    <p class="mb-1">Très bon document, merci beaucoup !</p>
                    <div>
                        <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalSignaler">Signaler</button>
                        <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modalSupprimer">Supprimer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
<!-- Modal Signaler -->
<div class="modal fade" id="modalSignaler" tabindex="-1" aria-labelledby="modalSignalerLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title" id="modalSignalerLabel">Signaler un commentaire</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        Es-tu sûr(e) de vouloir signaler ce commentaire ?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-danger">Confirmer</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Supprimer -->
<div class="modal fade" id="modalSupprimer" tabindex="-1" aria-labelledby="modalSupprimerLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="modalSupprimerLabel">Supprimer ton commentaire</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        Es-tu sûr(e) de vouloir supprimer ton commentaire ? Cette action est irréversible.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-danger">Supprimer</button>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
