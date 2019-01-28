<?php
namespace app\admin\controller;

class Role extends Base
{

    // 分配权限
    public function distribute()
    {
        return $this->object->distribute();
    }

    public function getTree()
    {
        return $this->object->getTree();
    }    

}
