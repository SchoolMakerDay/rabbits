# rabbits
<b>Rabbits A Basic Browser for Internet of ThingS</b>

<b>Cosa è RABBITS</b><br>
Rabbits è un sistema di gestione dei dati IoT basato su uno schema a tre strati (field/broker/panel).<br>
<center><img src="https://github.com/SchoolMakerDay/rabbits/blob/main/img/three-tier-iot.jpg" width="50%" height="50%" /></center><br>
L’elemento centrale è il Broker, una applicazione PHP server side che archivia in un database SQLite coppie “chiave/valore”.<br>
Le coppie chiave valore possono essere create, lette, modificate attraverso API inviate mediante il protocollo HTTP inserendo i dati come parametri di GET.<br>
I dispositivi embedded IoT (field) possono inviare dati dei sensori al broker che li memorizza o possono chiedere, in polling lo stato di comandi memorizzati nel broker per comandare gli attuatori.<br>
Per l’interazione umana con il sistema è disponibile una web app configurabile (panel) che consente di visualizzare e comandare attraverso  widget.<br>
E’ anche disponibile una dashboard per l’amministrazione delle coppie chiave/valore.<br>

<b>Installazione del sw RABBITS</b><br>
Il sistema può essere utilizzato nella sua versione già installata sulla piattaforma SMD oppure può essere installato localmente su una macchina di sviluppo.<br>
Se si usa il server SMD non è necessaria alcuna installazione.<br> 
Per installare su una macchina di sviluppo si deve disporre di un computer dotato di un web server locale (Apache, XAMPP, IIS …) e l’intera cartella rabbits va inserita nella cartella htdocs del web server.<br>
Nei file /rabbits/dashboard/index.html e /rabbits/panel/index.html si deve sostituire la definizione di baseurl:<br>
<i>var baseurl="https://www.schoolmakerday.it/rabbits/"; <br>
con la definizione:<br>
var baseurl="https://localhost/rabbits/";</i><br>
Nei dispositivi emdedded, per comunicare con il broker è necessario definire (vedi sezione Hackathon-esempi-Arduino-R4-Wifi in questo stesso Github):
- URL del sever
- porta di accesso
- SSID e PWD della rete locale in cui l’embedde è inserito 
Nel caso di utilizzo del server SMD i parametri di configurazione sono:
char ssid[] = SECRET_SSID; 
char pass[] = SECRET_PASS;
char serverAddress[] = "www.schoolmakerday.it"; 
int port = 80;
Nel caso di installazione locale il serverAddress deve essere il numero IP del computer che ospita il server Web e deve essere nella stessa rete locale.

Utilizzo del sw RABBITS
La documentazione delle API è disponibile all’URL:
https://www.schoolmakerday.it/rabbits/
La dashboard di amministrazione delle coppie chiave/valore è disponibile all’URL:
https://www.schoolmakerday.it/rabbits/dashboard/
Il pannello di intefaccia utente è disponibile all’URL:
https://www.schoolmakerday.it/rabbits/panel/
