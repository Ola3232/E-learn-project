<?php
include "PHP/connexion.php";
require "modalfiliere.php";

//Récupération des éléments pour insertion de filière 
if(isset($_POST['ajouter_filiere'])){
    $code_filiere=$_POST['code_filiere'];
    $libelle_filiere=$_POST['libelle_filiere'];
    $date_insertion=date('Y-m-d H:i:s'); // récupère la date et l'heure actuelles
    
    //Insertion filière
    $fil="INSERT INTO filiere(code_f,libelle) VALUES ('$code_filiere','$libelle_filiere')";
    $connexion->query($fil);
    header('location:index.php');
}
// Récupérer les clients et les adjoints
$clients = $connexion->query("SELECT * FROM e_user WHERE role = 'etudiant' ORDER BY code_f ASC");


// Changer le statut d'un client
if (isset($_GET['toggle_client'])) {
    $id = $_GET['toggle_client'];
    $stmt = $connexion->prepare("UPDATE e_user SET statut = IF(statut = 'actif', 'inactif', 'actif') WHERE id_user = ?");
    $stmt->execute([$id]);
    header("location:index.php");
    exit;
}

// Récupération des éléments pour insertion de filière 
if(isset($_POST['ajouter_filiere'])){
    $code_filiere = $_POST['code_filiere'];
    $libelle_filiere = $_POST['libelle_filiere'];

    $stmt = $connexion->prepare("INSERT INTO filiere(code_f, libelle) VALUES (?, ?)");
    $stmt->execute([$code_filiere, $libelle_filiere]);

    header("location:index.php");
    exit;
}

// Modification des filières
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier_filiere'])) {
    $ancien_code = mysqli_real_escape_string($connexion, $_POST['ancien_code_filiere']);
    $nouveau_code = mysqli_real_escape_string($connexion, $_POST['nouveau_code_filiere']);
    $libelle = mysqli_real_escape_string($connexion, $_POST['libelle_filiere']);

    $sql = "UPDATE filiere SET code_f = '$nouveau_code', libelle = '$libelle' WHERE code_f = '$ancien_code'";
    if ($connexion->query($sql)) {
        header("Location: filiere.php?modification=ok");
        exit();
    } else {
        echo "Erreur : " . $connexion->error;
    }
}
// Suopression filière 
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['supprimer_filiere'])) {
    $code = mysqli_real_escape_string($connexion, $_GET['supprimer_filiere']);

    $sql = "DELETE FROM filiere WHERE code_f = '$code'";
    if ($connexion->query($sql)) {
        header("Location: filiere.php?suppression=ok");
        exit();
    } else {    
        echo "Erreur lors de la suppression : " . $connexion->error;
    }
}
//Réccupérer les commentaires 
$sqlI = "SELECT id_commentaire, commentaire, nom FROM commentaires WHERE statut = 1 ORDER BY date_creation DESC";
$result = $connexion->query($sqlI);

// Supprimer commentaires 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);


    $stmt = $connexion->prepare("UPDATE  commentaires SET statut = 0 WHERE id_commentaire = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }

    $stmt->close();
    $connexion->close();
} else {
    echo "invalid";
}
?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <title>:: e-Learn:: Chat</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon"> <!-- Favicon-->

    <link rel="stylesheet" href="assets/css/e-learn.style.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-264428387-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
    
      gtag('config', 'UA-264428387-1');
    </script>
    <style>
.custom-modal-width {
  max-width: 60%; /* ou fixe, ex: 1000px */
}
</style>
</head>

<body>

<div id="elearn-layout" class="theme-purple">
    <!-- sidebar -->
    <div class="sidebar px-4 py-4 py-md-4  me-0">
        <div class="d-flex flex-column h-100">
            <a href="index.php" class="mb-0 brand-icon">
                <span class="logo-icon">
                    <svg  width="35" height="35" fill="currentColor" class="bi bi-app-indicator" viewBox="0 0 16 16">
                        <path d="M5.5 2A3.5 3.5 0 0 0 2 5.5v5A3.5 3.5 0 0 0 5.5 14h5a3.5 3.5 0 0 0 3.5-3.5V8a.5.5 0 0 1 1 0v2.5a4.5 4.5 0 0 1-4.5 4.5h-5A4.5 4.5 0 0 1 1 10.5v-5A4.5 4.5 0 0 1 5.5 1H8a.5.5 0 0 1 0 1H5.5z"/>
                        <path d="M16 3a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                    </svg>
                </span>
                <span class="logo-text">e-Learn</span>
            </a>
            <!-- Menu: main ul -->
            <ul class="menu-list flex-grow-1 mt-3">
                <li><a class="m-link" href="index.php"><i class="icofont-ui-home"></i><span>Tableau de bord</span></a></li>
                <li class="collapsed"><a class="m-link" data-bs-toggle="collapse" data-bs-target="#corses-Components" href="#"><i class="icofont-certificate"></i> <span>Cours</span><span class="arrow icofont-dotted-down ms-auto text-end fs-5"></span></a>
                    <ul class="sub-menu collapse" id="corses-Components">
                        <li><a class="ms-link" href="courses.php"><span>Cours</span></a></li>
                    </ul>
                </li>
                <li><a class="m-link"data-bs-toggle="modal" data-bs-target="#modalListeEtudiants"><i class="icofont-group-students"></i><span>Etudiant</span></a></li>
                <li><a class="m-link" data-bs-toggle="modal" data-bs-target="#ajouterfilière"><i class="icofont-cloud-upload"></i><span>Insertion filière</span></a></li>
                <li><a class="m-link" data-bs-toggle="modal" data-bs-target="#modalListeFilieres"><i class="icofont-file-document"></i> <span>Toutes les Filières</span></a></li>
                <li><a class="m-link" href="chat.php"><i class="icofont-ui-text-chat"></i> <span>Messages signalés</span></a></li>
            </ul>
            <!-- Theme: Switch Theme -->
            <ul class="list-unstyled mb-0">
                <li class="d-flex align-items-center justify-content-center">
                    <div class="form-check form-switch theme-switch">
                        <input class="form-check-input" type="checkbox" id="theme-switch">
                        <label class="form-check-label" for="theme-switch">Mode sombre!</label>
                    </div>
                </li>
            </ul>
            <!-- Menu: menu collepce btn -->
            <button type="button" class="btn btn-link sidebar-mini-btn text-light">
                <span class="ms-2"><i class="icofont-bubble-right"></i></span>
            </button>
        </div>
    </div>

    <!-- main body area -->
    <div class="main">
    <div class="body d-flex">
        <div class="container-xxl p-0 m-5">
            <div style="height: 800px;width: 90%;border: 1px solid black;border-radius: 30px;padding: 20px;">
                <h4>Messages signalés</h4>
                <div class="message-box" style="max-height: 600px; overflow-y: auto;">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="message-item d-flex justify-content-between align-items-center py-2 px-3 mb-3 border rounded" id="comment-<?= $row['id_commentaire'] ?>">
                            <div>
                                <strong><?= htmlspecialchars($row['nom']) ?>:</strong> <?= htmlspecialchars($row['commentaire']) ?>
                            </div>
                            <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $row['id_commentaire'] ?>)">Supprimer</button>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>
</div>  
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer ce message ? Cette action est irréversible.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteButton">Supprimer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ajouter Filière -->
<div class="modal fade" id="ajouterfilière" tabindex="-1" aria-labelledby="ajouterCoursModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="chat.php" method="POST" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title" id="ajouterCoursModalLabel">Ajouter une filière</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="code_filière" class="form-label">Code filière</label>
            <input type="text" class="form-control" name="code_filiere" id="code_filière" required>
          </div>
          <div class="mb-3">
            <label for="label_filière" class="form-label">Label </label>
            <input type="text" class="form-control" name="libelle_filiere" id="label_filière" required>
          </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            <button type="submit" class="btn btn-success" name="ajouter_filiere">Ajouter</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal étudiant -->
<div class="modal fade" id="modalListeEtudiants" tabindex="-1" aria-labelledby="modalListeEtudiantsLabel" aria-hidden="true">
<div class="modal-dialog custom-modal-width modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content shadow-lg rounded-4 border-0">
      <div class="modal-header bg-primary text-white rounded-top-4">
        <h4 class="modal-title fw-bold" id="modalListeEtudiantsLabel">👨‍🎓 Liste complète des étudiants</h4>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body bg-light" style="max-height: 70vh; overflow-y: auto;">
        <table class="table table-striped table-bordered table-hover rounded shadow-sm bg-white">
          <thead class="table-primary">
            <tr>
              <th>Étudiant</th>
              <th>Email</th>
              <th>Filière</th>
              <th>Année</th>
              <th>Date inscription</th>
              <th>Statut</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($clients as $client): ?>
              <tr>
                <td><?= htmlspecialchars($client['prenom'] . ' ' . $client['nom']) ?></td>
                <td><?= htmlspecialchars($client['email']) ?></td>
                <td><?= htmlspecialchars($client['code_f']) ?></td>
                <td><?= htmlspecialchars($client['annee']) ?></td>
                <td><?= htmlspecialchars($client['date_inscription']) ?></td>
                <td><?= htmlspecialchars($client['statut']) ?></td>
                <td>
                  <a href="?toggle_client=<?= $client['id_user'] ?>" class="btn btn-sm btn-outline-warning">
                    🔁 Activer/Désactiver
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Jquery Core Js -->
<script src="assets/bundles/libscripts.bundle.js"></script>

<!-- Jquery Page Js -->
<script src="../js/template.js"></script>

<script>

    function confirmDelete(commentId) {
    if (confirm("Êtes-vous sûr de vouloir masquer ce commentaire ?")) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "masquer_commentaire.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onload = function () {
            if (this.responseText.trim() === "success") {
                document.getElementById("comment-" + commentId).remove();
            } else {
                alert("Erreur lors de la mise à jour.");
            }
        };
        xhr.send("id=" + commentId);
    }
}
</script>

</body>

</html>
