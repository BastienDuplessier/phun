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

```php
<?php
require 'configuration.php';
require 'core/phun.php';

$hello = new Service();

$hello->bindWith(
    function($get, $post) {
        echo '<h1>Hello les gens (et le world)!</h1>';
    }
);

Phun::start();
?>
```

Cette fois-ci, ça fonctionne !

Créons maintenant un second service, qui lui se chargera de dire bonjour à un prénom choisi au hasard, `Xavier`.

```php
<?php
require 'configuration.php';
require 'core/phun.php';

$hello = new Service();
$hello_xavier = new Service();

$hello->bindWith(
    function($get, $post) {
        echo '<h1>Hello les gens (et le world)!</h1>';
    }
);

$hello_xavier->bindWith(
    function($get, $post) {
        echo '<h1>Hello Xavier!</h1>';
    }
);



Phun::start();
?>
```

Cette portion de code va échouer aussi, car le chemin doit être impérativement UNIQUE ! (on peut créer une URL basée sur le même chemin, mais il faut impérativement des variables GET pour que PHUN puisse faire la différence). Nous allons donc rajouter un chemin à notre second service :

```php
<?php
require 'configuration.php';
require 'core/phun.php';

$hello = new Service();
$hello_xavier = new Service(["hello", "Xavier"]);

$hello->bindWith(
    function($get, $post) {
        echo '<h1>Hello les gens (et le world)!</h1>';
    }
);

$hello_xavier->bindWith(
    function($get, $post) {
        echo '<h1>Hello Xavier!</h1>';
    }
);



Phun::start();
?>
```
Maintenant ça fonctionne! On peut accéder à notre page via l'url `hello/Xavier`!

####Liens entre les pages
Comme les services ont étés définis avant mes liaisons, je peux utiliser un service au sein d'une page. Imaginons par exemple que je veuille faire, sur ma page d'accueil un lien vers la page `hello/Xavier` et sur ma page `hello/Xavier`, un lien vers ma page d'accueil, je peux me servir de `$service->link("content", [args], [attributes])`. Le "content" est le texte du lien, "args" permet de passer un tableau contenant les variables GET à lier à l'URL (ici on n'en a pas besoin !), et les attributs permet de lier des attributs HTML au lien. Par exemple :

```php
<?php
require 'configuration.php';
require 'core/phun.php';

$hello = new Service();
$hello_xavier = new Service(["hello", "Xavier"]);

$hello->bindWith(
    function($get, $post) use ($hello_xavier) {
        echo '<h1>Hello les gens (et le world)!</h1>';
        echo $hello_xavier->link(
            'Dire coucou à Xavier!',
            [],
            ["style" => "background-color:red;color:white;padding:8px;"] 
        );
    }
);

$hello_xavier->bindWith(
    function($get, $post) use ($hello) {
        echo '<h1>Hello Xavier!</h1>';
        echo $hello->link(
            'Dire coucou au monde!',
            [],
            ["style" => "background-color:green;color:white;padding:8px;"] 
        );
    }
);



Phun::start();
?>
```

C'est assez commode de manipuler directement un service, cela permet de pas devoir retenir les URL's et à priori, si un lien interne casse, le projet devrait crasher :D

L'usage de "use" permet de passer le service de le "binding" de la lambda, il ne faut oublier de passer les services en argument à use chaque fois que l'ont veut chainer des modules entre eux!

### Variables GET
Faire un service par personne que je connais pour dire bonjour c'est un peu nul. Nous allons ajouter à notre service un paramètre GET prénom, voici la modification du code à effectuer:

```php
<?php
require 'configuration.php';
require 'core/phun.php';

$hello = new Service();
$hello_quelqun = new Service(
    ["hello"],
    [Parameter::get('prenom', 'string')]
);

$hello->bindWith(
    function($get, $post) use ($hello_quelqun) {
        echo '<h1>Hello les gens (et le world)!</h1>';
        echo $hello_quelqun->link(
            'Dire coucou à Xavier!',
            ['prenom' => 'Xavier'],
            ["style" => "background-color:red;color:white;padding:8px;"] 
        );
    }
);

$hello_quelqun->bindWith(
    function($get, $post) use ($hello) {
       echo '<h1>Hello '.$get['prenom'].'!</h1>';
        echo $hello->link(
            'Dire coucou au monde!',
            [],
            ["style" => "background-color:green;color:white;padding:8px;"] 
        );
    }
);



Phun::start();
?>
```

Plusieurs modifications sont à effectuer !

*   Premièrement, il faut ajouter un deuxième argument qui prendra la liste des
des paramètres. Dans laquelle on place un paramètre GET qui est caractérisés
par un nom ET un type. (Les types possibles sont : `string`, `int` ,`float`, `char` et `bool`. Le type le plus permissif et évidemment polymorphe est le type String, qui fonctionnera un peu tout le temps. Par contre, la cohérence des types est obligatoire pour qu'un contrat avec un service soit effectué. Ce qui est assez pratique car ça n'oblige pas à convertir les variables de l'URL, PHUN le fait pour vous :D

*   Ensuite, il fut modifier le lien et cette fois-ci, lui donner le paramètre GET attendu, sous forme de tableau associatif.

*   Pour finir, on exploite, dans la vue du service `hello_quelqun` la variables
`$get['prenom']` qui contiendra le prénom envoyé en argument. 

> TADAM, si jamais le type de l'expression est mauvais quand il est passé, ou que le nombre de paramètres est donné, l'application crashera car le contrat ne sera pas respecté !

Maintenant quand on clique sur notre lien on arrive à `hello/prenom=Xavier`. Ce qui est cool (si si), c'est qu'on peut modifier la valeur de prénom directement dans l'url !

Par contre, je trouve que le "prenom=" apparent est un peu moche. Donc je peux ajouter un troisième argument à mon paramètre qui prend un booléen pour enjoliver l'URL :D

```php
$hello_quelqun = new Service(
    ["hello"],
    [Parameter::get('prenom', 'string', true)]
);

```

Maintenant, on peut accéder à cette SUPERBE page via l'url `hello/LEPRENOMKETUVEUX`. Un autre avantage est que si on décide de changer l'URL de pages, il ne faut pas aller les modifier partout où elles ont étés utilisées :D

### to be continuou-haide
