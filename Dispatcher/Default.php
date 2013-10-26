<?php
require_once 'Lazyto/Dispatcher/Interface.php';
require_once 'Lazyto/Executor/Interface.php';
require_once 'Lazyto/Command/Abstract.php';
require_once 'Lazyto/Exception.php';

class Lazyto_Dispatcher_Default implements Lazyto_Dispatcher_Interface
{
	private static $_executors	= array();
	
	private $_maps	= array();
	
	public function __construct(array $options)
	{
		foreach ($options as $key => $value) {
			$method	= 'set' . ucfirst($key);
			$this->{$method}($value);
		}
	}
	
	public function setMaps(array $maps)
	{
		$this->_maps	= $maps;
		return $this;
	}
	
	public function dispatch(Lazyto_Command_Abstract $cmd)
	{
		$actEntity	= $cmd->getActEntity();
		$action	= $cmd->getAction();
		
		if (!isset(self::$_executors[$actEntity][$action])) {
			if (isset($this->_maps[$actEntity][$action])) {
				$classname	= ucfirst($this->_maps[$actEntity][$action]);
				$filename	= str_replace('_', '/', $classname) . '.php';
				if (!is_file($filename)) {
					$classname	= 'Lazyto_Executor_' . $classname;
					$filename	= 'Lazyto/Executor/' . $classname . '.php';
				} else {
					require_once $filename;
					if (!class_exists($classname)) {
						$classname	= 'Lazyto_Executor_' . $classname;
						$filename	= 'Lazyto/Executor/' . $classname . '.php';
					}
				}

				if (!is_file($filename)) {
					throw new Lazyto_Exception('Command cannot be executed. Cannot find Executor file.');
				} else {
					require_once $filename;
					if (!class_exists($classname)) {
						throw new Lazyto_Exception('Command cannot be executed. Canont find Executor.');
					}
				}

				$executor	= new $classname();

				if (!$executor instanceof Lazyto_Executor_Interface) {
					throw new Lazyto_Exception('Command cannot be executed. Executor is not a Lazyto_Executor_Interface.');
				}

				self::$_executors[$actEntity][$action]	= $executor;
			} else {
				throw new Lazyto_Exception('Command cannot be executed. Cannot find maps.');
			}
		}
		return self::$_executors[$actEntity][$action];
	}
}