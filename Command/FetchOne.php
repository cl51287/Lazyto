<?php
class Lazyto_Command_FetchOne extends Lazyto_Command_Abstract
{
	private $_conditions;
	
	private $_properties;
	
	public function setCondition($property, $value)
	{
		$this->_conditions[$property]	= $value;
		return $this;
	}
	
	public function getConditions()
	{
		return $this->_conditions;
	}
	
	public function setFetchProperties(array $properties)
	{
		$this->_properties	= $properties;
		return $this;
	}
	
	public function getFetchProperties()
	{
		return $this->_properties;
	}
}
