<?php
require_once 'Lazyto/Cmd/Abstract.php';
interface Lazyto_Executor_Interface
{
	public function exec(Lazyto_Cmd_Abstract $cmd);
}
