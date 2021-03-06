<?php
namespace zeus\domain;

class LocalCacheManager
{
	private static $cache = [];
	private $_cache_key;


    public function __construct($obj)
	{
		$this->_cache_key = get_class($obj);

		if(!isset(static::$cache[$this->_cache_key])){
			static::$cache[$this->_cache_key] = [];
		}
	}
	
	protected function putCache($key,$val){
		static::$cache[$this->_cache_key][$key] = $val;
	}
	
	protected function getCache($key){
		if(isset(static::$cache[$this->_cache_key][$key])){
			return static::$cache[$this->_cache_key][$key];
		}
		return null;
	}
	
	protected function removeCache($key){
		unset(static::$cache[$this->_cache_key][$key]);
	}
}