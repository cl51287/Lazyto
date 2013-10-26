<?php
class Lazyto_Result_FetchOne extends Lazyto_Result_Abstract
{
	/**
	 * @var Entity_Abstract
	 */
	private $_entity;
	
	public function setEntity(Entity_Abstract $entity)
	{
		$this->_entity	= $entity;
		return $this;
	}
	
	public function getEntity()
	{
		return $this->_entity;
	}
}