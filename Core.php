<?php
require_once 'Lazyto/Cmd/Interface.php';
require_once 'Lazyto/Res/Abstract.php';
class Lazyto_Core
{
	/**
	 * 
	 * @param Lazyto_Cmd_Interface $cmd
	 * @return Lazyto_Res_Abstract
	 */
	public function exec(Lazyto_Cmd_Interface $cmd)
	{
		$result	= new Lazyto_Res_FetchList($cmd);
		return $result;
	}
}
