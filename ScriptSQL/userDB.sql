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