<?php


use lib\Autoload;
use models\Log;

require_once 'lib/Autoload.php';
Autoload::autoload();

$key=$_GET['key'] ?? null;

if ($key===null){
    //chiave assente: presento key form
    $showKeyForm=true;
}
else {
    $showKeyForm=false;
    //chiave presente: ricerco e visualizzo i dati.    
    $res=Log::getAllKey($key);
}

// OUTPUT
$title="Log View";
include './views/firstSection.php';
?>
<?php if($showKeyForm) { ?>   
            <p>Inserisci la chiave da ricercare:</p>
            <form method="GET">
                Key: <input name='key'><br>
                <input type="submit">
            </form>
            
<?php } else { ?>            
            
            <h3>Dati con key=<?=$key ?></h3>
            <table>
                <thead>
                    <tr><th>Key</th><th>Value</th><th>Ts</th></tr>
                </thead>
                <tbody>
<?php 

foreach ($res as $row){
    echo"<tr>";
    echo"<td>{$row['key']}</td>";
    echo"<td>{$row['value']}</td>";
    echo"<td>{$row['ts']}</td>";
    echo"<tr>\n";
}

?>
                </tbody>
            </table>
<?php } ?> 
<?php            
include './views/lastSection.php';