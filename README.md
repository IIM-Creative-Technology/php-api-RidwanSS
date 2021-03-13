# api_iim_symfony - Ridwan A3 ALT

## Lien du projet

Pour la page avec les étudiants : api/étudiants
Pour la page avec un étudiant : api/étudiants/id

Pour la page avec les étudiants : api/intervenants
Pour la page avec un étudiant : api/intervenants/id

Pour la page avec les étudiants : api/matieres
Pour la page avec un étudiant : api/matieres/id

Pour la page avec les étudiants : api/classes
Pour la page avec un étudiant : api/classes/id

Ces pages ne sont accessibles que par les admins : Alexis, Nicolas et Karine

## Description du projet

Ce projet de cours est codé avec le framework Symfony. Dans ce projet, il est possible d'ajouter, d'éditer et de supprimer une classe, un étudiant, un intervenant et une matière.

L'entity Etudiant est relié à l'entity Classe
L'entity Matiere est relié à l'entity Classe
L'entity Matiere est relié à l'entity Intervenant

L'autentification est géré par une apiKey

AppFixtures.php permet de remplir chaque table de la bdd
