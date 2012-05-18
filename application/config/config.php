<?php

// Load configuration 
Configuration::loadConf($_SERVER["SERVER_ADMIN"]);

// APPLICATION_PATH je definovana v index.php a hned za ni je includovan tento soubor
define('LIB_PATH', APPLICATION_PATH . '/../library');
define('MODEL_PATH', APPLICATION_PATH . '/model');
define('TEMP_PATH', APPLICATION_PATH . '/../temp');
define('UPLOAD_PATH', TEMP_PATH . '/upload');
define('DOCUMENTROOT_PATH', APPLICATION_PATH . '/../public');
define('RESOURCES_PATH', APPLICATION_PATH . '/../resources');

// log
define('LOG_PATH', TEMP_PATH . '/log');

// protokol
if($_SERVER['SERVER_PORT'] == 80)
	define('PROTOCOL', 'http://');
elseif($_SERVER['SERVER_PORT'] == 443)
	define('PROTOCOL', 'https://');
else
	die("Internal Server Error! Bad port. [config.php]");

// vnejsi adresa teto website
define('WWW_PATH', PROTOCOL . $_SERVER['SERVER_NAME']);

/**
 * Class for loading configuration for specific server
 */
class Configuration {
	
	private $_serverAdmin;

	public function __construct($serverAdmin) {
		$this->_serverAdmin = $serverAdmin;
	}
	
	public static function loadConf($serverAdmin) {
		$conf = new Configuration($serverAdmin);
		$conf->load();
	}
	
	public function load() {

		switch($this->_serverAdmin) {
			// server Angel Hosting
			case "webmaster@vs5715.angel-hosting.cz":
				require_once "production.php";
				return;
			// localhost Petr
			case "petr@localhost":
				require_once "petr.php";
				return;
		}		
		die("Internal Server Error! [config.php]");
	}
}
