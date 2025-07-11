<?php
$server="localhost";
$user="root";
$pass="";
$bdname="e-learn";
//Etablir la connexion 
$connexion = new mysqli($server, $user, $pass, $bdname);

// Vérifier la connexion
if ($connexion->connect_error) {
    die("Échec de la connexion : " . $connexion->connect_error);
} else {
    $ms= "Connexion réussie !";
}

?>