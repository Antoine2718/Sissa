# Projet Sissa
## Nom de la base et fonctionnement
La base de données s'intitule : __"sissa"__.
Attention, il faut se reférer au fichier _"db.json"_ qui a pour chemin : _"SISSA/Projet/config/db.json"_, il s'agit d'un fichier dans lequel vous trouverez un utilisateur à créer dans phpmyadmin.
## Versions utilisées
Ce projet a été conçu et testé avec les versions suivantes :
- __PHP 8.3.14__ 
- __MySQL 9.1.0__
- __Apache 2.4.62.1__

# Comment procéder ?
Après configuration avec préférablement XAMPP ou WAMP du serveur web et du SGBD, il faut créer la base de données _sissa_, y importer le fichier _SISSA/ScriptSQL/createDB.sql_, configurer l'utilisateur _paula_ dans phpmyadmin, puis démarrer le test, vous devriez rentrer quelque chose dans le genre dans votre barre de recherche : "localhost/Sissa/Projet/pages/". ( A noter que ceci n'est vrai que si wamp ou xampp est configuré avec un lien symbolique vers le dossier Sissa )

# Exemples d'utilisateurs à tester
### Utilisateurs "normaux"
    Identifiant     Mot de Passe
    Jenny           "test"
    Princess        "test"
### Super utilisateur
    Identifiant     Mot de Passe
    hbrouard        "test"



# Contributeurs:
Antoine Lucas \
Bruno Duarte Lopes \
Paul Andrieu
