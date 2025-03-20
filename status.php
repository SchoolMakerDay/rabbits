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
    //chiave presente: verifico value  
    $value=$_GET['value'] ?? null;
    if ($value!==null){
        $l=new Log($key,$value);
        $l->insert();
    } 
    $l=Log::getLastKey($key);
    $value=$l->value ?? null;
    $ts=$l->ts ?? null;
}

// OUTPUT
$title="Status";
include './views/firstSection.php';
?>
<p>Consente di visionare il valore più recente corrispondente ad una chiave specificata e di inserire un nuovo valore</p>
<?php if($showKeyForm) { ?>   
            <p>Inserisci la chiave da ricercare:</p>
            <form method="GET">
                Key: <input name='key'><br>
                <input type="submit">
            </form>
            
<?php } else { ?>            
            
            <h2>Stato con key=<?=$key ?> e ts=<?=$ts ?></h2>
            <form method="GET">
                Value: <input name='value' value="<?= $value ?>"><br>
                <input type='hidden' name='key' value="<?= $key ?>">
                <input type="submit">
            </form>
<?php } ?> 
<?php            
include './views/lastSection.php';