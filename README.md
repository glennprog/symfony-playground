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

2. (Pour utilisateurs linux)
```
HTTPDUSER=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
```
```
sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX var/cache var/logs var/sessions
sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX var/cache var/logs var/sessions
```

*Ou aussi*
```
sudo apt-get install acl
sudo setfacl -R -m u:www-data:rX var/cache
sudo setfacl -dR -m u:www-data:rX var/cache
and so ... and so ... for session, logs 
```

*Ou aussi (mais pas recommandé)*
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

6. Add origin  
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
Le contenus n'est sert juste à jouer avec les composants, le code, etc.. Donc ce n'est pas un projet finis en l'état.
Le code n'est donc pas encore épuré, c'est tel un échafaudage, un small dratf pour jouer avec symf.

Des travaux seront menés pour avoir un playground safe et riche et surtout épuré en l'état.

# Arrive bientôt:

Un prochain travail portera sur la documentation du code, avec la génération de la doc associée.
Mise à jour du contenus bientôt de la documentation en cours (bientôt dans le wiki)


<(^__^)> aka sudo master :)