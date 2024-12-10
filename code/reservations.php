<?php
require("connection.php");
session_start(); 
$successMessage = "";
if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['submit'] )){

    $client=trim(htmlspecialchars($_POST['id_client']));
    $activite=trim(htmlspecialchars($_POST['id_activite']));
    $date_reservation=trim(htmlspecialchars($_POST['date_reservation']));
    $status=trim(htmlspecialchars($_POST['status']));

if(empty($activite) || empty($date_reservation) || empty($status)){
    echo "Please fill all the fields";
}
$date_obj = DateTime::createFromFormat('Y-m-d', $date_reservation);
    if (!$date_obj) {
        die("Les dates sont invalides ou incohérentes.");
    }



    $sql ="INSERT INTO reservations(id_client,id_activite,date_reservation,status)
    values ('$client','$activite','$date_reservation','$status')";


if(mysqli_query($connect,$sql) == TRUE){
    header("location: reservations.php");
    $_SESSION['successMessage'] = "Réservation ajoutée avec succès !";
    exit();
}
else{
   echo "Échec de l'ajout de la réservation.";
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
                    <a href="../index.php" aria-label="Home">
                        <span class="text-xl pl-2"><img src="../assets/youcode.png" alt="logo" width="130"></i></span>
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
                                  

    <main class="">
        <div class="flex flex-col md:flex-row ">
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
                                <a href="reservations.php"
                                    class="block py-1 md:py-3 pl-1 align-middle text-white no-underline hover:text-white border-b-2 border-gray-800 border-purple-500">
                                    <i class="fa fa-envelope pr-0 md:pr-3 text-purple-500"></i><span
                                        class="pb-1 md:pb-0 text-xs md:text-base text-gray-400 md:text-gray-200 block md:inline-block">Reservation</span>
                                </a>
                            </li>
                            <li class="mr-3 flex-1">
                                <a href="./activities.php"
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
            <section class="w-full">
                <div id="main" class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 w-full">
                    <div class="bg-gray-800 pt-3">
                        <div
                            class="rounded-tl-3xl bg-gradient-to-r from-blue-900 to-gray-800 p-4 shadow text-2xl text-white">
                            <h1 class="font-bold pl-2">Reservation</h1>
                        </div>
                    </div>

                    <div class='flex flex-1  flex-col md:flex-row lg:flex-row mx-2 w-full'>
                        <div class="mb-2 mx-4 flex items-center justify-end w-full ">

                            <div class="p-3">
                                <select name="" id=""
                                    class=" text-black font-bold py-2 px-4 rounded border border-gray-500"> >
                                    <option value="" select>Tri par : </option>
                                    <option value="date_reservation">Date</option>
                                    <option value="status">Status</option>
                                </select>

                            </div>
                            <div class="p-3">
                                <button id="open-form"
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded border border-gray-500">
                                    New Reservation
                                </button>
                            </div>
                        </div>
                        <div id='centeredFormModal'
                            class="modal-wrapper hidden fixed md:right-80 md:left-80 left-0 top-0  md:top-20 bg-gray-200 rounded-xl z-50 mb-8 ">
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
                                    <form id='form_id' class="w-full " method="POST" action="reservations.php">
                                        <div class="flex flex-wrap -mx-3 mb-6">
                                            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                                                <label
                                                    class="block uppercase tracking-wide text-gray-700 text-xs font-light mb-1"
                                                    for="id_client">
                                                    Client
                                                </label>
                                                <select name="id_client" id="id_client"
                                                    class="appearance-none block w-full  text-gray-700 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white-500">
                                                    <option value="">select client</option>
                                                    
                                                    <?php
                                                        $sql = "SELECT * FROM clients";
                                                        $result = mysqli_query($connect, $sql);

                                                        if ($result) { 
                                                            while ($row = mysqli_fetch_array($result)) {
                                                                echo "<option value='" . htmlspecialchars($row['id_client']) . "'>" . htmlspecialchars($row['nom']) ." ". htmlspecialchars($row['prenom']) ."</option>";
                                                            }
                                                        } else {
                                                            echo "Erreur lors de l'exécution de la requête : " . mysqli_error($connect);
                                                        }
                                                        ?>

                                                    
                                                </select>
                                                <p class="text-red-500 text-xs italic"></p>
                                            </div>

                                            <div class="w-full md:w-1/2 px-3">
                                                <label
                                                    class="block uppercase tracking-wide text-gray-700 text-xs font-light mb-1"
                                                    for="id_activite">
                                                    Activitie
                                                </label>
                                                <select name="id_activite" id="id_activite"
                                                    class="appearance-none block w-full  text-grey-darker border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white-500 focus:border-gray-600">
                                                    <option value="">select activitie</option>
                                                    <?php
                                                        $sql = "SELECT * FROM activites";
                                                        $result = mysqli_query($connect, $sql);

                                                        if ($result) { 
                                                            while ($row = mysqli_fetch_array($result)) {
                                                                echo "<option value='" . htmlspecialchars($row['id_activite']) . "'>" . htmlspecialchars($row['titre']) . "</option>";
                                                            }
                                                        } else {
                                                            echo "Erreur lors de l'exécution de la requête : " . mysqli_error($connect);
                                                        }
                                                        ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="flex flex-wrap -mx-3 mb-6">
                                            <div class="w-full px-3">
                                                <label
                                                    class="block uppercase tracking-wide text-grey-darker text-xs font-light mb-1"
                                                    for="date_reservation">
                                                    Date Reservation
                                                </label>
                                                <input
                                                    class="appearance-none block w-full bg-grey-200 text-grey-darker border border-grey-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-grey"
                                                    id="date_reservation" name="date_reservation" type="date"
                                                    placeholder="Y-M-D">
                                                <p class="text-grey-dark text-xs italic"></p>
                                            </div>
                                        </div>
                                        <div class="flex justify-center -mx-3 mb-2">
                                            <div
                                                class="flex items-center justify-center gap-20 w-full px-3 mb-6 md:mb-0">
                                                <label class="block uppercase tracking-wide text-l font-light mb-1"
                                                    >
                                                    Statut :
                                                </label>
                                                <div class="flex justify-between items-center gap-8">
                                                    <div class="flex items-center gap-2">
                                                        <label
                                                            class="block uppercase tracking-wide text-xs font-light mb-1"
                                                            for="status_en_attente">
                                                            En Attente
                                                        </label>
                                                        <input
                                                            class=" w-5 h-5 border border-gray-500 rounded  "
                                                            id="status_en_attente" name="status" value="En_attente"
                                                            type="radio">
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <label
                                                            class="block uppercase tracking-wide text-xs font-light mb-1"
                                                            for="status_confirmee">
                                                            Confirmée
                                                        </label>
                                                        <input
                                                            class=" w-5 h-5 border border-gray-500 rounded  "
                                                            id="status_confirmee" name="status" value="Confirmée"
                                                            type="radio">
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <label
                                                            class="block uppercase tracking-wide text-xs font-light mb-1"
                                                            for="status_annulee">
                                                            Annulée
                                                        </label>
                                                        <input
                                                            class=" w-5 h-5 border border-gray-500 rounded  "
                                                            id="status_annulee" name="status" value="Annulée"
                                                            type="radio">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-1 flex justify-between ">
                                            <span
                                                class='close-modal cursor-pointer bg-red-200 hover:bg-red-500 text-red-900 font-bold py-2 px-4 rounded'>
                                                Close
                                            </span>
                                            <button
                                             type="submit"
                                            name="submit"
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
                        <div class="flex flex-col mx-2  overflow-x-auto w-full ">
                            <div class="mb-2 border border-gray-300 rounded shadow-sm w-full">
                                <div class="bg-gray-200 px-2 py-3 border-b flex justify-between items-center">
                                    <strong>Reservations Table</strong>
                                </div>
                                <div class="p-3 overflow-x-auto">
                                    <table class="table-auto w-full min-w-max border-collapse border">
                                        <thead>
                                            <tr>
                                                <th class="border px-4 py-2 text-left text-sm md:text-base">ID Client</th>
                                                <th class="border px-4 py-2 text-left text-sm md:text-base">ID Activitie</th>
                                                <th class="border px-4 py-2 text-left text-sm md:text-base">Date Reservation</th>
                                                <th class="border px-4 py-2 text-left text-sm md:text-base">Status
                                                </th>
                                                <th class="border px-4 py-2 text-left text-sm md:text-base">Actions</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                    $sql = "SELECT reservations.date_reservation, reservations.status, clients.nom, activites.titre 
                                                    FROM reservations
                                                    JOIN clients ON reservations.id_client = clients.id_client
                                                    JOIN activites ON reservations.id_activite = activites.id_activite"; 
                                                   
                                            
                                            $result = mysqli_query($connect, $sql);
                                            if($result){
                                                while ($row = mysqli_fetch_array($result)) {
                                                    echo "<tr>";
                                                    echo"<td>$row[nom]</td>";
                                                    echo"<td>$row[titre]</td>";
                                                    echo"<td>$row[date_reservation]</td>";
                                                    if($row['status'] == "En_attente") {
                                                        echo"<td class='text-indigo-600'>$row[status]</td>";
                                                    }else if( $row["status"] == "Confirmée") {
                                                        echo"<td class='text-green-600'>$row[status]</td>";
                                                    }else{
                                                        echo"<td class='text-red-600'>$row[status]</td>";
                                                    }
                                                    echo " <td class=border px-4 py-2>
                                                       
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
                                            }else{
                                                echo " aucun data sur la table ". mysqli_error($connect);
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

        let sucess=document.querySelector('.sucess');
        setInterval(()=>{
            sucess.style.display="none";
        },3000)



        /*    show and close model add activitie  */
        let form = document.getElementById('centeredFormModal')

        document.getElementById('open-form').addEventListener('click', function () {
            form.classList.toggle('hidden');
        })

        let close = document.querySelectorAll('.close-modal')
        close.forEach(element => {
            element.addEventListener('click', function () {
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