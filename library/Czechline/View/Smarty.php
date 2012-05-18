<?php
/**
 *
 * @author http://blog.davidjclarke.co.uk/zend-framework-110-and-smarty-3-rc4.html
 */

/**
 * Smarty templating engine
 */

require_once(APPLICATION_PATH .'/../library/Smarty/Smarty.class.php');

class Czechline_View_Smarty extends Zend_View_Abstract 
{
	protected $_smarty;

	public function __construct($tmplPath = null, $extraParams = array()) {
		$this->_smarty = new Smarty();
		$this->_smarty->debugging = true;
		if(null !== $tmplPath) {
			$this->setScriptPath($tmplPath);
		}

		foreach($extraParams as $key => $value) {
			$this->_smarty->$key = $value;
		}
		
		/*
		 * Nasledujici je velmi důležité. Jinak bychom v Layout šablonách nemohli napsat:
		 * {$this->layout()->content}
		 */
		$this->assign('this', $this);
		
		/*
		 * Adresar s view hepery, vola parent metodu
		 */
		$this->addHelperPath( APPLICATION_PATH . "/../library/Czechline/View/Helper", 'Czechline_View_Helper');
	}

	public function _run() {
	   parent::run();
	}

	public function getEngine() {
		return $this->_smarty;
	}

	public function setScriptPath($path) {
		if(is_readable($path)) {
			//$this->_smarty->template_dir = $path;
			$this->_smarty->setTemplateDir($path);
			//return;
		} else {
			throw new Exception('Invalid path provided -> '.$path);
		}
	}
	
	public function addScriptPath($path)
	{
		if(is_readable($path)) {
			$this->_smarty->addTemplateDir($path);
		} else {
			throw new Exception('Invalid path provided -> '.$path);
		}
	}

	public function getScriptPaths() {
		return $this->_smarty->template_dir;
	}

	public function setBasePath($path, $classPrefix = 'Zend_View') {
		return $this->setScriptPath($path);
	}

	public function addBasePath($path, $classPrefix = 'Zend_View') {
		return $this->setSCriptPath($path);
	}

	public function __set($key, $val) {
		$this->_smarty->assign($key,$val);
	}

	public function __isset($key) {
		return (null !== $this->_smarty->get_template_vars($key));
	}

	public function __unset($key) {
		$this->_smarty->clearAssign($key);
	}

	public function assign($spec, $value = null) {
		if(is_array($spec)) {
			$this->_smarty->assign($spec);
			return;
		}

		$this->_smarty->assign($spec, $value);
	}

	public function clearVars() {
		$this->_smarty->clearAllAssign();
	}

	public function render($name) {
		return $this->_smarty->fetch($name);
	}
	
}
