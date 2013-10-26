<?php
class Lazyto_Executor_Mysql_FetchOne extends Lazyto_Executor_FetchOne
{
	/**
	 * @var Lazyto_Executor_Mysql_MysqlConfig
	 */
	private $_mysqlConfig;
	
	/**
	 * @var Mysqli
	 */
	private $_mysqli	= null;
	
	/**
	 * @var Lazyto_Executor_Mysql_MysqlEntityMaps
	 */
	private $_mysqlEntityMaps;
	
	public function __construct(Lazyto_Executor_Mysql_MysqlConfig $config, Lazyto_Executor_Mysql_MysqlEntityMaps $maps)
	{
		$this->_mysqlConfig	= $config;
		$this->_mysqlEntityMaps	= $maps;
	}
	
	private function _connect()
	{
		if (!$this->_mysqli) {
			$config	= $this->_mysqlConfig;
			$this->_mysqli	= new Mysqli($config->getHost(), $config->getUsername(), $config->getPassword(), $config->getPort());
			if ($this->_mysqli->connect_error) {
				throw new Lazyto_Exception('cannot connect mysqli :' . $this->_mysqli->connect_error);
			}
		}
	}
	
	protected function _exec(Lazyto_Command_FetchOne $cmd)
	{
		$this->_connect();
		
		$row	= $this->_fetchRow($cmd);
		
		$entity	= Lazyto_Entity::factory($cmd->getActEntity(), $row);
		
		return new Lazyto_Result_FetchOne($entity);
	}
	
	private function _fetchRow(Lazyto_Command_FetchOne $cmd)
	{
		$entityCode	= $cmd->getActEntity();
		
		$table	= $this->_mysqlEntityMaps->getEntityTable($entityCode);
		
		$where	= $values = array();
		foreach ($cmd->getConditions() as $property => $value) {
			$field	= $this->_mysqlEntityMaps->getEntityField($entityCode, $property);
			$where[]	= $field . ' = ?';
			$values[]	= $value;
		}
		
		$fields	= array();
		foreach ($cmd->getFetchProperties() as $property) {
			$fields[]	= $this->_mysqlEntityMaps->getEntityField($entityCode, $property);
		}
		
		$sql	= 'SELECT ' . implode(',', $fields) . ' FROM ' . $table . ' WHERE ' . implode(' AND ', $where) . ' LIMIT 1';
		
		$row	= array();
		if ($stmt = $this->_mysqli->prepare($sql)) {
			$stmt->bind_param(str_repeat('s', count($values)), $values);
			
			$stmt->execute();
			
			$stmt->bind_result($row);
			
			$stmt->fetch();
		} else {
			throw new Lazyto_Exception($this->_mysqli->error);
		}
		
		return $row;
	}
}