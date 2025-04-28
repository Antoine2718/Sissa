<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sissa</title>
    <style>
        body {
            font-family: Helvetica, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .contact-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: auto;
            padding: 20px;
        }

        .questions-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="text"],
        input[type="email"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        textarea {
            height: 100px;
        }

        button {
            background-color: #007BFF;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 25%;
        }

        button:hover {
            background-color: #3b4252;
        }

        #Message_bleu {
            color: #007BFF;
        }

        .contact-container {
            display: grid;
            grid-template-columns: 50% 50%; /* Définir les largeurs des colonnes */
            height: fit-content;
        }
        .left {
            padding: 20px;
        }

        .right {
            padding: 20px;
        }

    </style>
    <?php //Ajoute la barre de navigation
        include("../common/styles.php")
    ?>
</head>
<body>
    <!--Barre de navigation-->
    <?php //Ajoute la barre de navigation
        require_once("../common/db.php");
        include("../common/nav.php")
    ?>

    <!-- Contenu principal -->
    <div class="content">
        <h1>Contactez-nous</h1>
        <div class="contact-container">
            <div class="left">
                <h1 id="Message_bleu">Envoyer nous un message</h1>
                <form action="#" method="post">
                    <div class="form-group">
                        <label for="name">Nom:</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="subject">Sujet:</label>
                        <input type="text" id="subject" name="subject" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Message:</label>
                        <textarea id="message" name="message" required></textarea>
                    </div>
                    <button type="submit">Envoyer</button>
                </form>
            </div>
            <div class="right">
                <h2 id="Message_bleu">Informations de contact</h1>
                    <br>
                <h3 id="Message_bleu">email</h3>
                    contact@sissa.fr
                <h3 id="Message_bleu">Téléphone</h3>
                    06 ** ** ** **
                <h3 id="Message_bleu">Adresse</h3>
                    1 Rue de la Paix, Paris.
            </div>
        </div>
        <br>
        <div class="questions-container">
            <h2 id="Message_bleu">Questions fréquemment posées</h1>
                <br>
            <h3> D'où vient le nom Sissa ? </h3>
                La légende de Sissa est un mythe qui raconte l'histoire de l'invention des échecs, enj Inde, un roi aurait demandé au mathématicien Sissa
            d'inventé un jeu basé sur la stratégie et la réfléxion, Sissa aurait alors inventé le jeu des éches, le Roi souhaitant le remercié, Sissa a demande
            ce qui peut semblé un modeste salaire : 1 grains de riz sur la première case du plateau, 2 sur la deuxième, 4 sur la troisième,....
            <br> Ce qui constitue en fait la somme des termes d'une suite géométrique de raison 2 !
            <br> Ainsi cela représente en réalité 2 puissance 64 - 1 grains de riz ce qui est énorme !
            <br> Nous avons choissis ce nom en référence a cette histoire (bien que probablement éronnée, mais illustrant l'astuce et l'ingéniosité.
            <h3> Comment puis-je réinitialiser mon mot de passe ? </h3>
                Si vous êtes administrateur : <br>
                Vous pouvez réinitialiser votre mot de passe en cliquant sur "Mot de passe oublié" sur la page de connexion.
            <br> Si vous n'êtes pas utilisateur : <br> Utilisé(e) le formulaire de contact ci-dessus afin d'expliquer le problème à l'équipe technique de Sissa.
            <h3> Comment fonctionne l'IA de Sissa ? </h3>
            L'IA optimal de Sissa (niveau de jeu maximal), se base sur lalgorithme minimax appliqué au morpion.
            <br>
                L'algorithme Minimax est un algorithme de décision utilisé principalement dans les jeux à somme nulle comme le morpion 
            (ou tic-tac-toe), permettant d'optimiser la stratégie de jeu d'un joueur face à un adversaire. Pour expliquer son fonctionnement, 
            nous allons aborder les concepts fondamentaux qui le sous-tendent.
<br>
Représentation de l'état du jeu
            <br>
Le morpion se joue sur une grille 3x3, où chaque case peut être dans l’un des trois états : 
    vide, occupée par le joueur X (maximisant), ou occupée par le joueur O (minimisant). Chaque configuration de la grille peut être représentée 
            comme un nœud dans un arbre de recherche, où chaque nœud décrit un état possible du jeu. 
            <br> Ainsi la grille de morpion peut-être répresenté comme une matrice de M_3 (IF_3).

Évaluation des nœuds
L'algorithme Minimax traverse cet arbre de recherche en évaluant les nœuds selon deux principes distincts :
<br>
Maximisation (pour le joueur X) - Le joueur X cherche à maximiser son score, qui peut être codé de manière à renvoyer des valeurs 
            numériques positives lorsqu'il gagne, zéro en cas d'égalité, et des valeurs négatives lorsqu'il perd. <br>
Minimisation (pour le joueur O) - Le joueur O, en revanche, cherche à minimiser le score, en essayant de forcer le joueur X à obtenir
            le score le plus bas possible.
            <br>
Exploration des coups possibles
            <br>
L'algorithme fonctionne de manière récursive. Pour chaque état de jeu évalué, l'algorithme procède comme suit :

Si l'état est terminal (c'est-à-dire qu'un joueur a gagné ou que la grille est pleine), la fonction d'évaluation renvoie la valeur correspondante (-1, 0, +1).
Sinon, pour chaque coup possible (c'est-à-dire pour chaque case vide), un nœud est créé pour l'état résultant après le coup. 
            Pour le joueur X, il évalue la valeur maximale entre tous les coups possibles, et pour O, il évalue la valeur minimale.
            <br>
Calcul récursif <br>
Ce processus de maximisation et de minimisation se fait de manière récursive :

Si c'est le tour de X (maximize), [ V(n) = \max{V(n') | n' \in \text{successeurs de } n } ]
Si c'est le tour de O (minimize), [ V(n) = \min{V(n') | n' \in \text{successeurs de } n } ] <br>
Profondeur de recherche et élagage
Pour des jeux plus complexes, le facteur de profondeur joue un rôle crucial. Minimax peut être combiné avec des techniques comme 
            l'élagage alpha-bêta pour réduire le nombre de nœuds évalués. L'élagage permet d'éviter d'explorer des branches de l'arbre 
            qui ne peuvent pas influencer la décision finale, en maintenant deux valeurs : alpha (la meilleure valeur pour le joueur qui maximise) 
            et bêta (la meilleure valeur pour le joueur qui minimise).
            <br>
Conclusion
            <br>
L'algorithme Minimax est donc une méthode récursive qui utilise la stratégie de maximisation et minimisation pour évaluer les résultats futurs 
            d'un jeu, en prenant des décisions optimales basées sur les coups possibles et les réponses de l'adversaire. Sa simplicité et son efficacité 
            en font un choix de premier plan pour les jeux à somme nulle tels que le morpion.
            <h3> Comment fonctionne le système de classement ? </h3>
                Votre score est calculé en fonction de vos performances contre notre IA. Vous gagnez plus de points en battant 
            les niveaux de difficulté supérieurs. Le classement est mis à jour en temps réel.
            <h3> Puis-je proposer de nouvelles fonctionnalités ? </h3>
                Absolument ! Nous sommes toujours à l'écoute de nos utilisateurs. Vous pouvez nous soumettre vos idées via le 
            formulaire de contact ci-dessus ou en nous envoyant directement un email à suggestions@sissa.fr.
        </div>
    </div>
    <?php
        include("../common/footer.php");
    ?>
</body>
</html>
