<?php
/**
    @date 09 02 2023
    @author Giovanni Ragno
    @copyright https://creativecommons.org/licenses/by-sa/4.0/
    @date 24 02 2023
    @author Duilio Peroni
	@added logo,name and abstract
*/

use lib\Autoload;
use models\Log;

require_once 'lib/Autoload.php';
Autoload::autoload();
$protocol=(isset($_SERVER['HTTPS'])?($_SERVER['HTTPS']?'https':'http'):'http');
$myPath=dirname($_SERVER['SCRIPT_NAME']);
$baseurl="$protocol://{$_SERVER['HTTP_HOST']}$myPath/";

// OUTPUT
$title="Documentazione API e Web App";
include './views/firstSection.php';
?>
<p>Rabbits è un <b>message broker</b> per IoT pensato per lo sviluppo di attività didattiche.</br>
Un message broker è un componente software che svolge il ruolo da intermediario tra applicazioni o dispositivi che desiderano inviare e ricevere messaggi e smista i messaggi tra i vari dispositivi.<br>
Spesso i message broker utilizzano protocolli dedicati all'IoT come <b>MQTT</b>; questo message broker invece per maggiore semplicità utilizza il protocollo <b>HTTP</b>.<br>
Le informazioni sul message broker si presentano come <b>variabili</b> composte da coppie <b>"chiave-valore"</b>  che sono esposte in una <b>"dashboard"</b> associata al broker.<br>
Le variabili possono essere <b>"readonly"</b> e in questo caso hanno il significato di informazioni di stato che arrivano dai dispositivi oppure possono essere <b>modificabili</b> e in tale caso hanno il significato di <b>"immagini di comando"</b> per i dispositivi.<br>
Con la soluzione HTTP  le applicazioni e i dispositivi per mandare informazioni devono eseguire un <b>API</b> sotto forma di una richiesta <b>HTTP con parametri GET</b>  e per conoscere lo stato di una variabile devono effettuare una <b>interrogazione ciclica (polling)</b> di richiesta della variabile che viene ristituita in formato <b>JSON</b>.   </p>
<p>Nella prima sezione di questa pagina sono documentate le API disponibili e nella seconda sono presentate alcune webapp tra cui una <b><a href='<?= $baseurl ?>dashboard'>DASHBOARD</a></b> che potrà anche essere installata nei propri dispositivi.</p>
            <h2>Documentazione API</h2>
            <h3 class='api'>API time</h3>
            <p>Questa API restituisce l'ora corrente del server</p>
            <p>Si invoca con </p><pre><a href='<?= $baseurl ?>time.php'><?= $baseurl ?>time.php</a></pre>
            <p> e restituisce un oggetto JSON con campi: </p><ul>
                <li>status: deve avere valore 'OK', altrimenti c'è stato un errore</li>
                <li>data: oggetto con i campi "year","month","day","hour","minute","second" con i rispettivi valori</li>
            </ul> <p>Esempio di risposta:</p>
            <pre>{"status":"OK","data":{"year":"2022","month":"03","day":"14","hour":"09","minute":"04","second":"41"}}</pre>
            
            <h3 class='api'>API set</h3>
            <p>Questa API consente la registrazione da parte del server di dati che vengono inviati in forma di coppie chiave-valore</p>
            <p>Si invoca con </p><pre><?= $baseurl ?>set.php?key=KKK&value=VVV</pre>
            <p>in cui i due parametri key e value sono obbligatori. Il valore per key deve essere di tipo string lungo fino a 10 caratteri, il valore per value deve esssere di tipo string lungo fino a 255 caratteri.</p>
            <p>Alla registrazione della coppia key-value viene aggiunto anche il timestamp (data e ora)</p>
            <p>Ad ogni set il dato viene aggiunto (e non sovrascritto al precedente) mantenendo quindi una successione storica, per non accumulare troppi dati nel tempo ad ogni inserimento vengono comunque rimossi i dati con stessa chiave e timestamp antecedente a 48 ore prima.</p>
            <p>Restituisce un oggetto JSON con i dati ottenuti da una rilettura dei dati inseriti, in particolare i seguenti campi: </p><ul>
                <li>status: deve avere valore 'OK', altrimenti c'è stato un errore</li>
                <li>(solo se status OK)data: oggetto con i campi "key","value","ts"</li>
            </ul> 
            <p>Esempio di chiamata corretta:</p>
            <pre><a href='<?= $baseurl ?>set.php?key=esempio&value=12.34'><?= $baseurl ?>set.php?key=esempio&value=12.34</a></pre>
            <p>risposta:</p>
            <pre>{"status":"OK","data":{"key":"esempio","value":"12.34","ts":"2023-02-08 10:10:16"}}</pre>
            <p>Esempio di chiamata errata:</p>
            <pre><a href='<?= $baseurl ?>set.php?key=esempio'><?= $baseurl ?>set.php?key=esempio</a></pre>
            <p>risposta:</p>
            <pre>{"status":"ERROR"}</pre>
            
            <h3 class='api'>API get</h3>
            <p>Questa API consente di ottenere il valore più recente associato ad una chiave</p>
            <p>Si invoca con </p><pre><?= $baseurl ?>get.php?key=KKK</pre>
            <p>in cui il parametro key è obbligatorio. Il valore per key deve essere di tipo string lungo fino a 10 caratteri.</p>
            <p>Restituisce un oggetto JSON con campi: </p><ul>
                <li>status: deve avere valore 'OK', altrimenti c'è stato un errore</li>
                <li>(solo se status OK) data: oggetto con i campi "key","value","ts" o null se chiave mancante.</li>
            </ul> 
            <p>Esempio di chiamata corretta:</p>
            <pre><a href='<?= $baseurl ?>get.php?key=esempio'><?= $baseurl ?>get.php?key=esempio</a></pre>
            <p>risposta:</p>
            <pre>{"status":"OK","data":{"key":"esempio","value":"12.34","ts":"2023-02-08 10:10:16"}}</pre>
            <p>Esempio di chiamata errata:</p>
            <pre><a href='<?= $baseurl ?>get.php'><?= $baseurl ?>get.php</a></pre>
            <p>risposta:</p>
            <pre>{"status":"ERROR"}</pre>
            
            <h3 class='api'>API getlog</h3>
            <p>Questa API consente di ottenere tutti i valori presenti sul server associati ad una chiave</p>
            <p>Si invoca con </p><pre><?= $baseurl ?>getlog.php?key=KKK</pre>
            <p>in cui il parametro key è obbligatorio. Il valore per key deve essere di tipo string lungo fino a 10 caratteri.</p>
            <p>Restituisce un oggetto JSON con campi: </p><ul>
                <li>status: deve avere valore 'OK', altrimenti c'è stato un errore</li>
                <li>(solo se status OK) data: array di oggetti con i campi "key","value","ts" o array vuoto se chiave mancante.</li>
            </ul> 
            <p>Esempio di chiamata corretta:</p>
            <pre><a href='<?= $baseurl ?>getlog.php?key=esempio'><?= $baseurl ?>getlog.php?key=esempio</a></pre>
            <p>risposta:</p>
            <pre>{"status":"OK","data":[{"key":"esempio","value":"12.34","ts":"2023-02-08 10:26:50"},{"key":"esempio","value":"12.34","ts":"2023-02-08 10:10:16"}]}</pre>
            <p>Esempio di chiamata errata:</p>
            <pre><a href='<?= $baseurl ?>getlog.php'><?= $baseurl ?>getlog.php</a></pre>
            <p>risposta:</p>
            <pre>{"status":"ERROR"}</pre>
            
            <h3 class='api'>API getkeys</h3>
            <p>Questa API consente di ottenere per più chiavi il corrispondente valore più recente associato</p>
            <p>Si invoca con </p><pre><?= $baseurl ?>getkeys.php?keys=["KK1","KK2",...]</pre>
            <p>in cui il parametro keys è obbligatorio. Il valore per keys deve essere un array JSON con le chiavi che interessano.</p>
            <p>Restituisce un oggetto JSON con campi: </p><ul>
                <li>status: deve avere valore 'OK', altrimenti c'è stato un errore</li>
                <li>(solo se status OK) data: un oggetto avente per campi le chiavi passate, per ognuna si ha oggetto con i corrispondenti "key","value","ts" o null se chiave mancante.</li>
            </ul> 
            <p>Esempio di chiamata corretta:</p>
            <pre><a href='<?= $baseurl ?>getkeys.php?keys=["esempio","nonesiste"]'><?= $baseurl ?>getkeys.php?keys=["esempio","nonesiste"]</a></pre>
            <p>risposta:</p>
            <pre>{"status":"OK","data":{"esempio":{"key":"esempio","value":"12.34","ts":"2023-02-08 10:26:50"},"nonesiste":null}}</pre>
            <p>Esempio di chiamata errata:</p>
            <pre><a href='<?= $baseurl ?>getkeys.php'><?= $baseurl ?>getkeys.php</a></pre>
            <p>risposta:</p>
            <pre>{"status":"ERROR"}</pre>
            
            <h2>WebApp</h2>
            <h3 class='webapp'>WebApp LogView</h3>
            <p>All'indirizzo</p><pre><a href='<?= $baseurl ?>logview.php'><?= $baseurl ?>logview.php</a></pre>
            <p>è disponibile una webapp che consente di visionare tutte le registrazioni riferita ad una chiave specificata</p>

            <h3 class='webapp'>WebApp Status</h3>
            <p>All'indirizzo</p><pre><a href='<?= $baseurl ?>status.php'><?= $baseurl ?>status.php</a></pre>
            <p>è disponibile una webapp che consente di visionare il valore più recente corrispondente ad una chiave specificata e successivamente di inserire un nuovo valore</p>

            <h3 class='webapp'>WebApp Monitor</h3>
            <p>All'indirizzo</p><pre><a href='<?= $baseurl ?>monitor.php'><?= $baseurl ?>monitor.php</a></pre>
            <p>è disponibile una webapp che consente di visionare il valore più recente corrispondente ad una chiave specificata; la visualizzazione si aggiorna automaticamente ogni secondo</p>

            <h3 class='webapp'>WebApp Dashboard</h3>
            <p>All'indirizzo</p><pre><a href='<?= $baseurl ?>dashboard'><?= $baseurl ?>dashboard</a></pre>
            <p>è disponibile una webapp che generalizza le funzioni di "status" e "monitor", consente cioè di impostare valore associato a una o più "chiave di comando" e tenere monitorato il valore di una o più "chiave di stato"</p>

<?php            
include './views/lastSection.php';