<?php
namespace plug\oauth;
use plug\vendor\oauth\Gitee as Oauth;
/**
 * bool
 * 30024167@qq.com
 * Gitee oauth登陆
 */
class Gitee
{
	protected $gitee = null;

	/**
	 * [__construct 构造方法]
	 */
	function __construct(){
		include __DIR__.'/vendor/Gitee.php';
		$this->gitee = new Oauth();
		$this->gitee->client_id 		= 'ada13e081d010bde142e33fe892bb2879565283d4b0ad25ce4ec4bea96920dc3';
		$this->gitee->client_secret 	= '45a66fafbf43c5769fafe4957dba4da52702a52d1aabb68dc1c1e4185530d6a7';
		$this->gitee->redirect_uri 		= 'http://127.0.0.1:8000/admin/login/oauth/type/gitee';
	}

	/**
	 * [get_userinfo 获取用户信息]
	 * @param  [type] $code [code码]
	 * @return [array]      [结果集]
	 */
	public function get_userinfo($code){
		$res = $this->gitee->login($code);

		// 检测返回结果
		if( !isset($res['private_token']) ){
			return false;
		}
		// 返回数据
		return [
			'open_id'	=>	$res['private_token'],
			'head_img'	=>	$res['avatar_url'],
			'nickname'	=>	$res['name'],
			'location'	=>	'',
			'email'		=>	$res['email'],
			'phone'		=>	$res['phone'],
			'bio'		=>	$res['bio'],
			'url'		=>	$res['url'],
			'data'		=>	json_encode($res),
		];
	}


}
