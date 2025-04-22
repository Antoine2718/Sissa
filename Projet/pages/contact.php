<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sissa</title>
    <style>
        body {
            font-family: Helvetica, sans-serif;
            background-color: #f4f4f4;
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
            height: 100vh;
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
            <h3> Comment puis-je réinitialiser mon mot de passe ? </h3>
                Vous pouvez réinitialiser votre mot de passe en cliquant sur "Mot de passe oublié" sur la page de connexion. Un 
            email vous sera envoyé avec les instructions pour créer un nouveau mot de passe.
            <h3> Le jeu est-il disponible sur mobile ? </h3>
                Oui, notre site est entièrement responsive et s'adapte à tous les appareils. Vous pouvez jouer au morpion sur 
            votre smartphone ou tablette sans problème. Une application native est également en cours de 
            développement.
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
