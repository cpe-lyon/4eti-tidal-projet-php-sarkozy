# Projet TIDAL 4IRC
## Participants
* Enseignant: Pierre COUY et Yannick JOLY
* Étudiants:
    * Ruben CLERC
    * Théo CLERE
    * Josse DE OLIVEIRA
## Sujet
Le sujet choisi est **la création d'un framework web**
Le nom de notre framework est _PHP Sarkozy_ (en référence à _Symfony_).

En plus de notre coeur applicatif (`php-sarkozy\core`), on met à disposition 3 modules:
* Module **HttpModule** qui s'occupe de la mise en forme des requêtes et réponses, en appelant les 2 autres modules
* Module **RouterModule** qui permet de personnaliser le routage
* Module **LeTempsDesTemplates** qui est notre moteur de templating personnalisé

## Responsabilités des modules
### Core
Le coeur a pour responsabilité de:
* Référencer et charger les contrôleurs
* Référencer et charger les modules
* Écouter le port du serveur et recevoir/envoyer les requêtes
### HttpModule
HttpModule a des responsabilités qui se dissocient du coeur dans la mesure où on pourrait le remplacer pas un autre mode de transmission que les requêtes HTTP. Cependant, son rôle est essentiel et le serveur ne peut pas fonctionner sans ce module (ou un module alternatif si on en développe un à l'avenir pour les Websockets par exemple).
Il a pour responsabilités de:
* Convertir les requêtes HTTP en dur en objets Request
* Convertir les objets Request en appels aux contrôleurs
* Convertir les réponses de contrôleurs en objets Response
* Convertir les objets Response en réponses HTTP en dur
* Utiliser d'autres module pour ces tâches
### RouterModule
**TODO**
### Le Temps des Templates
Ce module a pour responsabilité de créer une réponse HTML depuis une vue en utilisant des fichiers de templates

## Répartition du travail
**TODO**
