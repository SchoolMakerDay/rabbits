<?php

namespace models;

use \PDOStatement; 

/**
 * Registra coooie chiave-valore con un timestamp
 *
 * @author gianni
 */
class Log {
    /** @var string chiave */
    public string $key;
    /** @var string valore */
    public string $value;
    /** @var string timestamp formato AAAA-MM-GG HH:MM:SS */
    public string $ts;
    
    /**
     * costruttore
     * @param string $key chiave
     * @param string $value valore
     * @param string|null $ts timestamp, se non passato viene determinato dal costruttore
     */
    public function __construct(string $key,string $value,?string $ts=null) {
        $this->key = $key;
        $this->value = $value;
        $this->ts = $ts===null?date('Y-m-d H:i:s'):$ts;
    }
    
    /**
     * isnserisce il dato in tabella
     * NB effettua un garbage collector
     *    eliminando i dati con stessa chiave antecedenti 48 ore
     * @return void
     */
    public function insert():void 
    {
        $sql="insert into log (key,value,ts) values (:k,:v,:t)";
        $stm=DataBase::getInstance()->conn->prepare($sql);
        $stm->execute([
            'k'=> $this->key,
            'v'=> $this->value,
            't'=> $this->ts,
        ]);
        //garbage collector
        $delDate= date("Y-m-d H:i:s",strtotime("-2 day"));
        $sql="delete from log where key=:k AND ts<'$delDate'";
        $stm=DataBase::getInstance()->conn->prepare($sql);
        $stm->execute([
            'k'=> $this->key,
        ]);
    }
    
    /**
     * Estrae tutti i dati con il valore di kay passata
     * @param string $key valore di key da ricercare
     * @return PDOStatement oggetto con query eseguita
     */
    public static function getAllKey(string $key): PDOStatement 
    {
        $sql="select * from log where key=:k order by ts DESC";
        $stm=DataBase::getInstance()->conn->prepare($sql);
        $stm->execute([
            'k'=> $key,
        ]);
        return $stm;
    }

    /**
     * Estrae tutti i dati con il valore di kay passata
     * @param string $key valore di key da ricercare
     * @return PDOStatement oggetto con query eseguita
     */
    public static function getArrayAllKey(string $key): array 
    {
        $ret=[];
        $stm= self::getAllKey($key);
        foreach ($stm as $row){
            $ret[]= new Log($row['key'],$row['value'],$row['ts']);
        }
        return $ret;
    }

    /**
     * Estrae l'ultimo log relativo alla key passata.
     * @param string $key
     * @return Log|null
     */
    public static function getLastKey(string $key): ?Log 
    {
        $sql="select * from log where key=:k order by ts DESC LIMIT 1";
        $stm=DataBase::getInstance()->conn->prepare($sql);
        $stm->execute([
            'k'=> $key,
        ]);
        $found= $stm->fetch(\PDO::FETCH_ASSOC);
        if ($found){
            return new Log($found['key'],$found['value'],$found['ts']);
        }
        return null;
    }


}
