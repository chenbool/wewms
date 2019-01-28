<?php
namespace plug\oauth;
use plug\vendor\oauth\Coding as Oauth;
/**
 * bool
 * 30024167@qq.com
 * oschina oauth登陆
 * https://www.oschina.net/openapi
 */
class Coding
{


    protected $oschina = null;

    /**
     * [__construct 构造方法]
     */
    function __construct(){
        include __DIR__.'/vendor/Coding.php';
        $this->oschina = new Oauth();
        $this->oschina->client_id         = 'b9edbf17987a54edf6d0e133395b74fc';
        $this->oschina->client_secret     = '605005ee92b3c6c80524296317a4119466124bf1';
        $this->oschina->redirect_uri      = 'http://127.0.0.1:8000/admin/login/oauth/type/coding';
    }

    /**
     * [get_userinfo 获取用户信息]
     * @param  [type] $code [code码]
     * @return [array]      [结果集]
     */
    public function get_userinfo($code){
        $res = $this->oschina->login($code);
        $res = $res['data'];

        // 检测返回结果
        if( !isset($res['id']) ){
            return false;
        }
        // 返回数据
        return [
            'open_id'   =>  $res['id'],
            'head_img'  =>  'https://coding.net'.$res['avatar'],
            'nickname'  =>  $res['name'],
            'location'  =>  $res['location'],
            'email'     =>  '',
            'phone'     =>  '',
            'bio'       =>  '',
            'url'       =>  '',
            'data'      =>  json_encode($res),
        ];
    }



}
