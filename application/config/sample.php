<?php

/**
 * Konfiguracni soubor pro provoz na produkcnim serveru
 */

// databaze
define('DB_DRIVER', 'pdo_mysql');
define('DB_USERNAME', 'facetoptimizer');
define('DB_PASSWORD', '******************');
define('DB_DBNAME', 'facetoptimizer');
define('DB_HOST', 'localhost');
define('DB_ENCODING', 'utf8');
define('DB_PROFILER', TRUE);

// APC
define('APC_ON', TRUE);