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
(1, "Alice","10", "https://aaah0mnbncqtinas.public.blob.vercel-storage.com/9zMJ22qaje-no-background-YDPYz7EYIKq8PV1JBRW4wVOMxfPDNT.png"),
(2, "Bob","9", "https://www.popsci.com/uploads/2020/01/07/WMD5M52LJFBEBIHNEEABHVB6LA.jpg?auto=webp&width=1440&height=864"),
(3, "Cédric","8", "https://i.pinimg.com/736x/cf/f1/b0/cff1b00211b10b5e9820ef6494b28da3.jpg"),
(4, "Daniel","7", "https://pbs.twimg.com/profile_images/1385724221406519300/PRJvcMCr_400x400.jpg"),
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
