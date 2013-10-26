<?php
class Lazyto_Executor_Mysql_MysqlEntityMaps
{
	public function getEntityTable($entityId)
	{
		return strtolower($entityId);
	}
	
	public function getEntityField($entityId, $property)
	{
		return strtolower($property);
	}
}