#PHUN
> **PHUN** est un framework très moderne. *Je rigole* :v

## Contexte
Initialement, **PHUN** a été pensé par [Pierre Ruyter](https://github.com/Grimimi).
L'idée était de proposer une collection d'outils pour créer très rapidement
des applications web au moyen du langage PHP.
(Qui, il faut le reconnaitre, est très facile à déployer).  Pour le rendre
"utile", il fallait impérativement que l'outil n'implique pas la
connaissance/compréhension d'une architecture statique.

> Le temps est passé et le framework n'est pas né.
Après que quelqu'un m'ait fait remarquer que je critiquais trop PHP et que si
je m'en servais, je découvrirais peut être la superbe de ce langage. J'ai décidé de reprendre ce projet (me l'approprier en somme :D) pour créer un
tout petit outil tout mignon, basé sur ce que j'ai pu apprécier avec d'autres
outils. C'est comme ça que PHUN2.0 (aha) est né.

## Concepts
Actuellement, PHUN repose essentiellement sur deux concepts fondamentaux :

*    **Let it crash**:
    Plutôt que d'évaluer tous les cas de figures possible, on laisse l'application planter au plus vite si elle ne respecte pas ses contrats

*    **Une application comme une collection de services**: Dans PHUN, on décrit des services qui sont caractérisés par leurs paramètres (GET et POST).
(Inspiration Ocisgen, il le faut !)

## Installation
`git clone https://github.com/xvw/phun` sur une machine ayant Apache devrait suffire.

## Structure d'un projet
Le point d'entrée de l'application est le fichier `index.php`. 

```php
<?php
require 'configuration.php';
require 'core/phun.php';

// Ici vous mettez ce que vous voulez.

Phun::start();
?>
```

Pour plus de swagg vous pouvez créer une include vers ce que vous voulez.
Si vous testez ce projet, il devrait afficher une belle erreur. 

### Un Hello World
L'erreur en question est qu'il n'existe aucun service satisfaisant le contrat initial. Soit, pas d'éléments en plus dans l'URL et pas de paramètres GET et POST... Sans plus attendre, crééons notre premier service !

```php
<?php
require 'configuration.php';
require 'core/phun.php';

$hello = new Service();


Phun::start();
?>
```

Maintenant, on a une belle erreur qui nous indique que le service trouvé n'est pas lié à une vue. Il faut donc le lier :D.
En effet, PHUN propose deux phases pour la création d'un service en deux temps.

*    On défini le service (son chemin, ses variables GET et POST);
*    On le lie à une vue.

Cette segmentation en deux points (honteusement inspirée (mal) de Ocisgen) permet d'utiliser les services dans les vues (et nous verrons pourquoi/comment un peu plus bas).

La liaison d'une vue est du simple PHP/HTML via une lambda: 
