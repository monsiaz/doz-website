# Guide de Déploiement et Bonnes Pratiques - DOZ Website

Ce document détaille la structure du projet et la procédure pour déployer les mises à jour sans erreur.

## 1. Structure du Projet

Le site est un site statique hébergé sur GitHub Pages. Tous les fichiers doivent se trouver à la **racine** du dépôt pour que le déploiement fonctionne correctement.

```
/ (Racine du dépôt)
├── index.html          # Page d'accueil (Vitrine)
├── coffee.html         # Page Coffee Shop (Menu, Galerie)
├── lagree.html         # Page Lagree (Planning, Concept)
├── blog.html           # Page Index du Blog
├── mentions-legales.html
├── assets/             # Images, Logos, Icônes
│   ├── img/
│   │   ├── gallery/    # Photos du site (doivent être ici)
│   │   ├── logo/       # Logos DOZ
│   │   └── perso/      # Personnages (Bonhommes)
├── css/                # Feuilles de style
│   └── style.css
├── js/                 # Scripts interactifs
│   └── script.js
├── blog/               # Dossier de génération d'articles (Python)
│   └── articles_html/  # Articles générés bruts
├── admin/              # CMS Decap (Config)
└── data/               # Données JSON pour le CMS
```

## 2. Bonnes Pratiques de Développement

### Images
*   **Toujours utiliser des chemins relatifs** : `assets/img/...` (et non `/assets/...` ou `site/assets/...`).
*   **Nommage** : Utiliser des minuscules et des tirets (ex: `megapro-schema.png`). Éviter les espaces et accents.
*   **Format** : Privilégier JPG/WEBP pour les photos, PNG/SVG pour les logos.

### Git & Déploiement
*   Ne **JAMAIS** créer de dossier nommé `site` à l'intérieur du projet, cela crée des conflits de sous-modules.
*   Si une erreur "submodule" apparaît sur GitHub Actions :
    1.  Vérifier qu'il n'y a pas de fichier `.gitmodules`.
    2.  Exécuter `git rm --cached site` pour nettoyer l'index.
*   Pour déployer :
    ```bash
    git add .
    git commit -m "Message clair"
    git push origin main
    ```

### Blog & CMS
*   Les articles sont des fichiers HTML statiques dans `blog/articles_html/` (ou à la racine s'ils sont publiés).
*   Pour ajouter un article : Copier le template HTML d'un article existant et modifier le contenu.
*   Lier l'article dans `blog.html`.

## 3. Résolution des Problèmes Courants

*   **Images cassées en ligne** : Vérifier la casse du nom de fichier (GitHub est sensible à la casse, macOS non). Vérifier que le fichier est bien dans `assets/img/gallery/`.
*   **Modifications non visibles** : Vider le cache du navigateur (Cmd+Shift+R) ou attendre 2-3 minutes que GitHub Pages se mette à jour.

