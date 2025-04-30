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
(4, "Daniel","7", "https://thafd.bing.com/th/id/OIP.w4u-Jc9Xx5TE0wYyOHva_wAAAA?pid=ImgDet&w=184&h=184&c=7&dpr=1,3"),
(5, "Eric","6", "https://miro.medium.com/v2/resize:fit:2400/1*0cgoVzt2xjIDs64axeeV9w@2x.jpeg"),
(6, "Francois","5", "https://thafd.bing.com/th/id/OIP.QccB1M1GLcdOM7jGDyxT6QAAAA?pid=ImgDet&w=184&h=184&c=7&dpr=1,3"),
(7, "Gérard","4", "https://thafd.bing.com/th/id/OIP.9763FywczzmAfUPmng_shwAAAA?pid=ImgDet&w=184&h=184&c=7&dpr=1,3"),
(8, "Harold","3", "https://cdn1.iconfinder.com/data/icons/facely-metapeople-3d-avatar-set/128/3._Black_Man.png"),
(9, "Ivan","2", "https://thafd.bing.com/th/id/OIP.Gk7KHsKdlwiAy3vl1jb6bgAAAA?pid=ImgDet&w=184&h=184&c=7&dpr=1,3"),
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

ALTER TABLE Article
-- Ajouter une colonne 'categorie' à la table 'Article'
ADD categorie VARCHAR(30) NOT NULL DEFAULT 'autre';

-- Insérer des articles dans la catégorie 'vêtements'
INSERT INTO `article` (`idArticle`, `prix`, `nom`, `stock`, `description`, `lien_image`, `categorie`) VALUES
(1, 19.99, 'T-shirt \"Morpion Masterr\"', 50, 'T-shirt gris inscription MASTERR.\r\nDouble R, pour un double résultat !\r\n100% coton pour un confort tout au long de la journée', '..\\images\\Shop-images\\tshirt-morpion.png', 'vêtements'),
(2, 34.90, 'Hoodie \"Think Like a Cross\"', 30, 'Hoodie bleu marine, 80% coton, 20% polyester pour une finition éclatante et un confort élevé tout au long de la journée.', '..\\images\\Shop-images\\sweat-think-crss.png', 'vêtements'),
(3, 28.00, 'T-shirt \"X Marks the Spot\"', 58, 'T-shirt blanc 100% coton pour un confort tout au long de la journée.', '..\\images\\Shop-images\\x-marks.png', 'vêtements'),
(4, 29.90, 'Hoodie \"O Strategy\"', 25, 'Hoodie bleu marine, 80% coton, 20% polyester pour une finition éclatante et un confort élevé tout au long de la journée.', '..\\images\\Shop-images\\sweat-o-strat.png', 'vêtements');

-- Insérer des articles dans la catégorie 'accessoires'
INSERT INTO `article` (`idArticle`, `prix`, `nom`, `stock`, `description`, `lien_image`, `categorie`) VALUES
(5, 11.99, 'Mug \"Game On\"', 20, 'Mug 325ml - idéal pour un latte - en céramique blanche et brillante avec grille de morpion imprimée.', '..\\images\\Shop-images\\game-on-tasse.png', 'accessoires'),
(6, 9.99, 'Calendrier stratégique 2025', 70, 'Un mois = une citation + une grille de morpion.\r\nChaque calendrier comporte une reliure à anneaux, 26 pages et une finition qualité satinée. Parfait pour être accroché à votre mur.', '..\\images\\Shop-images\\calendrier-strat.png', 'accessoires'),
(7, 22.49, 'Gourde \"Masterr\"', 30, 'Gourde Masterr, double R pour un DOUBLE RESULTAT !\r\nChaque gourde est fabriquée en acier inoxydable alimentaire et est livrée avec un bouchon à vis.\r\nGarde vos boissons fraîches pendant 24h et chaudes pendant 12h ( faut bien hydrater votre cerveau, ce génial instrument de bataille !).', '..\\images\\Shop-images\\gourde-masterr.png', 'accessoires'),
(8, 22, 'Sous-verres \"Morpion\"', 80, 'Sous-verres avec une grille de morpion imprimée.\r\nChaque sous-verre se compose de :\r\n-Un recto en bois.\r\n-Un verso en liège.\r\nPar lot de quatre.', '..\\images\\Shop-images\\sous-verre.png', 'accessoires'),
(9, 5.50, 'Carnet \"Stratégies gagnantes\"', 90, 'Carnet de notes à spirales avec une couverture en carton épais, mais souple.\r\nPages lignées à espacement réduit pour une prise de notes compactes.\r\nContient 160 pages.', '..\\images\\Shop-images\\carnet-strat-win.png', 'accessoires');

-- Insérer des articles dans la catégorie 'goodies'
INSERT INTO `article` (`idArticle`, `prix`, `nom`, `stock`, `description`, `lien_image`, `categorie`) VALUES
(10, 1.50, 'Sticker \"Je joue X\"', 200, 'Sticker à mettre partout pour afficher ton allégeance à X.\r\nFinition glacée.', '..\\images\\Shop-images\\x-sticker.png', 'goodies'),
(11, 1.50, 'Sticker \"Je joue O\"', 200, 'Sticker à mettre partout pour afficher ton allégeance à O.\r\nFinition glacée', '..\\images\\Shop-images\\o-sticker.png', 'goodies'),
(12, 3.99, 'Sticker \"Morpion\"', 300, 'Lot de 10 stickers avec des grilles de morpion.\r\nFinition glacée', '..\\images\\Shop-images\\morpion-sticker.png', 'goodies'),
(13, 13.87, 'Secret \"How to Always Win\"', 20, 'Dépliant haute qualité ( 300-350 g/m2 ) finition mate avec toutes les astuces pour ne jamais perdre.', '..\\images\\Shop-images\\depliant-win.png', 'goodies');

-- Insérer des articles dans la catégorie 'posters'
INSERT INTO `article` (`idArticle`, `prix`, `nom`, `stock`, `description`, `lien_image`, `categorie`) VALUES
(14, 49.30, 'Poster \"Stratégie gagnante\"', 20, 'Poster avec une grille de morpion.\r\nFournit avec un cadre noir.\r\nPapier non couché de qualité musée, épais et durable, A3.', '..\\images\\Shop-images\\poster-strat.png', 'posters');
-- Insérer des articles dans la catégorie 'jeux'
INSERT INTO `article` (`idArticle`, `prix`, `nom`, `stock`, `description`, `lien_image`, `categorie`) VALUES
(15, 24.99, 'Jeu de société \"Morpion Deluxe\"', 67, 'Version deluxe du morpion avec plateau en bois.', 'jeu-deluxe.jpg', 'jeux'),
(16, 19.99, 'Jeu de cartes \"Stratégie Morpion\"', 50, 'Jeu de cartes basé sur les stratégies du morpion.', 'jeu-cartes.jpg', 'jeux'),
(17, 29.99, 'Puzzle \"Grille géante\"', 140, 'Puzzle de 1000 pièces avec une grille de morpion.', 'puzzle-grille.jpg', 'jeux'),
(18, 34.99, 'Kit \"Morpion 3D\"', 1000, 'Jeu de morpion en 3D avec pièces en plastique.', 'kit-3d.jpg', 'jeux'),
(19, 22.50, 'Jeu \"Morpion électronique\"', 25, 'Version électronique du morpion avec sons et lumières.', 'jeu-electronique.jpg', 'jeux'),
(20, 17.99, 'Jeu \"Mini Morpion\"', 400, 'Version portable du morpion pour jouer partout.', 'jeu-mini.jpg', 'jeux'),
(21, 26.99, 'Jeu \"Morpion XXL\"', 150, 'Version géante du morpion pour jouer en extérieur.', 'jeu-xxl.jpg', 'jeux');

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
(3, 1), -- T-shirt "X Marks the Spot"
(4, 1); -- Hoodie "O Strategy"

-- Offre spéciale weekend pour les accessoires
INSERT INTO a_la_promotion (idArticle, idPromotion) VALUES
(5, 2),  -- Mug "Game On"
(6, 2),  -- Calendrier stratégique 2025
(8, 2), -- Dessous de verre "Morpion"
(9, 2); -- Carnet "Stratégies gagnantes"

-- Déstockage pour les jeux
INSERT INTO a_la_promotion (idArticle, idPromotion) VALUES
(15, 3), -- Jeu de société "Morpion Deluxe"
(17, 3), -- Puzzle "Grille géante"
(20, 3), -- Jeu "Mini Morpion"
(21, 3); -- Jeu "Morpion XXL"

-- Black Friday pour tous les produits populaires
INSERT INTO a_la_promotion (idArticle, idPromotion) VALUES
(2, 4),  -- Hoodie "Think Like a Cross"
(5, 4),  -- Mug "Game On" 
(11, 4), -- Badge "Je joue X"
(12, 4), -- Badge "Je joue O"
(15, 4); -- Jeu de société "Morpion Deluxe"

-- Modifie la table achete pour un suivi des prix d'achat
ALTER TABLE achete
ADD prix_achat DECIMAL(8,2) AFTER quantité_achat,
ADD prix_original DECIMAL(8,2) AFTER prix_achat,
ADD promotion_appliquee DECIMAL(2,2) AFTER prix_original,
ADD nom_promotion VARCHAR(50) AFTER promotion_appliquee;

-- Ajouter quelques achats pour tester les best-sellers
-- Note: prix_achat et prix_original représentent les prix UNITAIRES (et non le total pour la quantité)
INSERT INTO achete (idUtilisateur, idArticle, date_achat, quantité_achat, prix_achat, prix_original, promotion_appliquee, nom_promotion) VALUES 
(1, 5, '2025-04-01 10:15:00', 3, 10.19, 11.99, 0.15, 'Offre spéciale weekend'),  -- 3 Mugs "Game On" - avec promotion weekend (15%)
(3, 2, '2025-04-02 14:30:00', 1, 27.92, 34.90, 0.20, 'Soldes d\'été'),  -- 1 Hoodie "Think Like a Cross" - avec soldes d'été (20%)
(5, 10, '2025-04-03 09:45:00', 5, 1.50, 1.50, NULL, NULL), -- 5 Stickers "Je joue X" - pas de promotion
(33, 11, '2025-04-03 09:45:00', 4, 1.50, 1.50, NULL, NULL), -- 4 Stickers "Je joue O" - pas de promotion
(33, 15, '2025-04-05 16:20:00', 2, 17.49, 24.99, 0.30, 'Déstockage'); -- 2 Jeux "Morpion Deluxe" - avec déstockage (30%)

-- Ajout de partie
INSERT INTO coup (code_coup, numero_coup) VALUES
(285, 0, 1),
(286, 4, 2),
(287, 1, 3),
(288, 8, 4),
(289, 2, 5), 
(290, 0, 1),
(291, 4, 2),
(292, 1, 3),
(293, 2, 4),
(294, 6, 5),
(295, 3, 6),
(296, 5, 7),
(297, 7, 8),
(298, 8, 9);

INSERT INTO partie(idPartie, date_premier_coup, premier_joueur, idRobot, idUtilisateur) VALUES 
(28, '2025-04-30 22:04:17', 'X', 9, 1),
(30, '2025-04-30 22:17:10', 'X', 9, 1);

INSERT INTO joue_coup(idPartie, idCoup, date_coup) VALUES
(28, 285, '2025-04-30 22:04:17'), 
(28, 286, '2025-04-30 22:04:17'), 
(28, 287, '2025-04-30 22:04:17'), 
(28, 288, '2025-04-30 22:04:17'), 
(28, 289, '2025-04-30 22:04:17'),
(30, 290, '2025-04-30 22:17:10'), 
(30, 291, '2025-04-30 22:17:11'),
(30, 292, '2025-04-30 22:17:11'), 
(30, 293, '2025-04-30 22:17:12'),
(30, 294, '2025-04-30 22:17:12'),
(30, 295, '2025-04-30 22:17:13'),
(30, 296, '2025-04-30 22:17:13'),
(30, 297, '2025-04-30 22:17:15'),
(30, 298, '2025-04-30 22:17:15');
