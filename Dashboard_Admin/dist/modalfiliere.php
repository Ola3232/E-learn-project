<?php
include "PHP/connexion.php";
include "logout.php";

// Récupérer les filières
$filiere = $connexion->query("SELECT * FROM filiere ");

//Récupération des éléments pour insertion de filière 
if(isset($_POST['ajouter_filiere'])){
    $code_filiere=$_POST['code_filiere'];
    $libelle_filiere=$_POST['libelle_filiere'];
    
    //Insertion filière
    $fil="INSERT INTO filiere(code_f,libelle) VALUES ('$code_filiere','$libelle_filiere')";
    $connexion->query($fil);
    header('location:index.php');
}
// Modification des filières
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier_filiere'])) {
    $ancien_code = mysqli_real_escape_string($connexion, $_POST['ancien_code_filiere']);
    $nouveau_code = mysqli_real_escape_string($connexion, $_POST['nouveau_code_filiere']);
    $libelle = mysqli_real_escape_string($connexion, $_POST['libelle_filiere']);

    $sql = "UPDATE filiere SET code_f = '$nouveau_code', libelle = '$libelle' WHERE code_f = '$ancien_code'";
    if ($connexion->query($sql)) {
        header("location:index.php");
        exit();
    } else {
        echo "Erreur : " . $connexion->error;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['supprimer_filiere'])) {
    $code = mysqli_real_escape_string($connexion, $_GET['supprimer_filiere']);

    $sql = "DELETE FROM filiere WHERE code_f = '$code'";
    if ($connexion->query($sql)) {
        header("location:index.php");
        exit();
    } else {
        echo "Erreur lors de la suppression : " . $connexion->error;
    }
}
?>
<!-- Modal Filières -->
<div class="modal fade" id="modalListeFilieres" tabindex="-1" aria-labelledby="modalListeFilieresLabel" aria-hidden="true">
  <div class="modal-dialog custom-modal-width modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content shadow-lg rounded-4 border-0">
      <div class="modal-header bg-success text-white rounded-top-4">
        <h4 class="modal-title fw-bold" id="modalListeFilieresLabel">📚 Liste complète des filières</h4>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body bg-light" style="max-height: 70vh; overflow-y: auto;">
        <table class="table table-striped table-bordered table-hover rounded shadow-sm bg-white">
          <thead class="table-success">
            <tr>
              <th>Code filière</th>
              <th>Libellé</th>
              <th class="text-center">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($filiere as $index => $client): ?>
              <tr>
                <td><?= htmlspecialchars($client['code_f']) ?></td>
                <td><?= htmlspecialchars($client['libelle']) ?></td>
                <td class="text-center">
                  <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modifierModal<?= $index ?>">
                    ✏️
                  </button>
                  <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $index ?>">
                    🗑️
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php foreach ($filiere as $index => $client): ?>
    <!-- Modal Modifier -->
    <div class="modal fade" id="modifierModal<?= $index ?>" tabindex="-1" aria-labelledby="modifierModalLabel<?= $index ?>" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="modalfiliere.php" class="modal-content bg-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="modifierModalLabel<?= $index ?>">Modifier la filière</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="ancien_code_filiere" value="<?= $client['code_f'] ?>">
                    <div class="mb-3">
                        <label class="form-label">Nouveau code filière</label>
                        <input type="text" class="form-control" name="nouveau_code_filiere" value="<?= htmlspecialchars($client['code_f']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Libellé</label>
                        <input type="text" class="form-control" name="libelle_filiere" value="<?= htmlspecialchars($client['libelle']) ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success" name="modifier_filiere">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Supprimer -->
    <div class="modal fade" id="deleteModal<?= $index ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?= $index ?>" aria-hidden="true">
        <div class="modal-dialog">
            <form method="GET" action="modalfiliere.php" class="modal-content bg-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel<?= $index ?>">Confirmer la suppression</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    Voulez-vous vraiment supprimer la filière <strong><?= htmlspecialchars($client['libelle']) ?></strong> ?
                    <input type="hidden" name="supprimer_filiere" value="<?= $client['code_f'] ?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </div>
            </form>
        </div>
    </div>
<?php endforeach; ?>

<!-- Modal de confirmation -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow rounded-3">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="logoutModalLabel">Confirmation</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        Voulez-vous vraiment vous déconnecter ?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <form method="post" action="logout.php" class="m-0">
          <button type="submit" name="deconnexion" class="btn btn-danger">Confirmer</button>
        </form>
      </div>
    </div>
  </div>
</div>