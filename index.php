<?php
require("./code/connection.php");
session_start();
$successMessage = "";
$errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {

    $nom = trim(htmlspecialchars($_POST['nom']));
    $prenom = trim(htmlspecialchars($_POST['prenom']));
    $email = trim(htmlspecialchars($_POST['email']));
    $telephone = trim(htmlspecialchars($_POST['telephone']));
    $adresse = trim(htmlspecialchars($_POST['adresse']));
    $date_naissance = trim(htmlspecialchars($_POST['date_naissance']));

    if (empty($nom) || empty($prenom) || empty($email) || empty($telephone) || empty($adresse) || empty($date_naissance)) {
        $errors[] = "Tous les champs sont requis.";
    }

    if (strlen($telephone) > 15) {
        $errors[] = " phone < 15 char  ";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Adresse email invalide";
    }
    $date_debut_obj = DateTime::createFromFormat('Y-m-d', datetime: $date_naissance);
    if (!$date_debut_obj) {
        $errors[] = "La date de naissance est invalides.";

    }


    if (empty($errors)) {
        $sql_insert = "INSERT INTO clients(nom,prenom,email,telephone,adresse,date_naissance)
            values ('$nom','$prenom','$email','$telephone','$adresse','$date_naissance')";
        if (!mysqli_query($connect, $sql_insert)) {
            $errors[] = "errors sur connection avec mysql ";
        } else {
            header("Location: index.php");
            $_SESSION['successMessage'] = " Client ajoutée avec succès !";
            exit();
        }
    }

}
if (!empty($errors)) {
    foreach ($errors as $err) {
        echo "<div id='allerreur' class='bg-red-500 text-white font-bold py-2 px-4 mb-4 mx-10 md:mx-20 md:ml-80 text-center rounded flex  gap-2'>";
        echo "<p>" . htmlspecialchars($err) . "</p>";
        echo "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gestion des Réservations pour un Site de Voyage</title>
    <meta name="author" content="charaf-eddine-tbibzat">
    <meta name="description" content="description here">
    <meta name="keywords" content="keywords,here">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
    <link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css" />
    <link href="https://afeld.github.io/emoji-css/emoji.css" rel="stylesheet">

</head>

<body class="bg-gray-800 font-sans leading-normal tracking-normal mt-12">
    <header>
        <nav aria-label="menu nav" class="bg-gray-800 pt-2 md:pt-1 pb-1 px-1 mt-0 h-auto fixed w-full z-20 top-0">

            <div class="flex flex-wrap items-center">
                <div class="flex flex-shrink md:w-1/3 justify-center md:justify-start text-white">
                    <a href="#" aria-label="Home">
                        <span class="text-xl pl-2"><img src="./assets/youcode.png" alt="logo" width="130"></i></span>
                    </a>
                </div>

                <div class="flex flex-1 md:w-1/3 justify-center md:justify-start text-white px-2">
                    <span class="relative w-full">
                        <input aria-label="search" type="search" id="search" placeholder="Search"
                            class="w-full bg-gray-900 text-white transition border border-transparent focus:outline-none focus:border-gray-400 rounded py-3 px-2 pl-10 appearance-none leading-normal">
                        <div class="absolute search-icon" style="top: 1rem; left: .8rem;">
                            <svg class="fill-current pointer-events-none text-white w-4 h-4"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path
                                    d="M12.9 14.32a8 8 0 1 1 1.41-1.41l5.35 5.33-1.42 1.42-5.33-5.34zM8 14A6 6 0 1 0 8 2a6 6 0 0 0 0 12z">
                                </path>
                            </svg>
                        </div>
                    </span>
                </div>

                <div class="flex w-full pt-2 content-center justify-between md:w-1/3 md:justify-end">
                    <ul class="list-reset flex justify-between flex-1 md:flex-none items-center">
                        <li class="flex-1 md:flex-none md:mr-3">
                            <div class="relative inline-block">
                                <button onclick="toggleDD('myDropdown')" class="drop-button text-white py-2 px-2"> <span
                                        class="pr-2"><i class="em em-robot_face"></i></span> Hi, User <svg
                                        class="h-3 fill-current inline" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path
                                            d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                                    </svg></button>
                                <div id="myDropdown"
                                    class="dropdownlist absolute bg-gray-800 text-white right-0 mt-3 p-3 overflow-auto z-30 invisible">
                                    <input type="text" class="drop-search p-2 text-gray-600" placeholder="Search.."
                                        id="myInput" onkeyup="filterDD('myDropdown','myInput')">
                                    <a href="#"
                                        class="p-2 hover:bg-gray-800 text-white text-sm no-underline hover:no-underline block"><i
                                            class="fa fa-user fa-fw"></i> Profile</a>
                                    <a href="#"
                                        class="p-2 hover:bg-gray-800 text-white text-sm no-underline hover:no-underline block"><i
                                            class="fa fa-cog fa-fw"></i> Settings</a>
                                    <div class="border border-gray-800"></div>
                                    <a href="#"
                                        class="p-2 hover:bg-gray-800 text-white text-sm no-underline hover:no-underline block"><i
                                            class="fas fa-sign-out-alt fa-fw"></i> Log Out</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

        </nav>
    </header>
    <?php
    if (isset($_SESSION['successMessage'])) {
        echo "<div class='sucess bg-green-500 text-black font-bold  text-xl px-2 py-3 border-b fixed top-[50%] right-0 rounded '>
                 " . htmlspecialchars($_SESSION['successMessage']) . "
                 </div>";
        unset($_SESSION['successMessage']);
    }
    ?>
    <main>
        <div class="flex flex-col md:flex-row">
            <nav aria-label="alternative nav">
                <div
                    class="bg-gray-800  h-20 fixed bottom-0 mt-12 md:relative md:h-screen z-10 w-full md:w-48 content-center">

                    <div
                        class="md:mt-20 md:w-48 md:fixed md:left-0 md:top-0 content-center md:content-start text-left justify-between">
                        <ul
                            class="list-reset flex flex-row md:flex-col pt-3 md:py-3 px-1 md:px-2 text-center md:text-left">
                            <li class="mr-3 flex-1">
                                <a href="index.php"
                                    class="block py-1 md:py-3 pl-1 align-middle text-white no-underline hover:text-white border-b-2 border-gray-800 border-pink-500">
                                    <i class="fas fa-tasks pr-0 md:pr-3 text-pink-500"></i><span
                                        class="pb-1 md:pb-0 text-xs md:text-base text-gray-400 md:text-gray-200 block md:inline-block">Dashboard</span>
                                </a>
                            </li>
                            <li class="mr-3 flex-1">
                                <a href="./code/reservations.php"
                                    class="block py-1 md:py-3 pl-1 align-middle text-white no-underline hover:text-white border-b-2 border-gray-800 hover:border-purple-500">
                                    <i class="fa fa-envelope pr-0 md:pr-3"></i><span
                                        class="pb-1 md:pb-0 text-xs md:text-base text-gray-400 md:text-gray-200 block md:inline-block">Reservation</span>
                                </a>
                            </li>
                            <li class="mr-3 flex-1">
                                <a href="./code/activities.php"
                                    class="block py-1 md:py-3 pl-1 align-middle text-white no-underline hover:text-white border-b-2 border-gray-800 hover:border-blue-600">
                                    <i class="fas fa-chart-area pr-0 md:pr-3 text-white"></i><span
                                        class="pb-1 md:pb-0 text-xs md:text-base text-white md:text-white block md:inline-block">Activities</span>
                                </a>
                            </li>
                            <li class="mr-3 flex-1">
                                <a href="#"
                                    class="block py-1 md:py-3 pl-0 md:pl-1 align-middle text-white no-underline hover:text-white border-b-2 border-gray-800 hover:border-red-500">
                                    <i class="fa fa-wallet pr-0 md:pr-3"></i><span
                                        class="pb-1 md:pb-0 text-xs md:text-base text-gray-400 md:text-gray-200 block md:inline-block">Autre</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <section>
                <div id="main" class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 ">

                    <div class="bg-gray-800 pt-3">
                        <div
                            class="rounded-tl-3xl bg-gradient-to-r from-blue-900 to-gray-800 p-4 shadow text-2xl text-white">
                            <h1 class="font-bold pl-2">Dashboard</h1>
                        </div>
                    </div>

                    <div class="flex justify-center flex-wrap">
                        <div class="w-full md:w-1/2 xl:w-1/3 p-6">
                            <div
                                class="bg-gradient-to-b from-green-200 to-green-100 border-b-4 border-green-600 rounded-lg shadow-xl p-5">
                                <div class="flex flex-row items-center">
                                    <div class="flex-shrink pr-4">
                                        <div class="rounded-full p-5 bg-green-600"><i
                                                class="fa fa-wallet fa-2x fa-inverse"></i></div>
                                    </div>
                                    <div class="flex-1 text-right md:text-center">
                                        <h2 class="font-bold uppercase text-gray-600">Total Reservation</h2>
                                        <p class="font-bold text-3xl">
                                            <span class="text-green-500">
                                                <i class="fas fa-caret-up"></i>
                                                <?php
                                                $sql = "SELECT count(*) as total from reservations";
                                                $result = mysqli_query($connect, $sql);
                                                if ($result) {
                                                    $row = mysqli_fetch_assoc($result);
                                                    echo $row['total'];

                                                }
                                                ?>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-full md:w-1/2 xl:w-1/3 p-6">
                            <div
                                class="bg-gradient-to-b from-pink-200 to-pink-100 border-b-4 border-pink-500 rounded-lg shadow-xl p-5">
                                <div class="flex flex-row items-center">
                                    <div class="flex-shrink pr-4">
                                        <div class="rounded-full p-5 bg-pink-600"><i
                                                class="fas fa-users fa-2x fa-inverse"></i></div>
                                    </div>
                                    <div class="flex-1 text-right md:text-center">
                                        <h2 class="font-bold uppercase text-gray-600">Total Clients</h2>
                                        <p class="font-bold text-3xl">
                                            <span class="text-pink-500"><i class="fas fa-exchange-alt"></i>
                                                <?php
                                                $sql = 'SELECT count(*) as total from clients';
                                                $result = mysqli_query($connect, $sql);
                                                if ($result) {
                                                    $row = mysqli_fetch_assoc($result);
                                                    echo $row['total'];
                                                }

                                                ?>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="w-full md:w-1/2 xl:w-1/3 p-6">
                            <div
                                class="bg-gradient-to-b from-blue-200 to-blue-100 border-b-4 border-blue-500 rounded-lg shadow-xl p-5">
                                <div class="flex flex-row items-center">
                                    <div class="flex-shrink pr-4">
                                        <div class="rounded-full p-5 bg-blue-600"><i
                                                class="fas fa-server fa-2x fa-inverse"></i></div>
                                    </div>
                                    <div class="flex-1 text-right md:text-center">
                                        <h2 class="font-bold uppercase text-gray-600">Total Activities</h2>
                                        <p class="font-bold text-3xl">
                                            <?php
                                            $sql = "SELECT count(*) as total from activites";

                                            $resulta = mysqli_query($connect, $sql);
                                            if ($resulta) {
                                                $row = mysqli_fetch_assoc($resulta);
                                                echo $row['total'];
                                            }

                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div id='centeredFormModal'
                        class="modal-wrapper hidden fixed md:right-80 md:left-80 left-0 top-2 md:top-20 bg-gray-200 rounded-xl z-50">
                        <div class="overlay close-modal"></div>
                        <div class="modal modal-centered max-h-screen overflow-y-auto">
                            <div class="modal-content shadow-lg p-5">
                                <div class="border-b p-2 pb-3 pt-0 mb-4">
                                    <div class="flex justify-between items-center">
                                        Modal Client
                                        <span
                                            class='close-modal cursor-pointer px-3 py-1 rounded-full bg-gray-100 hover:bg-gray-200'>
                                            <i class="fas fa-times text-gray-700"></i>
                                        </span>
                                    </div>
                                </div>
                                <form id='form_id' class="w-full" method="POST" action="index.php">
                                    <?php if (!empty($errors)): ?>
                                        <div class="bg-red-200 p-3 mb-6">
                                            <?php foreach ($errors as $err): ?>
                                                <p class="text-red-500 text-xs italic"><?php echo htmlspecialchars($err); ?></p>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="flex flex-wrap -mx-3 mb-6">
                                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                                            <label
                                                class="block uppercase tracking-wide text-gray-700 text-xs font-light mb-1"
                                                for="nom">
                                                Nom
                                            </label>
                                            <input
                                                class="appearance-none block w-full  text-gray-700 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white-500"
                                                id="nom" name="nom" type="text" placeholder="">
                                            <p class="text-red-500 text-xs italic"></p>
                                        </div>
                                        <div class="w-full md:w-1/2 px-3">
                                            <label
                                                class="block uppercase tracking-wide text-gray-700 text-xs font-light mb-1"
                                                for="prenom">
                                                Prenom
                                            </label>
                                            <input
                                                class="appearance-none block w-full  text-grey-darker border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white-500 focus:border-gray-600"
                                                id="prenom" name="prenom" type="text" placeholder="">
                                        </div>
                                    </div>
                                    <div class="flex flex-wrap -mx-3 mb-6">
                                        <div class="w-full px-3">
                                            <label
                                                class="block uppercase tracking-wide text-grey-darker text-xs font-light mb-1"
                                                for="email">
                                                Email
                                            </label>
                                            <input
                                                class="appearance-none block w-full bg-grey-200 text-grey-darker border border-grey-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-grey"
                                                id="email" type="email" name="email" placeholder="example@gmail.com">
                                            <p class="text-grey-dark text-xs italic"></p>
                                        </div>
                                    </div>
                                    <div class="flex justify-center  -mx-3 mb-2 ">
                                        <div class=" w-full px-3 mb-6 md:mb-0">
                                            <label
                                                class="block uppercase tracking-wide text-grey-darker text-xs font-light mb-1"
                                                for="telephone">
                                                Phone
                                            </label>
                                            <input
                                                class="appearance-none block w-full bg-grey-200 text-grey-darker border border-grey-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-grey"
                                                id="telephone" name="telephone" type="text">
                                        </div>
                                        <div class="w-full px-3 mb-6 md:mb-0">
                                            <label
                                                class="block uppercase tracking-wide text-grey-darker text-xs font-light mb-1"
                                                for="adresse">
                                                Adresse
                                            </label>
                                            <input
                                                class="appearance-none block w-full bg-grey-200 text-grey-darker border border-grey-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-grey"
                                                id="adresse" name="adresse" type="text" placeholder="">
                                        </div>

                                    </div>
                                    <div class="w-full">
                                        <div class="  mb-6 md:mb-0">
                                            <label
                                                class="block uppercase tracking-wide text-grey-darker text-xs font-light mb-1"
                                                for="date_naissance">
                                                date Naissance
                                            </label>
                                            <input
                                                class="appearance-none block w-full bg-grey-200 text-grey-darker border border-grey-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-grey"
                                                id="date_naissance" name="date_naissance" type="date" placeholder="">
                                        </div>
                                    </div>


                                    <div class="mt-5 ">
                                        <button type="submit" name="submit"
                                            class='bg-green-500 hover:bg-green-800 text-white font-bold py-2 px-4 rounded'>
                                            Submit
                                        </button>

                                        <button type="reset" name="reset"
                                            class='close-modal cursor-pointer bg-red-200 hover:bg-red-500 text-red-900 font-bold py-2 px-4 rounded'>
                                            Close
                                        </button>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- FORM EDIT -->

                    <div id='EDIT-FormModal'
                        class="modal-wrapper hidden fixed md:right-80 md:left-80 left-0 top-2 md:top-20 bg-gray-200 rounded-xl z-50">
                        <div class="overlay close"></div>
                        <div class="modal modal-centered max-h-screen overflow-y-auto">
                            <div class="modal-content shadow-lg p-5">
                                <div class="border-b p-2 pb-3 pt-0 mb-4">
                                    <div class="flex justify-between items-center">
                                        Edit Client
                                        <span
                                            class='close cursor-pointer px-3 py-1 rounded-full bg-gray-100 hover:bg-gray-200'>
                                            <i class="fas fa-times text-gray-700"></i>
                                        </span>
                                    </div>
                                </div>
                                <form id='form_id' class="w-full" method="POST" action="index.php">
                                    <?php if (!empty($errors)): ?>
                                        <div class="bg-red-200 p-3 mb-6">
                                            <?php foreach ($errors as $err): ?>
                                                <p class="text-red-500 text-xs italic"><?php echo htmlspecialchars($err); ?></p>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php
                                    $id=$_GET['editid'];
                                    $sql = " SELECT * FROM clients where id_client='$id' ";
                                    $result = mysqli_query($connect, $sql);

                                    if ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                        <div class="flex flex-wrap -mx-3 mb-6">
                                            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                                                <label
                                                    class="block uppercase tracking-wide text-gray-700 text-xs font-light mb-1"
                                                    for="nom">
                                                    Nom
                                                </label>
                                                <input
                                                    class="appearance-none block w-full text-gray-700 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white-500"
                                                    id="nom" name="nom" type="text" value="<?php echo $row['nom']; ?>">
                                            </div>
                                            <div class="w-full md:w-1/2 px-3">
                                                <label
                                                    class="block uppercase tracking-wide text-gray-700 text-xs font-light mb-1"
                                                    for="prenom">
                                                    Prenom
                                                </label>
                                                <input
                                                    class="appearance-none block w-full text-grey-darker border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white-500 focus:border-gray-600"
                                                    id="prenom" name="prenom" type="text"
                                                    value="<?php echo $row['prenom']; ?>">
                                            </div>
                                        </div>
                                        <div class="flex flex-wrap -mx-3 mb-6">
                                            <div class="w-full px-3">
                                                <label
                                                    class="block uppercase tracking-wide text-grey-darker text-xs font-light mb-1"
                                                    for="email">
                                                    Email
                                                </label>
                                                <input
                                                    class="appearance-none block w-full bg-grey-200 text-grey-darker border border-grey-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-grey"
                                                    id="email" type="email" name="email"
                                                    value="<?php echo $row['email']; ?>">
                                            </div>
                                        </div>
                                        <div class="flex justify-center -mx-3 mb-2">
                                            <div class="w-full px-3 mb-6 md:mb-0">
                                                <label
                                                    class="block uppercase tracking-wide text-grey-darker text-xs font-light mb-1"
                                                    for="telephone">
                                                    Phone
                                                </label>
                                                <input
                                                    class="appearance-none block w-full bg-grey-200 text-grey-darker border border-grey-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-grey"
                                                    id="telephone" name="telephone" type="text"
                                                    value="<?php echo $row['telephone']; ?>">
                                            </div>
                                            <div class="w-full px-3 mb-6 md:mb-0">
                                                <label
                                                    class="block uppercase tracking-wide text-grey-darker text-xs font-light mb-1"
                                                    for="adresse">
                                                    Adresse
                                                </label>
                                                <input
                                                    class="appearance-none block w-full bg-grey-200 text-grey-darker border border-grey-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-grey"
                                                    id="adresse" name="adresse" type="text"
                                                    value="<?php echo $row['adresse']; ?>">
                                            </div>
                                        </div>
                                        <div class="w-full">
                                            <div class="mb-6 md:mb-0">
                                                <label
                                                    class="block uppercase tracking-wide text-grey-darker text-xs font-light mb-1"
                                                    for="date_naissance">
                                                    Date Naissance
                                                </label>
                                                <input
                                                    class="appearance-none block w-full bg-grey-200 text-grey-darker border border-grey-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-grey"
                                                    id="date_naissance" name="date_naissance" type="date"
                                                    value="<?php echo $row['date_naissance']; ?>">
                                            </div>
                                        </div>
                                    <?php
                                    } else {
                                        echo "<p class='text-red-500'>Aucun client trouvé.</p>";
                                    }
                                    ?>

                                    <div class="mt-5">
                                        <button type="submit" name="submit"
                                            class='bg-green-500 hover:bg-green-800 text-white font-bold py-2 px-4 rounded'>
                                            Submit
                                        </button>
                                        <button type="reset" name="reset"
                                            class='close cursor-pointer bg-red-200 hover:bg-red-500 text-red-900 font-bold py-2 px-4 rounded'>
                                            Close
                                        </button>
                                    </div>
                                </form>


                            </div>
                        </div>
                    </div>
                    <!------------------------------------------- end edit -->
                    <div class="flex flex-row flex-wrap flex-grow mt-2 w-full ">
                        <div class="flex flex-col   overflow-x-auto w-full ">
                            <div class="mb-2 border border-gray-300 rounded shadow-sm w-full">
                                <div class="bg-gray-200 px-2 py-3 border-b flex items-center justify-between">
                                    <strong>Users Table</strong>

                                    <div class="mb-2 mx-2 ">
                                        <div class="p-3">
                                            <button id="open-form"
                                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                                Add Client
                                            </button>
                                        </div>

                                    </div>
                                </div>

                                <div class="p-3 overflow-x-auto">
                                    <table class="table-auto w-full min-w-max border-collapse border">
                                        <thead>
                                            <tr>
                                                <th class="border px-4 py-2 text-left text-sm md:text-base">Name</th>
                                                <th class="border px-4 py-2 text-left text-sm md:text-base">prenom</th>
                                                <th class="border px-4 py-2 text-left text-sm md:text-base">email</th>
                                                <th class="border px-4 py-2 text-left text-sm md:text-base">telephone
                                                </th>
                                                <th class="border px-4 py-2 text-left text-sm md:text-base">address</th>
                                                <th class="border px-4 py-2 text-left text-sm md:text-base">
                                                    date_naissance</th>
                                                <th class="border px-4 py-2 text-left text-sm md:text-base">Actions</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql_show = "SELECT * FROM clients";
                                            $result = mysqli_query($connect, $sql_show);

                                            if ($result && mysqli_num_rows($result) > 0) {
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    echo "<tr>
                                                        <td class='border px-4 py-2'>{$row['nom']}</td>
                                                        <td class='border px-4 py-2'>{$row['prenom']}</td>
                                                        <td class='border px-4 py-2'>{$row['email']}</td>
                                                        <td class='border px-4 py-2'>{$row['telephone']}</td>
                                                        <td class='border px-4 py-2'>{$row['adresse']}</td>
                                                        <td class='border px-4 py-2'>{$row['date_naissance']}</td>
                                                        <td class='border px-4 py-2'>
                                                            <a  class='bg-teal-300 cursor-pointer rounded p-1 mx-1 text-green-500'>
                                                                <i class='fas fa-eye'></i>
                                                            </a>
                                                             <a href='index.php?editid={$row['id_client']}' 
                                                                class='bg-teal-300 cursor-pointer rounded p-1 mx-1 text-blue-500'>
                                                                <i class='fas fa-edit'></i>
                                                            </a>
                                                            <a class='bg-teal-300 cursor-pointer rounded p-1 mx-1 text-red-500'>
                                                                <i class='fas fa-trash'></i>
                                                            </a>
                                                        </td>
                                                    </tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='7' class='text-center'>Aucun client trouvé.</td></tr>";
                                            }
                                            ?>


                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>




    <script>

        let allerreur = document.querySelectorAll('#allerreur');
        allerreur.forEach((e) => {
            setInterval(() => {
                e.style.display = "none";
            }, 3000)
        })

        function toggleDD(myDropMenu) {
            document.getElementById(myDropMenu).classList.toggle("invisible");
        }
        //form add 
        let form = document.getElementById('centeredFormModal')
        document.getElementById('open-form').addEventListener('click', function () {
            form.classList.toggle('hidden');
        })
        //form edit
        let editForm = document.getElementById('EDIT-FormModal');
        let buttonEdit = document.querySelectorAll('.edit');

        buttonEdit.forEach((button) => {
            button.addEventListener('click', () => {
                if (editForm) {
                    editForm.classList.toggle('hidden');
                } else {
                    console.error('Modal not found!');
                }
            });
        });


        let closeedit = document.querySelectorAll('.close')
        closeedit.forEach(element => {
            element.addEventListener('click', function () {
                editForm.classList.toggle('hidden');
            })
        });






        let close = document.querySelectorAll('.close-modal')
        close.forEach(element => {
            element.addEventListener('click', function () {
                form.classList.toggle('hidden');
            })
        });

        let sucess = document.querySelector('.sucess');
        setInterval(() => {
            sucess.style.display = "none";
        }, 3000)

    </script>


</body>

</html>