<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

## Installation

**1.** Récupérer le projet
`git clone https://gitlab.com/ckcnet/admin-ckc.git`

**2.** Installer les dépendances Composer
`composer install` ou `php composer.phar install`

**3.** Installer les dépendance NPM
`npm install`

**4.** Créer et configurer le `.env` en dupliquant le `.env.example` <br>
- Générer la clé de l'application avec `php artisan key:generate`
- Renseigner les accès de sa BDD locale
- Renseigner son driver de mail et ses identifiants

**5.** Jouer les migrations
`php artisan migrate`

**6.** Jouer les seeders
`php artisan db:seed`
Les seeders ajoutent les entrées suivantes en BDD :
- Un admin de base **admin@ckc-net.com** / **password**
- Les rôles des admins (cf. listing des sections plus haut)

**7.** Terminé !

## Test technique - Ajouter une gestion de tags (Section Blog)
- Pour avoir accés à la section Blog du back-office, vous devez vous rendre dans la section Administrateurs pour vous accorder les droits
(Administrateurs => Détails (Admin CKC) => Permissions - Cocher la case "Accès au blog" puis "Mettre à jour")
- La section Blog est maintenant accessible depuis le menu à gauche
- Dans un article, Ajouter la possibilités d'y lier/créer des Tags (À la manière des Hastags Instagram par exemple)
- Un article peut être associé à 1-N Tags (Au moins un Tag pour un article)
- Un Tag peut être associé à 0-N articles
- Les nouveaux Tags douvent être crées à la volée (Pas d'interface de création de Tags en dehors du formulaire de l'article)
- Pas de front, uniquement la partie back-office
- Les outils de Laravel permettent largement de devolopper cette feature (Migration, Request, Validation, Model, Relations ...)
- Vous pouvez utiliser les packages/plugins front ou back déjà présent dans le projet (Pas de package composer supplémentaire)
- Créer une branche `feature-tags/VOTRE_NOM_PRENON` puis créer une Merge request dans GitLab
- Si vous avez des questions concernant ce test, n'hesitez pas nous contacter
