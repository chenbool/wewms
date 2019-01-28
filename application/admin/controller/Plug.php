<?php

namespace app\admin\controller;

class Plug extends Base
{

	/**
	 * [conf 配置插件]
	 * @return [type] [结果集]
	 */
	public function conf()
	{
		return $this->object->conf();
	}

	/**
	 * [install 安装插件]
	 * @return [json] [结果集]
	 */
	public function install()
	{
		return $this->object->install();
	}

	/**
	 * [uninstall 卸载插件]
	 * @return [type] [结果集]
	 */
	public function uninstall()
	{
		return $this->object->uninstall();
	}    
    
}
