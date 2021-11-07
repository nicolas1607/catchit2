        
            // Bienvenue sur Catch'it ! //


L'application permet de gérer vos collections parmis celles proposées sur le site.
En ajoutant les items que vous possedez déjà, vous augmentez leur popularité auprès
des autres utilisateurs. Il vous est également possible de donner votre avis sur un item.

/!\ IMPORTANT /!\
La gestion des collections et des items a été traité de manière à ce que l'on duplique
ceux proposés par l'admin. C'est à dire que si deux utilisateurs ajoutent l'item Faucon Milenium,
il y aura en tout trois items du Faucon en BD, un pour chaque user + celui de l'admin.
/!\ IMPORTANT /!\



    1. Installation

        1.1 Initialiser le projet

> git clone https://github.com/nicolas1607/catchit2
> cd catchit2

Configurer le fichier .env comme l'exemple suivant :
# DATABASE_URL="mysql://db_user:db_password@127.0.0.1:8889/catchit?serverVersion=5.7"

> composer install
> npm install encore
> npm run dev
> symfony console doc:data:create
> symfony console doc:schema:update --force
> symfony console doc:fix:load
    > yes
> symfony console doc:data:import db.sql   <!-- Ne fonctione pas chez moi. Importer le fichier dans phpMyAdmin
> symfony serve



    2. Compte administrateur

En tant que formateur du CEFIM, il vous est fournit un compte 
administrateur qui vous donnera accès à la liste des utilisateurs, ainsi 
qu'à la gestion des collections, des items et des commentaires :

> Adresse email : mauger@cefim.eu
> Mot de passe : mauger

Vous aurez également accès à un compte utilisateur afin de profiter
au maximum de l'expérience Catch'it :

> Adresse email : user@gmail.com
> Mot de passe : test


        2.1 Gestion administrateur

La liste des collections vous permet de :

    - consulter une collection
    - ajouter un nouvel item
    - modifier une collection
    - supprimer une collection

La liste des items vous permet de :

    - consulter un item
    - modifier un item
    - supprimer un item

La liste des commentaires vous permet de :

    - valider un commentaire
    - refuser un commentaire
    


    3. Compte utilisateur

Un utilisateur ne pourra ajouter aucune collection ni aucun item. Il pourra
en revanche ajouter à ses collections, une des celles proposées par les
administrateur et ajouter les items qu'il possède déjà.

Il lui est également possible de noter un item /5 ou encore de laisser un 
commentaire, qui sera valider ou refuser par l'admin s'il ne respecte pas la charte
du site.



    4. Compte visiteur

En tant que visiteur du site, vous n'avez pas la possibilité d'intéragir avec les
collections ou les items. Vous pouvez cependant consulter la totalité des collections, 
des items, de leur note globale ainsi que de leurs commentaires.