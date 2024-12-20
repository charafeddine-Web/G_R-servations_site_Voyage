<?php
require("connection.php");
session_start();
$successMessage="";
$deletemessgae = "";

$error=[];
if($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['submit'])){

    $titre = trim(htmlspecialchars($_POST['titre']));
    $destination =  trim(htmlspecialchars($_POST['destination'])) ;
    $prix =  trim(htmlspecialchars($_POST['prix'])) ;
    $date_debut =  trim(htmlspecialchars($_POST['date_debut'])) ;
    $date_fin =  trim(htmlspecialchars($_POST['date_fin'])) ;
    $places_disponibles =  trim(htmlspecialchars( $_POST['places_disponibles']));
    $description =  trim(htmlspecialchars( $_POST['description']));

    if (empty($titre) || empty($destination) || empty($prix) || empty($date_debut) || empty($date_fin) || empty($places_disponibles) || empty($description)) {
        $error[] = "Tous les champs sont requis.";
    }
    if (!is_numeric($prix) || $prix <= 0) {
        $error[] = "Le prix doit être un nombre positif.";
    }

    if (!is_numeric($places_disponibles) || $places_disponibles <= 0) {
        $error[] = "Les places disponibles doivent être un entier positif.";
    }
    $date_debut_obj = DateTime::createFromFormat('Y-m-d', $date_debut);
    $date_fin_obj = DateTime::createFromFormat('Y-m-d', $date_fin);
    if (!$date_debut_obj || !$date_fin_obj || $date_debut_obj > $date_fin_obj) {
        $error[] = "Les dates sont invalides ou incohérentes.";

    }
 
 
    $sql_exist="SELECT * from activites where titre='$titre'";
    $result_exist = mysqli_query($connect, $sql_exist);
    if (mysqli_num_rows($result_exist) > 0) {
        $error[] = "Une activité avec ce titre existe déjà.";
    }


if(empty($error)){
    $sql_activites="INSERT INTO activites (titre,destination,prix,date_debut,date_fin,places_disponibles,description)
    values ('$titre','$destination','$prix','$date_debut','$date_fin','$places_disponibles','$description')";

    if($connect->query($sql_activites) == FALSE){
        $error[] = "Erreur lors de l'ajout de l'activité: ";
    }else{
        $_SESSION["successMessage"]= "Activitie ajoutée avec succès !";
        header("Location: activities.php");
        exit();
    }

}

}


if (!empty($error)) {
    foreach ($error as $err) {
        echo "<div id='allerreur' class='bg-red-500 text-white font-bold py-2 px-4 mb-4 mt-8 mx-10 md:mx-20 md:ml-80 text-center rounded flex  gap-2'>";
        echo "<p>" . htmlspecialchars($err) . "</p>";
        echo "</div>";
    }
}

if(isset($_GET['delete_id'])){
    $delete_id=$_GET['delete_id'];

    $sql="DELETE from activites where id_activite=' $delete_id'";

    $result=mysqli_query($connect,$sql);
    if($result){
        header( "Location: activities.php");
        $_SESSION["deletemessgae"]= "Activitie  deleted successfully.!";
        exit();
    }else{
        $error[]="Activitie NO  deleted";
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
        <nav aria-label="menu nav" class="bg-gray-800 pt-2 md:pt-1 pb-1 px-1 mt-0 h-auto fixed w-full z-20 top-0 ">

            <div class="flex flex-wrap items-center">
            <div class="flex flex-shrink md:w-1/3 justify-center md:justify-start text-white">
                    <a href="#" aria-label="Home">
                        <span class="text-xl pl-2"><img src="../assets/youcode.png" alt="logo" width="130"></span>
                    </a>
                </div>

                <div class="flex flex-1 md:w-1/3 justify-center md:justify-start text-white px-2 pt-4">
                    <span class="relative w-full">
                        <form action="">
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
                        </form>
                    </span>
                </div>

                <div class="flex w-full pt-2 content-center justify-between md:w-1/3 md:justify-end">
                    <ul class="list-reset flex justify-between flex-1 md:flex-none items-center">
                        <li class="flex-1 md:flex-none md:mr-3">
                            <div class="relative inline-block">
                                <button onclick="toggleDD('myDropdown')" class="drop-button text-white py-2 px-2"> <span
                                        class="pr-2"><i class="em em-robot_face"></i></span> Hi, Admin <svg
                                        class="h-3 fill-current inline" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path
                                            d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                                    </svg></button>
                                <div id="myDropdown"
                                    class="dropdownlist absolute bg-gray-800 text-white right-0 mt-3 p-3 overflow-auto z-30 invisible">
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
               echo "<div class='sucess bg-green-500 text-black font-bold  text-xl px-2 py-3 border-b fixed  right-0 rounded ' style='margin-top:20px'>
                 " . htmlspecialchars($_SESSION['successMessage']) . "
                 </div>";
                unset($_SESSION['successMessage']); 
              }

            if (isset($_SESSION['deletemessgae'])) {
                echo "<div class='sucessdelete bg-green-500 text-black font-bold  text-xl px-2 py-3 border-b fixed  right-0 rounded ' style='margin-top:20px'>
                  " . htmlspecialchars($_SESSION['deletemessgae']) . "
                  </div>";
                 unset($_SESSION['deletemessgae']); 
               }
    ?>
           
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
                            <li class="mr-3 flex-1 ">
                                <a href="#"
                                    class="block py-1 md:py-3 pl-0 md:pl-1 align-middle text-white no-underline hover:text-white border-b-2 border-gray-800 hover:border-red-500">
                                    <i class="fa fa-wallet pr-0 md:pr-3"></i><span
                                        class="pb-1 md:pb-0 text-xs md:text-base text-gray-400 md:text-gray-200 block md:inline-block ">Log Out</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <section class=" w-full ">
                <div id="main" class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 w-full">
                    <div class="bg-gray-800 pt-3">
                        <div
                            class="rounded-tl-3xl bg-gradient-to-r from-blue-900 to-gray-800 p-4 shadow text-2xl text-white">
                            <h1 class="font-bold pl-2">Activities</h1>
                        </div>
                    </div>

                    <div class='flex flex-1  flex-col md:flex-row lg:flex-row '>
                        <div
                            class="mb-2 flex items-center justify-end w-full ">
                            
                            <div class="p-3">
                                <select name="" id=""class=" text-black font-bold py-2 px-4 rounded border border-gray-500">                                >
                                    <option value="" select>Tri par : </option>
                                    <option value="date_debut">Date Debut</option>
                                    <option value="date_fin">Date Fin</option>
                                    <option value="prix">Prix</option>
                                    <option value="destination">Restination</option>
                                </select>
                                
                            </div>
                            <div class="p-3">
                                <button id="open-form"
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded border border-gray-500">
                                    New Activitie
                                </button>
                            </div>
                        </div>
                        <div id='centeredFormModal' class="modal-wrapper  hidden fixed md:right-80 md:left-80 left-0 top-0  md:top-0 bg-gray-200 rounded-xl z-50 mb-8 ">
                            <div class="overlay close-modal"></div>
                            <div class="modal modal-centered max-h-screen overflow-y-auto ">
                                <div class="modal-content shadow-lg p-2">
                                    <div class="border-b  pb-2 pt-0 ">
                                        <div class="flex justify-between items-center">
                                            Modal Activities
                                            <span
                                                class='close-modal cursor-pointer px-3 py-1 rounded-full bg-gray-100 hover:bg-gray-200'>
                                                <i class="fas fa-times text-gray-700"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <form id='form_id' class="w-full  " method="POST" action="activities.php">
                                      <?php if (!empty($error)) : ?>
                                            <div class="bg-red-200 p-3 mb-6">
                                                <?php foreach ($error as $err): ?>
                                                    <p class="text-red-500 text-xs italic"><?php echo htmlspecialchars($err); ?></p>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                        
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
                                                    id="prix" name="prix" type="text" placeholder="99">
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
                                                    id="date_debut" name="date_debut" type="date" placeholder="2024-12-20">
                                            </div>
                                            <div class="w-full px-3 mb-6 md:mb-0">
                                                <label
                                                    class="block uppercase tracking-wide text-grey-darker text-xs font-light mb-1"
                                                    for="date_fin">
                                                    Date Fin
                                                </label>
                                                <input
                                                    class="appearance-none block w-full bg-grey-200 text-grey-darker border border-grey-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-grey"
                                                    id="date_fin" name="date_fin" type="date" placeholder="2024-01-01">  
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
                                       

                                        <div class="mt-1 flex justify-center items-center gap-4">
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
                        <div class="flex flex-col   w-full ">
                            <div class="mb-2 border border-gray-300 rounded shadow-sm w-full">
                                <div class="bg-gray-200 px-2 py-3 border-b">
                                    Activities Table
                                </div>
                                <div class=" overflow-x-auto">
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
                                                
                                                    <a href='activities.php?delete_id={$row['id_activite']}' class='bg-teal-300 cursor-pointer rounded p-1 mx-1 text-red-500'>
                                                        <i class='fas fa-trash'></i>
                                                    </a>
                                                </td>";
                                                echo "</tr>";
                                            }
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

let allerreur=document.querySelectorAll('#allerreur');
allerreur.forEach((e)=>{
    setInterval(()=>{
    e.style.display="none";
},3000)
})


        let sucess=document.querySelector('.sucess');
        setInterval(()=>{
            sucess.style.display="none";
        },3000)

        
        let sucessdelete=document.querySelector('.sucessdelete');
        setInterval(()=>{
            sucessdelete.style.display="none";
        },3000)

        
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
