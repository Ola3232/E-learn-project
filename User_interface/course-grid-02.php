<?php
require 'connexion.php';
session_start();

if (!isset($_SESSION['user']) || !isset($_SESSION['email'])||!isset($_SESSION['nom'])) {
    header("Location: index-university.php");
    exit();   
}

$email = $_SESSION['email'];
// $nomUtilisateur = $_SESSION['nom']." ".$_SESSION['prenom'];

// Récupération des infos de l'utilisateur
$sql = "SELECT code_f, annee,email,id_user,prenom FROM e_user WHERE email = ?";
$stmt = $connexion->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    die("Utilisateur non trouvé.");
}

$codeF = $user['code_f'];
$annee = $user['annee'];
$id_user=$user['id_user'];
$nomUtilisateur = $_SESSION['nom']." ".$user['prenom'];;

// Récupération des documents
$sqlDocs = "SELECT * FROM docs WHERE code_f = ? AND annee = ?";
$stmtDocs = $connexion->prepare($sqlDocs);
$stmtDocs->bind_param("ss", $codeF, $annee);
$stmtDocs->execute();
$resultDocs = $stmtDocs->get_result();
$stmtDocs->close();

// Récupération de l'identifiant du document via code_f + annee
$sqlDocs = "SELECT id_doc FROM docs WHERE code_f = ? AND annee = ?";
$stmtDocsid = $connexion->prepare($sqlDocs);
$stmtDocsid->bind_param("ss", $codeF, $annee);
$stmtDocsid->execute();
$doc = $stmtDocsid->get_result()->fetch_assoc();
$stmtDocsid->close();


$document_id = $doc['id_doc'];

// Insertion d'un commentaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['commentaire']) && !isset($_POST['supp'])) {
    $commentaire = htmlspecialchars($_POST['commentaire']);
   

    $sqlInsert = "INSERT INTO commentaires (nom, commentaire, id_doc,id_user) VALUES (?, ?, ?,?)";
    $stmtInsert = $connexion->prepare($sqlInsert);
    $stmtInsert->bind_param("ssii", $nomUtilisateur, $commentaire, $document_id,$id_user);
    $stmtInsert->execute();
    $stmtInsert->close();

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
}

// Suppression d'un commentaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supp'])) {
    $commentaire_id = intval($_POST['commentaire_id']);

    if ($user['email'] === $email) {
        $sqlDelete = "DELETE FROM commentaires WHERE id_commentaire = ?";
        $stmtDelete = $connexion->prepare($sqlDelete);
        $stmtDelete->bind_param("i", $commentaire_id);
        $stmtDelete->execute();
        $stmtDelete->close();

        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    } else {
        echo "<script>alert('Vous n\\'êtes pas autorisé à supprimer ce commentaire.');</script>";
    }
}

// Récupération des commentaires
$sqlCommentaires = "SELECT id_commentaire, nom, commentaire, date_creation FROM commentaires WHERE id_doc = ? ORDER BY date_creation DESC";
$stmtCommentaires = $connexion->prepare($sqlCommentaires);
$stmtCommentaires->bind_param("i", $document_id);
$stmtCommentaires->execute();
$resultCommentaires = $stmtCommentaires->get_result();
$stmtCommentaires->close();

?>
<!DOCTYPE html>
<html class="no-js" lang="en">


<!-- Mirrored from htmldemo.net/edumall/edumall/course-grid-02.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 01 Apr 2025 08:32:31 GMT -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-Learn</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="###">

    <!-- CSS (Font, Vendor, Icon, Plugins & Style CSS files) -->

    <!-- Font CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400&amp;display=swap" rel="stylesheet">

    <!-- Vendor CSS (Bootstrap & Icon Font) -->
    <link rel="stylesheet" href="assets/css/vendor/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/css/vendor/edumall-icon.css">
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">

    <!-- Plugins CSS (All Plugins Files) -->
    <link rel="stylesheet" href="assets/css/plugins/aos.css">
    <link rel="stylesheet" href="assets/css/plugins/swiper-bundle.min.css">
    <link rel="stylesheet" href="assets/css/plugins/perfect-scrollbar.css">
    <link rel="stylesheet" href="assets/css/plugins/jquery.powertip.min.css">
    <link rel="stylesheet" href="assets/css/plugins/glightbox.min.css">
    <link rel="stylesheet" href="assets/css/plugins/flatpickr.min.css">
    <link rel="stylesheet" href="assets/css/plugins/ion.rangeSlider.min.css">
    <link rel="stylesheet" href="assets/css/plugins/select2.min.css">
    
    <!-- Style CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .btn-logout {
          background-color: #ff4d4f;
          color: white;
          border: none;
          padding: 12px 24px;
          font-size: 16px;
          border-radius: 8px;
          cursor: pointer;
          transition: background-color 0.3s ease, transform 0.2s ease;
          box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
    
        .btn-logout:hover {
          background-color: #e04344;
          transform: scale(1.05);
        }
    
        .btn-logout:active {
          transform: scale(0.98);
        }

        .card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-radius: 1rem;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
}

.card .card-title {
    font-size: 1.1rem;
    font-weight: 600;
}

.card .btn {
    border-radius: 0.5rem;
}
.modal-fullscreen .modal-content {
  height: 100%;
  width: 100%;
  max-width: 100%;
  border-radius: 0;
}

.modal-fullscreen .modal-body {
  height: calc(100vh - 56px); /* 56px = hauteur de l'en-tête (modal-header) */
  overflow-y: auto;
}

#iframeViewer {
  height: 100%;
}

      </style>
<!-- Bootstrap CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

    <main class="main-wrapper">

        <!-- Header start -->
        <div class="header-section header-sticky">

            <!-- Header Main Start -->
            <div class="header-main">
                <div class="container">
                    <!-- Header Main Wrapper Start -->
                    <div class="header-main-wrapper">

                        <!-- Header Logo Start -->
                        <span class="logo-icon">
                            <svg  width="35" height="35" fill="currentColor" class="bi bi-app-indicator" viewBox="0 0 16 16">
                                <path d="M5.5 2A3.5 3.5 0 0 0 2 5.5v5A3.5 3.5 0 0 0 5.5 14h5a3.5 3.5 0 0 0 3.5-3.5V8a.5.5 0 0 1 1 0v2.5a4.5 4.5 0 0 1-4.5 4.5h-5A4.5 4.5 0 0 1 1 10.5v-5A4.5 4.5 0 0 1 5.5 1H8a.5.5 0 0 1 0 1H5.5z"/>
                                <path d="M16 3a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                            </svg>
                        </span>
                        <span style="font-weight: bold;font-size:23px;">e-Learn</span>
                        <!-- Header Logo End -->

                

                        <!-- Header Inner Start -->
                        <div class="header-inner">
                                <button type="button" class="btn btn-danger btn-logout" data-bs-toggle="modal" data-bs-target="#logoutModal">
                                Se déconnecter
                                </button>


                            <!-- Header Search Start -->
                            <div class="header-serach">
                                <form action="#">
                                    <input type="text" class="header-serach__input" placeholder="Rechercher le cours...">
                                    <button class="header-serach__btn"><i class="fas fa-search"></i></button>
                                </form>
                            </div>
                            <!-- Header Search End -->

                            <!-- Header Navigation Start -->
                            <div class="header-navigation d-none d-xl-block">
                                <nav class="menu-primary">
                                    
                                </nav>
                            </div>

                        </div>
                        <!-- Header Inner End -->

                    </div>
                    <!-- Header Main Wrapper End -->
                </div>
            </div>
            <!-- Header Main End -->

        </div>
        <!-- Header End -->



        <!-- Page Banner Section Start -->
        <div class="page-banner bg-color-05">
            <div class="page-banner__wrapper">
                <div class="container">

                    <!-- Page Breadcrumb Start -->
                    <div class="page-breadcrumb">
                        <ul class="breadcrumb">
                            
                        </ul>
                    </div>
                    <!-- Page Breadcrumb End -->

                    <!-- Page Banner Caption Start Inscription en avant-->
                    <div class="page-banner__caption text-center">
                        <h2 class="page-banner__main-title">Cours disponibles </h2>
                    </div>
                    <!-- Page Banner Caption End -->

                </div>
            </div>
        </div>
        <!-- Page Banner Section End -->
        <!-- Courses Start -->
        <div class="courses-section section-padding-01">
            <div class="container">

                <!-- Archive Filter Bars Start -->
                <div class="archive-filter-bars">

                    <div class="archive-filter-bar">
                    <p>Nombre de cours disponibles : <?= $resultDocs->num_rows ?></p>

                    </div>

                </div>
                <!-- Archive Filter Bars End -->

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="grid">
                        <div class="row g-3 py-3">
                        <div class="row">
<?php

foreach ($resultDocs as $coursItem):
?>
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <!-- Image de la première page du PDF -->
            <img src="photo1.jpg" class="card-img-top" alt="Aperçu du PDF" style="height: 250px; object-fit: cover; border-top-left-radius: .5rem; border-top-right-radius: .5rem;">

            <div class="card-body d-flex flex-column">
                <h5 class="card-title"><?= htmlspecialchars($coursItem['label']) ?></h5>
                <p class="text-secondary small mb-3">
                    <strong>Année : </strong><?= $coursItem['annee'] ?> <br>
                    <strong>Filière : </strong><?= $coursItem['code_f'] ?><br>
                    <strong>Par : </strong><?= $coursItem['auteur'] ?><br>
                    <strong>Publié le : </strong><?= $coursItem['date_ajout'] ?>
                </p>
                <a data-bs-toggle="modal" data-bs-target="#lectureModal"
                class="btn btn-primary mt-auto open-doc"
                data-id="<?= $coursItem['fichier'] ?>">
                Voir le cours
                </a>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>

  <!-- Modal contenant le document (plein écran) -->
<div class="modal fade" id="lectureModal" tabindex="-1" aria-labelledby="lectureModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title" id="lectureModalLabel">Lecture du document</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body p-4">

        <!-- Contenu dynamique -->
        <div id="documentContainer" class="container">
          <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white d-flex align-items-center">
              <i class="bi bi-file-earmark-text-fill me-2"></i>
              <h5 class="mb-0">Nom du document : <strong id="docName">Chargement...</strong></h5>
            </div>
            <div class="card-body p-0">
              <iframe id="pdfViewer" src="" width="100%" height="600px" style="border: none;"></iframe>
            </div>
          </div>

          <!-- Section Commentaires (statique pour l'instant) -->
          <div class="card mb-4">
  <div class="card-header">
    <h5>Commentaires</h5>
  </div>
  <div class="card-body">
    <form class="mb-3" method="POST">
      <div class="mb-3">
        <label for="commentaire" class="form-label">Ajouter un commentaire :</label>
        <textarea class="form-control" name="commentaire" id="commentaire" rows="3" placeholder="Écris ton commentaire ici..." required></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Publier</button>
    </form>

    <div class="list-group">
      <?php while ($row = $resultCommentaires->fetch_assoc()): ?>
        <div class="list-group-item">
          <div class="d-flex w-100 justify-content-between">
            <h6 class="mb-1"><?= htmlspecialchars($row['nom']) ?></h6>
            <small><?= date("d/m/Y H:i", strtotime($row['date_creation'])) ?></small>
          </div>
          <p class="mb-1"><?= nl2br(htmlspecialchars($row['commentaire'])) ?></p>
          <div>
            <?php if ($email === $email): ?>
              <form method="POST" class="d-inline">
                <input type="hidden" name="commentaire_id" value="<?= $row['id_commentaire'] ?>">
                <button type="submit" name="supp" class="btn btn-outline-danger btn-sm">Supprimer</button>
              </form>
            <?php endif; ?>
            <!-- Bouton de signalement (non fonctionnel ici mais en place) -->
            <button class="btn btn-outline-secondary btn-sm" disabled>Signaler</button>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
</div>


        </div>

      </div>
    </div>
  </div>
</div>

        <!-- Courses End -->


        <!-- Footer Start -->
        <div class="footer-section bg-color-10">

            <!-- Footer Widget Area Start -->
            <div class="footer-widget-area section-padding-01">
                <div class="container">
                    <div class="row gy-6">

                        <div class="col-md-4">
                            <!-- Footer Widget Start -->
                            <div class="footer-widget">
                                <span class="logo-icon">
                                    <svg  width="35" height="35" fill="currentColor" class="bi bi-app-indicator" viewBox="0 0 16 16">
                                        <path d="M5.5 2A3.5 3.5 0 0 0 2 5.5v5A3.5 3.5 0 0 0 5.5 14h5a3.5 3.5 0 0 0 3.5-3.5V8a.5.5 0 0 1 1 0v2.5a4.5 4.5 0 0 1-4.5 4.5h-5A4.5 4.5 0 0 1 1 10.5v-5A4.5 4.5 0 0 1 5.5 1H8a.5.5 0 0 1 0 1H5.5z"/>
                                        <path d="M16 3a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                    </svg>
                                </span>
                                <span style="font-weight: bold;font-size:23px;">e-Learn</span>
                                <div class="footer-widget__info">
                                    <span class="title">Contacter nous au</span>
                                    <span class="number">00000000000</span>
                                </div>
                                <div class="footer-widget__info">
                                    <p>AB536 Abomey-calavi</p>
                                    <p>contact@e-Learning.com</p>
                                </div>

                                <div class="footer-widget__social-02">
                                    <a class="twitter" href="https://twitter.com/" target="_blank"><i class="fab fa-twitter"></i></a>
                                    <a class="facebook" href="https://www.facebook.com/" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                    <a class="skype" href="#" target="_blank"><i class="fab fa-skype"></i></a>
                                    <a class="youtube" href="https://www.youtube.com/" target="_blank"><i class="fab fa-youtube"></i></a>
                                    <a class="linkedin" href="https://www.linkedin.com/" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                                </div>
                            </div>
                            <!-- Footer Widget End -->
                        </div>

                        <div class="col-md-8">
                            <div class="row gy-6">

                                <div class="col-sm-4">
                                    <!-- Footer Widget Start -->
                                    <div class="footer-widget">
                                        <h4 class="footer-widget__title">En savoir plus</h4>

                                        <ul class="footer-widget__link">
                                            <li><a href="###">A propos de nous </a></li>
                                        </ul>
                                    </div>
                                    <!-- Footer Widget End -->
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

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
    <!-- JS Vendor, Plugins & Activation Script Files -->

    <!-- Vendors JS -->
    <script src="assets/js/vendor/modernizr-3.11.7.min.js"></script>
    <script src="assets/js/vendor/jquery-3.6.0.min.js"></script>
    <script src="assets/js/vendor/jquery-migrate-3.3.2.min.js"></script>
    <script src="assets/js/vendor/bootstrap.bundle.min.js"></script>

    <!-- Plugins JS -->
    <script src="assets/js/plugins/aos.js"></script>
    <script src="assets/js/plugins/parallax.js"></script>
    <script src="assets/js/plugins/swiper-bundle.min.js"></script>
    <script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="assets/js/plugins/jquery.powertip.min.js"></script>
    <script src="assets/js/plugins/nice-select.min.js"></script>
    <script src="assets/js/plugins/glightbox.min.js"></script>
    <script src="assets/js/plugins/jquery.sticky-kit.min.js"></script>
    <script src="assets/js/plugins/imagesloaded.pkgd.min.js"></script>
    <script src="assets/js/plugins/masonry.pkgd.min.js"></script>
    <script src="assets/js/plugins/flatpickr.js"></script>
    <script src="assets/js/plugins/range-slider.js"></script>
    <script src="assets/js/plugins/select2.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Activation JS -->
    <script src="assets/js/main.js"></script>

<script>
document.querySelectorAll('.open-doc').forEach(btn => {
    btn.addEventListener('click', function () {
        const fichier = this.dataset.id;
        document.getElementById('pdfViewer').src = fichier;

        // // Enlever l'extension du fichier
        // const nomSansExtension = fichier.split('.').slice(0, -1).join('.');
        // document.getElementById('docName').textContent = nomSansExtension;
    });
});
</script>
</body>


<!-- Mirrored from htmldemo.net/edumall/edumall/course-grid-02.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 01 Apr 2025 08:32:31 GMT -->
</html>