<?php
class Lazyto_Executor_Mysql_ServerConfig
{
	const CNFKEY_HOST		= 'host';
	const CNFKEY_USERNAME	= 'username';
	const CNFKEY_PASSWORD	= 'password';
	const CNFKEY_DBNAME		= 'dbname';
	const CNFKEY_CHARSET	= 'charset';
	const CNFKEY_PORT		= 'prot';
	
	private static $_configs	= array();
	
	private $_host;
	private $_username;
	private $_password;
	private $_dbname;
	private $_charset;
	private $_port;
	
	private function __construct(array $config)
	{
		$keys	= array(
			self::CNFKEY_HOST, self::CNFKEY_USERNAME, self::CNFKEY_PASSWORD,
			self::CNFKEY_DBNAME, self::CNFKEY_CHARSET, self::CNFKEY_PORT
		);
		$config[self::CNFKEY_PORT]	= isset($config[self::CNFKEY_PORT]) ? $config[self::CNFKEY_PORT] : '';
		$config[self::CNFKEY_CHARSET]	= isset($config[self::CNFKEY_CHARSET]) ? $config[self::CNFKEY_CHARSET] : '';
		
		foreach ($keys as $key) {
			$property	= '_' . $key;
			$this->{$property}	= $config[$key];
		}
	}
	
	private static function _checkConfig(array $config)
	{
		if (empty($config['host'])) {
			throw new Lazyto_Exception('mysql server config host cannot be empty.');
		}
		
		if (empty($config['username'])) {
			throw new Lazyto_Exception('mysql server config username cannot be empty.');
		}
		
		if (!isset($config['password'])) {
			throw new Lazyto_Exception('mysql server config must have password.');
		}
		
		if (empty($config['dbname'])) {
			throw new Lazyto_Exception('mysql server config dbname cannot be empty.');
		}
	}
	
	/**
	 * 添加mysql服务器配置
	 * @param Array $config 配置数组
	 * @param String $type 服务器配置类型（同一个类型多个配置都会被保存下来）
	 */
	public static function addServer(array $config, $type = 'default')
	{
		self::_checkConfig($config);
		
		$type	= empty($type) ? 'default' : $type;
		
		if (isset(self::$_configs[$type])) {
			self::$_configs[$type][]	= new self($config);
		} else {
			self::$_configs[$type]	= array(new self($config));
		}
	}
	
	/**
	 * 获取mysql服务器配置
	 * @param String $type 服务器配置类型
	 * @param Mixed $moreCallback 如果同一类型存在多个服务器会使用回调方法来选取一个（默认为空时采用随机的方式）
	 *                            该参数类型同call_user_func_array的第一个参数，同时回调时会传入该配置类型下所有服务器做为第一个参数
	 * @param Array $params 回调函数的参数（该函数仅在有回调函数时才起作用，该参数与回调函数参数一起构成回调，该参数见call_user_func_array第二个参数）
	 * @return Lazyto_Executor_Mysql_ServerConfig | null
	 */
	public static function getServer($type = 'default', $moreCallback = '', array $params = array())
	{
		$type	= empty($type) ? 'default' : $type;
		
		if (!isset(self::$_configs[$type])) {
			return null;
		} else {
			$allNum	= count(self::$_configs[$type]);
			if ($allNum == 1) {
				return self::$_configs[$type][0];
			} else {
				if ($moreCallback) {
					array_unshift($params, self::$_configs[$type]);
					return call_user_func_array($moreCallback, $params);
				} else {
					$rand	= rand(0, $allNum - 1);
					return self::$_configs[$type][$rand];
				}
			}
		}
	}
	
	public function getHost()
	{
		return $this->_host;
	}
	
	public function getUsername()
	{
		return $this->_username;
	}
	
	public function getPassword()
	{
		return $this->_password;
	}
	
	public function getDbname()
	{
		return $this->_dbname;
	}
	
	public function getCharset()
	{
		return $this->_charset;
	}
	
	public function getPort()
	{
		return $this->_port;
	}
}