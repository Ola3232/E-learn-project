<?php
include "PHP/connexion.php";


if (isset($_POST['ajouter'])) {
    $nom = $_POST['titre'];
    $filiere = $_POST['filiere'];
    $annee = $_POST['annee'];
    $document = $_FILES['document']['name'];
    $chemin = "../uploads/$document";
    $date = date('Y-m-d H:i:s');
    $auteur=$_POST['auteur'];

    move_uploaded_file($_FILES['document']['tmp_name'], $chemin);

    //  Correction ici : insertion du nom de fichier seul
    $sql = "INSERT INTO docs (label, annee, code_f, fichier,auteur,date_ajout) VALUES ('$nom', '$annee', '$filiere', '$chemin','$auteur','$date')";
    $resultat = $connexion->query($sql);
    header("Location:courses.php");
    exit();
};


include "modalfiliere.php";

//Récupération des éléments pour insertion de filière 
if(isset($_POST['ajouter_filiere'])){
$code_filiere=$_POST['code_filiere'];
$libelle_filiere=$_POST['libelle_filiere'];

//Insertion filière
$fil="INSERT INTO filiere(code_f,libelle) VALUES ('$code_filiere','$libelle_filiere')";
$connexion->query($fil);
header('location:courses.php');

}

// Récupérer les clients et les adjoints
$clients = $connexion->query("SELECT * FROM e_user WHERE role = 'etudiant' ORDER BY code_f ASC");


// Changer le statut d'un client
if (isset($_GET['toggle_client'])) {
    $id = $_GET['toggle_client'];
    $stmt = $connexion->prepare("UPDATE e_user SET statut = IF(statut = 'actif', 'inactif', 'actif') WHERE id_user = ?");
    $stmt->execute([$id]);
    header("Location: courses.php");
    exit;
}

//Récupération des éléments pour insertion de filière 
if(isset($_POST['ajouter_filiere'])){
    $code_filiere=$_POST['code_filiere'];
    $libelle_filiere=$_POST['libelle_filiere'];
    
    //Insertion filière
    $fil="INSERT INTO filiere(code_f,libelle) VALUES ('$code_filiere','$libelle_filiere')";
    $connexion->query($fil);
    header('Location: courses.php');
}

?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">


<!-- Mirrored from pixelwibes.com/template/e-learn/html/dist/courses.php by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 01 Apr 2025 09:01:16 GMT -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <title>:: e-Learn:: Courses</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon"> <!-- Favicon-->

    <!-- project css file  -->
    <link rel="stylesheet" href="assets/css/e-learn.style.min.css">
    
    <!-- Google Code  -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-264428387-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
    
      gtag('config', 'UA-264428387-1');
    </script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
    <div class="main px-lg-4 px-md-4">

        <!-- Body: Header -->
        <div class="header">
            <nav class="navbar py-4">
                <div class="container-xxl">
    
                    <!-- header rightbar icon -->
                    <div class="h-right d-flex align-items-center mr-5 mr-lg-0 order-1">
                        
                        
                        <div class="dropdown user-profile ml-2 ml-sm-3 d-flex align-items-center zindex-popover">
                            <div class="u-info me-2">
                                <p class="mb-0 text-end line-height-sm "><span class="font-weight-bold">Administrateur système</span></p>
                                <small>Profile Administrateur</small>
                            </div>
                            <a class="nav-link dropdown-toggle pulse p-0" href="#" role="button" data-bs-toggle="dropdown" data-bs-display="static">
                                <img class="avatar lg rounded-circle img-thumbnail" src="assets/images/profile_av.png" alt="profile">
                            </a>
                            <div class="dropdown-menu rounded-lg shadow border-0 dropdown-animation dropdown-menu-end p-0 m-0">
                                <div class="card border-0 w280">
                                    <div class="card-body pb-0">
                                        <div class="d-flex py-1">
                                            <img class="avatar rounded-circle" src="assets/images/profile_av.png" alt="profile">
                                            <div class="flex-fill ms-3">
                                                <p class="mb-0"><span class="font-weight-bold">Administrateur</span></p>
                                            </div>
                                        </div>
                                        <div><hr class="dropdown-divider border-dark"></div>
                                    </div>
                                    <div class="list-group m-2 ">
                                        <a href="ui-elements/auth-signin.php" class="list-group-item list-group-item-action border-0 "><i class="icofont-logout fs-6 me-3"></i>Signout</a>
                                       </div>
                                </div>
                            </div>
                        </div>
                    </div>
    
                    <!-- menu toggler -->
                    <button class="navbar-toggler p-0 border-0 menu-toggle order-3" type="button" data-bs-toggle="collapse" data-bs-target="#mainHeader">
                        <span class="fa fa-bars"></span>
                    </button>
    
                    <!-- main menu Search-->
                    <div class="order-0 col-lg-4 col-md-4 col-sm-12 col-12 mb-3 mb-md-0 ">
                        <div class="input-group flex-nowrap input-group-lg">
                            
                        </div>
                    </div>
    
                </div>
            </nav>
        </div>

        <!-- Body: Contenu principal -->
        <div class="card-header py-3 px-0 d-flex align-items-center justify-content-between border-bottom">
            <h3 class="fw-bold flex-fill">Listes de cours </h3>
            <div class="d-flex gap-2">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ajouterCoursModal">
                    <i class="icofont-plus"></i> Ajouter un cours
                </button>
            </div>
        </div>

        <div class="row g-3 py-3">
    <?php
    $cours = $connexion->query("SELECT * FROM docs ORDER BY annee");
    foreach ($cours as $coursItem):
        $id = $coursItem['id_doc']; // uniformisation
    ?>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($coursItem['label']) ?></h5>
                <p class="card-text"><?= htmlspecialchars($coursItem['code_f']) ?> - <?= htmlspecialchars($coursItem['annee']) ?></p>
                <div class="d-flex justify-content-between">
                    <!-- Bouton Modifier -->
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modifierModal<?= $id ?>">
                        <i class="icofont-edit"></i>
                    </button>

                    <!-- Bouton Supprimer -->
                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDelete<?= $id ?>">
                        <i class="icofont-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de confirmation suppression -->
<div class="modal fade" id="confirmDelete<?= $id ?>" tabindex="-1" aria-labelledby="confirmDeleteLabel<?= $id ?>" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow border-0">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="confirmDeleteLabel<?= $id ?>">Confirmer la suppression</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body text-center">
        <p>Êtes-vous sûr de vouloir supprimer ce cours ? Cette action est irréversible.</p>
        <div class="d-flex justify-content-center gap-2 mt-3">
          <a href="supprimer_cours.php?id=<?= $id ?>" class="btn btn-danger">Oui, supprimer</a>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        </div>
      </div>
    </div>
  </div>
</div>


    <!-- Modal Modification -->
    <div class="modal fade" id="modifierModal<?= $id ?>" tabindex="-1" aria-labelledby="modifierCoursModalLabel<?= $id ?>" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="modifier.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $id ?>">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modifierCoursModalLabel<?= $id ?>">Modifier le cours</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="titre<?= $id ?>" class="form-label">Titre du cours</label>
                            <input type="text" class="form-control" name="titre" id="titre<?= $id ?>" value="<?= htmlspecialchars($coursItem['label']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="annee<?= $id ?>" class="form-label">Année</label>
                            <select class="form-select" name="annee" id="annee<?= $id ?>" required>
                                <?php
                                $options = ['Licence1', 'Licence2', 'Licence3'];
                                foreach ($options as $option):
                                    $val = htmlspecialchars($option);
                                ?>
                                    <option value="<?= $val ?>" <?= $coursItem['annee'] == $val ? 'selected' : '' ?>><?= $val ?></option>
                                <?php endforeach; ?>
                            </select>

                        </div>
                        <div class="mb-3">
                            <label for="filiere<?= $id ?>" class="form-label">Filière</label>
                            <select class="form-select" name="filiere" id="filiere<?= $id ?>" required>
                              <?php
                              $result = $connexion->query("SELECT code_f, libelle FROM filiere");
                              while ($row = $result->fetch_assoc()):
                                  $code_f = htmlspecialchars($row['code_f']);
                                  $libelle = htmlspecialchars($row['libelle']);
                                  $selected = ($coursItem['code_f'] == $code_f) ? 'selected' : '';
                              ?>
                                  <option value="<?= $code_f ?>" <?= $selected ?>><?= $libelle ?></option>
                              <?php endwhile; ?>
                          </select>

                                <div class="mb-3">
                                  <label for="auteur<?= $id ?>" class="form-label">Auteur du cours</label>
                                  <input type="text" class="form-control" name="auteur" id="auteur<?= $id ?>" value="<?= htmlspecialchars($coursItem['auteur']) ?>" required>
                              </div>

                        </div>
                        <div class="mb-3">
                            <label for="fichier<?= $id ?>" class="form-label">Fichier PDF (laisser vide pour ne pas changer)</label>
                            <input type="file" class="form-control" name="fichier" id="fichier<?= $id ?>" accept=".pdf">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

    </div>
</div>

<!-- Modal Ajouter -->
<div class="modal fade" id="ajouterCoursModal" tabindex="-1" aria-labelledby="ajouterCoursModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="courses.php" method="POST" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title" id="ajouterCoursModalLabel">Ajouter un nouveau cours</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="titre" class="form-label">Titre du cours</label>
            <input type="text" class="form-control" name="titre" id="titre" required>
          </div>
          <div class="mb-3">
            <label for="annee" class="form-label">Année</label>
            <select class="form-select" name="annee" id="annee" required>
              <option value="">Sélectionnez une année</option>
              <option value="Licence1">Licence1</option>
              <option value="Licence2">Licence2</option>
              <option value="Licence3">Licence3</option>
            </select>
          </div>
          <div class="col-md-6">
          <div class="modal-form">
         <label class="form-label">Filière</label>
            <select name="filiere" id="" class="form-control" >
                <?php 
                $affi1 = 'SELECT * FROM filiere order by code_f';
                $result1 = $connexion->query($affi1);
                foreach($result1 as $row1) {
                echo "<option value='". $row1['code_f']."'>".$row1['libelle']."</option>";
                }
                ?>
            </select>
                     </div>
                </div>

              <div class="mb-3">
                <label for="auteur" class="form-label">Auteur du cours</label>
                <input type="text" class="form-control" name="auteur" id="auteur" required>
              </div>
                                    
          <div class="mb-3">
            <label for="fichier" class="form-label">Fichier PDF</label>
            <input type="file" class="form-control" name="document" id="fichier" required>
          </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            <button type="submit" class="btn btn-primary" name="ajouter">Ajouter</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Ajouter Filière -->
<div class="modal fade" id="ajouterfilière" tabindex="-1" aria-labelledby="ajouterCoursModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="courses.php" method="POST" enctype="multipart/form-data">
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

<!-- Scripts -->
<script src="assets/bundles/libscripts.bundle.js"></script>
<script src="../js/template.js"></script>

</body>
</html>