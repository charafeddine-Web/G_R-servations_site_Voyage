<?php
$connect = mysqli_connect("localhost", "root", "Charaf2024", "agence_de_voyage",3307);

if ($connect) {
    echo "Connexion réussie !";
} else {
    echo "Erreur : " . mysqli_connect_error();
}
?>
