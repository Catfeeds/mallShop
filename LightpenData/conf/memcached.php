<?php
/**
 * Memcached 缓存配置
 */
return array(
    'MEMCACHE_HOST'=>'127.0.0.1',
    'MEMCACHE_PORT'=>11211,
    'MEMCACHE_EXPIRATION'=>0,
    'MEMCACHE_PREFIX'=>'MB',
    'MEMCACHE_COMPRESSION'=> FALSE,//memcache 会用到
    'MEMCACHE_EXPIREDAY'=>'86400',
	'MEMCACHE_EXPIREHOUR'=>'3600'
);