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
