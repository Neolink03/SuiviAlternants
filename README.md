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
     - name: Dossier
       machineName : dossier
     - name: En attente
       machineName : attente
     - name: Etude
       machineName : etude
 
 transitions:
     - name: Complet
       machineName : complet
       startStateMachineName : dossier
       endStateMachineName : etude
     - name: Incomplet
       machineName : incomplet
       startStateMachineName : dossier
       endStateMachineName : attente
     - name: Relance
       machineName : relance
       startStateMachineName : attente
       endStateMachineName : attente
     - name: Complet
       machineName : completfromattente
       startStateMachineName : attente
       endStateMachineName : etude
```

nb:
- Les machines name ne doivent contenir que des lettres en miniscule et aucun espace.
- startStateMachineName et endStateMachineName doivent être un machineName donné à un état précédemment