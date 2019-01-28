<?php
namespace plug\oauth;
use plug\vendor\oauth\Github as Oauth;
/**
 * bool
 * 30024167@qq.com
 * Github oauth登陆
 * https://github.com/settings/developers
 */
class Github
{
	protected $github = null;

	/**
	 * [__construct 构造方法]
	 */
	function __construct(){
		include __DIR__.'/vendor/Github.php';
		$this->github = new Oauth();
		$this->github->client_id 		= '42174649196890322032';
		$this->github->client_secret 	= 'e613dea5ee6c9f8ece545856d1a055d404e3beb5';
	}

	/**
	 * [get_userinfo 获取用户信息]
	 * @param  [type] $code [code码]
	 * @return [array]      [结果集]
	 */
	public function get_userinfo($code){
		$res = $this->github->login($code);
		// 检测返回结果
		if( !isset($res['node_id']) ){
			return false;
		}
		// 返回数据
		return [
			'open_id'	=>	$res['node_id'],
			'head_img'	=>	$res['avatar_url'],
			'nickname'	=>	$res['name'],
			'location'	=>	$res['location'],
			'email'		=>	$res['email'],
			'bio'		=>	$res['bio'],
			'url'		=>	$res['url'],
			'data'		=>	json_encode($res),
			'phone'		=>	''
			// 'id'		=>	$res['id']
		];
	}


}
