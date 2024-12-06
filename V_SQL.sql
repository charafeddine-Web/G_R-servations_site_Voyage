create database agence_de_voyage;
use agence_de_voyage;

create table clients(
id_client INT primary key auto_increment Not null ,
nom varchar(100),
prenom varchar(100),
email varchar(150) not null unique,
telephone varchar(15),
adresse text,
date_naissance date
);
use agence_de_voyage;

create table activites(
id_activite INT  primary key not null auto_increment,
titre varchar(150),
description text,
destination varchar(100),
prix decimal(10.2) not null,
date_debut date,
date_fin date ,
places_disponibles INT not null 
);

create table reservations(
id_reservation INT primary key not null auto_increment,
id_client INT,
id_activite INT,
date_reservation timestamp ,
status enum('En_attente','Confirmée','Annulée'),
CONSTRAINT fk_reservation_client FOREIGN KEY (id_client) REFERENCES clients(id_client) ON DELETE CASCADE,
CONSTRAINT fk_reservation_activite FOREIGN KEY (id_activite) REFERENCES activites(id_activite) ON DELETE CASCADE
);

/******************************* Insertion des donnees **********************************************/

INSERT INTO clients (nom, prenom, email, telephone, adresse, date_naissance) 
VALUES 
('tbibzat', 'charaf', 'charaf.tbibzat@example.com', '0601234567', '12 Rue de Paris', '2004-03-15'),
('Azzedine', 'zemmari', 'zemmari.azzdine@example.com', '0612345678', '45 Avenue de Lyon', '2000-07-22'),
('ilyas', 'phasi', 'phasi.ilyas@example.com', '0623456789', '78 Boulevard de Marseille', '2005-01-10');

INSERT INTO activites (titre, description,destination, prix, date_debut, date_fin, places_disponibles) 
VALUES 
('Cours de yoga', 'Yoga relaxant pour débutants','casa' ,50.00, '2024-12-10', '2024-12-15', 20),
('Randonnée en montagne', 'Excursion guidée en montagne','paris', 75.00, '2024-12-20', '2024-12-22', 15),
('Atelier de cuisine', 'Apprentissage des bases de la cuisine','tanger', 40.00, '2024-12-25', '2024-12-26', 10);

/*TRUNCATE   agence_de_voyage.activites;*/

INSERT INTO reservations (id_client, id_activite, date_reservation, status) 
VALUES 
(1, 1, CURRENT_TIMESTAMP, 'En_attente'),
(2, 2, CURRENT_TIMESTAMP, 'Confirmée'),
(3, 3, CURRENT_TIMESTAMP, 'Annulée');


/****************************** Update Activite **************************************************/
update activites 
set 
titre="Trip",
description="Trip with Friende",
prix=5000,
date_debut="2024-12-25",
date_fin="2024-05-25",
places_disponibles=5
where id_activite=1;
/*select * from activites;*/

/*******************************DELETE RESERVATION **************************************************/
select * from reservations;
DELETE from reservations where id_reservation=1;

/************************************Jointure*********************************************************/

SELECT 
    activites.*, 
    clients.nom, 
    clients.prenom
FROM activites
INNER JOIN reservations ON activites.id_activite = reservations.id_activite
INNER JOIN clients ON reservations.id_client = clients.id_client



