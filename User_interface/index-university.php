<?php
session_start();
require 'connexion.php';

if (isset($_POST['s_inscrire'])) {
    // Récupération et sécurisation des données
    $nom = htmlspecialchars(trim($_POST['nom']));
    $prenom = htmlspecialchars(trim($_POST['prenom']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['mot_de_passe']);
    $confirm_password = trim($_POST['confirmer_mot_de_passe']);
    $annee = $_POST['annee'];
    $fil = $_POST['filiere'];
    $date_inscription = date('Y-m-d H:i:s'); // récupère la date et l'heure actuelles

    // Vérification si les mots de passe correspondent
    if ($password !== $confirm_password) {
        die("Les mots de passe ne correspondent pas.");
    }

    // Vérification si l'email existe déjà
    $check = $connexion->prepare("SELECT id_user FROM e_user WHERE email = ?");
    $check->execute([$email]);
    if ($check->fetch()) {
        die("Cet email est déjà utilisé.");
    }

    // Insertion sécurisée sans hachage du mot de passe (selon ta demande)
    $sql = "INSERT INTO e_user (nom, prenom, email, annee, mdp, code_f,date_inscription) VALUES (?, ?, ?, ?, ?, ?,?)";
    $stmt = $connexion->prepare($sql);
    $stmt->execute([$nom, $prenom, $email, $annee, $password, $fil,$date_inscription]);

    // Redirection vers la page de connexion
    header("Location: index-university.php");
    exit();
}


if (isset($_POST['se_connecter'])) {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $mot_de_passe = trim($_POST['mot_de_passe']);

    if (empty($email) || empty($mot_de_passe)) {
        die("Veuillez remplir tous les champs.");
    }

    // Préparation de la requête
    $stmt = $connexion->prepare("SELECT id_user, nom, prenom, email, annee, code_f FROM e_user WHERE email = ? AND mdp = ?");
    $stmt->bind_param("ss", $email, $mot_de_passe);
    $stmt->execute();
    $result = $stmt->get_result();

    // Vérification du résultat
    if ($user = $result->fetch_assoc()) {
        $_SESSION['user'] = $user;
        $_SESSION['email'] = $user['email'];  
        $_SESSION['nom'] = $user['nom'];  
        $_SESSION['prenom'] = $user['prenom']; 

        header("Location: course-grid-02.php");
        exit();
    } else {
        echo "<script>alert('Email ou mot de passe incorrect.');</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html class="no-js" lang="en">
<!-- Mirrored from htmldemo.net/edumall/edumall/index-university.PHP by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 01 Apr 2025 08:31:51 GMT -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E_learn</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="">

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

</head>

<body>

    <main class="main-wrapper">

        <!-- Header start -->
        <div class="header-section header-sticky">

            <!-- Header Top Start --> 
            <div class="header-top-04">
                <div class="container custom-container">

                    <div class="row gy-2 align-items-center">
                        
                        <div class="col-sm-4 col-5">

                            <!-- Header Logo Start -->
                                <span class="logo-icon">
                                    <svg  width="35" height="35" fill="currentColor" class="bi bi-app-indicator" viewBox="0 0 16 16">
                                        <path d="M5.5 2A3.5 3.5 0 0 0 2 5.5v5A3.5 3.5 0 0 0 5.5 14h5a3.5 3.5 0 0 0 3.5-3.5V8a.5.5 0 0 1 1 0v2.5a4.5 4.5 0 0 1-4.5 4.5h-5A4.5 4.5 0 0 1 1 10.5v-5A4.5 4.5 0 0 1 5.5 1H8a.5.5 0 0 1 0 1H5.5z"/>
                                        <path d="M16 3a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                    </svg>
                                </span>
                                <span style="font-weight: bold;font-size:23px;">e-Learn</span>
                            <!-- Header Logo End -->

                        </div>
                        <div class="col-sm-4 col-7">

                            <!-- Header Top Bar Wrapper Info Start -->
                            <div class="header-top-bar-wrap__info d-flex justify-content-end align-items-center">

                                

                                <div class="header-user-02__box d-none d-lg-flex">
                                    <div class="header-user-02__icon">
                                        <i class="far fa-user"></i>
                                    </div>
                                    <div class="header-user-02__info">
                                        <button class="header-user-02__link" data-bs-toggle="modal" data-bs-target="#loginModal">Se connecter</button>
                                    </div>
                                </div>

                                
                            </div>
                            <!-- Header Top Bar Wrapper Info End -->

                        </div>
                    </div>

                </div>
            </div>
            <!-- Header Top End -->

            <!-- Header Main Start -->
            <div class="header-main-03 d-none d-xl-block">
                <div class="container custom-container">

                    <!-- Header Navigation Start -->
                    <div class="header-navigation">
                        <nav class="menu-primary">
                            <ul class="menu-primary__container menu-primary__container-02 justify-content-center">
                                <li><a class="active" href="#"><span>Acceuil</span></a>

                                    <ul class="mega-menu">
                                        <li>
                                            <!-- Mega Menu Content Start -->
                                            <div class="mega-menu-content">
                                                <div class="row">
                                                    <div class="col-xl-3">
                                                        <div class="menu-content-list">
                                                            <a href="index-university.PHP" class="menu-content-list__link">e-Learn</a>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-9">
                                                        <div class="menu-content-banner" style="background-image: url(assets/images/home-megamenu-bg.jpg);">
                                                            <h4 class="menu-content-banner__title">Atteindre vos objectifs avec e-Learn </h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Mega Menu Content Start -->
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="#"><span>Cours</span></a>
                                    <ul class="sub-menu">
                                        <li><a data-bs-toggle="modal" data-bs-target="#loginModal"><span>Accès au cours </span></a></li> 
                                    </ul>
                                </li>
                                <li>
                                    <a href="#"><span>Pages</span></a>
                                    <ul class="sub-menu">
                                        <li><a href="####"><span>A propos de nous</span></a></li>
                                        <li><a href="####"><span>Nous contacter</span></a></li>
                                    </ul>
                                </li></ul>
                        </nav>
                    </div>
                    <!-- Header Navigation End -->

                </div>
            </div>
            <!-- Header Main End -->

        </div>
        <!-- Header End -->



        <!-- Slider Section Start -->
        <div class="slider-section slider-section-04">
            <div class="slider-wrapper" style="background-image: url(assets/images/E-learning.png);">
                <div class="container custom-container">

                    <div class="row gy-10 align-items-center">
                        <div class="col-lg-8">
                            <!-- Slider Caption Start -->
                            <div class="slider-caption-04 pe-lg-10" data-aos="fade-up" data-aos-duration="1000">
                                <h4 class="slider-caption-04__sub-title">Bienvenue sur e-Learn</h4>
                                <h2 class="slider-caption-04__main-title">Apprenez à votre <span>rythme</span>,où que vous soyez.</h2>
                            </div>
                        </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="about-section section-padding-02 scene">
            <div class="container custom-container">

                <div class="about-title">
                    <!-- Section Title Start -->
                    <div class="section-title mb-0" data-aos="fade-up" data-aos-duration="1000">
                        <h2 class="section-title__title-03">Un espace de discussion pour apprendre ensemble</h2>
                    </div>
                    <!-- Section Title End -->
                </div>

                <!-- About Image Start -->
                <div class="about-image scene">
                    <div class="about-image__image p-1" data-aos="fade-up" data-aos-duration="1000">
                        <!-- Image a insérer -->
                        <img src="assets/images/download-image.png" alt="About" width="371" height="619">
                        <img src="assets/images/download-image-02.png" alt="About" width="371" height="619">
                        <img src="assets/images/canvas-menu-image.png" alt="About" width="371" height="619">
                    </div>  
                </div>
                <!-- About Image End -->

            </div>

            <div class="about-section__shape-01" data-depth="0.4"></div>
            <div class="about-section__shape-02" data-depth="-0.4"></div>
        </div>
        <div class="about-section section-padding-01">
            <div class="container custom-container">

                

            </div>
        </div>
        <div class="academics-section bg-color-05 section-padding-01 scene">
            <div class="container custom-container">

                <!-- Section Title Start -->
                <div class="section-title text-center" data-aos="fade-up" data-aos-duration="1000">
                    <h2 class="section-title__title-03"><mark>Avantages</mark></h2>
                    <p class="mt-0">*******</p>
                </div>
                <!-- Section Title End -->

                <div class="row g-6">
                    <div class="col-lg-4 col-md-4 col-sm-6">

                        <!-- Academics Start -->
                        <div class="academics-item text-center" data-aos="fade-up" data-aos-duration="1000">
                                <div class="academics-item__image">
                                    <img src="assets/images/Premium Vector _ Online Library and Media Books Archive Concept_.png " alt="University" width="370" height="270">
                                    <h3 class="academics-item__title">Tous les cours en PDF à portée de main.</h3>
                                </div>
                                <div class="academics-item__description">
                                    <p>Accède à l’ensemble des cours directement en format PDF, où que tu sois. Que tu sois sur ton ordinateur, ta tablette ou ton smartphone, tu peux consulter les documents mis à disposition par les enseignants à tout moment. Plus besoin de chercher ou de demander, tout est centralisé et facile à retrouver.</p>
                                </div>
                        </div>
                        <!-- Academics End -->

                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6">

                        <!-- Academics Start -->
                        <div class="academics-item text-center" data-aos="fade-up" data-aos-duration="1000">
                                <div class="academics-item__image">
                                    <img src="assets/images/Card.png" alt="University" width="370" height="270">
                                    <h3 class="academics-item__title">Échange et progresse avec les autres étudiants.</h3>
                                </div>
                                <div class="academics-item__description">
                                    <p>Un espace de discussion te permet de poser des questions, partager tes idées et t’entraider avec les autres membres de la plateforme. Que ce soit pour comprendre un concept ou enrichir tes connaissances, le travail collaboratif est au cœur de ton apprentissage.</p>
                                </div>
                        </div>
                        <!-- Academics End -->

                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6">

                        <!-- Academics Start -->
                        <div class="academics-item text-center" data-aos="fade-up" data-aos-duration="1000">
                                <div class="academics-item__image">
                                    <!-- img a insérer -->
                                    <img src="assets/images/Audiobook listening_ Online library.png" alt="University" width="370" height="270">
                                    <h3 class="academics-item__title">Télécharge, lis, relis... à volonté.</h3>
                                </div>
                                <div class="academics-item__description">
                                    <p>Tous les documents de cours peuvent être téléchargés en un clic. Tu peux les conserver, les imprimer ou les relire autant de fois que tu le souhaites. Cette liberté te permet de réviser à ton rythme, selon ton emploi du temps et tes préférences.</p>
                                </div>
                        </div>
                        <!-- Academics End -->

                    </div>
                </div>

            </div>


        </div>

        <div class="blog-section bg-color-05 section-padding-01">
            <div class="container custom-container">

                <!-- Section Title Start -->
                <div class="section-title text-center" data-aos="fade-up" data-aos-duration="1000">
                    <h2 class="section-title__title-03">Cours en ligne <mark> aujourd’hui</mark></h2>
                    <p class="mt-0">*******</p>
                </div>
                <!-- Section Title End -->

                <div class="row gy-6">
                    <div class="col-lg-6">
                        <!-- Blog Post Start -->
                        <div class="blog-post-01" data-aos="fade-up" data-aos-duration="1000">
                            <div class="blog-post-01__thumbnail">
                                <!-- Image a insérer -->
                                <img src="assets/images/EdTech.png" alt="Blog" width="570" height="320">
                            </div>
                            <div class="blog-post-01__info">
                                <div class="blog-post-01__categories">
                                    <h3 class="blog-post-01__title">Partage ta compréhension avec la communauté.</h3>
                                </div>
                                <p style="color: white;">Chaque cours dispose d’un espace commentaire où tu peux exprimer ta vision, partager tes résumés, poser des questions ou compléter les contenus avec tes propres recherches. C’est un lieu d’échange bienveillant où chaque étudiant peut contribuer à enrichir la compréhension collective. En partageant ta propre manière d’aborder un sujet, tu aides les autres à voir sous un autre angle, tout en consolidant ta propre maîtrise</p>
                            </div>
                        </div>
                        <!-- Blog Post End -->
                    </div>
                    <div class="col-lg-3 col-sm-6">

                        <!-- Blog Post Start -->
                        <div class="blog-post-02" data-aos="fade-up" data-aos-duration="1000">
                            <div class="blog-post-02__thumbnail">
                                <img src="assets/images/Etu1.png" alt="Blog" width="270" height="168">
                            </div>
                            <div class="blog-post-02__info">
                                <div class="blog-post-02__categories">
                                    <a href="#">Une interface claire, pour aller droit au but.</a>
                                </div>
                                <h3 class="blog-post-02__title">Navigue facilement entre les cours, les fichiers et les discussions.</h3>
                            </div>
                        </div>
                        <!-- Blog Post End -->

                    </div>
                    <div class="col-lg-3 col-sm-6">

                        <!-- Blog Post Start -->
                        <div class="blog-post-02" data-aos="fade-up" data-aos-duration="1000">
                            <div class="blog-post-02__thumbnail">
                                <img src="assets/images/Etu2.png" alt="Blog" width="270" height="168">
                            </div>
                            <div class="blog-post-02__info">
                                <div class="blog-post-02__categories">
                                    <h3 class="blog-post-02__title">La connaissance n’attend pas : commence aujourd’hui</h3>
                                </div>
                                
                            </div>
                        </div>
                        <!-- Blog Post End -->

                    </div>
                </div>


            </div>
        </div>
        <!-- Modifie ici -->
        <div class="footer-section">

            <!-- Footer Widget Area Start -->
            <div class="footer-widget-area section-padding-01">
                <div class="container custom-container">
                    <div class="row gy-6">

                        <div class="col-lg-4 col-md-6 order-lg-3">
                            <!-- Footer Widget Start -->
                            <div class="footer-widget">
                                
                            </div>
                            <!-- Footer Widget End -->
                        </div>

                        <div class="col-lg-4 col-md-6 order-lg-2">
                            <div class="row gy-6">

                                <div class="col-sm-6">
                                    <!-- Footer Widget Start -->
                                    <div class="footer-widget">
                                        <h4 class="footer-widget__title">En savoir plus</h4>

                                        <ul class="footer-widget__link footer-widget__link-02">
                                            <li><a href="####">A propos de nous </a></li>
                                        </ul>
                                    </div>
                                    <!-- Footer Widget End -->
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 order-lg-1">
                            <!-- Footer Widget Start -->
                             <!-- A modifier ici  -->
                            <div class="footer-widget">
                                <span class="logo-icon">
                                    <svg  width="35" height="35" fill="currentColor" class="bi bi-app-indicator" viewBox="0 0 16 16">
                                        <path d="M5.5 2A3.5 3.5 0 0 0 2 5.5v5A3.5 3.5 0 0 0 5.5 14h5a3.5 3.5 0 0 0 3.5-3.5V8a.5.5 0 0 1 1 0v2.5a4.5 4.5 0 0 1-4.5 4.5h-5A4.5 4.5 0 0 1 1 10.5v-5A4.5 4.5 0 0 1 5.5 1H8a.5.5 0 0 1 0 1H5.5z"/>
                                        <path d="M16 3a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                    </svg>
                                </span>
                                <span style="font-weight: bold;font-size:23px;">e-Learn</span>

                                <p class="footer-widget__description mb-10">Lorem OOOpsum dolor sit amet, consectetur adipisc ing elit. Nunc maximus, nulla utlaoki comm odo sagittis.</p>

                                <p class="footer-widget__copyright mt-0">&copy; 2025 <span> e-Learning </span> Fais avec <i class="fa fa-heart"></i> par nous </p>
                            </div>
                            <!-- Footer Widget End -->
                        </div>

                    </div>
                </div>
            </div></div>
        <button id="backBtn" class="back-to-top backBtn">
            <i class="arrow-top fas fa-arrow-up"></i>
            <i class="arrow-bottom fas fa-arrow-up"></i>
        </button>
    </main>

    <!-- Log In Modal Start -->
    <div class="modal fade" id="loginModal">
        <div class="modal-dialog modal-dialog-centered modal-login">

            <!-- Modal Wrapper Start -->
            <div class="modal-wrapper">
                <button class="modal-close" data-bs-dismiss="modal"><i class="fas fa-times"></i></button>

                <!-- Modal Content Start -->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Se connecter </h5>
                        <p class="modal-description">Avez vous un compte ? <button data-bs-toggle="modal" data-bs-target="#registerModal">Inscrivez vous</button></p>
                    </div>
                    <div class="modal-body">
                       <form action="index-university.php" method="post">
                            <div class="modal-form">
                                <label class="form-label">Email</label>
                                <input type="text" class="form-control" placeholder="nom@gmail.com" name="email">
                            </div>
                            <div class="modal-form">
                                <label class="form-label">Mot de passe </label>
                                <input type="password" class="form-control" placeholder="******" name="mot_de_passe">
                            </div>
                            <div class="modal-form">
                                <button class="btn btn-primary btn-hover-secondary w-100" name="se_connecter">Se connecter </button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Modal Content End -->

            </div>
            <!-- Modal Wrapper End -->

        </div>
    </div>
    <!-- Log In Modal End -->

    <!-- Log In Modal Start -->
    <div class="modal fade" id="registerModal">
        <div class="modal-dialog modal-dialog-centered modal-register">

            <!-- Modal Wrapper Start -->
            <div class="modal-wrapper">
                <button class="modal-close" data-bs-dismiss="modal"><i class="fas fa-times"></i></button>

                <!-- Modal Content Start -->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">S'incrire</h5>
                        <p class="modal-description">Avez vous déjà un compte? <button data-bs-toggle="modal" data-bs-target="#loginModal">Se connecter</button></p>
                    </div>
                    <div class="modal-body">

                        <form action="index-university.php" method="post">
                            <div class="row gy-5">
                                <div class="col-md-6">
                                    <div class="modal-form">
                                        <label class="form-label">Nom</label>
                                        <input type="text" class="form-control" placeholder="Nom" name="nom" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="modal-form">
                                        <label class="form-label">Prénom</label>
                                        <input type="text" class="form-control" placeholder="Prénom" name="prenom" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="modal-form">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" placeholder="exemple@gmail.com" name="email" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="modal-form">
                                        <label class="form-label">Mot de passe</label>
                                        <input type="password" class="form-control" placeholder="******" name="mot_de_passe" required minlength="6">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="modal-form">
                                        <label class="form-label">Confirmer le mot de passe</label>
                                        <input type="password" class="form-control" placeholder="******" name="confirmer_mot_de_passe" required minlength="6">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="modal-form">
                                        <label class="form-label">Année d'étude</label>
                                        <select name="annee" class="form-control">
                                            <option value="Licence1">Licence1</option>
                                            <option value="Licence2">Licence2</option>
                                            <option value="Licence3">Licence3</option>
                                        </select>
                                    </div>
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
                                <div class="col-md-12">
                                    <div class="modal-form">
                                        <button class="btn btn-primary btn-hover-secondary w-100" name="s_inscrire" type="submit">S'inscrire</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
                <!-- Modal Content End -->

            </div>
            <!-- Modal Wrapper End -->

        </div>
    </div>
    <!-- Log In Modal End -->

    <!-- Edumall Demo Option Start -->
    <div class="edumall-demo-option">

        <div class="edumall-demo-option__toolbar">
            <button class="toolbar-action demo-open" data-bs-tooltip="tooltip" data-bs-placement="left" title="Select Demo"><i class="fas fa-pencil-ruler"></i></button>
            <a class="toolbar-action" href="https://hasthemes.com/contact-us/" data-bs-tooltip="tooltip" data-bs-placement="left" title="Support Channel"><i class="far fa-life-ring"></i></a>
            <a class="toolbar-action" href="https://1.envato.market/qnL5nL" data-bs-tooltip="tooltip" data-bs-placement="left" title="Purchase EduMall"><i class="fas fa-shopping-basket"></i></a>
        </div>

        <div class="edumall-demo-option__panel">

            <div class="edumall-demo-option__header">
                <h5 class="edumall-demo-option__title">EduMall - Professional LMS Education Center HTML Template</h5>
                <a class="edumall-demo-option__btn btn btn-primary btn-hover-secondary" href="https://1.envato.market/qnL5nL"><i class="fas fa-shopping-basket"></i> Buy Now</a>
            </div>

            <div class="edumall-demo-option__body">
                <div class="edumall-demo-option-item">
                    <a href="index.html" data-bs-tooltip="tooltip" data-bs-placement="top" title="Main Demo">
                        <img src="assets/images/demo/home-01.jpg" alt="Home" width="130" height="158">
                    </a>
                </div>
                <div class="edumall-demo-option-item">
                    <a href="index-course-hub.html" data-bs-tooltip="tooltip" data-bs-placement="top" title="Course Hub">
                        <img src="assets/images/demo/home-02.jpg" alt="Home" width="130" height="158">
                    </a>
                </div>
                <div class="edumall-demo-option-item">
                    <a href="index-online-academy.html" data-bs-tooltip="tooltip" data-bs-placement="top" title="Online Academy">
                        <img src="assets/images/demo/home-03.jpg" alt="Home" width="130" height="158">
                    </a>
                </div>
                <div class="edumall-demo-option-item">
                    <a href="index-education-center.html" data-bs-tooltip="tooltip" data-bs-placement="top" title="Education Center">
                        <img src="assets/images/demo/home-04.jpg" alt="Home" width="130" height="158">
                    </a>
                </div>
                <div class="edumall-demo-option-item">
                    <a href="index-university.php" data-bs-tooltip="tooltip" data-bs-placement="top" title="University">
                        <img src="assets/images/demo/home-05.jpg" alt="Home" width="130" height="158">
                    </a>
                </div>
                <div class="edumall-demo-option-item">
                    <a href="index-language-academic.html" data-bs-tooltip="tooltip" data-bs-placement="top" title="Language Academic">
                        <img src="assets/images/demo/home-06.jpg" alt="Home" width="130" height="158">
                    </a>
                </div>
                <div class="edumall-demo-option-item">
                    <a href="index-single-instructor.html" data-bs-tooltip="tooltip" data-bs-placement="top" title="Single Instructor">
                        <img src="assets/images/demo/home-07.jpg" alt="Home" width="130" height="158">
                    </a>
                </div>
                <div class="edumall-demo-option-item">
                    <a href="index-dev.html" data-bs-tooltip="tooltip" data-bs-placement="top" title="Dev">
                        <img src="assets/images/demo/home-08.jpg" alt="Home" width="130" height="158">
                    </a>
                </div>
                <div class="edumall-demo-option-item">
                    <a href="index-online-art.html" data-bs-tooltip="tooltip" data-bs-placement="top" title="Online Art">
                        <img src="assets/images/demo/home-09.jpg" alt="Home" width="130" height="158">
                    </a>
                </div>
            </div>

        </div>

    </div>
    <!-- Edumall Demo Option End -->




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


    <!-- Activation JS -->
    <script src="assets/js/main.js"></script>


</body>


<!-- Mirrored from htmldemo.net/edumall/edumall/index-university.php by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 01 Apr 2025 08:31:55 GMT -->
</html>