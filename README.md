Suivis des alternants
========================

Installation des dépendances :
`composer install`

Création de la base de donnée, de ses tables et de données initiales :
`app:database:initialize`


Creation d'un workflow depuis un fichier
-
Example de format d'import d'un workflow yml :

```yaml
states:
    -
        name: 'Dossiér'
    -
        name: 'En attente'
    -
        name: 'Etude'
         
transitions:
    -
        name: 'Complet'
        startStateName:
            name: 'Dossiér'
        endStateName:
            name: 'Etude'
    -
        name: 'Incomplet'
        startStateName:
            name: 'Dossiér'
        endStateName:
            name: 'En attente'
    -
        name: 'Relance'
        startStateName:
            name: 'En attente'
        endStateName:
            name: 'En attente'
    -
        name: 'Complet'
        startStateName:
            name: 'En attente'
        endStateName:
            name: 'Etude'
```

nb:
- startStateName et endStateName doivent être un name donné à un état précédemment