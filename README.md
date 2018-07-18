playground
==========

A Symfony project created on June 26, 2018, 9:16 pm.



Installation and set-up for more and good helping. Please read this if needed.
=============================================================================
Translation comming soon asap ;-)


1. Création du projet
php symfony.phar new playground 3.3.3

2. (for linux users recommended)
HTTPDUSER=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX var/cache var/logs var/sessions
sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX var/cache var/logs var/sessions

    OR (recommended)
    sudo apt-get install acl
    sudo setfacl -R -m u:www-data:rX var/cache
    sudo setfacl -dR -m u:www-data:rX var/cache
    and so ... and so ... for session, logs 

    OR (but not recommended)
    chmod -R 777 var/*  (or sudo chmod -R 777 var/)

3. Mise à jour du composer
php composer.phar self-update
php composer.phar update


4. Initialisation des réglages du repository git
git init   
→ à faire depuis la console par exemple dans le répertoire du projet

5. Ajout et mandat de dépôt
git add .
=> Penser à gitignore pour enlever les fichier à ne pas déposer dans git (database configuration par exemple ...)
→ à faire depuis la console par exemple dans le répertoire du projet        

6. Voir le status du repository
git status
→ à faire depuis la console par exemple dans le répertoire du projet

7. Indiquer à Git de conserver les changements
git commit -m "Initial commit"
→ à faire depuis la console par exemple dans le répertoire du projet

8. GitHub : Créer un nouveau repository sur github
→ à faire depuis l'application github (https://github.com)

9. 
git remote add origin https://github.com/glennprog/symfony-playground.git
→ à faire depuis la console par exemple dans le répertoire du projet
    
    9.1
    La commande au dessus utilise https et non ssh. Pour basculer en https, faire d'abord comme suite :
    git remote set-url origin https://github.com/glennprog/symfony-playground.git
    git remote -v
    => git remote -v pour vérifier si la configuration à bien basculer en https.
    => channel officiel help.github : https://help.github.com/articles/changing-a-remote-s-url/

10. Pousser le code sur la branche distante dans GitHub
git push -u origin master
→ à faire depuis la console par exemple dans le répertoire du projet

11. Utiliser git à ses fins pour le projet
Branch, edit, commit, merge, push




Principal Contenus du playground
================================

Tous le contenus n'est pas renseigné, il sera mis à jour bientôt.

- Commit des Services
    - AnswerHadnler service
    - WatchDogLogger service
    - message generator service
    - injection of core Services
    - injection of own service

Bonus : 
- Alerts message adding (could see here https://github.com/glennprog/custom-alert)
- Using of flashbag and alert message box
- using of repository for own queries, class : GM\QuestionAnswersBundle\Repository\WatchDogLoggerRepository
- WatchDogLogger : A trailer log (with writing in DB) rfor the action handled in the application.
- message generator : A generator of message regarding the action handled.
- AnswerHadnler Handler of Answer (using as a service also)



