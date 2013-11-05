<?php
class Lazyto_Executor_Mysql_EntityMaps
{
	const TABLES_MAP_KEY	= 'tablesmap';
	
	const FIELDS_MAP_KEY	= 'fieldsmap';
	
	private static $_instance;
	
	private $_entityMaps	= array();
	
	private function __construct()
	{
		
	}
	
	public static function getEntityMaps($entityId, array $properties)
	{
		if (null === self::$_instance) {
			self::$_instance	= new self();
		}
		return self::$_instance;
	}

	/**
	 * 设置实体的射关系（包括表映射——实体涉及的表及对应的别名，属性对应字段的映射）
	 * @param String $entityId
	 * @param Array $tablesMap 以别名为键，表名为值的键值对
	 * @param Array $fieldsMap 以属性名为键，表别名.表字段为值的键值对
	 * @return Lazyto_Executor_Mysql_EntityMaps
	 */
	public function setEntityMaps($entityId, array $tablesMap, array $fieldsMap)
	{
		$this->_entityMaps[$entityId][self::TABLES_MAP_KEY]	= array();
		
		foreach ($tablesMap as $alias => $tablename) {
			$this->_entityMaps[$entityId][self::TABLES_MAP_KEY][(string)$alias]	= (string) $tablename;
		}
		
		$this->_entityMaps[$entityId][self::FIELDS_MAP_KEY]	= array();
		
		foreach ($fieldsMap as $property => $field) {
			$field	= (string) $field;
			$aliasTableField	= explode('.', $field);
			if (count($aliasTableField) != 2) {
				throw new Lazyto_Exception('FieldsMap format error. It must be aliasTable.Field.');
			}
			$aliasTable	= $aliasTableField[0];
			
			if (!isset($this->_entityMaps[$entityId][self::TABLES_MAP_KEY][$aliasTable])) {
				throw new Lazyto_Exception('FieldsMap error. Field\'s table not exists.');
			}
			$trueTable	= $this->_entityMaps[$entityId][self::TABLES_MAP_KEY][$aliasTable];
			$this->_entityMaps[$entityId][self::FIELDS_MAP_KEY][(string)$property]	= array($trueTable, $aliasTableField[1]);
		}
		return $this;
	}
	
	/**
	 * 根据指定实体的属性获取其对应的表名和字段
	 * @param type $entityId
	 * @param array $properties
	 * @return Array 
	 * array(
	 *	'tables'	=> array($table1, $table2,……)
	 *	'fields'	=> array($field1, $field2,……)
	 * )
	 */
	public function getEntityTablesAndFields($entityId, array $properties)
	{
		if (!isset($this->_entityMaps[$entityId])) {
			throw new Lazyto_Exception('You must set ' . $entityId . ' entity maps.');
		}
		$entityMaps	= $this->_entityMaps[$entityId];
		
		if (!isset($entityMaps[self::TABLES_MAP_KEY])) {
			throw new Lazyto_Exception('You must set ' . $entityId . ' tables map.');
		}
		
		if (!isset($entityMaps[self::FIELDS_MAP_KEY])) {
			throw new Lazyto_Exception('You must set ' . $entityId . ' fields map.');
		}
		
		$tables	= $entityMaps[self::TABLES_MAP_KEY];
		$fields	= $entityMaps[self::FIELDS_MAP_KEY];
		
		$newFields = $newTables	= array();
		foreach ($properties as $property) {
			if (isset($fields[$property])) {
				$newFields[]	= $fields[$property][1];
				$newTables[$fields[$property][0]]	= $fields[$property][0];
			} else {
				$newFields[]	= strtolower($property);
			}
		}
		
		return array('tables' => array_values($newTables), 'fields' => $newFields);
	}
	
	/**
	 * 
	 * @param type $entityId
	 * @param type $property
	 * @throws Lazyto_Exception
	 * @return Array table,field键值对
	 */
	public function getPropertyField($entityId, $property)
	{
		if (!isset($this->_entityMaps[$entityId])) {
			throw new Lazyto_Exception('You must set ' . $entityId . ' entity maps.');
		}
		
		$entityMaps	= $this->_entityMaps[$entityId];
		
		if (!isset($entityMaps[self::TABLES_MAP_KEY])) {
			throw new Lazyto_Exception('You must set ' . $entityId . ' tables map.');
		}
		
	}
	
}