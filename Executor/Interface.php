<?php
require_once 'Lazyto/Cmd/Abstract.php';
interface Lazyto_Executor_Interface
{
	/**
	 * 
	 * @param Lazyto_Command_Abstract $cmd
	 * @return Lazyto_Result_Abstract
	 */
	public function exec(Lazyto_Command_Abstract $cmd);
}
