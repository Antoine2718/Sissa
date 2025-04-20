ALTER TABLE Article
ADD categorie VARCHAR(30) NOT NULL DEFAULT 'autre';
-- Insertion d’articles avec catégories et images libres de droit
INSERT INTO Article (prix, nom, stock, description, lien_image, categorie)
VALUES 
-- Vêtements
(19.99, 'T-shirt "Morpion Master"', 50, 'T-shirt noir avec logo morpion en style pixel art.', 'tshirt-morpion.jpg', 'vêtements'),
(34.90, 'Sweat "Think Like a Cross"', 30, 'Sweat gris à capuche avec motif en croix.', 'sweat-cross.jpg', 'vêtements'),
(16.50, 'Casquette "O Player"', 40, 'Casquette noire brodée avec un grand O blanc.', 'casquette-o.jpg', 'vêtements'),
(22.99, 'T-shirt "X Marks the Spot"', 60, 'T-shirt blanc avec un grand X rouge au centre.', 'tshirt-x.jpg', 'vêtements'),
(29.90, 'Sweat "O Strategy"', 25, 'Sweat bleu marine avec un motif O stylisé.', 'sweat-o.jpg', 'vêtements'),
(18.50, 'Casquette "Xtreme"', 35, 'Casquette rouge avec un X brodé en noir.', 'casquette-xtreme.jpg', 'vêtements'),

-- Accessoires
(11.99, 'Mug "Game On"', 100, 'Mug en céramique avec grille de morpion imprimée.', 'mug-game-on.jpg', 'accessoires'),
(9.99, 'Calendrier stratégique 2025', 70, 'Un mois = une citation + une grille de morpion.', 'calendrier-strategique.jpg', 'accessoires'),
(2.99, 'Stylo "Xtra Point"', 120, 'Stylo bille avec embout en forme de X.', 'stylo-x.jpg', 'accessoires'),
(14.99, 'Tapis de souris "Morpion"', 80, 'Tapis de souris avec une grille de morpion imprimée.', 'tapis-souris-morpion.jpg', 'accessoires'),
(7.99, 'Porte-clés "X et O"', 150, 'Porte-clés en métal avec X et O entrelacés.', 'porte-cles-xo.jpg', 'accessoires'),
(5.50, 'Carnet "Stratégies gagnantes"', 90, 'Carnet de notes avec couverture morpion.', 'carnet-strategies.jpg', 'accessoires'),
(8.99, 'Sac "Tactique Morpion"', 50, 'Sac en toile avec une grille de morpion imprimée.', 'sac-tactique.jpg', 'accessoires'),

-- Goodies
(1.50, 'Badge "Je joue X"', 200, 'Badge à épingler pour afficher ton allégeance à X.', 'badge-x.jpg', 'goodies'),
(1.50, 'Badge "Je joue O"', 200, 'Version O du badge.', 'badge-o.jpg', 'goodies'),
(7.50, 'Pochette stratégique', 60, 'Pochette noire avec plateau brodé.', 'pochette-strategique.jpg', 'goodies'),
(3.99, 'Sticker "Morpion"', 300, 'Lot de 10 stickers avec des grilles de morpion.', 'sticker-morpion.jpg', 'goodies'),
(6.99, 'Bracelet "X ou O"', 150, 'Bracelet en silicone avec motifs X et O.', 'bracelet-xo.jpg', 'goodies'),
(4.50, 'Aimant "Grille Morpion"', 100, 'Aimant pour frigo avec une grille de morpion.', 'aimant-morpion.jpg', 'goodies'),
(2.99, 'Marque-page "Stratégie"', 80, 'Marque-page avec des astuces de morpion.', 'marque-page.jpg', 'goodies'),
(5.99, 'Écusson "Champion Morpion"', 70, 'Écusson brodé pour les fans de morpion.', 'ecusson-champion.jpg', 'goodies'),

-- Posters
(12.99, 'Poster "Stratégie gagnante"', 40, 'Poster avec une grille de morpion et les meilleures stratégies.', 'poster-strategie.jpg', 'posters'),
(15.99, 'Poster "X vs O"', 30, 'Poster artistique représentant un duel entre X et O.', 'poster-x-vs-o.jpg', 'posters'),
(10.99, 'Poster "Grille classique"', 50, 'Poster minimaliste avec une grille de morpion.', 'poster-grille.jpg', 'posters'),
(14.50, 'Poster "Morpion Vintage"', 25, 'Poster au style rétro avec une grille de morpion.', 'poster-vintage.jpg', 'posters'),
(13.99, 'Poster "Championnat Morpion"', 35, 'Poster commémoratif d’un tournoi fictif.', 'poster-championnat.jpg', 'posters'),
(11.50, 'Poster "Tactique X"', 30, 'Poster avec des astuces pour jouer X.', 'poster-tactique-x.jpg', 'posters'),
(11.50, 'Poster "Tactique O"', 30, 'Poster avec des astuces pour jouer O.', 'poster-tactique-o.jpg', 'posters'),

-- Jeux
(24.99, 'Jeu de société "Morpion Deluxe"', 20, 'Version deluxe du morpion avec plateau en bois.', 'jeu-deluxe.jpg', 'jeux'),
(19.99, 'Jeu de cartes "Stratégie Morpion"', 50, 'Jeu de cartes basé sur les stratégies du morpion.', 'jeu-cartes.jpg', 'jeux'),
(29.99, 'Puzzle "Grille géante"', 15, 'Puzzle de 1000 pièces avec une grille de morpion.', 'puzzle-grille.jpg', 'jeux'),
(34.99, 'Kit "Morpion 3D"', 10, 'Jeu de morpion en 3D avec pièces en plastique.', 'kit-3d.jpg', 'jeux'),
(22.50, 'Jeu "Morpion électronique"', 25, 'Version électronique du morpion avec sons et lumières.', 'jeu-electronique.jpg', 'jeux'),
(17.99, 'Jeu "Mini Morpion"', 40, 'Version portable du morpion pour jouer partout.', 'jeu-mini.jpg', 'jeux'),
(26.99, 'Jeu "Morpion XXL"', 15, 'Version géante du morpion pour jouer en extérieur.', 'jeu-xxl.jpg', 'jeux');
-- TO DO : Ajouter des images libres de droit pour chaque article dans le dossier images
-- TO DO : Améliorer la description de chaque article
-- TO DO : Ajouter des articles supplémentaires pour chaque catégorie
-- PS : Il est évident que ce bout de code ira dans le fichier SQL de création de la base de données, mais je l'ai mis ici pour que l'on puisse ppour le moment gérer plus facilement ce côté de la base de données.