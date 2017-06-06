Suivis des alternants
========================

Installation des dépendances :
`composer install`

Création de la base de donnée, de ses tables et de données initiales :
`app:database:initialize`

Users Dev
- 
### Admin 
    email : admin@yopmail.com 
    Password : admin
### Manager
    email: manager@yopmail.com
    Password: manager
### Student
    email: student@yopmail.com
    Password: student
### Jury
    email: jury@yopmail.com
    Password: jury

Creation d'un workflow depuis un fichier
-
Example de format d'import d'un workflow yml :

```yaml
states:
    -
        name: 'Dossiér'
        jury_can_edit: false
        send_mail: false
    -
        name: 'En attente'
        jury_can_edit: false
        send_mail: false
    -
        name: 'Etude'
        jury_can_edit: false
        send_mail: false
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

Import d'étudiants au format csv
```csv
Joubert;Antoine;joub@example.com
Martin;Marc;marc.martin@example.com
```