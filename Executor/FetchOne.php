<?php
require_once 'Lazyto/Executor/Interface.php';

abstract class Lazyto_Executor_FetchOne implements Lazyto_Executor_Interface
{
	public function exec(Lazyto_Command_Abstract $cmd)
	{
		$result	= $this->_exec($cmd);
		if (!$result instanceof Lazyto_Result_FetchOne) {
			throw new Lazyto_Exception('result is not a Lazyto_Result_FetchOne.');
		}
		return $result;
	}
	
	abstract protected function _exec(Lazyto_Command_FetchOne $cmd);
}
