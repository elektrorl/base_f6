# Base Theme Drupal 7 | Foundation 6 · FredORL version

<img src="https://raw.githubusercontent.com/elektrorl/base_f6/master/logo.png" alt="Logo Foundation" width="90">



### Manual Setup / en local
Le dossier du projet «projectname» va être créé automatiquement.

```bash
git clone https://github.com/elektrorl/base_f6.git projectname
```

Ensuite on installe les dépendances

```bash
cd projectname
npm install
bower install
```

Pour compiler: `npm start` pour déclencher Gulp. À la fin de Gulp, les fichiers présents dans `src` seront compilés dans `css` et `js` et uploadés dans.
Finally, run `npm start` to run Gulp. Your finished site will be created in a folder called `dist`, viewable at this URL:

```
http://localhost:8000
```

To create compressed, production-ready assets, run `npm run build`.

