<?php
require './LightpenData/conf/config_ucenter.php';
require './uc_client/client.php';

class UcApi{
    static protected $lastAction = '';
    static protected $lastErrorCode = '';
    static protected $authPre = 'bpi_';
    static protected $uid = '';
    static protected $username = '';
    static protected $password = '';
    static protected $email ='';
    static protected $errorCode = array(
        'reg' => array(
            '-1' => '用户名不合法' ,
            '-2' => '包含不允许注册的词语' ,
            '-3' => '用户名已经存在' ,
            '-4' => 'Email格式有误' ,
            '-5' => 'Email不允许注册' ,
            '-6' => '该Email已经注册' ,
        ) ,
        'login' => array(
            '-1' => '用户不存在' ,
            '-2' => '密码错误' ,
            '-3' => '安全提示问答错误' ,
        ),
        'editUserInfo' => array(
            '1'=>'更新成功',
            '0'=>'没有做任何修改',
            '-1'=>'旧密码不正确',
            '-4'=>'Email 格式有误',
            '-5'=>'Email 不允许注册',
            '-6'=>'该 Email 已经被注册',
            '-7'=>'没有做任何修改',
            '-8'=>'该用户受保护无权限更改'
        ),
        'checkemail' => array(
            '-4' => 'Email格式错误' ,
            '-5' => '该Email不允许注册' ,
            '-6' => '该Email已经被注册' ,
        ),
        'checkname' => array(
            '-1' => '用户名不合法' ,
            '-2' => '包含不允许注册的词语' ,
            '-3' => '用户名已存在' ,
        ),
        'addfeed' => array(
            '0' => '增加事件动态失败' ,
        ),
        
    );
    /**
     * 
     *  同步登陆
     * 
     * @param unknown $username   用户名 （必须）
     * @param unknown $password   密码 （必须）
     * @param number $isuid  
     * @return multitype:unknown string Ambigous <mixed, string, multitype:, unknown> |boolean
     * @author Lando<806728685@qq.com>
     * @since  2016-9-21
     */
    static function login($username, $password, $isuid = 0) {
       
       list($uid, $username, $password, $email) = uc_user_login($username, $password, $isuid);
       setcookie(self::$authPre . 'auth', '', -86400);
       if($uid > 0) {
           self::$uid = $uid;
           self::$username = $username;
           self::$password = md5($password);
           self::$email = $email;
           setcookie(self::$authPre . 'auth', uc_authcode($uid . "\t" . $username . "\t" . md5($password) . "\t" . $email, 'ENCODE'));
           return array(
               'uid' => $uid ,
               'username' => $username ,
               'password' => $password ,
               'email'  => $email ,
               'synlogin' => uc_user_synlogin($uid),
           );
       } else{
            self::$lastAction = 'login';
            self::$lastErrorCode = $uid;
            return FALSE;
       }
    }
    /**
     * 
     * 同步注册账号
     * 
     * @param unknown $username  用户名
     * @param unknown $password  密码
     * @param unknown $email  邮箱
     * @param string $autologin  是否设置自动登陆
     * @return mixed|boolean
     * @author Lando<806728685@qq.com>
     * @since  2016-9-21
     */
    static function reg($username, $password, $email, $autologin = false) {
        $ip = get_client_ip();
        $zhuce = uc_user_register($username, $password, $email, '', '',$ip);
        if($zhuce > 0) {
            if($autologin){
                self::$uid = $uid;
                self::$username = $username;
                self::$password = md5($password);
                self::$email = $email;
                setcookie(self::$authPre . 'auth', uc_authcode($uid . "\t" . $username . "\t" . md5($password) . "\t" . $email, 'ENCODE'));
            }
            return $zhuce;   //返回UID
        } else {
            self::$lastAction = 'reg';
            self::$lastErrorCode = $zhuce;
            return $zhuce;
        }
    }
    /**
     * 
     * 同步资料修改(本接口函数用于更新用户资料。更新资料需验证用户的原密码是否正确，除非指定 ignoreoldpw 为 1。如果只修改 Email 不修改密码，可让 newpw 为空；同理如果只修改密码不修改 Email，可让 email 为空。)
     * 
     * @param unknown $username 用户名
     * @param unknown $oldpw  老密码
     * @param unknown $newpw  新密码
     * @param unknown $email  邮箱信息
     * @param unknown $ignoreoldpw  是否忽略旧密码 1:忽略，更改资料不需要验证密码；0:(默认值) 不忽略，更改资料需要验证密码
     * @author Lando<806728685@qq.com>
     * @since  2016-9-24
     */
    static  function  editUserInfo($username, $oldpw, $newpw, $email, $ignoreoldpw = 1){
        $editReturn = uc_user_edit($username, $oldpw, $newpw, $email, $ignoreoldpw);
        if($editReturn > 0) {
            return $editReturn;  //返回UID
        } else {
            self::$lastAction = 'editUserInfo';
            self::$lastErrorCode = $editReturn;
            return FALSE;
        }
    }
    
    /**
     * 
     * 同步退出
     * 
     * @return string
     * @author Lando<806728685@qq.com>
     * @since  2016-9-21
     */
    static function logout() {
        setcookie(self::$authPre . 'auth', '', -86400);
        return uc_user_synlogout();
    }
    /**
     * 
     * 添加事件
     * 
     * @param unknown $uid
     * @param unknown $username
     * @param unknown $url
     * @param unknown $where
     * @param unknown $action
     * @param unknown $event
     * @param unknown $desc
     * @param unknown $images
     * @return mixed|boolean
     * @author Lando<806728685@qq.com>
     * @since  2016-9-21
     */
    static function addFeed($uid, $username, $url, $where, $action, $event, $desc, $images =array()) {
        $feed = array();
        $feed['icon'] = 'thread';
        $feed['title_template'] = '<b>{username} 在{where}{action}了{event}</b>';
        $feed['title_data'] = array(
            'username' => $username ,
            'where' => $where ,
            'action' => $action ,
            'event' => $event ,
            );
        $feed['body_template'] = '<br>{message}';
        $feed['body_data'] = array(
            'message' => cutstr(strip_tags(preg_replace("/\[.+?\]/is", '', $desc)), 150) ,
        );
        $feed['images'] = $images;
        
        $addfeed = uc_feed_add($feed['icon'], $uid, $username, $feed['title_template'], $feed['title_data'], $feed['body_template'], $feed['body_data'], '', '', $feed['images']);
        
        if($addfeed > 0) {
            return $addfeed;
        } else {
            self::$lastAction = 'addfeed';
            self::$lastErrorCode = $addfeed;
            return FALSE;
        }

    }
    /**
     * 
     * 检查 Email 地址
     * 
     * @param unknown $email
     * @return boolean
     * @author Lando<806728685@qq.com>
     * @since  2016-9-21
     */
    static function checkEmail($email) {
        $checkemail = uc_user_checkemail();
        if($checkemail > 0) {
            return TRUE;
        }else{
            self::$lastAction = 'checkemail';
            self::$lastErrorCode = $checkemail;
            return FALSE;
        }
    }
    /**
     * 
     * 检查用户名
     * 
     * @param unknown $username
     * @return boolean
     * @author Lando<806728685@qq.com>
     * @since  2016-9-21
     */
    static function checkName($username) {
        $checkname = uc_user_checkname();
        if($checkname > 0) {
            return TRUE;
        }else{
            self::$lastAction = 'checkname';
            self::$lastErrorCode = $checkname;
            return FALSE;
        }
    }
    /**
     * 
     * 检查是否同步登陆
     * 
     * @author Lando<806728685@qq.com>
     * @since  2016-9-21
     */
    static function isLogin () {
       return self::getUserByCookie();
    }
    /**
     * 
     * 获取登陆账号的COOKIE
     * 
     * @return multitype:string |boolean
     * @author Lando<806728685@qq.com>
     * @since  2016-9-21
     */
    static function getUserByCookie() {
        if(!empty($_COOKIE[self::$authPre . 'auth'])) {
            list(self::$uid, self::$username, self::$password, self::$email) = explode("\t", uc_authcode($_COOKIE[self::$authPre . 'auth'], 'DECODE'));
            return array(
                'uid' => self::$uid,
                'username' => self::$username,
                'password' => self::$password,
                'email' => self::$email,
            );
        } else {
            return FALSE;
        }

    }
    /**
     * 
     * 获取登陆账号的UID
     * 
     * @return string
     * @author Lando<806728685@qq.com>
     * @since  2016-9-21
     */
    static function getUid() {
        if(empty(self::$uid)) {
            self::getUserByCookie();
        }  
            return self::$uid;
    }
    /**
     * 
     * 获取登陆账号的账号
     * 
     * @return string
     * @author Lando<806728685@qq.com>
     * @since  2016-9-21
     */
    static function getUserName() {
        if(empty(self::$username)) {
            self::getUserByCookie();
        }  
            return self::$username;
    }
    /**
     * 
     * 获取登陆账号的密码
     * 
     * @return string
     * @author Lando<806728685@qq.com>
     * @since  2016-9-21
     */
    static function getPassWord() {
        if(empty(self::$password)) {
            self::getUserByCookie();
        }  
            return self::$password;
    }
    /**
     * 
     * 获取登陆账号的邮箱
     * 
     * @return string
     * @author Lando<806728685@qq.com>
     * @since  2016-9-21
     */
    static function getEmail() {
        if(empty(self::$email)) {
            self::getUserByCookie();
        }  
            return self::$email;
    }
    /**
     * 
     * 获取错误代码
     * 
     * @return string
     * @author Lando<806728685@qq.com>
     * @since  2016-9-21
     */
    static function getError() {
        //return self::$lastErrorCode = '' ? '' :  '错误代码: ' . self::$lastErrorCode . ' : ' . self::$errorCode[self::$lastAction][self::$lastErrorCode];
        return self::$lastErrorCode = '' ? '' :  '错误: ' . self::$errorCode[self::$lastAction][self::$lastErrorCode];
    }
    /**
     * 同步删除账号
     * @param unknown $username 传过来用户名
     * @author Asa<asa@renlaifeng.cn>
     * @since  2016-11-17
     */
    static function delUser($username) {
    	$infoA = uc_get_user($username);
    	if($infoA){
    		 $delRes = uc_user_delete($infoA['0']);
    		 if($delRes) return $delRes;
    	}
    }
    
}
?>