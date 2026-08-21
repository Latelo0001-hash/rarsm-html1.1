# Dépendances front-end

Dernier audit : 21 août 2026.

## Bibliothèques actives principales

- jQuery 3.7.1 ;
- Bootstrap JavaScript 4.6.2 avec Popper intégré ;
- Isotope 3.0.6 ;
- PhotoSwipe 4.1.2 ;
- Owl Carousel 2.2.1.

Le thème historique dépend encore de plusieurs extensions jQuery locales. Leur retrait ou leur remplacement doit être testé page par page avant toute nouvelle migration majeure.

## Nettoyage effectué

- suppression de jQuery 3.3.1 et de Bootstrap JavaScript 4.1.1 ;
- suppression de PrettyPhoto, remplacé par PhotoSwipe ;
- suppression des connecteurs Flickr et Instagram inutilisés ;
- suppression du jeton Instagram ancien présent dans le JavaScript ;
- suppression de jQuery Migrate et Modernizr, qui n'étaient plus chargés ;
- conservation temporaire de `html5shiv` et `respond` dans les commentaires conditionnels destinés à Internet Explorer.

## Maintenance

Les fichiers distribués sont regroupés dans `js/compressed.js`. Après une modification de la liste `scripts` de `gulpfile.js`, le bundle doit être reconstruit et les pages principales doivent être contrôlées dans un navigateur.
