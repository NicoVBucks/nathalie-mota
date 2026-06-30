# Thème WordPress — Nathalie Mota

Thème sur-mesure pour le portfolio de la photographe événementielle Nathalie Mota
(site cible : motaphoto.com).

## Installation

1. Clonez ce dépôt dans `wp-content/themes/` de votre installation WordPress locale :
   ```bash
   cd wp-content/themes
   git clone <url-du-repo> nathalie-mota
   ```
2. Dans l'admin WordPress : **Apparence > Thèmes** → activez « Nathalie Mota ».
3. **Extensions** : installez et activez **Contact Form 7**.
4. **Apparence > Menus** : créez un menu, assignez-le à l'emplacement *Menu principal*,
   et ajoutez les liens :
   - *Accueil* → page d'accueil ;
   - *À propos* → page « À propos » ;
   - *Contact* → **lien personnalisé** avec l'URL `#contact` (ouvre la modale).
     Vous pouvez aussi lui ajouter la classe CSS `js-open-contact`
     (option « Classes CSS » des éléments de menu).

## Contact Form 7

Créez un formulaire avec les champs **nom**, **e-mail**, **réf. photo (optionnel)**, **message**.
Nommez le champ réf. photo `ref-photo` (il sera prérempli automatiquement à l'étape 3).
Renseignez ensuite l'ID du formulaire :

```php
// wp-config.php ou functions.php
define( 'NATHALIEMOTA_CF7_ID', 'VOTRE_ID' );
```

## Structure

```
nathalie-mota/
├── style.css                       En-tête du thème + styles
├── functions.php                   Setup, menus, enqueue des ressources
├── index.php                       Accueil (hero + catalogue à venir étape 4)
├── header.php                      Logo + navigation
├── footer.php                      Footer + appel de la modale
├── page.php                        Pages (dont « À propos »)
├── single.php                      Page infos d'une photo (étape 3)
├── js/
│   └── scripts.js                  Ouverture/fermeture de la modale
└── template-parts/
    └── modal-contact.php           Modale de contact (Contact Form 7)
```

## Bonnes pratiques respectées

- Ressources JS/CSS chargées via `wp_enqueue_*` (rien en dur).
- Menu géré dans l'admin (`register_nav_menus` / `wp_nav_menu`).
- Polices Google Fonts chargées par enqueue.
- Animations 100 % CSS (`transition`).
- Accessibilité : focus visible, lien d'évitement, `prefers-reduced-motion`.

## Suivi des étapes

- [x] Étape 1 — Thème, header, footer, modale de contact
- [ ] Étape 2 — Structure de contenu (CPT « photo », taxonomies, champs)
- [ ] Étape 3 — Template single
- [ ] Étape 4 — Page d'accueil (hero, filtres, catalogue, pagination Ajax)
- [ ] Étape 5 — Lightbox
- [ ] Étape 6 — Export du site
