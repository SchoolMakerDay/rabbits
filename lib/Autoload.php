<?php

namespace lib;


/**
 * Description of Autoload
 *
 * @author gianni
 */
class Autoload {
    
        /**
         * registra la funzione di autoload
         */
	public static function autoload()
	{
		spl_autoload_register('self::loader');
	}
	
        /**
         * include il file con la dichiarazione della classe
         * @param string $class
         */
	public static function loader(string $class)
	{
		require str_replace('\\', '/', $class).'.php'; 
	}

}