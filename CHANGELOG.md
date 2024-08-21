## Version 1.0 - 21 août 2024 - Version majeure
- ➕ Ajout de la création des instances.
- ➕ Ajout de la modification des instances.
- ➕ Ajout d'un système de notification de réussite/échec lors de la réalisation d'une action d'administration.
- ➕ Ajout complet du support d'authentification OAuth2.
- ➕ Ajout d'une pagination dans le changelog pour limiter l'affichage à trois versions par page.
- ➕ Ajout d'une gestion dynamique du chemin vers CHANGELOG.md dans le footer.
- ➕ Ajout d'une protection CSRF sur les formulaires pour renforcer la sécurité.
- ➕ Ajout de la fonctionnalité pour visualiser les documents PDF directement dans une modale.
- ➕ Ajout d'une notification Bootstrap verte en cas de succès lors de l'ajout d'un document.
- ➕ Ajout de la dernière version affichée dynamiquement dans le pied de page.
- ➕ Ajout de liens vers les réseaux sociaux dans le pied de page.
- 🔄 Modification du comportement du formulaire pour masquer automatiquement les éléments en fonction de la sélection.
- 🔄 Révision de la variable d'environnement.
- 🔄 Révision du code général avec segmentation des entités.
- 🔄 Révision de la fonction changelog pour afficher seulement trois versions par page.
- 🔄 Révision du pied de page pour s'assurer qu'il fonctionne correctement quel que soit le répertoire dans lequel se trouve le script.
- 🔄 Révision du script de pagination sur la page d'accueil pour un affichage plus fluide des documents.
- 🔄 Révision de l'interface utilisateur de la page d'accueil pour un meilleur alignement et mise en page.
- 🔄 Refactorisation du code pour inclure les formulaires dans des fichiers séparés.
- ❌ Suppression du script de recherche non fonctionnel dans l'attente d'une implémentation ElasticSearch.

## Version 0.9a - 20 août 2024
- 🔄 FIX: Type d'instance.

## Version 0.8b - 20 août 2024
- ➕ Ajout des variables d'environnements.

## Version 0.7b - 20 août 2024
- ➕ Ajout de l'authentification avec Google.

## Version 0.6b - 11 août 2024
- ➕ Création d'actes confidentiels visibles uniquement par les utilisateurs connectés.
- 🔄 Blocage de la notification Discord en cas d'actes confidentiels.

## Version 0.6a - 11 août 2024
- ➕ Possibilité d'envoyer ou non la notification Discord.

## Version 0.5a - 11 août 2024
- ➕ Ajout d'un webhook pour notification Discord.
- ➕ Ajout d'un système de pagination lors de la consultation de l'ensemble des actes.

## Version 0.4a - 25 juillet 2024
- 🔄 Révision de la nomenclature en base de données.
- ➕ L'utilisateur peut maintenant modifier son mot de passe dans "mon profil".

## Version 0.3a - 22 juillet 2024
- 🔄 Modification de l'ordre d'affichage.
- 🔄 Correction du code CSS.
- 🔄 FIX du type d'instance (seul l'ID était affiché).

## Version 0.2a - 21 juillet 2024
- ➕ Ajout de permissions utilisateurs.
- ➕ Ajout d'un modal d'édition des utilisateurs.
- 🔄 FIX du type d'instance.

## Version 0.1a - 18 juillet 2024
- ➕ Fonctionnalités principales de gestion des documents ajoutées.
- ➕ Interface utilisateur de base mise en place.
