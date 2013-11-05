<?php
class Lazyto_Entity
{
	private function __construct()
	{
		
	}
	
	public static function factory($entityCode, array $entityValues = array())
	{
		$classname	= 'Entity_' . ucfirst($entityCode);
		$filename	= 'Entity/' . ucfirst($entityCode) . '.php';
		
		if (!is_file($filename)) {
			throw new Lazyto_Exception($entityCode . ' entity not exists. File cannot be found.');
		}
		
		require_once $filename;
		if (!class_exists($classname)) {
			throw new Lazyto_Exception($entityCode . ' entity not exists Cannot find class.');
		}
		
		return new $classname($entityValues);
	}

}
