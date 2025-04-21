/*
* Ajout des lignes qui correspondent aux utilisateurs, leur rang etc..
*/
/*
 Modification de la table Utilisateur
*/
ALTER TABLE Utilisateur DROP COLUMN nom;
ALTER TABLE Utilisateur MODIFY COLUMN mdp VARCHAR(250);
/*
    Ajout des rangs
*/
INSERT INTO rang (nomRang,points_minimum,couleur_rang) VALUES
("Nouveau",0,"#ffffff"),/*Rang par défaut*/
("Novice",150,"#c0eb75"),
("Apprenti",300,"#01ae75"),
("Expert",450,"#CB4154"),
("Maitre",750,"#8a2be2"),
("Grand-Maitre",1250,"#FF0000")
;
/*
    Ajout des utilisateurs
*/
INSERT INTO utilisateurs (points,identifiant,mdp,type,idRang) VALUES
(0,"hbrouard","$2y$10$qgsA4FPow6oLrM5OX9dfo.Wzk0DxnFx8tAd2Rnl3NZX/QNo0o3dMK","admin",1) /*Mot de passe: test */
;