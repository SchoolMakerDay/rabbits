<?php

namespace models;

use lib\Singleton;
use PDO;

/**
 * Description of DataBase
 *
 * @author gianni
 */
class DataBase extends Singleton {
    const DSN="sqlite:data/maker.sqlite";
    /** @var PDO connessione al db */
    public PDO $conn;
    
    protected function __construct() {
        $this->conn = new PDO(self::DSN);
    }

}
