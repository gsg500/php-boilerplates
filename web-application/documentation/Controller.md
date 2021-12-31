# Controller
De controller regelt al het verkeer tussen de controller->model en controller->view. De controller is een "doorsluis" class die in principe geen weet heeft wat er in de database staat of wat de gebruiker ziet. De controller geeft opdrachten aan de model en haalt data op om vervolgens te verzenden naar de view.

Standaard methode voor de controller is als volgt:
* void **render**( string, array )

Hierbij is de string een pad naar de view bestand, mappen en bestanden worden gescheiden door een **.**
Render haalt alle beschikbare data bij elkaar en stuurt deze op naar het view bestand. Data wat altijd word meegegeven zijn:
* errors
* notifications
* success berichten
* Template object
* Overige data

Alle data is gebundeld in een $stack variabele, maar is ook aan te roepen door middel van $keyNaam array. 

**$stack[ 'data' ]** is dus aan te roepen als **$data**