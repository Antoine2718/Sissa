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
   PRIMARY KEY(idUtilisateur, idArticle),
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

ALTER TABLE rang add CONSTRAINT C_couleur_rang CHECK(couleur_rang like "#%")
