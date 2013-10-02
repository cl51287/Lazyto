<?php
require_once 'Lazyto/Cmd/Abstract.php';
interface Lazyto_Dispatcher_Interface
{
	/**
	 * 
	 * @param Lazyto_Command_Abstract $cmd
	 * @return Lazyto_Executor_Interface
	 */
	public function dispatch(Lazyto_Command_Abstract $cmd);
}