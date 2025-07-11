<?php
include "PHP/connexion.php";

// 1. On prépare la requête SQL pour compter les documents
$sql1 = "SELECT COUNT(id_doc) AS nb_documents FROM docs";

// 2. On exécute la requête
$nbCours = $connexion->query($sql1);

// 3. On récupère le résultat
$resultat = $nbCours->fetch_assoc();

// Compter les étudiants 
$sql2 = "SELECT COUNT(id_user) AS nb_etudiant FROM e_user WHERE role='etudiant'";
$nbetu = $connexion->query($sql2);
$resultat1 = $nbetu->fetch_all(MYSQLI_ASSOC);


// Requête SQL : Compter par filière ET par année
$sql3 = "
    SELECT code_f, annee, COUNT(*) AS total
    FROM e_user
    GROUP BY code_f, annee
";

// Exécution
$resultat2 = $connexion->query($sql3);

// Tableau pour stocker les résultats
$filiereData = [];

// Récupération
if ($resultat2 && $resultat2->num_rows > 0) {
    while ($row = $resultat2->fetch_assoc()) {
        $filiereData[] = $row;
    }
} else {
    echo "Aucune filière trouvée.";
}

function afficherAnnee($annee) {
    if ($annee == 1) {
        return '1ʳᵉ';
    } else {
        return $annee . 'ᵉ';
    }
}

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
?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">
<!-- Mirrored from pixelwibes.com/template/e-learn/html/dist/index.php by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 01 Apr 2025 09:00:18 GMT -->
<head>
    <meta charset="utf-8"> 
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <title>:: e-Learn:: Education Dashboard </title>
    <link rel="icon" href="favicon.ico" type="image/x-icon"> <!-- Favicon-->
    <!-- plugin css file  -->
    <link rel="stylesheet" href="assets/css/carousel.min.css" />
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
                                    <form method="post" action="logout.php">
                                       <button type="submit" class="list-group-item list-group-item-action border-0 " name="deconnexion">Se déconnecter</button>
                                     </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- menu toggler -->
                    <button class="navbar-toggler p-0 border-0 menu-toggle order-3" type="button" data-bs-toggle="collapse" data-bs-target="#mainHeader">
                        <span class="fa fa-bars"></span>
                    </button>
                    <div class="order-0 col-lg-4 col-md-4 col-sm-12 col-12 mb-3 mb-md-0 ">
                        
                    </div>
                </div>
            </nav>
        </div>
        <!-- Body: Body -->
        <div class="body d-flex py-lg-3 py-md-2">
            <div class="container-xxl">
                <div class="row clearfix g-3">
                    <div class="col-lg-8 col-md-12 flex-column">
                        <div class="row row-deck g-3">
                            <div class="col-12 col-xl-12 col-lg-12">
                                <div class="card mb-3 color-bg-200">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-12 col-lg-5 order-lg-2">
                                                <div class="text-center p-4">
                                                    <img src="assets/images/study.svg" alt="..." class="img-fluid set-md-img">
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-7 order-lg-1">
                                                <h5 class="fw-bold">Bienvenue sur le tableau de bord</h5>
                                                <p>Nombre total de cours : <strong><?=  $resultat['nb_documents'] ?></strong></p>
                                                <p>Nombre total d'étudiants : <strong><?= $resultat1[0]['nb_etudiant']?></strong></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3 color-bg-200">
                            <div class="card-header py-3">
                                <h6 class="mb-0 fw-bold">Étudiants par filière</h6>
                            </div>
                            <div class="card-body">
                               <ul class="list-group">
                                  <?php foreach($filiereData as $filiere): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                       <?= htmlspecialchars($filiere['code_f']) ?> - <?= afficherAnnee(htmlspecialchars($filiere['annee'])) ?> année
                                        <span class="badge bg-primary rounded-pill"><?= $filiere['total'] ?></span>
                                    </li>
                                  <?php endforeach; ?>
                                </ul>
                             </div>

                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <div class="card mb-3 color-bg-200">
                            <div class="card-body">
                                <div class="daily_practice">
                                    <h6 class="mb-3 fw-bold">Pratique journalière</h6>
                                    <div class="row g-2">
                                        <div class="col-12 col-sm-6 col-md-6 col-lg-12 col-xl-6">
                                            <div class="card bg-lightblue">
                                                <div class="card-body">
                                                    <h6 class="fw-bold mb-0 color-defult">Lecture</h6>
                                                    <small class="color-defult">Cours théoriques</small>
                                                    <div class="duration d-flex align-items-center justify-content-between pt-5">
                                                        <span class="fw-bold color-defult">30Min</span>
                                                        <span class="fw-bold color-careys-pink"><i class="icofont-listening fs-2"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6 col-lg-12 col-xl-6">
                                            <div class="card bg-lightgreen">
                                                <div class="card-body">
                                                    <h6 class="fw-bold mb-0 color-defult">Vidéo</h6>
                                                    <small class="color-defult">Supports visuels</small>
                                                    <div class="duration d-flex align-items-center justify-content-between pt-5">
                                                        <span class="fw-bold color-defult">15Min</span>
                                                        <span class="fw-bold color-careys-pink"><i class="icofont-video-cam fs-2"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="dividers-block"></div>
                                <div class="upcoming-lessons">
                                    <h6 class="mb-3 fw-bold">Leçons à venir</h6>
                                    <div class="card line-lightblue mb-3">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="lesson_name">
                                                    <h6 class="mb-0 fw-bold">Introduction à la cybersécurité</h6>
                                                    <small class="text-muted">Disponible le : 25 avril 2025</small>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mt-5">
                                                <a href="#" class="btn btn-sm btn-primary">Voir plus</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card bg-dark mb-3">
                            <div class="card-body">
                                <div class="card-header py-3">
                                    <h6 class="mb-0 fw-bold text-white">Are you ready next lessons </h6>
                                </div>
                                <div class="digital-clock d-flex justify-content-center align-items-center min-height-220">
                                    <figure>
                                        <div class="face top"><p id="s"></p></div>
                                        <div class="face front"><p id="m"></p></div>
                                        <div class="face left"><p id="h"></p></div>
                                    </figure>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- Row End -->
            </div>
        </div>
    </div>
</div>

<!-- Modal Ajouter Filière -->
<div class="modal fade" id="ajouterfilière" tabindex="-1" aria-labelledby="ajouterCoursModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="index.php" method="POST" enctype="multipart/form-data">
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
<?php
require "modalfiliere.php";
?>

<!-- Jquery Core Js -->
<script src="assets/bundles/libscripts.bundle.js"></script>
<script src="assets/bundles/carousel.bundle.js"></script>
<script src="assets/bundles/apexcharts.bundle.js"></script> 
<script src="../js/template.js"></script>
<script src="../js/page/elearn-index.js"></script>
</body>
</html>
