/*
Base: Projet Sissa DB
SGBD: Mysql 
*/

/*
CREATION DES TABLES DE LA BASE depuis le code généré par Looping
Quelques modifications: COUNTER -> INT AUTO_INCREMENT
                        BYTE -> TINYINT
*/

CREATE TABLE Coup(
   idCoup INT AUTO_INCREMENT,
   code_coup CHAR(3),
   numero_coup TINYINT,
   PRIMARY KEY(idCoup)
);

CREATE TABLE Article(
   idArticle INT AUTO_INCREMENT,
   prix DECIMAL(8,2),
   nom VARCHAR(50) NOT NULL,
   stock INT NOT NULL,
   description VARCHAR(200) NOT NULL,
   lien_image VARCHAR(50),
   PRIMARY KEY(idArticle)
);

CREATE TABLE Rang(
   idRang INT AUTO_INCREMENT,
   nomRang VARCHAR(20) NOT NULL,
   points_minimum INT NOT NULL,
   couleur_rang CHAR(7) NOT NULL,
   PRIMARY KEY(idRang)
);

CREATE TABLE Robot(
   idRobot INT AUTO_INCREMENT,
   nomRobot VARCHAR(50) NOT NULL,
   niveauRobot TINYINT NOT NULL,
   lien_icone VARCHAR(150),
   PRIMARY KEY(idRobot)
);

CREATE TABLE Promotion(
   idPromotion INT AUTO_INCREMENT,
   nom_promotion VARCHAR(50) NOT NULL,
   proportion_promotion DECIMAL(2,2) NOT NULL,
   debut_promotion DATETIME NOT NULL,
   fin_promotion DATETIME NOT NULL,
   PRIMARY KEY(idPromotion)
);

CREATE TABLE Utilisateur(
   idUtilisateur INT AUTO_INCREMENT,
   nom VARCHAR(50) NOT NULL,
   points INT NOT NULL,
   identifiant VARCHAR(50),
   mdp VARCHAR(50) NOT NULL,
   type CHAR(5) NOT NULL,
   idRang INT NOT NULL,
   PRIMARY KEY(idUtilisateur),
   FOREIGN KEY(idRang) REFERENCES Rang(idRang)
);

CREATE TABLE Partie(
   idPartie INT AUTO_INCREMENT,
   date_premier_coup DATETIME,
   premier_joueur CHAR(1),
   idRobot INT NOT NULL,
   idUtilisateur INT NOT NULL,
   PRIMARY KEY(idPartie),
   FOREIGN KEY(idRobot) REFERENCES Robot(idRobot),
   FOREIGN KEY(idUtilisateur) REFERENCES Utilisateur(idUtilisateur)
);

CREATE TABLE Remboursement(
   idRemboursement INT AUTO_INCREMENT,
   prix_remboursé DECIMAL(8,2),
   date_remboursement DATETIME,
   idUtilisateur INT NOT NULL,
   idArticle INT NOT NULL,
   PRIMARY KEY(idRemboursement),
   FOREIGN KEY(idUtilisateur) REFERENCES Utilisateur(idUtilisateur),
   FOREIGN KEY(idArticle) REFERENCES Article(idArticle)
);

CREATE TABLE achete(
   idUtilisateur INT,
   idArticle INT,
   date_achat DATETIME,
   quantité_achat INT,
   PRIMARY KEY(idUtilisateur, idArticle, date_achat),
   FOREIGN KEY(idUtilisateur) REFERENCES Utilisateur(idUtilisateur),
   FOREIGN KEY(idArticle) REFERENCES Article(idArticle)
);

CREATE TABLE joue_coup(
   idPartie INT,
   idCoup INT,
   date_coup DATETIME,
   PRIMARY KEY(idPartie, idCoup),
   FOREIGN KEY(idPartie) REFERENCES Partie(idPartie),
   FOREIGN KEY(idCoup) REFERENCES Coup(idCoup)
);

CREATE TABLE a_la_promotion(
   idArticle INT,
   idPromotion INT,
   PRIMARY KEY(idArticle, idPromotion),
   FOREIGN KEY(idArticle) REFERENCES Article(idArticle),
   FOREIGN KEY(idPromotion) REFERENCES Promotion(idPromotion)
);

ALTER TABLE coup add CONSTRAINT C_numero_coup CHECK(numero_coup between 0 and 9);

ALTER TABLE rang add CONSTRAINT C_couleur_rang CHECK(couleur_rang like "#%");

/*
* Ajout des lignes qui correspondent aux utilisateurs, leur rang etc..
*/
/*
 Modification de la table Utilisateur
*/
ALTER TABLE Utilisateur DROP COLUMN nom;
ALTER TABLE Utilisateur MODIFY COLUMN mdp VARCHAR(250);

/*
    Ajout des IA
*/
INSERT INTO robot (idRobot,	nomRobot,	niveauRobot,	lien_icone) VALUES
(1, "Alice","10", "https://docmed.ru/upload/iblock/49c/49cf137831eefc72f330fdaae639ee0b.png"),
(2, "Bob","9", "https://pbs.twimg.com/profile_images/1385724221406519300/PRJvcMCr_400x400.jpg"),
(3, "Cédric","8", "https://i.pinimg.com/736x/cf/f1/b0/cff1b00211b10b5e9820ef6494b28da3.jpg"),
(4, "Daniel","7", "https://www.popsci.com/uploads/2020/01/07/WMD5M52LJFBEBIHNEEABHVB6LA.jpg?auto=webp&width=1440&height=864"),
(5, "Eric","6", "https://attic.sh/pbhjkckp5v4gta1pazoa3zjz8bha"),
(6, "Francois","5", "https://thafd.bing.com/th/id/OIP.QccB1M1GLcdOM7jGDyxT6QAAAA?pid=ImgDet&w=184&h=184&c=7&dpr=1,3"),
(7, "Gérard","4", "https://thafd.bing.com/th/id/OIP.9763FywczzmAfUPmng_shwAAAA?pid=ImgDet&w=184&h=184&c=7&dpr=1,3"),
(8, "Harold","3", "https://cdn1.iconfinder.com/data/icons/facely-metapeople-3d-avatar-set/128/3._Black_Man.png"),
(9, "Ivan","2", "https://images.squarespace-cdn.com/content/v1/60908318cfa0741ace5d53c2/1622671636760-O7MZ9RQ2KTVE0WA4U0U7/Fichier+3%402x.png?format=1500w"),
(10, "Jean","1", "https://preview.redd.it/sfln9t4ddwt51.png?auto=webp&s=0a246cc8fc8036921d303863061660ac7fa8a5f1")
;

/*
    Ajout des rangs
*/
INSERT INTO rang (nomRang,points_minimum,couleur_rang) VALUES
("Nouveau",0,"#000000"),/*Rang par défaut*/
("Novice",150,"#c0eb75"),
("Apprenti",300,"#01ae75"),
("Expert",450,"#CB4154"),
("Maitre",750,"#8a2be2"),
("Grand-Maitre",1250,"#FF0000")
;
/*
    Ajout des utilisateurs
*/
INSERT INTO utilisateur (points,identifiant,mdp,type,idRang) VALUES
(0,"Grimdal","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","user",1),
(0,"Raptor","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","user",1),
(0,"PetiteFée","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","user",1),
(0,"Pewfan","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","user",1),
(0,"Seltade","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","user",1),
(0,"Kairrin","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","user",1),
(0,"Potaaato","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","user",1),
(0,"Neptendus","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","user",1),
(0,"RainbowMan","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","user",1),
(0,"Exominiate","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","user",1),
(0,"Meyriu","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","user",1),
(0,"Ectobiologiste","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","user",1),
(0,"ItsGodHere","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","user",1),
(0,"MaxMadMen","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","user",1),
(0,"TankerTanker","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","user",1),
(0,"Felipero","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","user",1),
(0,"TheFlyingBat","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","user",1),
(0,"JustEpic","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","user",1),
(0,"LeGrandBlond","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","user",1),
(0,"Azalee","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","user",1),
(0,"OarisKiller","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","user",1),
(0,"LeHamster","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","user",1),
(0,"Dialove","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","user",1),
(0,"Frexs","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","user",1),
(0,"Rofaly","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","user",1),
(0,"GeoMan","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","user",1),
(0,"Kirna","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","user",1),
(0,"Gruty","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","user",1),
(0,"Fridame","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","user",1),
(0,"Fluxy","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","user",1),
(0,"Drastics","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","user",1),
(0,"Grimace","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","user",1),
(0,"hbrouard","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","admin",1), /*Mot de passe: test */
(125,"Jenny","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","user",1), /*Mot de passe: test */
(2216,"Princess","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","user",6), /*Mot de passe: test */
(212,"Albert","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","user",2), /*Mot de passe: test */
(451,"PythonMan","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","user",4), /*Mot de passe: test */
(330,"Pierre","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","user",3), /*Mot de passe: test */
(120,"Jacques","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","user",1), /*Mot de passe: test */
(12,"Newt","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","user",1), /*Mot de passe: test */
(45,"Nunu","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","user",1), /*Mot de passe: test */
(151,"Will","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","user",2), /*Mot de passe: test */
(253,"BraveLava","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","user",2), /*Mot de passe: test */
(1125,"Epiccc","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","user",5) /*Mot de passe: test */
;
ALTER TABLE achete DROP PRIMARY KEY;
-- Ce changement est fait afin de permettre au même utilisateur d'acheter le même article plusieurs fois à des dates différentes.
-- On ajoute une clé primaire composite sur idUtilisateur, idArticle et date_achat
ALTER TABLE achete ADD PRIMARY KEY(idUtilisateur, idArticle, date_achat);

ALTER TABLE Article
-- Ajouter une colonne 'categorie' à la table 'Article'
ADD categorie VARCHAR(30) NOT NULL DEFAULT 'autre';

-- Insérer des articles dans la catégorie 'vêtements'
INSERT INTO `article` (`idArticle`, `prix`, `nom`, `stock`, `description`, `lien_image`, `categorie`) VALUES
(1, 19.99, 'T-shirt \"Morpion Masterr\"', 50, 'T-shirt gris inscription MASTERR.\r\nDouble R, pour un double résultat !\r\n100% coton pour un confort tout au long de la journée', '..\\images\\Shop-images\\tshirt-morpion.png', 'vêtements'),
(2, 34.90, 'Hoodie \"Think Like a Cross\"', 30, 'Hoodie bleu marine, 80% coton, 20% polyester pour une finition éclatante et un confort élevé tout au long de la journée.', '..\\images\\Shop-images\\sweat-think-crss.png', 'vêtements'),
(4, 28.00, 'T-shirt \"X Marks the Spot\"', 58, 'T-shirt blanc 100% coton pour un confort tout au long de la journée.', '..\\images\\Shop-images\\x-marks.png', 'vêtements'),
(5, 29.90, 'Hoodie \"O Strategy\"', 25, 'Hoodie bleu marine, 80% coton, 20% polyester pour une finition éclatante et un confort élevé tout au long de la journée.', '..\\images\\Shop-images\\sweat-o-strat.png', 'vêtements');

-- Insérer des articles dans la catégorie 'accessoires'
INSERT INTO `article` (`idArticle`, `prix`, `nom`, `stock`, `description`, `lien_image`, `categorie`) VALUES
(7, 11.99, 'Mug \"Game On\"', 100, 'Mug 325ml - idéal pour un latte - en céramique blanche et brillante avec grille de morpion imprimée.', '..\\images\\Shop-images\\game-on-tasse.png', 'accessoires'),
(8, 9.99, 'Calendrier stratégique 2025', 70, 'Un mois = une citation + une grille de morpion.\r\nChaque calendrier comporte une reliure à anneaux, 26 pages et une finition qualité satinée. Parfait pour être accroché à votre mur.', '..\\images\\Shop-images\\calendrier-strat.png', 'accessoires'),
(9, 22.49, 'Gourde \"Masterr\"', 120, 'Gourde Masterr, double R pour un DOUBLE RESULTAT !\r\nChaque gourde est fabriquée en acier inoxydable alimentaire et est livrée avec un bouchon à vis.\r\nGarde vos boissons fraîches pendant 24h et chaudes pendant 12h ( faut bien hydrater votre cerveau, ce génial instrument de bataille !).', '..\\images\\Shop-images\\gourde-masterr.png', 'accessoires'),
(10, 22, 'Sous-verres \"Morpion\"', 80, 'Sous-verres avec une grille de morpion imprimée.\r\nChaque sous-verre se compose de :\r\n-Un recto en bois.\r\n-Un verso en liège.\r\nPar lot de quatre.', '..\\images\\Shop-images\\sous-verre.png', 'accessoires'),
(12, 5.50, 'Carnet \"Stratégies gagnantes\"', 90, 'Carnet de notes à spirales avec une couverture en carton épais, mais souple.\r\nPages lignées à espacement réduit pour une prise de notes compactes.\r\nContient 160 pages.', '..\\images\\Shop-images\\carnet-strat-win.png', 'accessoires'),
(13, 8.99, 'Sac \"Tactique Morpion\"', 50, 'Sac en toile avec une grille de morpion imprimée.', 'sac-tactique.jpg', 'accessoires');

-- Insérer des articles dans la catégorie 'goodies'
INSERT INTO `article` (`idArticle`, `prix`, `nom`, `stock`, `description`, `lien_image`, `categorie`) VALUES
(14, 1.50, 'Badge \"Je joue X\"', 200, 'Badge à épingler pour afficher ton allégeance à X.', 'badge-x.jpg', 'goodies'),
(15, 1.50, 'Badge \"Je joue O\"', 200, 'Version O du badge.', 'badge-o.jpg', 'goodies'),
(16, 7.50, 'Pochette stratégique', 60, 'Pochette noire avec plateau brodé.', 'pochette-strategique.jpg', 'goodies'),
(17, 3.99, 'Sticker \"Morpion\"', 300, 'Lot de 10 stickers avec des grilles de morpion.', 'sticker-morpion.jpg', 'goodies'),
(18, 6.99, 'Bracelet \"X ou O\"', 150, 'Bracelet en silicone avec motifs X et O.', 'bracelet-xo.jpg', 'goodies'),
(19, 4.50, 'Aimant \"Grille Morpion\"', 100, 'Aimant pour frigo avec une grille de morpion.', 'aimant-morpion.jpg', 'goodies'),
(20, 2.99, 'Marque-page \"Stratégie\"', 80, 'Marque-page avec des astuces de morpion.', 'marque-page.jpg', 'goodies'),
(21, 5.99, 'Écusson \"Champion Morpion\"', 70, 'Écusson brodé pour les fans de morpion.', 'ecusson-champion.jpg', 'goodies');

-- Insérer des articles dans la catégorie 'posters'
INSERT INTO `article` (`idArticle`, `prix`, `nom`, `stock`, `description`, `lien_image`, `categorie`) VALUES
(22, 12.99, 'Poster \"Stratégie gagnante\"', 40, 'Poster avec une grille de morpion et les meilleures stratégies.', 'poster-strategie.jpg', 'posters'),
(23, 15.99, 'Poster \"X vs O\"', 30, 'Poster artistique représentant un duel entre X et O.', 'poster-x-vs-o.jpg', 'posters'),
(24, 10.99, 'Poster \"Grille classique\"', 50, 'Poster minimaliste avec une grille de morpion.', 'poster-grille.jpg', 'posters'),
(25, 14.50, 'Poster \"Morpion Vintage\"', 25, 'Poster au style rétro avec une grille de morpion.', 'poster-vintage.jpg', 'posters'),
(26, 13.99, 'Poster \"Championnat Morpion\"', 35, 'Poster commémoratif d’un tournoi fictif.', 'poster-championnat.jpg', 'posters'),
(27, 11.50, 'Poster \"Tactique X\"', 30, 'Poster avec des astuces pour jouer X.', 'poster-tactique-x.jpg', 'posters'),
(28, 11.50, 'Poster \"Tactique O\"', 30, 'Poster avec des astuces pour jouer O.', 'poster-tactique-o.jpg', 'posters');

-- Insérer des articles dans la catégorie 'jeux'
INSERT INTO `article` (`idArticle`, `prix`, `nom`, `stock`, `description`, `lien_image`, `categorie`) VALUES
(29, 24.99, 'Jeu de société \"Morpion Deluxe\"', 20, 'Version deluxe du morpion avec plateau en bois.', 'jeu-deluxe.jpg', 'jeux'),
(30, 19.99, 'Jeu de cartes \"Stratégie Morpion\"', 50, 'Jeu de cartes basé sur les stratégies du morpion.', 'jeu-cartes.jpg', 'jeux'),
(31, 29.99, 'Puzzle \"Grille géante\"', 14, 'Puzzle de 1000 pièces avec une grille de morpion.', 'puzzle-grille.jpg', 'jeux'),
(32, 34.99, 'Kit \"Morpion 3D\"', 10, 'Jeu de morpion en 3D avec pièces en plastique.', 'kit-3d.jpg', 'jeux'),
(33, 22.50, 'Jeu \"Morpion électronique\"', 25, 'Version électronique du morpion avec sons et lumières.', 'jeu-electronique.jpg', 'jeux'),
(34, 17.99, 'Jeu \"Mini Morpion\"', 40, 'Version portable du morpion pour jouer partout.', 'jeu-mini.jpg', 'jeux'),
(35, 26.99, 'Jeu \"Morpion XXL\"', 15, 'Version géante du morpion pour jouer en extérieur.', 'jeu-xxl.jpg', 'jeux');

-- Insertion de données de test pour les promotions

-- Ajouter quelques promotions
INSERT INTO Promotion (nom_promotion, proportion_promotion, debut_promotion, fin_promotion) VALUES
('Soldes d\'été', 0.20, '2025-04-01 00:00:00', '2025-05-31 23:59:59'),
('Offre spéciale weekend', 0.15, '2025-04-20 00:00:00', '2025-04-23 23:59:59'),
('Déstockage', 0.30, '2025-04-15 00:00:00', '2025-04-30 23:59:59'),
('Black Friday', 0.25, '2025-11-29 00:00:00', '2025-12-01 23:59:59');

-- Associer des promotions aux articles
-- Soldes d'été pour les vêtements
INSERT INTO a_la_promotion (idArticle, idPromotion) VALUES
(1, 1), -- T-shirt "Morpion Master"
(2, 1), -- Hoodie "Think Like a Cross"
(4, 1), -- T-shirt "X Marks the Spot"
(5, 1); -- Hoodie "O Strategy"

-- Offre spéciale weekend pour les accessoires
INSERT INTO a_la_promotion (idArticle, idPromotion) VALUES
(7, 2),  -- Mug "Game On"
(8, 2),  -- Calendrier stratégique 2025
(10, 2), -- Tapis de souris "Morpion"
(12, 2); -- Carnet "Stratégies gagnantes"

-- Déstockage pour les jeux
INSERT INTO a_la_promotion (idArticle, idPromotion) VALUES
(29, 3), -- Jeu de société "Morpion Deluxe"
(31, 3), -- Puzzle "Grille géante"
(34, 3), -- Jeu "Mini Morpion"
(35, 3); -- Jeu "Morpion XXL"

-- Black Friday pour tous les produits populaires
INSERT INTO a_la_promotion (idArticle, idPromotion) VALUES
(2, 4),  -- Hoodie "Think Like a Cross"
(7, 4),  -- Mug "Game On" 
(14, 4), -- Badge "Je joue X"
(15, 4), -- Badge "Je joue O"
(29, 4); -- Jeu de société "Morpion Deluxe"

-- Ajouter quelques achats pour tester les best-sellers
INSERT INTO achete (idUtilisateur, idArticle, date_achat, quantité_achat) VALUES
(1, 7, '2025-04-01 10:15:00', 3),  -- 3 Mugs "Game On"
(1, 2, '2025-04-02 14:30:00', 1),  -- 1 Hoodie "Think Like a Cross"
(1, 14, '2025-04-03 09:45:00', 5), -- 5 Badges "Je joue X"
(1, 15, '2025-04-03 09:45:00', 4), -- 4 Badges "Je joue O"
(1, 29, '2025-04-05 16:20:00', 2); -- 2 Jeux "Morpion Deluxe"
