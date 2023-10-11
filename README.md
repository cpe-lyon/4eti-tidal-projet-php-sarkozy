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
* Utiliser d'autres modules pour ces tâches
### HttpRoutingModule
Ce module a pour responsabilité de faire connaitre au coeur applicatif quelle méthode de controller appeler en fonction du chemin et de la méthode
Il est utilisé par le HttpModule pour convertir les Request en appels aux contrôleurs

Le module calcule une priorité depuis le chemin, la priorité à un nombre de bits égale au nombre de slash, le bit le plus lourd est mis à 1, les autres bits indiquent si le mot entre les slash est statique (1) ou si c'est un argument (0) : \
`/` -> 1 = 1 \
`/[path]` -> 1 = 1 \
`/[path]/` -> 1 0 = 2 \
`/user/[id]/cart/` -> 1 1 0 1 = 13 \
`/user/default/cart` -> 1 1 1 1 = 15 \

Les priorités les plus grandes sont vérifiées en premier

### Le Temps des Templates
Ce module a pour responsabilité de créer une réponse HTML depuis une vue en utilisant des fichiers de templates

## Répartition du travail
**TODO @josse.de-oliveira**
