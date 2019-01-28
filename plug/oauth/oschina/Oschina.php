<?php
namespace plug\oauth;
use plug\vendor\oauth\Oschina as Oauth;
/**
 * bool
 * 30024167@qq.com
 * oschina oauth登陆
 * https://www.oschina.net/openapi
 */
class Oschina
{


    protected $oschina = null;

    /**
     * [__construct 构造方法]
     */
    function __construct(){
        include __DIR__.'/vendor/Oschina.php';
        $this->oschina = new Oauth();
        $this->oschina->client_id         = 'ovuEB7vDZ3Wr4j7Zod6a';
        $this->oschina->client_secret     = 'c6QjFZOOfoANGioVPHbrhUYM15KQlxup';
        $this->oschina->redirect_uri      = 'http://127.0.0.1:8000/admin/login/oauth/type/oschina';
    }

    /**
     * [get_userinfo 获取用户信息]
     * @param  [type] $code [code码]
     * @return [array]      [结果集]
     */
    public function get_userinfo($code){
        $res = $this->oschina->login($code);

        // 检测返回结果
        if( !isset($res['id']) ){
            return false;
        }
        // 返回数据
        return [
            'open_id'   =>  $res['id'],
            'head_img'  =>  $res['avatar'],
            'nickname'  =>  $res['name'],
            'location'  =>  $res['location'],
            'email'     =>  $res['email'],
            'phone'     =>  '',
            'bio'       =>  '',
            'url'       =>  $res['url'],
            'data'      =>  json_encode($res),
        ];
    }



}
