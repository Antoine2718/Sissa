ALTER TABLE Article
-- Ajouter une colonne 'categorie' à la table 'Article'
ADD categorie VARCHAR(30) NOT NULL DEFAULT 'autre';

-- Insérer des articles dans la catégorie 'vêtements'
INSERT INTO `article` (`idArticle`, `prix`, `nom`, `stock`, `description`, `lien_image`, `categorie`) VALUES
(1, 19.99, 'T-shirt \"Morpion Master\"', 50, 'T-shirt noir avec logo morpion en style pixel art.', 'tshirt-morpion.jpg', 'vêtements'),
(2, 34.90, 'Hoodie \"Think Like a Cross\"', 30, 'Hoodie bleu marine, 80% coton, 20% polyester pour une finition éclatante et un confort élevé tout au long de la journée.', '..\\images\\Shop-images\\sweat-think-crss.png', 'vêtements'),
(3, 16.50, 'Casquette \"O Player\"', 39, 'Casquette noire brodée avec un grand O blanc.', 'casquette-o.jpg', 'vêtements'),
(4, 28.00, 'T-shirt \"X Marks the Spot\"', 58, 'T-shirt blanc 100% coton pour un confort tout au long de la journée.', '..\\images\\Shop-images\\x-marks.png', 'vêtements'),
(5, 29.90, 'Hoodie \"O Strategy\"', 25, 'Hoodie bleu marine, 80% coton, 20% polyester pour une finition éclatante et un confort élevé tout au long de la journée.', '..\\images\\Shop-images\\sweat-o-strat.png', 'vêtements'),
(6, 18.50, 'Casquette \"Xtreme\"', 35, 'Casquette rouge avec un X brodé en noir.', 'casquette-xtreme.jpg', 'vêtements');

-- Insérer des articles dans la catégorie 'accessoires'
INSERT INTO `article` (`idArticle`, `prix`, `nom`, `stock`, `description`, `lien_image`, `categorie`) VALUES
(7, 11.99, 'Mug \"Game On\"', 100, 'Mug 325ml - idéal pour un latte - en céramique blanche et brillante avec grille de morpion imprimée.', '..\\images\\Shop-images\\game-on-tasse.png', 'accessoires'),
(8, 9.99, 'Calendrier stratégique 2025', 70, 'Un mois = une citation + une grille de morpion.\r\nChaque calendrier comporte une reliure à anneaux, 26 pages et une finition qualité satinée. Parfait pour être accroché à votre mur.', '..\\images\\Shop-images\\calendrier-strat.png', 'accessoires'),
(9, 2.99, 'Stylo \"Xtra Point\"', 120, 'Stylo bille avec embout en forme de X.', 'stylo-x.jpg', 'accessoires'),
(10, 14.99, 'Tapis de souris \"Morpion\"', 80, 'Tapis de souris avec une grille de morpion imprimée.', 'tapis-souris-morpion.jpg', 'accessoires'),
(11, 7.99, 'Porte-clés \"X et O\"', 150, 'Porte-clés en métal avec X et O entrelacés.', 'porte-cles-xo.jpg', 'accessoires'),
(12, 5.50, 'Carnet \"Stratégies gagnantes\"', 90, 'Carnet de notes avec couverture morpion.', 'carnet-strategies.jpg', 'accessoires'),
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