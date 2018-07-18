# playground symfony

A Symfony project created on June 26, 2018, 9:16 pm.

Objectif :
partager des astuces, des idées tant sur le code que sur l'installation ou le déploiement projet symfony.

## Astuces/conseil Installation

Sera mise à jour bientôt en prenant compte des version symfony etc.


1. Création du projet (Symfony 3)
```
php symfony.phar new playground 3.3.3
```

2. (for linux users recommended)
```
HTTPDUSER=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
```
```
sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX var/cache var/logs var/sessions
sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX var/cache var/logs var/sessions
```

*OR (recommended)*
```
sudo apt-get install acl
sudo setfacl -R -m u:www-data:rX var/cache
sudo setfacl -dR -m u:www-data:rX var/cache
and so ... and so ... for session, logs 
```

*OR (but not recommended)*
```
chmod -R 777 var/*  (or sudo chmod -R 777 var/)
```

3. Mise à jour du composer
```
php composer.phar self-update
php composer.phar update
```

## Astuces/conseil Installation et utilisation nouveau repo git du projet

1. Initialisation des réglages du repository git
```
git init   
```

2. Ajout et mandat de dépôt
```
git add .
```

Penser à gitignore pour enlever les fichier à ne pas déposer dans git (database configuration par exemple ...)      

3. Voir le status du repository
```
git status
```

4. Indiquer à Git de conserver les changements
```
git commit -m "Initial commit"
```

5. GitHub : Créer un nouveau repository sur https://github.com

6. Add orignin  
```
git remote add origin https://github.com/glennprog/symfony-playground.git
```
La commande au dessus utilise https et non ssh. Pour basculer en https, faire d'abord comme suite :
```
git remote set-url origin https://github.com/glennprog/symfony-playground.git
git remote -v
```
git remote -v pour vérifier si la configuration à bien basculer en https.
channel officiel help.github : https://help.github.com/articles/changing-a-remote-s-url/

7. Pousser le code sur la branche distante dans GitHub
```
git push -u origin master
```

8. Utiliser git à ses fins pour le projet
```
Branch, edit, commit, merge, push, status, add ...
```


# Principal Contenus du playground

Tous le contenu n'est pas renseigné, il sera mis à jour bientôt.

- Services
    - QuestionHadnler service
    - AnswerHadnler service
    - WatchDogLogger service
    - message generator service
    - Form Manager service
    - injection des service Core
    - injection des services custom

    Bonus : 
    - Outils Alerts message (disponible depuis le repos https://github.com/glennprog/custom-alert)
    - Utlisation des flashbag injecté dans la réponse de la requête.
    - Utilisation des repository entité pour des requête custom (par exemple QuestionRepository)
    - WatchDogLogger : Traceur de log sur Suppression et mis à jour; Avec écriture de log en DB par exemple.
    - message generator : Un générateur de message associés aux actions (création, mise à jour, etc)
    - AnswerHadnler Handler of Answer (using as a service also)

Mise à jour du contenus bientôt

# Arrive bientôt:

Un prochain travail portera sur la documentation du code, avec la génération de la doc associée.



<(^__^)>