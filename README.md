# Symfony - Examen

Pour travailler sur ce projet : 
- **créer un fork** du projet (sur la page [https://github.com/Dreeckan/exam-symfony](https://github.com/Dreeckan/exam-symfony), cliquer sur le bouton `fork`, en haut à droite de la page)
- Cloner **votre** projet (commande `git clone` par exemple)
- Créer une branche pour faire tout l'examen
- À la fin de l'examen, ajouter un dump (export de votre BdD depuis PhpMyAdmin) à la racine du projet (`exam-symfony.sql` par exemple)
- À la fin de l'examen, vous **devez** envoyer un zip de votre code sur Discord et vous **pouvez** faire une PR à destination du projet d'origine (afin de faciliter mes retours pour la correction)

**1 point bonus** peut être gagné si votre code est valide PSR-12 et PSR-4 (3 erreurs autorisées au total).

La durée prévue est d'environ 4h. Des points peuvent être perdus pour le retard du rendu :
- 1 point si le rendu est fait après 19h
- 2 point si le rendu est fait après 23h59

Liste des exercices
1. Contrôleurs et routes (2 points)
2. Vues avec Twig (3 points)
3. Doctrine et la base de données (4 points)
4. Formulaires et entités (4 points)
5. Créer et utiliser des services (5 points)
6. Questions de cours (2 points)

   
## 1. Contrôleurs et routes (2 points)

- Créer un controller : 
  - [ ] nommé `DefaultController`, 
  - [ ] contenant une action `index`,
  - [ ] dont le chemin est `/`
  - [ ] créer le template associé (`default/index.html.twig`) (le laisser vide, il sera rempli dans l'exercice 2)
  
- Créer une deuxième route :
  - [ ] dont l'action s'appelle `contact`
  - [ ] dont le chemin est `/contact`
  - [ ] créer le template associé `default/contact.html.twig` (le laisser vide, il sera rempli dans les exercices 2 et 4)
  
## 2. Vues avec Twig (3 points)

- [ ] Créer un template `default/layout.html.twig` héritant de `base.html.twig`
  - [ ] Y ajouter les feuilles de style et les javascripts (version "Bundle") de [Boostrap 4](https://getbootstrap.com/docs/4.6/getting-started/introduction/)
  - [ ] Créer un bloc `header`, un bloc `footer` et un bloc `content`
  - [ ] Ajouter une div **autour** du bloc content, avec la classe `container-fluid`
  - [ ] Dans `footer`, ajouter le contenu `<div>Un site tout droit libre de tous droits</div>`
  - Dans `default/index.html.twig` :
    - [ ] Adapter votre vue pour hériter de `layout.html.twig` et utiliser ses blocs
    - [ ] Dans le `header` ajouter le contenu `<h1>un titre magnifique</h1>`
    - [ ] À l'aide d'un [filtre Twig](https://twig.symfony.com/doc/), faire en sorte que chaque mot de ce titre ait une majuscule
    - [ ] Vérifier que les éléments sont bien affichés
  - Dans `default/contact.html.twig` :
    - [ ] Adapter votre vue pour hériter de `layout.html.twig` et utiliser ses blocs
    - [ ] Dans le `header` ajouter le contenu `<h1>Nous Contacter</h1>`
    - [ ] Dans le contenu, ajouter le texte : `Nous sommes actuellement le : ` et afficher la date du jour au format `d/m/Y H:i:s` (avec le filtre Twig `date`) (s'il y a un décalage d'une ou deux heures, peu importe)
    - [ ] Vérifier que les éléments sont bien affichés
  
## 3. Doctrine et la base de données (4 points)

### 3.0. Configuration

- [ ] Vérifier / mettre à jour la configuration de Doctrine et de la base de données (BdD)
- [ ] Créer la BdD

### 3.1. Entité
- [ ] Créer une entité `Contact` avec les propriétés suivantes :
  - [ ] `id`, integer, non null, auto increment
  - [ ] `email`, string (128), non null
  - [ ] `subject`, string (255), non null
  - [ ] `message`, text, non null
  - [ ] `created_at`, datetime
- [ ] Faire en sorte que la propriété prenne la date et heure du jour lors de la création d'un nouvel objet `Contact` (objet `DateTime` contenant la date et l'heure de création de l'objet)
- [ ] Créer la table correspondante dans votre BdD

### 3.2. Enregistrement de données

- [ ] Dans l'action `contact`, au chargement de la page, créer une instance de `Contact` avec les données suivantes :
  - `email` : `test@test.com`
  - `subject` : `Ceci est un test`
  - `message` : `Un message de test, pouvant être long, ou non. Celui-ci ne l'est pas :) .`
- [ ] Sauvegarder cette donnée dans la BdD
- [ ] Vérifier le fonctionnement

### 3.3. Récupération de données

- [ ] Dans l'action `index`, récupérer toutes les entrées de votre table `contact`
  - [ ] Les afficher dans un tableau HTML dans la vue `default/index.hml.twig`
- [ ] Dans le repository correspondant, écrire une méthode renvoyant tous les objets `Contact` dont le champ email est `test@test.com`

## 4. Formulaires et entités (4 points)

- [ ] Commenter le code fait précédemment pour l'action `contact`, lors de l'exercice 3.3. 
- [ ] Créer un formulaire `ContactType` pour gérer l'entité `Contact` (et les différents champs utiles)
- [ ] Afficher ce formulaire dans la vue `default/contact.html.twig`
  - [ ] Le mettre en forme avec Bootstrap 4
- [ ] Gérer la soumission et la validation de ce formulaire
  - [ ] Règles de validation : 
    - [ ] Le champ `email` ne doit pas être vide **et** être un email valide
    - [ ] Le champ `subject` ne doit pas être vide et doit comporter entre 6 et 200 caractères
    - [ ] Le champ `message` ne doit pas être vide et doit comporter au moins 50 caractères
  - [ ] Une fois le message enregistré, rediriger vers l'action `index`
  - [ ] Vérifier que les données sont bien insérées
- [ ] Afficher tous les messages contenus dans la table `contact` dans `default/index.hml.twig` et vérifier.
  
## 5. Créer et utiliser des services (5 points)

### 5.1. Un lanceur de dés

- [ ] Créer une classe `App\Services\DiceThrower` (lanceur de dés) et y implémenter :
  - [ ] Une méthode `rollDices` (lance des dés) prenant en paramètres un nombre de dés (nombre entier > 0) et le nombre de faces des dés à lancer (nombre entier > 1) et renvoyant un tableau contenant le résultat de chaque dé
    - Exemple d'utilisation : `rollDices(5, 8)` (lance 5 dés à 8 faces)
    - Exemple de résultat : `[5, 3, 2, 6, 8]` (renvoie séparément le résultat des 5 dés)
  - [ ] Une méthode `rollTwenty` (lance un dé à 20 faces) prenant en paramètre un nombre de dés (nombre entier > 0) et renvoyant un tableau contenant le résultat de chaque dé
    - Exemple d'utilisation : `rollTwenty(5)` (lance 5 dés à 20 faces)
    - Exemple de résultat : `[5, 13, 6, 16, 20]` (renvoie séparément le résultat des 5 dés)
  - [ ] Une méthode `rollHundred` (lance un dé à 100 faces) prenant en paramètre un nombre de dés (nombre entier > 0) et renvoyant un tableau contenant le résultat de chaque dé
    - Exemple d'utilisation : `rollHundred(3)` (lance 3 dés à 100 faces)
    - Exemple de résultat : `[75, 13, 6]` (renvoie séparément le résultat des 3 dés)
  
### 5.2. Résoudre des actions

- [ ] Créer une classe `App\Services\ActionResolver` (résolveur d'actions) :
  - [ ] Faire en sorte qu'elle puisse utiliser notre classe `App\Services\DiceThrower`
  - [ ] Ajouter une méthode `attack` :
    - [ ] Qui prend en paramètre 2 objets `Character`. Le premier est l'attaquant, le second est le défenseur.
    - [ ] Renvoie `null` si l'attaque a échoué, la quantité de dégâts infligés sinon
      
    - Fonctionnement de la méthode : 
      - [ ] Lance 1 dé à 100 faces (méthode `rollHundred`). Si le résultat est supérieur à l'attribut `strength` de l'attaquant, alors l'attaque échoue (renvoyer `null`)
      - [ ] Si l'attaque réussie, le défenseur lance 1 dé à 100 faces (méthode `rollHundred`). Si le résultat est inférieur ou égal à l'attribut `defense` du défenseur, alors l'attaque échoue (renvoyer `null`)
      - [ ] Si rien n'a été renvoyé jusqu'à cette étape, lancer 6 dés à 20 faces et en faire la somme. Renvoyer cette somme.
      - Version en pseudo-code :
```text
fonction attack (attaquant, défenseur) {
    variable testAttaque = DiceThrower.rollHundred(1)
    Si testAttaque > attaquant.strength alors
        retourne null
    FinSi
    
    variable testDéfense = DiceThrower.rollHundred(1)
    Si testDéfense <= défenseur.defense alors 
        retourne null
    FinSi
    
    variable désDeDégâts = DiceThrower.rollTwenty(6)
    
    retourne somme(désDeDégâts)
}
```

### 5.3. Afficher les résultats d'une bataille

Gimli et Legolas ont décidé de s'entrainer à nouveau ! Ils vont à nouveau s'affronter jusqu'à ce que l'un d'eux abandonne le combat. Vous trouverez une partie du code déjà prêt dans `src/Controller/BattleController.php` et `templates/battle/test.html.twig`. 

- [ ] Injecter le service `ActionResolver` dans l'action `test` ou dans le constructeur du contrôleur
  - [ ] S'en servir dans le code présent
- [ ] Plusieurs fautes sont présentes (et certaines affichent des erreurs) dans les fichiers `BattleController.php` et `test.html.twig`, les corriger.
- [ ] Vérifier que tout fonctionne

## 6. Questions de cours (2 points)

### 6.1. Qu'est-ce qu'un thème de formulaire ?

- [ ] Un ensemble de règles de validation, définie dans l'entité liée au formulaire
- [ ] Un fichier PHP définissant le rendu du formulaire
- [ ] Un fichier Twig permettant de changer l'affichage des formulaires du site (tous ou seulement certains)
- [ ] Un fichier de configuration permettant de définir l'affichage de tous les formulaires du site

### 6.2. Qu'est-ce qu'un ParamConverter ?

- [ ] Un outil de Doctrine permettant de convertir les paramètres d'une action en entrée dans une table de la BdD
- [ ] Un outil permettant de convertir les paramètres d'une méthode d'un service en un service
- [ ] Un outil permettant de convertir l'objet Request en un paramètre d'une vue Twig
- [ ] Un outil permettant de convertir les paramètres des routes en objets 

### 6.3. Quelle suite de commandes fonctionne pour créer un projet et lancer le serveur local ?

- [ ] `symfony new --full new_project`, `composer install`, `symfony serve`
- [ ] `symfony new --full new_project`, `cd new_project`, `symfony serve`
- [ ] `composer install`, `cd new_project`, `symfony serve`
- [ ]`symfony serve`, `composer install`
  
### 6.4. Traductions

#### 6.4.1. Quel nom de fichier de traduction est correct pour le domain `front` ?

- [ ] `front.yml`
- [ ] `front.fr.yml`
- [ ] `translations.front.fr.yml`

#### 6.4.2. Quel appel d'une traduction est correct ?

- `{{ 'Ceci est une traduction' }}`
- `{{ 'Ceci est une traduction'|trans('front') }}`
- `{{ 'Ceci est une traduction'|trans({}, 'front') }}`