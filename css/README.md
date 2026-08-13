# Organisation des styles

`site.css` est le fichier réellement chargé par les pages du site. Il contient
deux couches historiques dans cet ordre :

1. le thème d'origine et ses composants, jusqu'aux environs de la ligne 28152 ;
2. les personnalisations du projet, introduites par le commentaire
   `RARSM brand layer`, puis enrichies au fil des pages et des correctifs.

L'ordre de ces règles fait partie du rendu actuel : les règles RARSM placées à
la fin remplacent volontairement certaines règles du thème.

## Règles de maintenance

- Ne pas relancer l'ancien `gulpfile.js` : sa chaîne Gulp 3 n'est pas
  reproductible et peut écraser les personnalisations actuelles.
- Ajouter les nouveaux correctifs sous un commentaire explicite à la fin de
  `site.css`, en les limitant à une page ou à un composant.
- Préfixer autant que possible les nouveaux sélecteurs avec une classe de page
  (`.home-modern`, `.activities-page`, `.rarsm-shop-page`, etc.).
- Vérifier les affichages mobile, tablette et bureau après toute modification.
- Mettre à jour la version `?v=` de `site.css` dans toutes les pages actives
  lorsqu'un changement doit invalider le cache des navigateurs.

## Découpage futur

La couche RARSM représente plus de 13 000 lignes et n'est pas encore une unité
syntaxique autonome garantie. Son extraction vers un fichier séparé doit être
faite progressivement par composants, avec comparaison visuelle avant/après,
et non par une coupe mécanique au marqueur historique.
