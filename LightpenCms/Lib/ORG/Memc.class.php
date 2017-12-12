<?PHP
/**
 * 
 * Demo:
 * $cacheObj = new Memc();
 * $cacheObj -> set('keyName','this is value');
 * $cacheObj -> get('keyName');
 * 
 * @author    Lando<806728685@qq.com>
 * @since     2016-6-16
 * @version   1.0
 */
class Memc{


    private $local_cache = array();
    private $m;
    private $client_type;
    private $expiration=0;
    protected $errors = array();

    public function __construct()
    {
        $this->client_type = class_exists('Memcache') ? "Memcache" : (class_exists('Memcached') ? "Memcached" : FALSE);

        if($this->client_type)
        {
            // 判断引入类型
            switch($this->client_type)
            {
                case 'Memcached':
                    $this->m = new Memcached();
                    break;
                case 'Memcache':
                    $this->m = new Memcache();
                    // if (auto_compress_tresh){
                    // $this->setcompressthreshold(auto_compress_tresh, auto_compress_savings);
                    // }
                    break;
            }
            $this->autoConnect();
        }
        else
        {
            echo 'ERROR: Failed to load Memcached or Memcache Class (∩_∩)';
            exit;
        }
    }

    /**
     * 
     * 连接Memcached缓存服务器
     * 
     * @author Lando<806728685@qq.com>
     * @since  2016-6-16
     */
    private function autoConnect()
    {
        $configServer = array(
                'host' => C('MEMCACHE_HOST'),
                'port' => C('MEMCACHE_PORT'),
                'weight' => 1,
        );
        if(!$this->addServer($configServer)){
            echo 'ERROR: Could not connect to the server named '.C('MEMCACHE_HOST');
        }else{
            //echo 'SUCCESS:Successfully connect to the server named '.C('MEMCACHE_HOST');
        }
    }
    /**
     *
     * Key值加密，内部调用方法
     *
     * @param unknown $key
     * @return string
     * @author Lando<806728685@qq.com>
     * @since  2016-6-16
     */
    private function key_name($key)
    {
        return md5(strtolower(C('MEMCACHE_PREFIX').$key));
    }
    /**
     * 
     * 添加Memcached缓存服务器
     * 
     * @param array $server=array('host'=>'127.0.0.1','port'=>11211,'weight'=>111)  服务器配置 
     * @return boolean
     * @author Lando<806728685@qq.com>
     * @since  2016-6-16
     */
    public function addServer($server){
        extract($server);
        return $this->m->addServer($host, $port, $weight);
    }
    /**
     * 
     * 设置缓存数据
     * 
     * @param string/array $key  键    array(array('key'=>1,'value'=>'1','expiration'=>60),array('key'=>2,'value'=>'2','expiration'=>60))
     * @param string $value  值
     * @param number $expiration 过期时间，以秒计算(0:永不过期) 
     * @return boolean
     * @author Lando<806728685@qq.com>
     * @since  2016-6-16
     */
    public function set($key = NULL, $value = NULL, $expiration = 0)
    {
        if(is_null($expiration)){
            $expiration = $this->expiration;
        }
        if(is_array($key))
        {
            foreach($key as $multi){
                if(!isset($multi['expiration']) || $multi['expiration'] == ''){
                    $multi['expiration'] = $this->config['config']['expiration'];
                }
                $this->set($this->key_name($multi['key']), $multi['value'], $multi['expiration']);
            }
        }else{
            $this->local_cache[$this->key_name($key)] = $value;
            switch($this->client_type){
                case 'Memcache':
                    $add_status = $this->m->set($this->key_name($key), $value, MEMCACHE_COMPRESSED, $expiration);
                    break;
                case 'Memcached':
                    $add_status = $this->m->set($this->key_name($key), $value, $expiration);
                    break;
            }
            return $add_status;
        }
    }
    /**
     * @Name: 向已存在元素后追加数据
     * @param:$key key
     * @param:$value value
     * @return : true OR false
     * add by cheng.yafei
     **/
    public function append($key = NULL, $value = NULL)
    {
    
        $this->local_cache[$this->key_name($key)] = $value;
        $append_status = NULL;
        switch($this->client_type)
        {
            case 'Memcache':
                $append_status = $this->m->append($this->key_name($key), $value);
                break;
            default:
            case 'Memcached':
                $append_status = $this->m->append($this->key_name($key), $value);
                break;
        }
        return $append_status;
    }
    /**
     * 
     * 获取缓存数据
     * 
     * @param string $key  键
     * @return multitype:|boolean|mixed|Ambigous <mixed, string>
     * @author Lando<806728685@qq.com>
     * @since  2016-6-16
     */
    public function get($key = NULL)
    {
        if($this->m)
        {
            if(isset($this->local_cache[$this->key_name($key)]))
            {
                return $this->local_cache[$this->key_name($key)];
            }
            if(is_null($key)){
                $this->errors[] = 'The key value cannot be NULL';
                return FALSE;
            }
            	
            if(is_array($key)){
                foreach($key as $n=>$k){
                    $key[$n] = $this->key_name($k);
                }
                return $this->m->getMulti($key);
            }else{
                return $this->m->get($this->key_name($key));
            }
        }else{
            return FALSE;
        }
    }
    /**
     * 
     * 删除缓存数据
     * 
     * @param string|array $key 键 (字符串或者变量都可以传入) array('key1','key2','key3')
     * @param string $expiration 
     * @return boolean
     * @author Lando<806728685@qq.com>
     * @since  2016-6-16
     */
    public function delete($key, $expiration = 0)
    {
        if(is_null($key))
        {
            $this->errors[] = 'The key value cannot be NULL';
            return FALSE;
        }

        if(is_null($expiration))
        {
            $expiration = $this->expiration;
        }

        if(is_array($key))
        {
            foreach($key as $multi)
            {
                $this->delete($multi, $expiration);
            }
        }
        else
        {
            unset($this->local_cache[$this->key_name($key)]);
            return $this->m->delete($this->key_name($key), $expiration);
        }
    }
    /**
     * 
     * 清空所有缓存数据
     * 
     * @return boolean
     * @author Lando<806728685@qq.com>
     * @since  2016-6-16
     */
    public function deleteAll()
    {
        return $this->m->flush();
    }
    /**
     * 
     * 获取Memcached/Memcache版本号
     * 
     * @return Ambigous <multitype:, string>
     * @author Lando<806728685@qq.com>
     * @since  2016-6-16
     */
    public function getVersion()
    {
        return $this->m->getVersion();
    }
    /**
     * 
     * 获取Memcached/Memcache当前的状态数据
     * 
     * @param string $type
     * @return multitype:
     * @author Lando<806728685@qq.com>
     * @since  2016-6-16
     */
    public function getStats($type="items")
    {
        switch($this->client_type)
        {
            case 'Memcache':
                $stats = $this->m->getStats($type);
                break;
                	
            default:
            case 'Memcached':
                $stats = $this->m->getStats();
                break;
        }
        return $stats;
    }
    /**
     * 
     * 开启大值自动压缩
     * 
     * @param unknown $tresh  控制多大值进行自动压缩的阈值
     * @param real $savings  指定经过压缩实际存储的值的压缩率，值必须在0和1之间。默认值0.2表示20%压缩率。
     * @return boolean
     * @author Lando<806728685@qq.com>
     * @since  2016-6-16
     */
    public function setCompressThreshold($tresh, $savings=0.2)
    {
        switch($this->client_type)
        {
            case 'Memcache':
                $setcompressthreshold_status = $this->m->setCompressThreshold($tresh, $savings=0.2);
                break;

            default:
                $setcompressthreshold_status = TRUE;
                break;
        }
        return $setcompressthreshold_status;
    }
    /**
     * 
     * 获取错误
     * 
     * @author Lando<806728685@qq.com>
     * @since  2016-6-16
     */
    public function getError(){
        return $this->errors;
    }
    
    public function __destruct(){
        unset($this->m);
    }
    
}


