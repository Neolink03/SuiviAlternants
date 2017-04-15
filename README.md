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
     - name: Dossiér
     - name: En attente
     - name: Etude
 
transitions:
     - name: Complet
       startStateName : Dossiér
       endStateName : Etude
     - name: Incomplet
       startStateName : Dossiér
       endStateName : En attente
     - name: Relance
       startStateName : En attente
       endStateName : En attente
     - name: Complet
       startStateName : En attente
       endStateName : Etude
```

nb:
- startStateName et endStateName doivent être un name donné à un état précédemment