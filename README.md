# rarsm.org
site web officiel pour la publication de l'ouvrage RARSM

## Démarrage local

```bash
cp includes/config.sample.php includes/config.php
php -S localhost:8080
```

Le site reste utilisable sans base de données pour une démonstration : les
comptes, paniers et commandes sont alors conservés dans la session PHP.

## Base de données MySQL locale

Le schéma de développement se trouve dans `database/rarsm_store_schema.sql`.
Après installation de MySQL avec Homebrew :

```bash
brew services start mysql
mysql -u root
```

Créez ensuite la base et un utilisateur local depuis l'invite MySQL :

```sql
CREATE DATABASE IF NOT EXISTS rarsm_store
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'rarsm_user'@'localhost'
  IDENTIFIED BY 'choisir-un-mot-de-passe-local';
CREATE USER IF NOT EXISTS 'rarsm_user'@'127.0.0.1'
  IDENTIFIED BY 'choisir-un-mot-de-passe-local';
GRANT ALL PRIVILEGES ON rarsm_store.* TO 'rarsm_user'@'localhost';
GRANT ALL PRIVILEGES ON rarsm_store.* TO 'rarsm_user'@'127.0.0.1';
FLUSH PRIVILEGES;
```

Importez enfin le schéma et reportez le même mot de passe dans votre copie
locale de `includes/config.php` :

```bash
mysql -u rarsm_user -p -h 127.0.0.1 rarsm_store \
  < database/rarsm_store_schema.sql
```

La configuration locale `includes/config.php` n'est pas versionnée. Les
identifiants de production peuvent aussi être fournis avec les variables
`RARSM_DB_DSN`, `RARSM_DB_USER` et `RARSM_DB_PASSWORD`.

L'ancienne intégration Twitter/X attend les variables serveur suivantes :

- `RARSM_TWITTER_CONSUMER_KEY`
- `RARSM_TWITTER_CONSUMER_SECRET`
- `RARSM_TWITTER_ACCESS_TOKEN`
- `RARSM_TWITTER_ACCESS_TOKEN_SECRET`

Le formulaire de contact nécessite deux adresses configurées sur le serveur :

- `RARSM_CONTACT_TO` : adresse qui reçoit les demandes ;
- `RARSM_CONTACT_FROM` : adresse du domaine utilisée comme expéditeur.

La fonction PHP `mail()` doit également être reliée au service de messagerie
du serveur. L'adresse du visiteur est placée dans `Reply-To`, jamais dans
`From`, afin de respecter les politiques SPF et DMARC.

Ne placez jamais de clé ou de mot de passe réel dans un fichier suivi par Git.

## Architecture du projet

Les principales pages éditoriales sont des fichiers HTML à la racine :
`index.html`, `book.html`, `author.html`, `institutions.html`, `activites.html`,
`pricing.html`, `faq.html` et `contact.html`.

Les parties dynamiques passent par PHP :

- `includes/bootstrap.php` initialise la session, la base, la langue, le
  compte, la boutique et la mise en page partagée ;
- `includes/auth.php` gère l'inscription et la connexion ;
- `includes/store.php` gère le catalogue, le panier et les commandes ;
- `includes/layout.php` rend les pages PHP et les composants communs ;
- `actions/` contient les traitements des formulaires ;
- `database/` contient les schémas MySQL et PostgreSQL.

Le dossier `Shop/` contient uniquement d'anciennes maquettes HTML. Il est
conservé comme archive visuelle et ne doit pas être utilisé pour le parcours
actif de la boutique.

## Front-end historique

Le projet provient d'un thème utilisant Gulp 3, Sass et plusieurs greffons
jQuery. Le fichier compilé actuellement chargé par les pages est
`css/site.css`, complété par `js/compressed.js`, `js/rarsm-i18n.js` et
`js/rarsm-ui.js`.

Le fichier `gulpfile.js` est historique et aucun `package.json` reproductible
n'est présent. Ne lancez donc pas une recompilation Sass globale sans avoir
d'abord modernisé cette chaîne de construction : elle pourrait écraser les
adaptations RARSM ajoutées directement à `css/site.css`.

Les règles de maintenance et le plan de séparation progressive des styles sont
documentés dans `css/README.md`.

## Contrôles rapides

```bash
find . -name '*.php' -print0 | xargs -0 -n1 php -l
git diff --check
```
