<?php
session_start();
include "PHP/connexion.php";

if ($connexion->connect_error) {
    die("Connexion échouée : " . $connexion->connect_error);
}

$id = $_POST['id'];
$titre = $_POST['titre'];
$annee = $_POST['annee'];
$filiere = $_POST['filiere'];
$auteur=$_POST['auteur'];

$fichier = $_FILES['fichier'];
$fichier_nom = $fichier['name'];
$fichier_tmp = $fichier['tmp_name'];
$fichier_type = $fichier['type'];
$fichier_error = $fichier['error'];

if ($fichier_nom) {
    $extension = pathinfo($fichier_nom, PATHINFO_EXTENSION);
    $nouveau_nom = uniqid() . '_' . $fichier_nom;

    // Dossier cible pour stocker le fichier
    $destination_relative = '../../uploads/' . $nouveau_nom; // pour le déplacement physique
    $destination_bdd = '../uploads/' . $nouveau_nom;         // pour enregistrer dans la BDD

    // Crée le dossier s’il n'existe pas
    if (!is_dir('../../uploads')) {
        mkdir('../../uploads', 0777, true);
    }

    if (move_uploaded_file($fichier_tmp, $destination_relative)) {
        $sql = "UPDATE docs SET label=?, annee=?, code_f=?, fichier=?,auteur=? WHERE id_doc=?";
        $stmt = $connexion->prepare($sql);
        $stmt->bind_param("sssssi", $titre, $annee, $filiere, $destination_bdd,$auteur, $id);
    } else {
        die("Erreur lors de l'enregistrement du fichier.");
    }
} else {
    $sql = "UPDATE docs SET label=?, annee=?, code_f=? ,auteur=? WHERE id_doc=?";
    $stmt = $connexion->prepare($sql);
    $stmt->bind_param("ssssi", $titre, $annee, $filiere,$auteur, $id);
}

if ($stmt->execute()) {
    header("Location: courses.php?modification=success");
    exit();
} else {
    echo "Erreur : " . $stmt->error;
}
?>
