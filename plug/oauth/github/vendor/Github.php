<?php
namespace plug\vendor\oauth;
/**
 * bool
 * 30024167@qq.com
 * Github oauth登陆
 * https://github.com/settings/developers
 */
class Github
{
	public $client_id		=	'***';
	public $client_secret	=	'******';

	function __construct(){
	}

	// 快捷方法 直接登陆
	public function login($code){
		$url = $this->get_access_token($code);
		return $this->get_userinfo($url);
	}

	// 第一步:获取 access_token 地址
	public function get_access_token($code){
		$url = "https://github.com/login/oauth/access_token?client_id={$this->client_id}&client_secret={$this->client_secret}&code={$code}";
		return 'https://api.github.com/user?'.$this->curl_get($url);
	}

	// 第二步:获取用户信息
	public function get_userinfo($url){
		return json_decode( $this->curl_get($url), true);
	}

	// curl发送get请求
    public function curl_get($url=''){
    	
        $ch = curl_init();
        //设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //不验证证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //不验证证书
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);

        //执行并获取HTML文档内容
        $output = curl_exec($ch);

        //释放curl句柄
        curl_close($ch);

        return $output;
    }


}

/* 
	//使用案例

	use extend\oauth\Github;
	$github = new Github();
	$res = $github->login($code);
	var_dump($res);

 */