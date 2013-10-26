<?php
abstract class Lazyto_Command_Abstract
{
	protected $_actEntity;

	protected $_params	= array();
	
	public function __construct($actEntity)
	{
		$this->_actEntity	= $actEntity;
	}

	public function getAction()
	{
		$className	= get_class($this);
		return strtolower(str_replace('Lazyto_Command_', '', $className));
	}
	
	public function getActEntity()
	{
		return $this->_actEntity;
	}
	
	public function setActEntity($actEntity)
	{
		$this->_actEntity	= $actEntity;
		return $this;
	}
	
	public function getParam($paramKey, $default = null)
	{
		return isset($this->_params[$paramKey]) ? $this->_params[$paramKey] : $default;
	}
	
	public function setParam($paramKey, $paramValue)
	{
		$this->_params[$paramKey]	= $paramValue;
		return $this;
	}
}