<?php
require("connection.php");

if($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['submit'])){

    $titre = trim(htmlspecialchars($_POST['titre']));
    $destination =  trim(htmlspecialchars($_POST['destination'])) ;
    $prix =  trim(htmlspecialchars($_POST['prix'])) ;
    $date_debut =  trim(htmlspecialchars($_POST['date_debut'])) ;
    $date_fin =  trim(htmlspecialchars($_POST['date_fin'])) ;
    $places_disponibles =  trim(htmlspecialchars( $_POST['places_disponibles']));
    $description =  trim(htmlspecialchars( $_POST['description']));

    if (empty($titre) || empty($destination) || empty($prix) || empty($date_debut) || empty($date_fin) || empty($places_disponibles) || empty($description)) {
        die("Tous les champs sont requis.");
    }
    if (!is_numeric($prix) || $prix <= 0) {
        die("Le prix doit être un nombre positif.");
    }

    if (!is_numeric($places_disponibles) || $places_disponibles <= 0) {
        die("Les places disponibles doivent être un entier positif.");
    }
    $date_debut_obj = DateTime::createFromFormat('Y-m-d', $date_debut);
    $date_fin_obj = DateTime::createFromFormat('Y-m-d', $date_fin);
    if (!$date_debut_obj || !$date_fin_obj || $date_debut_obj > $date_fin_obj) {
        die("Les dates sont invalides ou incohérentes.");
    }

$sql_activites="INSERT INTO activites (titre,destination,prix,date_debut,date_fin,places_disponibles,description)
values ('$titre','$destination','$prix','$date_debut','$date_fin','$places_disponibles','$description')";

if($connect->query($sql_activites) == FALSE){
    echo "Erreur : " . mysqli_error($connect);
}else{
    echo "Données ajoutées avec succès";
    header("Location: activities.php");
    exit();
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
    <link href="https://afeld.github.io/emoji-css/emoji.css" rel="stylesheet"> <!--Totally optional :) -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js" integrity="sha256-xKeoJ50pzbUGkpQxDYHD7o7hxe0LaOGeguUidbq6vis=" crossorigin="anonymous"></script> -->

</head>

<body class="bg-gray-800 font-sans leading-normal tracking-normal mt-12">
    <header>
        <nav aria-label="menu nav" class="bg-gray-800 pt-2 md:pt-1 pb-1 px-1 mt-0 h-auto fixed w-full z-20 top-0">

            <div class="flex flex-wrap items-center">
            <div class="flex flex-shrink md:w-1/3 justify-center md:justify-start text-white">
                    <a href="#" aria-label="Home">
                        <span class="text-xl pl-2"><img src="../assets/youcode.png" alt="logo" width="130"></span>
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
                        <!-- <li class="flex-1 md:flex-none md:mr-3">
                        <a class="inline-block py-2 px-4 text-white no-underline" href="#">Active</a>
                    </li>
                    <li class="flex-1 md:flex-none md:mr-3">
                        <a class="inline-block text-gray-400 no-underline hover:text-gray-200 hover:text-underline py-2 px-4" href="#">link</a>
                    </li> -->
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

    <main class="">
        <div class="flex flex-col md:flex-row">
            <nav aria-label="alternative nav">
                <div
                    class="bg-gray-800  h-20 fixed bottom-0 mt-12 md:relative md:h-screen z-10 w-full md:w-48 content-center">

                    <div
                        class="md:mt-20 md:w-48 md:fixed md:left-0 md:top-0 content-center md:content-start text-left justify-between">
                        <ul
                            class="list-reset flex flex-row md:flex-col pt-3 md:py-3 px-1 md:px-2 text-center md:text-left">
                            <li class="mr-3 flex-1">
                                <a href="../index.php"
                                    class="block py-1 md:py-3 pl-1 align-middle text-white no-underline hover:text-white border-b-2 border-gray-800 hover:border-pink-500">
                                    <i class="fas fa-tasks pr-0 md:pr-3"></i><span
                                        class="pb-1 md:pb-0 text-xs md:text-base text-gray-400 md:text-gray-200 block md:inline-block">Dashboard</span>
                                </a>
                            </li>
                            <li class="mr-3 flex-1">
                                <a href="./reservations.php"
                                    class="block py-1 md:py-3 pl-1 align-middle text-white no-underline hover:text-white border-b-2 border-gray-800 hover:border-purple-500">
                                    <i class="fa fa-envelope pr-0 md:pr-3"></i><span
                                        class="pb-1 md:pb-0 text-xs md:text-base text-gray-400 md:text-gray-200 block md:inline-block">Reservation</span>
                                </a>
                            </li>
                            <li class="mr-3 flex-1">
                                <a href="activities.php"
                                    class="block py-1 md:py-3 pl-1 align-middle text-white no-underline hover:text-white border-b-2 border-blue-600">
                                    <i class="fas fa-chart-area pr-0 md:pr-3 text-blue-600"></i><span
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
            <section class="">
                <div id="main" class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 w-full">
                    <div class="bg-gray-800 pt-3">
                        <div
                            class="rounded-tl-3xl bg-gradient-to-r from-blue-900 to-gray-800 p-4 shadow text-2xl text-white">
                            <h1 class="font-bold pl-2">Activities</h1>
                        </div>
                    </div>

                    <div class='flex flex-1  flex-col md:flex-row lg:flex-row '>
                        <div
                            class="mb-2 mx-4 flex items-center justify-end w-full ">
                            
                            <div class="p-3">
                                <select name="" id=""class=" text-black font-bold py-2 px-4 rounded border border-gray-800">                                >
                                    <option value="" select>Tri par : </option>
                                    <option value="date_debut">Date Debut</option>
                                    <option value="date_fin">Date Fin</option>
                                    <option value="prix">Prix</option>
                                    <option value="destination">Restination</option>
                                </select>
                                
                            </div>
                            <div class="p-3">
                                <button id="open-form"
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded border border-gray-800">
                                    New Activitie
                                </button>
                            </div>
                        </div>
                        <div id='centeredFormModal' class="modal-wrapper hidden fixed md:right-80 md:left-80 left-0 top-0  md:top-20 bg-gray-200 rounded-xl z-50 mb-8 ">
                            <div class="overlay close-modal"></div>
                            <div class="modal modal-centered">
                                <div class="modal-content shadow-lg p-5">
                                    <div class="border-b p-2 pb-3 pt-0 ">
                                        <div class="flex justify-between items-center">
                                            Modal Activities
                                            <span
                                                class='close-modal cursor-pointer px-3 py-1 rounded-full bg-gray-100 hover:bg-gray-200'>
                                                <i class="fas fa-times text-gray-700"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <form id='form_id' class="w-full " method="POST" action="activities.php">
                                        <div class="flex flex-wrap -mx-3 mb-6">
                                            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                                                <label
                                                    class="block uppercase tracking-wide text-gray-700 text-xs font-light mb-1"
                                                    for="titre">
                                                    Titre
                                                </label>
                                                <input
                                                    class="appearance-none block w-full  text-gray-700 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white-500"
                                                    id="titre" name="titre" type="text" placeholder="trip">
                                                <p class="text-red-500 text-xs italic"></p>
                                            </div>
                                            <div class="w-full md:w-1/2 px-3">
                                                <label
                                                    class="block uppercase tracking-wide text-gray-700 text-xs font-light mb-1"
                                                    for="destination">
                                                    Destination
                                                </label>
                                                <input
                                                    class="appearance-none block w-full  text-grey-darker border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white-500 focus:border-gray-600"
                                                    id="destination" name="destination" type="text" placeholder="Paris">
                                            </div>
                                        </div>
                                        <div class="flex flex-wrap -mx-3 mb-6">
                                            <div class="w-full px-3">
                                                <label
                                                    class="block uppercase tracking-wide text-grey-darker text-xs font-light mb-1"
                                                    for="prix">
                                                    Prix
                                                </label>
                                                <input
                                                    class="appearance-none block w-full bg-grey-200 text-grey-darker border border-grey-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-grey"
                                                    id="prix" name="prix" type="text" placeholder="99$">
                                                <p class="text-grey-dark text-xs italic"></p>
                                            </div>
                                        </div>
                                        <div class="flex justify-center  -mx-3 mb-2 ">
                                            <div class=" w-full px-3 mb-6 md:mb-0">
                                                <label
                                                    class="block uppercase tracking-wide text-grey-darker text-xs font-light mb-1"
                                                    for="date_debut">
                                                    Date Debut
                                                </label>
                                                <input
                                                    class="appearance-none block w-full bg-grey-200 text-grey-darker border border-grey-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-grey"
                                                    id="date_debut" name="date_debut" type="text" placeholder="2024-12-20">
                                            </div>
                                            <div class="w-full px-3 mb-6 md:mb-0">
                                                <label
                                                    class="block uppercase tracking-wide text-grey-darker text-xs font-light mb-1"
                                                    for="date_fin">
                                                    Date Fin
                                                </label>
                                                <input
                                                    class="appearance-none block w-full bg-grey-200 text-grey-darker border border-grey-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-grey"
                                                    id="date_fin" name="date_fin" type="text" placeholder="2024-01-01">  
                                            </div>
                                            
                                        </div>
                                        <div class="w-full">
                                            <div class="  mb-6 md:mb-0">
                                                    <label
                                                        class="block uppercase tracking-wide text-grey-darker text-xs font-light mb-1"
                                                        for="places_disponibles">
                                                        Places disposable
                                                    </label>
                                                    <input
                                                        class="appearance-none block w-full bg-grey-200 text-grey-darker border border-grey-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-grey"
                                                        id="places_disponibles" name="places_disponibles" type="text" placeholder="10">
                                            </div>
                                        </div>
                                        <div class="w-full">
                                            <div class="  mb-2 md:mt-2">
                                                    <label
                                                        class="block uppercase tracking-wide text-grey-darker text-xs font-light mb-1"
                                                        for="description">
                                                        Description
                                                    </label>
                                                    <textarea
                                                        class="appearance-none block w-full bg-grey-200 text-grey-darker border border-grey-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-grey"
                                                        id="description" name="description"  placeholder="description">
                                                    </textarea>
                                            </div>
                                        </div>
                                       

                                        <div class="mt-1 flex justify-between ">
                                        <button
                                        type="reset"   
                                            class='close-modal cursor-pointer bg-red-200 hover:bg-red-500 text-red-900 font-bold py-2 px-4 rounded'>
                                            Close
                                        </button>
                                            <button
                                            name="submit"
                                            type="submit"
                                                class='bg-green-500 hover:bg-green-800 text-white font-bold py-2 px-4 rounded'>
                                                Submit 
                                            </button>
                                           
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="flex flex-row flex-wrap flex-grow mt-2 w-full ">
                        <div class="flex flex-col mx-2   w-full ">
                            <div class="mb-2 border border-gray-300 rounded shadow-sm w-full">
                                <div class="bg-gray-200 px-2 py-3 border-b">
                                    Activities Table
                                </div>
                                <div class="p-3 overflow-x-auto">
                                    <table class="table-auto w-full min-w-max border-collapse border">
                                        <thead>
                                            <tr>
                                                <th class="border px-4 py-2 text-left text-sm md:text-base">Titre</th>
                                                <th class="border px-4 py-2 text-left text-sm md:text-base">Destination</th>
                                                <th class="border px-4 py-2 text-left text-sm md:text-base">Prix</th>
                                                <th class="border px-4 py-2 text-left text-sm md:text-base">Date debut
                                                </th>
                                                <th class="border px-4 py-2 text-left text-sm md:text-base">Date Fin</th>
                                                <th class="border px-4 py-2 text-left text-sm md:text-base">
                                                Description</th>
                                                <th class="border px-4 py-2 text-left text-sm md:text-base">Places Disponible</th>
                                                <th class="border px-4 py-2 text-left text-sm md:text-base">Actions</th>


                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $sql="SELECT *  FROM activites";
                                            $result = $connect->query($sql);
                                            if($result->num_rows > 0){
                                            while($row = $result->fetch_assoc()){
                                                echo "<tr>";
                                                echo "<td class='border px-4 py-2'> {$row['titre']}</td>";
                                                echo "<td class='border px-4 py-2'> {$row['destination']}</td>";
                                                echo "<td class='border px-4 py-2'> {$row['prix']}</td>";
                                                echo "<td class='border px-4 py-2'> {$row['date_debut']}</td>";
                                                echo "<td class='border px-4 py-2'> {$row['date_fin']}</td>";
                                                echo "<td class='border px-4 py-2'> {$row['description']}</td>";
                                                echo "<td class='border px-4 py-2'> {$row['places_disponibles']}</td>";
                                                echo "<td class='border px-4 py-2'>
                                                    <a
                                                        class='bg-teal-300 cursor-pointer rounded p-1 mx-1 text-green-500'>
                                                        <i class='fas fa-eye'></i>
                                                    </a>
                                                    <a
                                                        class='bg-teal-300 cursor-pointer rounded p-1 mx-1 text-blue-500'>
                                                        <i class='fas fa-edit'></i>
                                                    </a>
                                                    <a class='bg-teal-300 cursor-pointer rounded p-1 mx-1 text-red-500'>
                                                        <i class='fas fa-trash'></i>
                                                    </a>
                                                </td>";
                                                echo "</tr>";
                                            }
                                        }
                                            
                                            ?>




<!-- 
                                            <tr>
                                                <td class="border px-4 py-2">Micheal </td>
                                                <td class="border px-4 py-2">Clarke</td>
                                                <td class="border px-4 py-2">Clarke@gmail.com</td>
                                                <td class="border px-4 py-2">+212 659848273</td>
                                                <td class="border px-4 py-2">safi 3zib dr3i</td>
                                                <td class="border px-4 py-2">2004-20-10</td>
                                                </td>
                                                <td class="border px-4 py-2">
                                                    <a
                                                        class="bg-teal-300 cursor-pointer rounded p-1 mx-1 text-green-500">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a
                                                        class="bg-teal-300 cursor-pointer rounded p-1 mx-1 text-blue-500">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a class="bg-teal-300 cursor-pointer rounded p-1 mx-1 text-red-500">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr> -->
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
/*    show and close model add activitie  */
let form=document.getElementById('centeredFormModal')

    document.getElementById('open-form').addEventListener('click',function () {
        form.classList.toggle('hidden');
    })

let close=document.querySelectorAll('.close-modal')
    close.forEach(element => {
        element.addEventListener('click',function () {
        form.classList.toggle('hidden');
    })
    });

/**********end code **************** */

function toggleDD(myDropMenu) {
    document.getElementById(myDropMenu).classList.toggle("invisible");
}

    </script>

</body>

</html>


<!-- <section class="form_staf fixed top-10  md:top-20  md:left-80 md:right-80  bg-gray-900 text-white p-8 rounded-lg shadow-lg z-50">
    <form action="" method="post" class="space-y-6">
        <div class="flex items-center gap-4 w-full flex-wrap md:flex-nowrap ">
            <div class="flex flex-col space-y-2 w-full ">
                <label for="fullname" class="text-sm font-medium">Full Name</label>
                <input 
                    type="text" 
                    id="fullname" 
                    name="fullname" 
                    class="w-full px-4 py-2 rounded border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-600 text-gray-900">
            </div>

            <div class="flex flex-col space-y-2 w-full">
                <label for="Tell" class="text-sm font-medium">Phone</label>
                <input 
                    type="text" 
                    id="Tell" 
                    name="Tell" 
                    class="w-full px-4 py-2 rounded border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-600 text-gray-900">
            </div>
        </div>
       
        <div class="flex flex-col space-y-2">
            <label for="date_naissence" class="text-sm font-medium">Date of Birth</label>
            <input 
                type="date" 
                id="date_naissence" 
                name="date_naissence" 
                class="w-full px-4 py-2 rounded border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-600 text-gray-900">
        </div>

        <div class="flex flex-col space-y-2">
            <label for="groupe" class="text-sm font-medium">Group</label>
            <input 
                type="text" 
                id="groupe" 
                name="groupe" 
                class="w-full px-4 py-2 rounded border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-600 text-gray-900">
        </div>

        <div class="flex flex-col space-y-2">
            <label for="date_recrut" class="text-sm font-medium">Date Recruited</label>
            <input 
                type="date" 
                id="date_recrut" 
                name="date_recrut" 
                class="w-full px-4 py-2 rounded border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-600 text-gray-900">
        </div>

        <div class="flex justify-between items-center space-x-4 mt-4">
            <button 
                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded transition duration-200">
                Save
            </button>
            <button 
                type="button" 
                class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded transition duration-200">
                Close
            </button>
        </div>
    </form>
</section> -->