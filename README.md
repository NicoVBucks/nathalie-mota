# Thème WordPress — Nathalie Mota

Thème sur-mesure pour le portfolio de la photographe événementielle Nathalie Mota
(site cible : motaphoto.com).

Aucun page builder : templates PHP, styles et scripts écrits à la main.

## Prérequis

- WordPress 6.0 ou supérieur, PHP 7.4 ou supérieur
- **Contact Form 7** — formulaire de la modale de contact
- **CPT UI** et **Secure Custom Fields (SCF)** — structure de contenu (voir plus bas)

## Installation

1. Clonez ce dépôt dans `wp-content/themes/` de votre installation WordPress :
   ```bash
   cd wp-content/themes
   git clone <url-du-repo> nathalie-mota
   ```
2. Dans l'admin WordPress : **Apparence > Thèmes** → activez « Nathalie Mota ».
3. **Extensions** : installez et activez Contact Form 7, CPT UI et SCF.
4. Recréez la **structure de contenu** décrite ci-dessous, puis saisissez les photos.
5. **Apparence > Menus** : créez un menu, assignez-le à l'emplacement *Menu principal*,
   et ajoutez les liens :
   - *Accueil* → page d'accueil ;
   - *À propos* → page « À propos » ;
   - *Contact* → **lien personnalisé** avec l'URL `#contact` (ouvre la modale).
     Vous pouvez aussi lui ajouter la classe CSS `js-open-contact`
     (option « Classes CSS » des éléments de menu).

   En l'absence de menu assigné, un menu de secours prend le relais
   (`nathaliemota_fallback_menu`).
6. Créez une page vide de slug `mentions-legales`, et désignez une page de
   politique de confidentialité dans **Réglages > Confidentialité** : le pied de
   page pointe automatiquement sur l'une et l'autre.

## Structure de contenu

Créée en back-office avec CPT UI et SCF, elle vit en base de données et non dans
le code. **Sans elle, le catalogue et la page infos restent vides.**

- **Type de contenu** : `photo`
- **Taxonomies** : `categorie` (Réception, Mariage, Concert, Télévision)
  et `format` (Paysage, Portrait)
- **Champs SCF** : `reference` (texte) et `type` (liste : Argentique / Numérique)
- **Année de prise de vue** : portée par la date de publication native de
  WordPress, pas par un champ dédié — c'est elle qui pilote le tri du catalogue
  et la navigation entre photos.

Les filtres de la page d'accueil sont alimentés dynamiquement par `get_terms` :
une catégorie ajoutée en back-office apparaît sans toucher au code.

## Contact Form 7

Créez un formulaire avec les champs **nom**, **e-mail**, **réf. photo (optionnel)**
et **message**. Nommez impérativement le champ réf. photo `ref-photo` : c'est ce
nom que le JavaScript cible pour le préremplir.

Renseignez ensuite l'identifiant du formulaire dans `functions.php` :

```php
define( 'NATHALIEMOTA_CF7_ID', 'VOTRE_ID' );
```

Le champ réf. photo se préremplit uniquement lorsque la modale est ouverte depuis
une page photo — ailleurs, il n'y a pas de photo en contexte, le champ reste vide.

## Tailles d'images (Green Code)

Le thème déclare quatre tailles sur-mesure pour ne jamais servir les originaux,
très lourds :

| Taille               | Dimensions | Recadrée | Usage                                    |
| -------------------- | ---------- | -------- | ---------------------------------------- |
| `photo_thumbnail`    | 600 × 600  | oui      | grille carrée du catalogue               |
| `photo_thumbnail_2x` | 1200 ×1200 | oui      | écrans Retina (via `srcset`)             |
| `photo_large`        | 1600 ×1600 | non      | page infos, lightbox, hero — ratio natif |
| `photo_nav`          | 220 × 160  | oui      | miniature de navigation de la page infos |

> **Attention** : une taille d'image n'est découpée qu'au moment du téléversement.
> Pour des photos déjà présentes en base, lancez « Regenerate Thumbnails », sinon
> WordPress se rabat silencieusement sur le fichier d'origine.

## Structure des fichiers

```
nathalie-mota/
├── style.css                       En-tête du thème + tous les styles
├── functions.php                   Setup, enqueue, tailles d'images, requête photos, handler Ajax
├── index.php                       Template de repli (obligatoire, non utilisé en pratique)
├── front-page.php                  Page d'accueil : hero, filtres, catalogue
├── header.php                      Logo, bouton du menu mobile, navigation
├── footer.php                      Footer + lightbox + modale de contact
├── page.php                        Pages (dont « À propos »)
├── single.php                      Page infos d'une photo
├── js/
│   ├── scripts.js                  Menu mobile et modale de contact
│   ├── catalogue.js                Filtres, tri et « Charger plus » en Ajax
│   ├── filtres.js                  Listes déroulantes personnalisées
│   └── lightbox.js                 Lightbox
└── template-parts/
    ├── modal-contact.php           Modale de contact (Contact Form 7)
    ├── photo-block.php             Bloc photo réutilisable (catalogue + apparentées)
    ├── filtre.php                  Liste déroulante personnalisée réutilisable
    └── fleche.php                  Icône flèche (navigation du single + lightbox)
```

## Bonnes pratiques respectées

- Ressources JS et CSS chargées via `wp_enqueue_*`, jamais en dur dans le HTML.
- Menu géré dans l'administration (`register_nav_menus` / `wp_nav_menu`).
- Filtres alimentés dynamiquement depuis les taxonomies (`get_terms`).
- Requête du catalogue factorisée (`nathaliemota_query_photos`), partagée entre
  l'affichage initial et les appels Ajax.
- Appels Ajax protégés par un nonce, et bouton « Charger plus » masqué dès qu'il
  ne reste plus de photos — pas d'appel inutile à l'API.
- Animations 100 % CSS (`transition`), y compris le survol qui fait basculer la
  miniature de navigation.
- Accessibilité : focus visible, lien d'évitement, `aria-*` sur les composants
  interactifs, `prefers-reduced-motion`.
- Responsive desktop et mobile : menu plein écran, grilles empilées, marges
  adaptées via les tokens CSS.

## Maintenance

Incrémentez la constante `NATHALIEMOTA_VERSION` dans `functions.php` à chaque
modification de CSS ou de JavaScript : elle sert au cache busting des ressources.

Toutes les couleurs et polices sont centralisées dans les variables CSS en tête
de `style.css`. Ne codez jamais une valeur en dur : passez par ces tokens.

## Suivi des étapes

- [x] Étape 1 — Thème, header, footer, modale de contact
- [x] Étape 2 — Structure de contenu (CPT « photo », taxonomies, champs)
- [x] Étape 3 — Template single (page infos d'une photo)
- [x] Étape 4 — Page d'accueil (hero, filtres Ajax, pagination)
- [x] Étape 5 — Lightbox
- [ ] Étape 6 — Export du site (ZIP fichiers + base de données)
