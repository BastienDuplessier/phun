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

*    **Une application comme une collection de services**:
