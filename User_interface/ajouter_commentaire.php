<?php
session_start();
include 'connexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['commentaire'], $_POST['id_doc'])) {
    $commentaire = htmlspecialchars($_POST['commentaire']);
    $document_id = intval($_POST['id_doc']);
    $id_user = $_SESSION['id_user']; // ou autre source
    $nomUtilisateur = $_SESSION['nom']; // ou autre

    $sqlInsert = "INSERT INTO commentaires (nom, commentaire, id_doc, id_user) VALUES (?, ?, ?, ?)";
    $stmtInsert = $connexion->prepare($sqlInsert);
    $stmtInsert->bind_param("ssii", $nomUtilisateur, $commentaire, $document_id, $id_user);
    $stmtInsert->execute();
    $stmtInsert->close();

    // Affichage des commentaires mis à jour
    $stmtSelect = $connexion->prepare("SELECT nom, commentaire FROM commentaires WHERE id_doc = ? ORDER BY id DESC");
    $stmtSelect->bind_param("i", $document_id);
    $stmtSelect->execute();
    $result = $stmtSelect->get_result();

    while ($row = $result->fetch_assoc()) {
        echo "<p><strong>" . htmlspecialchars($row['nom']) . " :</strong> " . htmlspecialchars($row['commentaire']) . "</p>";
    }
    $stmtSelect->close();
}
?>
