<?php

/**
 * Vlastní třída zajišťující přesměrování logovaných zpráv do příslušných logů. 
 * Jako dílčí loggery se používají Zend_Log a loguje se vždy do souboru. 
 * Povolené typy logů jsou definovány v poli $_logs. Jméno logu je pak zároveň názvem 
 * magické metody, která pomocí něj loguje.
 * @author Petr Sobotka
 *
 */
class Czechline_Logger 
{
	protected $_path;
	protected $_logs = array( 'info'      => false,
	                          'error'     => false,
	                          'auth'      => false,
	                          'search'    => false,
	                          'profile'   => false,
	                          'api'       => false,
							  'exception' => false
							);
	
	/**
	 * Jediným parametrem je cesta k adresáři, do kterého chceme logovat. 
	 * Cesta by měla být bez koncového lomítka. 
	 * @param String $pathToLogTo
	 */
	public function __construct( $pathToLogTo )
	{
		// TODO: nějaká validace vstupní cesty by neuškodila
		$this->_path = $pathToLogTo;
	}
	
	/**
	 * Metoda zvolí požadovaný log a zapíše do něj zprávu. Pokud log není inicializován, 
	 * vytvoří jeho instanci.
	 * @param $logName
	 * @param $message
	 * @return void
	 */
	protected function _log( $logName, $message)
	{
		if($this->_logs[$logName] == false)
		{
			$writer = new Zend_Log_Writer_Stream( $this->_path . '/' . $logName . '.log' );
			$this->_logs[$logName] = new Zend_Log($writer);
		}
		$log = $this->_logs[$logName];
		$log->info($message);
	}
	
	/**
	 * Magická metoda umožňující volat loggování do jednotlivých logů. 
	 * Validuje se oproti poli $_logs. 
	 * @param String $method
	 * @param String $params
	 * @return void
	 */
    public function __call($method, $params)
    {
    	//throw new Exception(print_r($params, true));
        $logName = strtolower($method);
    	if(array_key_exists($logName, $this->_logs))
		{
            switch (count($params)) {
                case 0:
                    throw new Exception('Missing log message');
                case 1:
                    $message = $_SERVER['REMOTE_ADDR'] . ' ' . $params[0] ; // zaznamename IP adresu a logovanou zpravu
                    break;
                default:
                    throw new Exception('Invalid number of parameters: ' . count($params));
            }
			$this->_log($logName, $message);
		} else {
			throw new Exception("Log type doesn't exist");
		}
    }
	
	
}