<?php 

error_reporting(E_ALL | E_STRICT);

$_SERVER['SERVER_NAME'] = 'fo';
$_SERVER['SERVER_ADMIN'] = 'petr@localhost';
$_SERVER['SERVER_PORT'] = 80;

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'testing'));


// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

// Loadnu nas vlastni PHP config
require_once APPLICATION_PATH . '/config/config.php';

/** Zend_Application */
require_once 'Zend/Application.php';

require_once 'Zend/Test/PHPUnit/ControllerTestCase.php';

require_once 'ControllerTestCase.php';
