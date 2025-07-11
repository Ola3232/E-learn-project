<?php


// // Empêche la mise en cache
// header("Cache-Control: no-cache, must-revalidate");
// header("Pragma: no-cache");
// header("Expires: 0");


if(isset($_POST['deconnexion'])){
    // Supprimer toutes les variables de session
    $_SESSION = [];
    
    // Détruire la session
    session_destroy();
    
    // Empêcher la mise en cache de la page précédente
    header("Cache-Control: no-cache, must-revalidate");
    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
    
    // Rediriger vers la page de connexion
    header("location:ui-elements/auth-signin.php");
    exit();
    }
?>
