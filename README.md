# BaseThemeF6 FredORL version

<img src="https://raw.githubusercontent.com/elektrorl/base_f6/master/logo.png" alt="Logo Foundation" width="90">

### Manual Setup / en local
Le dossier du projet `projectname` va être créé automatiquement.

```bash
git clone https://github.com/elektrorl/base_f6.git projectname
```

Ensuite on installe les dépendances.

```bash
cd projectname
npm install
bower install
```

### Pour compiler avec Gulp
`npm start` pour déclencher Gulp. À la fin de Gulp, les fichiers présents dans `src` seront compilés dans `css` et `js` et uploadés dans.

### Pour toute nouvelle utilisation
Ouvrir `gulpfile.js` et éditer les options SFTP pour uploader les fichiers compilés directement sur le nouveau serveur SSH.
Vérifier les chemins des dossiers `css` et `js`.
