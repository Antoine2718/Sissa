# Projet Sissa

## Présentation du projet

Le projet Sissa est un site web statique nécessitant une configuration spécifique pour fonctionner correctement. Ce document explique en détail toutes les étapes nécessaires pour installer et configurer ce projet sur votre environnement de travail.

## Configuration de la base de données

### Informations générales

- **Nom de la base de données**: `sissa`
- **Fichier de configuration**: `Sissa/Projet/config/db.json`
- **Utilisateur à créer dans phpmyadmin**: Les informations de connexion se trouvent dans le fichier `db.json`

### Versions recommandées

Ce projet a été conçu et testé avec les versions suivantes :

- **PHP**: 8.3.14
- **MySQL**: 9.1.0
- **Apache**: 2.4.62.1

## Guide d'installation

### 1. Installation de l'environnement

Vous avez le choix entre XAMPP ou WAMP :

#### Configuration avec XAMPP

1. **Installation de XAMPP**
   - Téléchargez XAMPP depuis [le site officiel](https://www.apachefriends.org/fr/index.html)
   - Installez-le en suivant les instructions d'installation
   - Assurez-vous que les versions installées correspondent aux recommandations ci-dessus

2. **Configuration du lien symbolique**
   - Localisez le dossier `htdocs` dans votre installation XAMPP (généralement `C:\xampp\htdocs` sous Windows)
   - Créez un lien symbolique vers le dossier du projet :
     - Sous Windows (en tant qu'administrateur) : `mklink /D C:\xampp\htdocs\Sissa [chemin_vers_votre_dossier_Sissa]`
     - Sous Linux/macOS : `ln -s [chemin_vers_votre_dossier_Sissa] /opt/lampp/htdocs/Sissa`

3. **Démarrage des services**
   - Lancez le panneau de contrôle XAMPP
   - Démarrez les services Apache et MySQL

#### Configuration avec WAMP

1. **Installation de WAMP**
   - Téléchargez WAMP depuis [le site officiel](https://www.wampserver.com/)
   - Installez-le en suivant les instructions d'installation
   - Assurez-vous que les versions installées correspondent aux recommandations ci-dessus

2. **Configuration du lien symbolique**
   - Localisez le dossier `www` dans votre installation WAMP (généralement `C:\wamp64\www` sous Windows)
   - Créez un lien symbolique vers le dossier du projet :
     - Sous Windows (en tant qu'administrateur) : `mklink /D C:\wamp64\www\Sissa [chemin_vers_votre_dossier_Sissa]`

3. **Démarrage des services**
   - Lancez WAMP
   - Vérifiez que l'icône dans la barre des tâches est verte (services actifs)

### 2. Configuration de la base de données

1. **Création de la base de données**
   - Ouvrez phpMyAdmin (http://localhost/phpmyadmin)
   - Créez une nouvelle base de données nommée `sissa`

2. **Importation du schéma**
   - Importez le fichier SQL `Sissa/ScriptSQL/createDB.sql` dans la base de données `sissa`

3. **Création de l'utilisateur**
   - Dans phpMyAdmin, allez dans l'onglet "Utilisateurs"
   - Créez l'utilisateur `paula` (ou le nom d'utilisateur mentionné dans le fichier `db.json`)
   - Attribuez-lui le mot de passe indiqué dans le fichier `db.json`
   - Accordez-lui tous les privilèges sur la base de données `sissa`

### 3. Test de l'application

Après avoir configuré votre environnement, vous pouvez accéder à l'application via :

```
http://localhost/Sissa/Projet/pages/
```

## Comptes utilisateurs pour les tests

### Utilisateurs standards

| Identifiant | Mot de Passe |
|-------------|--------------|
| Jenny       | test         |
| Princess    | test         |

### Super utilisateur (administrateur)

| Identifiant | Mot de Passe |
|-------------|--------------|
| hbrouard    | test         |

## Problèmes courants et solutions

### Erreur de connexion à la base de données
- Vérifiez que le service MySQL est bien démarré
- Assurez-vous que les informations de connexion dans `db.json` sont correctes
- Vérifiez que l'utilisateur MySQL a les privilèges nécessaires

### Page blanche ou erreur 404
- Vérifiez que le service Apache est bien démarré
- Assurez-vous que le lien symbolique vers le dossier Sissa est correctement configuré
- Vérifiez les chemins d'accès dans l'URL

### Erreurs PHP
- Vérifiez la version de PHP utilisée
- Activez l'affichage des erreurs dans le fichier `php.ini` pour faciliter le débogage

## Contributeurs

- Antoine Lucas
- Bruno Duarte Lopes
- Paul Andrieu