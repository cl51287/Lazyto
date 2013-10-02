<?php
require_once 'Lazyto/Cmd/Abstract.php';
require_once 'Lazyto/Res/Abstract.php';
require_once 'Lazyto/Dispatcher/Interface.php';
class Lazyto_Core
{
	/**
	 *
	 * @var Lazyto_Dispatcher_Interface 
	 */
	private $_dispatcher	= null;
	
	private static $_instance	= null;
	
	private function __construct()
	{
	}
	
	public static function getInstance()
	{
		if (null === self::$_instance) {
			self::$_instance	= new self();
		}
		return self::$_instance;
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
			
		}
		return $this->_dispatcher;
	}
}
