<?php
require("./code/connection.php");
session_start();
$successMessage="";
$error=[];
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $id_client = isset($_POST['id']) ? intval($_POST['id']) : null;
    $nom = isset($_POST['nom']) ? trim(htmlspecialchars($_POST['nom'])) : '';
    $prenom = isset($_POST['prenom']) ? trim(htmlspecialchars($_POST['prenom'])) : '';
    $email = isset($_POST['email']) ? trim(htmlspecialchars($_POST['email'])) : '';
    $telephone = isset($_POST['telephone']) ? trim(htmlspecialchars($_POST['telephone'])) : '';
    $adresse = isset($_POST['adresse']) ? trim(htmlspecialchars($_POST['adresse'])) : '';
    $date_naissance = isset($_POST['date_naissance']) ? trim(htmlspecialchars($_POST['date_naissance'])) : '';

    if (empty($id_client) || empty($nom) || empty($prenom) || empty($email)) {
        $errors[] = "Les champs ID, nom, prénom, et email sont obligatoires.";
    }

    if (empty($errors)) {
        $sql = "UPDATE clients SET nom='$nom', prenom='$prenom', email='$email', telephone='$telephone', adresse='$adresse', date_naissance='$date_naissance' WHERE id_client=$id_client";
        $result = mysqli_query($connect, $sql);

        if ($result) {
            $_SESSION['successMessage'] = "Client mis à jour avec succès.";
            header('Location: index.php');
            exit();
        } else {
            $error[] = "Erreur lors de la mise à jour du client : " . mysqli_error($connect);
        }
    }
}



