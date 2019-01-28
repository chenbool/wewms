<?php
namespace app\common\controller;

use think\Request,
    think\Controller;

/**
 * 公共控制器
 * 作者:bool
 * QQ  :30024167
 */
class Base extends Controller
{
    protected $object;
    
    /**
     * [__construct 构造方法]
     */
	function __construct()
    {
        parent::__construct();
        $this->_service();
	}

    /**
     * [index index页面]
     * @return [view] [视图]
     */
    public function index()
    {
        return $this->object->index();
    }

    /**
     * [create create页面]
     * @return [view] [视图]
     */
    public function create()
    {
        $this->assign( $this->object->create() ); 
        return view();
    }

    /**
     * [save 保存数据]
     * @return [bool] [结果集]
     */
    public function save()
    {
        return $this->object->save();
    }

    /**
     * [edit 编辑]
     * @param  [int] $id [id]
     * @return [view]    [视图]
     */
    public function edit($id)
    {
        $this->assign( $this->object->edit($id) ); 
        return view();
    }

    /**
     * [update 更新数据]
     * @return [bool] [结果集]
     */
    public function update()
    {
        return $this->object->update();
    }

    /**
     * [delete 删除操作]
     * @return [bool] [结果集]
     */
    public function delete()
    {
        $id = request()->param('id');
        return $this->object->delete($id);
    }

    /**
     * [_empty 空界面]
     * @param  [string] $name [方法名称]
     * @return [string]       [结果集]
     */
    public function _empty($name)
    {
        return $name.'方法不存在';
    }

    /**
     * [_service 自动实例化Service对象]
     * @return [object] [Service对象]
     */
    protected function _service()
    {   
        // app\admin\Index
        $serviceName = "app\\".request()->module()."\\service\\".request()->controller();

        // 判断类是否存在
        if( class_exists($serviceName) ){
            $this->object = new $serviceName();
        }
        
    }

}
