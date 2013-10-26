<?php
require_once 'Lazyto/Command/Abstract.php';
require_once 'Lazyto/Result/Abstract.php';
require_once 'Lazyto/Dispatcher/Interface.php';
class Lazyto_Core
{
	/**
	 *
	 * @var Lazyto_Dispatcher_Interface 
	 */
	private $_dispatcher	= null;
	
	private static $_instance	= null;
	
	private static $_isInit	= false;
	
	private function __construct()
	{
		
	}
	
	public static function getInstance()
	{
		if (!self::$_isInit) {
			throw new Lazyto_Exception('Lazyto is not initialized.');
		}
		
		if (null === self::$_instance) {
			self::$_instance	= new self();
		}
		return self::$_instance;
	}
	
	public static function init()
	{
		
	}
	
	/**
	 * 
	 * @param Lazyto_Command_Interface $cmd
	 * @return Lazyto_Result_Abstract
	 */
	public function exec(Lazyto_Command_Abstract $cmd)
	{
		$executor	= $this->getDispatcher()->dispatch($cmd);
		$result	= $executor->exec($cmd);
		return $result;
	}
	
	public function setDispatcher(Lazyto_Dispatcher_Interface $dispatcher)
	{
		$this->_dispatcher	= $dispatcher;
		return $this;
	}
	
	public function getDispatcher()
	{
		if (null === $this->_dispatcher) {
			$this->setDispatcher(new Lazyto_Dispatcher_Default());
		}
		return $this->_dispatcher;
	}
}
