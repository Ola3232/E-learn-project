<?php
include "PHP/connexion.php";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Récupérer le chemin du fichier avant suppression
    $req = $connexion->prepare("SELECT fichier FROM docs WHERE id_doc = ?");
    $req->bind_param("i", $id);
    $req->execute();
    $result = $req->get_result();
    $doc = $result->fetch_assoc();

    if ($doc && file_exists('../../' . $doc['fichier'])) {
        unlink('../../' . $doc['fichier']); // Supprimer le fichier du serveur
    }

    // Supprimer l'entrée de la base de données
    $stmt = $connexion->prepare("DELETE FROM docs WHERE id_doc = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: courses.php?delete=success");
        exit();
    } else {
        echo "Erreur lors de la suppression : " . $stmt->error;
    }
} else {
    echo "ID invalide.";
}
?>
