<?php
require ('./connection.php');
if(isset($GET['id_client'])){
    $nom = trim(htmlspecialchars($_POST['nom']));
    $prenom = trim(htmlspecialchars($_POST['prenom']));
    $email = trim(htmlspecialchars($_POST['email']));
    $telephone = trim(htmlspecialchars($_POST['telephone']));
    $adresse = trim(htmlspecialchars($_POST['adresse']));
    $date_naissance = trim(htmlspecialchars($_POST['date_naissance']));
    $id_cli=$_GET['id_client'];



    
    // $sql="SELECT * from where id_client='$id_cli' ";
    // $result=$connect->query($sql);
    // $result->fetch_assoc();
    
    
}


