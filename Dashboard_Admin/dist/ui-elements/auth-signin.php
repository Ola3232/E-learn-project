<?php
include "../PHP/connexion.php";
session_start();
if (isset($_POST['se_connecter'])) {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $mot_de_passe = trim($_POST['mot_de_passe']);
    $role="admin";

    if (empty($email) || empty($mot_de_passe)) {
        die("Veuillez remplir tous les champs.");
    }

    // Préparation de la requête
    $stmt = $connexion->prepare("SELECT id_user, nom, prenom, email, annee, code_f FROM e_user WHERE email = ? AND mdp = ? AND role=?");
    $stmt->bind_param("sss", $email, $mot_de_passe,$role);
    $stmt->execute();
    $result = $stmt->get_result();

    // Vérification du résultat
    if ($user = $result->fetch_assoc()) {
        $_SESSION['user'] = $user;
        $_SESSION['email']=$email;
        header("Location:../index.php");
        exit();
    } else {
        echo "<script>alert('Email ou mot de passe incorrect.');</script>";
    }

    $stmt->close();
}

?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">


<!-- Mirrored from pixelwibes.com/template/e-learn/html/dist/ui-elements/auth-signin.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 01 Apr 2025 09:01:46 GMT -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <title>:: e-Learn:: Signin</title>
    <link rel="icon" href="../favicon.ico" type="image/x-icon"> <!-- Favicon-->
    <!-- project css file  -->
    <link rel="stylesheet" href="../assets/css/e-learn.style.min.css">
    <!-- Google Code  -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-264428387-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
    
      gtag('config', 'UA-264428387-1');
    </script>
</head>

<body>

<div id="elearn-layout" class="theme-black">

    <!-- main body area -->
    <div class="main p-2 py-3 p-xl-5 ">
        
        <!-- Body: Body -->
        <div class="body d-flex p-0 p-xl-5">
            <div class="container-xxl">

                <div class="row g-0">
                    <div class="col-lg-6 d-none d-lg-flex justify-content-center align-items-center rounded-lg auth-h100">
                        <div style="max-width: 25rem;">
                            <div class="text-center mb-5">
                                <svg  width="4rem"  fill="none" class="bi bi-app-indicator" viewBox="0 0 16 16">
                                    <path class="fill-primary" d="M5.5 2A3.5 3.5 0 0 0 2 5.5v5A3.5 3.5 0 0 0 5.5 14h5a3.5 3.5 0 0 0 3.5-3.5V8a.5.5 0 0 1 1 0v2.5a4.5 4.5 0 0 1-4.5 4.5h-5A4.5 4.5 0 0 1 1 10.5v-5A4.5 4.5 0 0 1 5.5 1H8a.5.5 0 0 1 0 1H5.5z"/>
                                    <path class="fill-primary" d="M16 3a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                </svg>
                            </div>
                            <div class="mb-5">
                                <h2 class="color-900 text-center">Connecter vous à votre compte E-Learning</h2>
                            </div>
                            <!-- Image block -->
                            <div class="">
                                <img src="../assets/images/online-study.svg" alt="online-study">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 d-flex justify-content-center align-items-center border-0 rounded-lg auth-h100">
                        <div class="w-100 p-4 p-md-5 card border-0 bg-dark text-light" style="max-width: 32rem;">
                            <!-- Form -->
                            <form class="row g-1 p-0 p-4" method="post" action="auth-signin.php">
                                
                                <div class="col-12">
                                    <div class="mb-2">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control form-control-lg" placeholder="name@example.com" name="email">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-2">
                                        <div class="form-label">
                                            <span class="d-flex justify-content-between align-items-center">
                                              Mot de passe
                                                
                                            </span>
                                        </div>
                                        <input type="password" class="form-control form-control-lg" placeholder="***************" name="mot_de_passe">
                                    </div>
                                </div>
                                <div class="col-12 text-center mt-4">
                                <button class="btn btn-primary btn-hover-secondary w-100" name="se_connecter">Se connecter </button>
                                </div>
                            </form>
                            <!-- End Form -->
                        </div>
                    </div>
                </div> <!-- End Row -->
                
            </div>
        </div>

    </div>

</div>

<!-- Jquery Core Js -->
<script src="../assets/bundles/libscripts.bundle.js"></script>

</body>

<!-- Mirrored from pixelwibes.com/template/e-learn/html/dist/ui-elements/auth-signin.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 01 Apr 2025 09:01:46 GMT -->
</html>