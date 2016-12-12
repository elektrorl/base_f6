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
Ouvrir `gulpfile.js` et éditer les options SFTP (`function sftpOpts`) pour uploader les futurs fichiers compilés sur le nouveau serveur SSH. Idéalement ne pas utiliser le mot de passe, mais préférer une identification par clé SSH. [Sinon envisager le fichier `.ftppass`](https://www.npmjs.com/package/gulp-sftp#authentication).
Vérifier les chemins des dossiers `css` et `js`.
