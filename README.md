# Blog Php MVC

Système de blog en PHP orienté objet utilisant une architecture MVC, l'injection de dépandances et un conteneur de services respectant le principe du singleton.

## Prérequis
* Maîtrise du langage PHP
* Maîtrise de la POO
* Bonnes connaissances en SQL
* Savoir utiliser Composer

## Installation

Clonez le projet:

```
$ git clone git://github.com/didpoule/blog_php.git
```

À l'aide du terminal, deplacez-vous dans le répertoire:

```
$ cd blog_php
```

Installez les dépendances à l'aide de Composer:

```
$ composer update
```

Renommez le fichier database.inc.yml en database.yml:

```
$ mv config/database.inc.yml config/database.yml
```


Ouvrez le fichier pour entrer les informations de connexion à la base de données.

Exemple:
```
database:
    host: localhost
    port: 8889
    name: blog
    user: root
    password: root
```

Importez la base de données

```
$ mysql -u[utilisateur] -p [nom_base_de_donnees] < blog_php.sql
```

Les informations de connexion au backoffice sont

Utilisateur:

```
admin
```

Mot de passe:

```
admin
```